<?php

namespace App\Models;

use CodeIgniter\Model;

class GajiMingguanModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'gaji_mingguan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = ['user_id', 'tanggal_mulai', 'tanggal_selesai', 'total_jam', 'total_absensi', 'gaji', 'gaji_pokok', 'tunjangan', 'lain_lain', 'lembur', 'terlambat', 'potongan', 'total', 'admin_id'];

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

    public function getSum($tanggal_mulai, $tanggal_selesai)
    {
        $total = $this->selectSum('total')
            ->where('tanggal_mulai', $tanggal_mulai)
            ->where('tanggal_selesai', $tanggal_selesai)
            ->get()
            ->getRow()
            ->total;
        return $total;
    }
}
