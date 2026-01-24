<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;

class Home extends BaseController
{    
    public function index()
    {
        $data['title'] = 'Home';
        $bulan = date("m");
        $tahun = date("Y");
        $user_id = session()->get('id');
        
        $data['user'] = $this->KaryawanModel->getUserAndJabatan($user_id);
        $data['absensi'] = $this->AbsensiModel->getAbsensi();
        $data['absensi_bulan'] = $this->AbsensiModel->getAbsensiBulan();
        $data['hadir'] = $this->AbsensiModel->getHadir($user_id, $bulan, $tahun);
        $data['izin'] = $this->UnableModel->getUnable($user_id, "Izin",  $bulan, $tahun);
        $data['sakit'] = $this->UnableModel->getUnable($user_id, "Sakit",  $bulan, $tahun);
        $data['terlambat'] = $this->KaryawanModel->hitungKeterlambatan($user_id, $bulan, $tahun);
        $data['setting'] = $this->PengaturanModel->find(1);
        
        // Get workorder baru yang assigned ke pegawai
        $workorder_ids = $this->db->query('SELECT DISTINCT workorder_id FROM workorder_pegawai WHERE user_id = ? ORDER BY workorder_id DESC LIMIT 1', [$user_id])->getResultArray();
        if (!empty($workorder_ids)) {
            $latest_workorder_id = $workorder_ids[0]['workorder_id'];
            $data['new_workorder'] = $this->WorkOrderModel->find($latest_workorder_id);
        } else {
            $data['new_workorder'] = null;
        }
        
        return view('pegawai/home/read', $data);
    }
}