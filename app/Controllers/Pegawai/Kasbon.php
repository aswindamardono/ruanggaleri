<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;

class Kasbon extends BaseController
{
    public function index()
    {
        $data['title'] = 'Kasbon';
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['kasbon'] = $this->KasbonModel->where('user_id', session()->get('id'))->findAll();
        $data['setting'] = $this->PengaturanModel->find(1);
        return view('pegawai/kasbon/read', $data);
    }

    public function create()
    {
        $data['title'] = 'Ajukan Kasbon';
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['setting'] = $this->PengaturanModel->find(1);
        return view('pegawai/kasbon/create', $data);
    }

    public function save()
    {
        $validate = $this->validate([
            'jumlah' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'Jumlah kasbon harus di isi!',
                    'numeric' => 'Jumlah kasbon harus berupa angka!',
                ],
            ],
            'keterangan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Keterangan harus di isi!',
                ],
            ],
        ]);

        if (!$validate) {
            return redirect()->to(base_url('/kasbon/create'))->withInput();
        }

        $this->KasbonModel->insert([
            'user_id' => session()->get('id'),
            'nominal' => $this->request->getPost('jumlah'),
            'keterangan' => $this->request->getPost('keterangan'),
            'persetujuan' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        session()->setFlashdata('pesan', 'Permohonan kasbon berhasil diajukan, menunggu persetujuan operator.');
        return redirect()->to(base_url('/kasbon'));
    }
}
