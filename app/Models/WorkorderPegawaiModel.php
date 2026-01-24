<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkorderPegawaiModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'workorder_pegawai';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
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

    public function getPegawaiByWorkorder($workorder_id)
    {
        return $this->select('workorder_pegawai.*, users.name')
                    ->join('users', 'users.id = workorder_pegawai.user_id', 'left')
                    ->where('workorder_pegawai.workorder_id', $workorder_id)
                    ->findAll();
    }

    public function deletePegawaiByWorkorder($workorder_id)
    {
        return $this->where('workorder_id', $workorder_id)->delete();
    }
}
