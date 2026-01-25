<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;

class Workorder extends BaseController
{
    public function index()
    {
        $data['title'] = 'Work Order';
        $workorder = $this->WorkOrderModel->orderBy('id', 'DESC')->findAll();
        
        // Tambahkan pegawai untuk setiap workorder
        foreach ($workorder as &$wo) {
            $wo['pegawai_list'] = $this->WorkorderPegawaiModel->select('users.name')
                                                               ->join('users', 'users.id = workorder_pegawai.user_id')
                                                               ->where('workorder_pegawai.workorder_id', $wo['id'])
                                                               ->findAll();
        }
        
        $data['workorder'] = $workorder;
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['setting'] = $this->PengaturanModel->find(1);
        return view('operator/workorder/read', $data);
    }

    public function create()
    {
        $data['title'] = 'Tambah Work Order';
        $data['pegawai'] = $this->KaryawanModel->where('role', 'Pegawai')->findAll();
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['setting'] = $this->PengaturanModel->find(1);
        return view('operator/workorder/create', $data);
    }

    public function save()
    {
        $user_ids_json = $this->request->getPost('user_id');
        $user_ids = json_decode($user_ids_json, true) ?? [];
        
        $validate = $this->validate([
            'user_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pegawai harus dipilih minimal 1!',
                ],
            ],
            'tanggal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal harus di isi!',
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
            return redirect()->to(base_url('/operator/workorder/create'))->withInput();
        }

        // Insert workorder
        $data_insert = [
            'tanggal' => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $workorder_id = $this->WorkOrderModel->insert($data_insert);

        // Insert multiple pegawai
        if (!empty($user_ids)) {
            foreach ($user_ids as $user_id) {
                $this->WorkorderPegawaiModel->insert([
                    'workorder_id' => $workorder_id,
                    'user_id' => $user_id,
                ]);
            }
        }

        session()->setFlashdata('pesan', 'Data Work Order berhasil ditambahkan');
        session()->setFlashdata('alert', 'success');
        return redirect()->to(base_url('/operator/workorder'));
    }

    public function delete($id)
    {
        // Delete from workorder_pegawai
        $this->WorkorderPegawaiModel->deletePegawaiByWorkorder($id);
        // Delete from workorder
        $this->WorkOrderModel->delete($id);
        session()->setFlashdata('pesan', 'Data Work Order berhasil dihapus');
        session()->setFlashdata('alert', 'success');
        return redirect()->to(base_url('/operator/workorder'));
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Work Order';
        $data['workorder'] = $this->WorkOrderModel->find($id);
        $data['pegawai_terpilih'] = $this->WorkorderPegawaiModel->getPegawaiByWorkorder($id);
        $data['pegawai'] = $this->KaryawanModel->where('role', 'Pegawai')->findAll();
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['setting'] = $this->PengaturanModel->find(1);
        return view('operator/workorder/edit', $data);
    }

    public function update($id)
    {
        $user_ids_json = $this->request->getPost('user_id');
        $user_ids = json_decode($user_ids_json, true) ?? [];

        $validate = $this->validate([
            'user_id' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Pegawai harus dipilih minimal 1!',
                ],
            ],
            'tanggal' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal harus di isi!',
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
            return redirect()->to(base_url('/operator/workorder/edit/' . $id))->withInput();
        }

        $data_update = [
            'tanggal' => $this->request->getPost('tanggal'),
            'keterangan' => $this->request->getPost('keterangan'),
        ];

        $this->WorkOrderModel->update($id, $data_update);

        // Delete old pegawai
        $this->WorkorderPegawaiModel->deletePegawaiByWorkorder($id);

        // Insert new pegawai
        if (!empty($user_ids)) {
            foreach ($user_ids as $user_id) {
                $this->WorkorderPegawaiModel->insert([
                    'workorder_id' => $id,
                    'user_id' => $user_id,
                ]);
            }
        }

        session()->setFlashdata('pesan', 'Data Work Order berhasil diubah');
        session()->setFlashdata('alert', 'success');
        return redirect()->to(base_url('/operator/workorder'));
    }
}
