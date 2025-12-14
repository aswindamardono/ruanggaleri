# Dokumentasi Fitur Lembur dan Terlambat Penggajian

## Deskripsi Fitur
Fitur ini menambahkan sistem perhitungan gaji yang lebih komprehensif dengan memperhitungkan:
- **Lembur**: Bonus gaji tambahan untuk jam kerja ekstra
- **Terlambat**: Potongan gaji untuk keterlambatan

## Rumus Perhitungan

### Gaji Pokok Per Jam
```
Gaji Per Jam = Gaji Pokok / Total Jam Kerja
```

### Bonus Lembur
```
Bonus Lembur = Jam Lembur × (Gaji Per Jam × 1.5)
```
- Jam lembur dihitung dengan multiplier 1.5x gaji per jam

### Potongan Terlambat
```
Potongan Terlambat = Jam Terlambat × (Gaji Per Jam × 0.5)
```
- Jam terlambat dihitung dengan multiplier 0.5x gaji per jam

### Total Gaji Final
```
Total Gaji = Gaji Pokok + Tunjangan + Lain-lain + Bonus Lembur - Potongan Terlambat
```

## Perubahan yang Dilakukan

### 1. Model (PenggajianModel.php)
- Menambahkan field ke `allowedFields`:
  - `lembur`: Jam kerja lembur
  - `terlambat`: Jam keterlambatan
  - `potongan`: Potongan gaji dari keterlambatan

### 2. Controller (Penggajian.php)
- **Method save()**:
  - Menambahkan validasi untuk field `lembur` dan `terlambat`
  - Melakukan kalkulasi bonus lembur dan potongan terlambat
  - Menyimpan data lembur, terlambat, dan potongan ke database

- **Method update()**:
  - Menambahkan validasi untuk field `lembur1` dan `terlambat1`
  - Melakukan kalkulasi ulang total gaji berdasarkan data baru
  - Update record penggajian dengan nilai terbaru

### 3. View (read.php)
- **Tabel Penggajian**:
  - Menambahkan kolom: "Lembur (Jam)", "Terlambat (Jam)", "Potongan"
  - Menampilkan nilai lembur, terlambat, dan potongan untuk setiap data gaji

- **Modal Tambah Data**:
  - Input field "Jam Lembur" (default 0)
  - Input field "Jam Terlambat" (default 0)
  - Keterangan bantuan untuk masing-masing field

- **Modal Edit Data**:
  - Input field "Jam Lembur" dengan nilai lama
  - Input field "Jam Terlambat" dengan nilai lama
  - Keterangan bantuan untuk masing-masing field

- **JavaScript**:
  - Fungsi `calculateTotal()` untuk hitung total otomatis saat tambah data
  - Fungsi `calculateEditTotal()` untuk hitung total otomatis saat edit data
  - Mempertimbangkan bonus lembur dan potongan terlambat dalam perhitungan

### 4. Seeder (PenggajianSeeder.php)
- Generate data random untuk:
  - Jam lembur: 0-10 jam
  - Jam terlambat: 0-15 jam
  - Perhitungan bonus dan potongan otomatis
  - Total gaji dengan semua komponen

## Cara Penggunaan

### Menambah Data Penggajian
1. Klik tombol "Tambah" di halaman Penggajian
2. Pilih bulan, tahun, dan karyawan
3. Input gaji pokok, tunjangan, lain-lain
4. Input jam lembur dan jam terlambat
5. Total otomatis akan terhitung
6. Klik "Submit" untuk simpan

### Mengedit Data Penggajian
1. Klik tombol "Edit" pada baris data gaji
2. Ubah nilai gaji pokok, tunjangan, lain-lain, lembur, atau terlambat
3. Total otomatis akan terhitung ulang
4. Klik "Update" untuk simpan perubahan

### Menjalankan Seeder
```bash
cd D:\xampp\htdocs\ruanggaleri
D:\xampp\php\php.exe spark db:seed PenggajianSeeder
```

## Database Fields
Tabel `penggajian` sekarang memiliki field tambahan:
- `lembur` (int): Jam kerja lembur
- `terlambat` (int): Jam keterlambatan
- `potongan` (int): Jumlah potongan gaji dari keterlambatan

## Validasi Input
- `lembur`: Required, harus diisi
- `terlambat`: Required, harus diisi
- Nilai default adalah 0 jika tidak ada lembur atau terlambat

## Note
- Perhitungan dilakukan real-time di JavaScript saat input data
- Perhitungan juga dilakukan di backend saat saving untuk validasi
- Semua perhitungan menggunakan integer untuk menghindari decimal values
- Field `potongan` diisi otomatis berdasarkan perhitungan
