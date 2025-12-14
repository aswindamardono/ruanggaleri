<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UpdateTerlambatPresentSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();
        $absensiModel = new \App\Models\AbsensiModel();
        
        // Ambil semua user yang punya data absensi
        $users = $db->table('presents')
            ->select('user_id')
            ->distinct()
            ->get()
            ->getResultArray();

        // Hitung dan update terlambat_menit untuk setiap user
        foreach ($users as $user) {
            $user_id = $user['user_id'];
            
            // Loop untuk 12 bulan tahun ini dan tahun sebelumnya
            $tahun_mulai = date('Y') - 1;
            $tahun_akhir = date('Y');
            
            for ($tahun = $tahun_mulai; $tahun <= $tahun_akhir; $tahun++) {
                for ($bulan = 1; $bulan <= 12; $bulan++) {
                    $absensiModel->hitungDanUpdateTerlambat($user_id, $bulan, $tahun);
                }
            }
        }

        echo "Update terlambat_menit selesai untuk semua user dan periode!\n";
    }
}
