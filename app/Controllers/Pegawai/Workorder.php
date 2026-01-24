<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;

class Workorder extends BaseController
{
    public function index()
    {
        $data['title'] = 'Work Order';
        $user_id = session()->get('id');
        
        // Get workorder IDs dari workorder_pegawai
        $workorder_ids = $this->db->query('SELECT DISTINCT workorder_id FROM workorder_pegawai WHERE user_id = ?', [$user_id])->getResultArray();
        $ids = array_column($workorder_ids, 'workorder_id');
        
        // Get workorder yang assigned ke pegawai ini
        if (!empty($ids)) {
            $data['workorder'] = $this->WorkOrderModel->whereIn('id', $ids)
                ->orderBy('tanggal', 'DESC')
                ->findAll();
        } else {
            $data['workorder'] = [];
        }
            
        $data['user'] = $this->KaryawanModel->getUserAndJabatan($user_id);
        $data['setting'] = $this->PengaturanModel->find(1);
        return view('pegawai/workorder/read', $data);
    }
}
