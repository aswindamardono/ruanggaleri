<?php

namespace App\Models;

use CodeIgniter\Model;

class WorkOrderModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'workorder';
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

    public function getWorkOrderWithUser()
    {
        return $this->select('workorder.*, users.name')
                    ->join('users', 'users.id = workorder.user_id', 'left')
                    ->orderBy('workorder.tanggal', 'DESC')
                    ->findAll();
    }

    public function getWorkOrderByUserWithName($user_id)
    {
        return $this->select('workorder.*, users.name')
                    ->join('users', 'users.id = workorder.user_id', 'left')
                    ->where('workorder.user_id', $user_id)
                    ->orderBy('workorder.tanggal', 'DESC')
                    ->findAll();
    }
}
