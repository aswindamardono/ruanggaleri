<?php

namespace App\Models;

use CodeIgniter\Model;

class AbsensiModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'presents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = ['user_id', 'date', 'hour_in', 'image_in', 'location_in', 'hour_out', 'image_out', 'location_out', 'terlambat_menit'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getAbsensi()
    {
        return $this->where('user_id', session()->get('id'))->where('date', date('Y-m-d'))->first();
    }

    public function getAbsensiBulan($bulan = null, $tahun = null)
    {
        if (!$bulan) {
            $bulan = date('m');
        }
        if (!$tahun) {
            $tahun = date('Y');
        }
        return $this->where('MONTH(date)', $bulan)->where('YEAR(date)', $tahun)->where('user_id', session()->get('id'))->orderBy('date', 'ASC')->findAll();
    }


    public function getHadir($id, $bulan, $tahun)
    {
        $count = $this->where('user_id', $id)
            ->where('MONTH(date)', $bulan)
            ->where('YEAR(date)', $tahun)
            ->where('image_out !=', '')
            ->countAllResults();

        return $count ? $count : 0;
    }

    public function getJam($id, $bulan, $tahun)
    {
        $query = $this->where('user_id', $id)
            ->where('MONTH(date)', $bulan)
            ->where('YEAR(date)', $tahun)
            ->where('image_out !=', '');

        $totalJam = 0;
        $results = $query->findAll();

        foreach ($results as $result) {
            $date = date_create($result['date']);
            $day = date_format($date, 'D');

            $jadwal = $this->db->table('jadwal')
                ->where('hari', $day)
                ->where('user_id', $id)
                ->get()
                ->getRowArray();

            if ($jadwal) {
                $totalJam += $jadwal['jam_mengajar'];
            }
        }

        return $totalJam ?? 0;
    }

    public function getTerlambatMenit($id, $bulan, $tahun)
    {
        // Baca langsung dari database field terlambat_menit
        $result = $this->selectSum('terlambat_menit')
            ->where('user_id', $id)
            ->where('MONTH(date)', $bulan)
            ->where('YEAR(date)', $tahun)
            ->get()
            ->getRow();

        return $result->terlambat_menit ?? 0;
    }

    public function hitungDanUpdateTerlambat($id, $bulan, $tahun)
    {
        // Ambil semua record absensi untuk user, bulan, dan tahun tertentu
        $presents = $this->where('user_id', $id)
            ->where('MONTH(date)', $bulan)
            ->where('YEAR(date)', $tahun)
            ->findAll();

        foreach ($presents as $present) {
            $date = date_create($present['date']);
            $day = date_format($date, 'D');

            // Ambil jadwal untuk hari tersebut
            $jadwal = $this->db->table('jadwal')
                ->where('hari', $day)
                ->where('user_id', $id)
                ->get()
                ->getRowArray();

            $terlambat_menit = 0;

            if ($jadwal) {
                // Hitung selisih waktu antara jam_masuk jadwal dengan jam masuk actual
                $jam_masuk_jadwal = $jadwal['jam_masuk'];
                $jam_masuk_actual = $present['hour_in'];

                // Ubah ke timestamp untuk perbandingan
                $time_jadwal = strtotime($jam_masuk_jadwal);
                $time_actual = strtotime($jam_masuk_actual);

                // Jika actual lebih besar dari jadwal, maka ada keterlambatan
                if ($time_actual > $time_jadwal) {
                    $selisih = $time_actual - $time_jadwal;
                    $menit = floor($selisih / 60);
                    
                    // Jika terlambat lebih dari 5 menit, hitung sebagai terlambat
                    // Jika kurang dari atau sama dengan 5 menit, abaikan
                    if ($menit > 5) {
                        // Kurangi 5 menit dari keterlambatan
                        $terlambat_menit = $menit - 5;
                    }
                }
            }

            // Update field terlambat_menit pada record present
            $this->update($present['id'], [
                'terlambat_menit' => $terlambat_menit
            ]);
        }
    }



}