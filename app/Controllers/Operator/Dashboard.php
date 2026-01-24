<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;

class Dashboard extends BaseController
{
    public function index()
    {
        $data['title'] = 'Dashboard';
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['jabatan'] = $this->JabatanModel->countAllResults();
        $data['gty'] = $this->KaryawanModel->where('jabatan_id', 2)->countAllResults();
        $data['gtty'] = $this->KaryawanModel->where('jabatan_id', 5)->countAllResults();
        $data['uptd'] = $this->KaryawanModel->where('jabatan_id', 4)->countAllResults();
        $data['operator'] = $this->KaryawanModel->where('jabatan_id', 3)->countAllResults();
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['penggajian'] = $this->PenggajianModel;
        
        // Count data untuk dashboard cards
        $data['total_workorder'] = $this->WorkOrderModel->countAllResults();
        $data['total_pegawai'] = $this->KaryawanModel->whereIn('role', ['Pegawai'])->countAllResults();
        $data['total_izin'] = $this->UnableModel->countAllResults();
        $data['total_kasbon'] = $this->KasbonModel->countAllResults();
        
        return view('operator/dashboard/read', $data);
    }
}