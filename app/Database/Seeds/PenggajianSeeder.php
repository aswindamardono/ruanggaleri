<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PenggajianSeeder extends Seeder
{
    public function run()
    {
        $data = [];
        
        // Generate sample data for 12 months and 5 employees
        $bulan_range = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
        $user_ids = [2, 3, 4, 5, 6]; // Assuming users with role 'Pegawai'
        $tahun = date('Y');
        $admin_id = 1; // Assuming admin user
        
        foreach ($bulan_range as $bulan) {
            foreach ($user_ids as $user_id) {
                // Generate random salary components
                $gaji_pokok = rand(3000000, 5000000);
                $tunjangan = rand(500000, 1500000);
                $lain_lain = rand(0, 500000);
                $total_jam = rand(160, 200);
                $total_absensi = rand(18, 22);
                
                // Generate random overtime and lateness
                $lembur = rand(0, 10);
                $terlambat = rand(0, 15);
                
                // Calculate bonus lembur and potongan terlambat
                $gaji_per_jam = $total_jam > 0 ? $gaji_pokok / $total_jam : 0;
                $bonus_lembur = $lembur * ($gaji_per_jam * 1.5);
                $potongan_terlambat = $terlambat * ($gaji_per_jam * 0.5);
                
                // Total gaji final
                $total = $gaji_pokok + $tunjangan + $lain_lain + $bonus_lembur - $potongan_terlambat;
                
                $data[] = [
                    'user_id' => $user_id,
                    'bulan' => $bulan,
                    'tahun' => $tahun,
                    'total_jam' => $total_jam,
                    'total_absensi' => $total_absensi,
                    'gaji' => $total,
                    'gaji_pokok' => $gaji_pokok,
                    'tunjangan' => $tunjangan,
                    'lain_lain' => $lain_lain,
                    'lembur' => $lembur,
                    'terlambat' => $terlambat,
                    'potongan' => (int)$potongan_terlambat,
                    'total' => (int)$total,
                    'admin_id' => $admin_id,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];
            }
        }
        
        // Using Query Builder
        $this->db->table('penggajian')->insertBatch($data);
    }
}
