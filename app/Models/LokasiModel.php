<?php

namespace App\Models;

use CodeIgniter\Model;

class LokasiModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'lokasi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];

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

    //Join
    // public function getLokasiWithMandor()
    // {
    //     return $this->select('users.*, jabatan.name_jabatan, jabatan.akronim')
    //         ->join('jabatan', 'jabatan.id = users.jabatan_id')
    //         ->get()
    //         ->getResultArray();
    // }

    public function getLokasiWithMandor()
    {
        return $this->select('lokasi.*, users.name')
            ->join('users', 'users.id = lokasi.user_id')
            ->where('users.jabatan_id', 1)
            ->get()
            ->getResultArray();
    }

    public function lokasiUser($id)
    {
        return $this->select('lokasi.*, users.name')
            ->join('users', 'users.lokasi_id = lokasi.id')            
            ->where('users.id', $id)
            ->get()
            ->getRowArray();
    }

    public function getLokasiJadwalUser($id)
    {        
        return $this->select('users.*, jadwal.*, lokasi.*')
            ->join('jadwal', 'lokasi.id = jadwal.lokasi_id') 
            ->join('users', 'users.lokasi_id = lokasi.id')            
            ->where('jadwal.user_id', $id)            
            ->get()
            ->getRowArray();
    }
}