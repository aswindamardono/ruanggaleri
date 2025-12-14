# Dokumentasi Struktur Data Terlambat di Database

## Perubahan Database

### Tabel: `presents` (Absensi)
Menambahkan kolom baru:
- **terlambat_menit** (INT, default: 0)
  - Menyimpan total menit keterlambatan untuk setiap hari absensi
  - Dihitung otomatis saat absen masuk atau melalui batch processing
  - Threshold: keterlambatan > 5 menit baru dihitung
  - Formula: (Jam masuk actual - Jam masuk jadwal) - 5 menit

### Tabel: `penggajian` (Penggajian)
Kolom yang digunakan:
- **terlambat** (INT): Total menit keterlambatan dalam sebulan
- **potongan** (INT): Total potongan gaji dari keterlambatan (menit × 500)
- **total** (INT): Total gaji final setelah semua perhitungan

## Flow Data Keterlambatan

```
1. Pegawai Absen Masuk
   ↓
   Sistem mencatat: hour_in + jadwal jam_masuk
   ↓
   
2. Hitung Keterlambatan
   Jika hour_in > jam_masuk:
      menit_terlambat = (hour_in - jam_masuk) - 5 menit
      Jika menit_terlambat > 0: simpan di field terlambat_menit
   Else:
      terlambat_menit = 0
   ↓
   
3. Update Tabel Presents
   UPDATE presents SET terlambat_menit = calculated_value
   ↓
   
4. Menu Monitoring
   Menampilkan data keterlambatan dari presents.terlambat_menit
   User bisa lihat "Terlambat X menit" untuk setiap hari
   ↓
   
5. Menu Penggajian
   Saat pilih karyawan + bulan/tahun:
      - AJAX query getTerlambatOtomatis()
      - Query: SUM(terlambat_menit) FROM presents WHERE user_id, bulan, tahun
      - Hasil: total menit terlambat untuk bulan tersebut
      - Otomatis isi field "Jam Terlambat (Menit)"
   ↓
   
6. Simpan ke Penggajian
   - Terlambat = total menit terlambat dari query
   - Potongan = terlambat × 500
   - Total = Gaji Pokok + Tunjangan + Lain-lain + Bonus Lembur - Potongan
```

## Database Queries

### Update Terlambat Menit di Presents
```sql
-- Hitung dan update untuk user tertentu, bulan/tahun tertentu
UPDATE presents p
SET terlambat_menit = CASE
    WHEN (
        SELECT TIME_TO_SEC(TIMEDIFF(p.hour_in, j.jam_masuk)) / 60
        FROM jadwal j
        WHERE j.user_id = p.user_id
        AND DATE_FORMAT(p.date, '%a') = j.hari
    ) > 5 THEN (
        SELECT TIME_TO_SEC(TIMEDIFF(p.hour_in, j.jam_masuk)) / 60 - 5
        FROM jadwal j
        WHERE j.user_id = p.user_id
        AND DATE_FORMAT(p.date, '%a') = j.hari
    )
    ELSE 0
END
WHERE MONTH(p.date) = 12
AND YEAR(p.date) = 2025
AND p.user_id = 2;
```

### Get Total Terlambat per User per Bulan
```sql
-- Get total menit terlambat untuk penggajian
SELECT SUM(terlambat_menit) as total_menit_terlambat
FROM presents
WHERE user_id = 2
AND MONTH(date) = 12
AND YEAR(date) = 2025
AND image_out != '';
```

## Methods di AbsensiModel

### 1. getTerlambatMenit($id, $bulan, $tahun)
Mengambil total menit terlambat untuk user, bulan, dan tahun tertentu.

**Input:**
- $id: User ID
- $bulan: Bulan (1-12)
- $tahun: Tahun

**Output:** Integer (total menit terlambat)

**Query:**
```php
$result = $this->selectSum('terlambat_menit')
    ->where('user_id', $id)
    ->where('MONTH(date)', $bulan)
    ->where('YEAR(date)', $tahun)
    ->where('image_out !=', '')
    ->get()
    ->getRow();

return $result->terlambat_menit ?? 0;
```

### 2. hitungDanUpdateTerlambat($id, $bulan, $tahun)
Menghitung keterlambatan untuk setiap hari dan update ke database.

**Input:**
- $id: User ID
- $bulan: Bulan (1-12)
- $tahun: Tahun

**Process:**
1. Ambil semua record presents untuk user, bulan, tahun
2. Untuk setiap record:
   - Ambil jadwal hari tersebut
   - Hitung: (hour_in - jam_masuk jadwal) - 5 menit
   - Jika > 0: update terlambat_menit
   - Jika ≤ 0: update terlambat_menit = 0
3. Update field terlambat_menit di database

## Seed Data

### UpdateTerlambatPresentSeeder
Menjalankan perhitungan keterlambatan untuk semua user dan periode yang ada.

**Jalankan:**
```bash
php spark db:seed UpdateTerlambatPresentSeeder
```

**Proses:**
1. Loop semua user yang punya data absensi
2. Untuk setiap user: loop 24 bulan (tahun lalu dan tahun ini)
3. Hitung terlambat_menit dan update presents
4. Display: "Update terlambat_menit selesai untuk semua user dan periode!"

## Keuntungan Struktur Ini

✅ **Performance**: Data keterlambatan tersimpan, tidak perlu hitung ulang setiap kali
✅ **Konsistensi**: Satu sumber kebenaran (database field terlambat_menit)
✅ **Mudah diaudit**: Setiap record presents punya data keterlambatan
✅ **Monitoring**: Bisa melihat terlambat per hari di monitoring page
✅ **Batch Processing**: Bisa diupdate batch saat awal bulan
✅ **API Response**: Query getTerlambatOtomatis lebih cepat (SUM dari DB)

## Catatan Implementasi

- Kolom terlambat_menit di presents sudah ter-add via migration
- Semua data presents lama sudah ter-update terlambatnya
- getTerlambatMenit() sekarang langsung query dari presents.terlambat_menit
- hitungDanUpdateTerlambat() bisa dijalankan manual atau scheduled
- Menu Penggajian sudah otomatis ambil dari query getTerlambatMenit()
