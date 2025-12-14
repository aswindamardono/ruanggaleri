# Dokumentasi Fitur Potongan Gaji Otomatis Terlambat

## Deskripsi Fitur
Fitur ini menambahkan sistem otomatis untuk menghitung potongan gaji berdasarkan data keterlambatan pegawai dari menu Monitoring Absensi. Ketika operator memilih karyawan di menu Penggajian, sistem akan secara otomatis menampilkan total menit terlambat untuk bulan dan tahun yang dipilih.

## Aturan Keterlambatan
- **Threshold**: Keterlambatan lebih dari 5 menit baru dihitung
- **Potongan**: Rp 500 per menit keterlambatan
- **Perhitungan**: Total menit terlambat = (Jam masuk actual - Jam masuk jadwal) - 5 menit

## Rumus Perhitungan

### Total Menit Terlambat Sebulan
```
Total Menit Terlambat = Σ(Jam Masuk Actual - Jam Masuk Jadwal - 5 menit)
                        untuk setiap hari yang terlambat > 5 menit
```

### Potongan Gaji dari Keterlambatan
```
Potongan Terlambat = Total Menit Terlambat × 500
```

### Total Gaji Final
```
Total Gaji = Gaji Pokok + Tunjangan + Lain-lain + Bonus Lembur - Potongan Terlambat
```

## Perubahan yang Dilakukan

### 1. Helper (all_helper.php)
**Fungsi Baru: `menit_terlambat($jam_masuk, $jam_absen)`**
- Menghitung total menit keterlambatan antara jam masuk jadwal dan actual
- Return: integer (dalam menit)

### 2. Model (AbsensiModel.php)
**Method Baru: `getTerlambatMenit($id, $bulan, $tahun)`**
- Menghitung total menit terlambat untuk user, bulan, dan tahun tertentu
- Hanya menghitung keterlambatan > 20 menit (sesuai rule monitoring)
- Return: integer (total menit terlambat)

### 3. Controller (Penggajian.php)
**Method Baru: `getTerlambatOtomatis()`**
- AJAX endpoint untuk mengambil data terlambat otomatis
- Dipanggil saat operator memilih karyawan
- Return: JSON dengan struktur:
  ```json
  {
    "status": "success",
    "terlambat_menit": 150,
    "terlambat_jam": 2,
    "terlambat_menit_sisa": 30,
    "display": "2 jam 30 menit"
  }
  ```

**Update Method: `save()` dan `update()`**
- Mengubah perhitungan potongan terlambat dari `0.5x gaji per jam` menjadi `500 per menit`
- Rumus: `potongan_terlambat = terlambat * 500`

### 4. View (read.php)
**Update Input Field Terlambat**
- Label: "Jam Terlambat (Menit)" untuk klarifikasi bahwa input dalam satuan menit
- Helper text: "Potongan terlambat: 500 per menit. (Otomatis: X jam Y menit)"
- Nilai diisi otomatis saat memilih karyawan via AJAX

**Update JavaScript**
- Tambah AJAX call pada event `#guru change`
- Memanggil `getTerlambatOtomatis()` untuk ambil data otomatis
- Update nilai `#terlambat` dengan nilai dari API
- Update helper text dengan format "Otomatis: X jam Y menit"
- Ubah perhitungan potongan: dari `terlambat * (gajiPerJam * 0.5)` menjadi `terlambat * 500`

## Cara Kerja

### Flow Penggajian
1. Operator masuk ke halaman Operator → Penggajian
2. Klik tombol "Tambah"
3. Pilih Bulan dan Tahun
4. Pilih Karyawan dari dropdown
   - Sistem melakukan AJAX request ke `getTerlambatOtomatis`
   - API menghitung total menit terlambat dari tabel `presents`
   - Field "Jam Terlambat" otomatis terisi dengan nilai dari monitoring
   - Helper text menampilkan format pembacaan (jam dan menit)
5. Input field Gaji Pokok, Tunjangan, Lain-lain, Lembur
6. Field Jam Terlambat bisa diedit manual jika diperlukan
7. Total gaji otomatis terhitung dengan potongan = menit terlambat × 500
8. Klik Submit untuk simpan

### Contoh Perhitungan
```
Karyawan: Coba
Bulan: Desember 2025
Tahun: 2025

Data Monitoring:
- 01/12: Masuk 08:10 (jadwal 08:00) → 10 menit, dikurangi 5 menit = 5 menit dihitung
- 02/12: Masuk 08:03 (jadwal 08:00) → 3 menit, tidak dihitung (≤ 5 menit)
- 03/12: Masuk 07:50 (jadwal 08:00) → Tepat waktu, tidak dihitung
- 04/12: Masuk 09:00 (jadwal 08:00) → 60 menit, dikurangi 5 menit = 55 menit dihitung

Total Menit Terlambat = 5 + 0 + 55 = 60 menit
Potongan Gaji = 60 × 500 = Rp 30.000

Data Penggajian:
- Gaji Pokok: Rp 3.000.000
- Tunjangan: Rp 500.000
- Lain-lain: Rp 0
- Lembur: 5 jam
- Terlambat: 60 menit

Gaji Per Jam = 3.000.000 / 160 = Rp 18.750
Bonus Lembur = 5 × (18.750 × 1.5) = 5 × 28.125 = Rp 140.625
Potongan Terlambat = 60 × 500 = Rp 30.000

Total = 3.000.000 + 500.000 + 0 + 140.625 - 30.000 = Rp 3.610.625
```

## Database Fields
Tabel `penggajian`:
- `lembur` (int, default: 0): Jam kerja lembur
- `terlambat` (int, default: 0): Total menit keterlambatan
- `potongan` (int, default: 0): Jumlah potongan gaji dari keterlambatan

## Validasi
- Field `terlambat` bersifat required saat tambah/update
- Nilai default 0 jika tidak ada keterlambatan
- Nilai otomatis terisi dari monitoring, tapi bisa diedit manual

## Route API
```
POST /operator/penggajian/getTerlambatOtomatis

Parameters:
- user_id: ID user/karyawan
- bulan: Bulan (1-12)
- tahun: Tahun

Response:
{
  "status": "success",
  "terlambat_menit": 150,
  "terlambat_jam": 2,
  "terlambat_menit_sisa": 30,
  "display": "2 jam 30 menit"
}
```

## Testing
Setelah implementasi, lakukan testing:

1. **Test AJAX**: Pilih karyawan yang ada data monitoring terlambat
   - Verifikasi field Jam Terlambat terisi otomatis
   - Verifikasi helper text menampilkan format yang benar

2. **Test Perhitungan**: Simpan data penggajian
   - Verifikasi potongan = menit terlambat × 500
   - Verifikasi total gaji sudah dikurangi potongan

3. **Test Edit**: Edit data penggajian
   - Verifikasi nilai terlambat terbaca dengan benar
   - Verifikasi perhitungan total ulang dengan benar

4. **Test Manual Edit**: Edit field terlambat manual
   - Verifikasi potongan terhitung dengan nilai baru
   - Verifikasi total terhitung dengan benar

## Notes
- Keterlambatan yang kurang dari atau sama dengan 5 menit tidak dihitung
- Perhitungan menit terlambat hanya untuk hari kerja dengan status "image_out" (pulang)
- AJAX memerlukan jQuery (sudah ada di template)
- Nilai terlambat dalam satuan MENIT, bukan jam
