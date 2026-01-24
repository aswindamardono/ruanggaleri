<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;

class Kasbon extends BaseController
{
    public function index()
    {
        $data['title'] = 'Kasbon';
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['bulan'] = [
            [ "no" => 1, "nama" => "Januari"],
            [ "no" => 2, "nama" => "Februari"],
            [ "no" => 3, "nama" => "Maret"],
            [ "no" => 4, "nama" => "April"],
            [ "no" => 5, "nama" => "Mei"],
            [ "no" => 6, "nama" => "Juni"],
            [ "no" => 7, "nama" => "Juli"],
            [ "no" => 8, "nama" => "Agustus"],
            [ "no" => 9, "nama" => "September"],
            [ "no" => 10, "nama" => "Oktober"],
            [ "no" => 11, "nama" => "November"],
            [ "no" => 12, "nama" => "Desember"],
        ];
        $data['tahun'] = [ date("Y"), date("Y")-1, date("Y")-2, date("Y")-3, date("Y")-4];
        $data['bulan1'] = '';
        $data['tahun1'] = '';
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['kasbon'] = $this->KaryawanModel->getAllKasbon();
        return view('operator/kasbon/read', $data);
    }

    public function updatekasbon($id)
    {
        $validate = $this->validate([
            'persetujuan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Persetujuan harus di isi!',
                ],
            ],
        ]);

        if (!$validate) {
            return redirect()->to(base_url('/operator/kasbon/'))->withInput();
        }

        $this->KasbonModel->update($id, [
            'persetujuan' => $this->request->getPost('persetujuan'),
            'admin_id' => session()->get('id'),
        ]);
        session()->setFlashdata('pesan', 'Data Permohonan Kasbon berhasil diupdate.');
        return redirect()->to(base_url('/operator/kasbon'));
    }

    public function carikasbon()
    {
        $validate = $this->validate([
            'bulan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Bulan harus di isi!',
                ],
            ],
            'tahun' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tahun harus di isi!',
                ],
            ],
        ]);
        if (!$validate) {
            return redirect()->to(base_url('/operator/kasbon'))->withInput();
        }
        $bulan1 = $this->request->getPost('bulan');
        $tahun1 = $this->request->getPost('tahun');
        $data['title'] = 'Kasbon';
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['bulan'] = [
            [ "no" => 1, "nama" => "Januari"],
            [ "no" => 2, "nama" => "Februari"],
            [ "no" => 3, "nama" => "Maret"],
            [ "no" => 4, "nama" => "April"],
            [ "no" => 5, "nama" => "Mei"],
            [ "no" => 6, "nama" => "Juni"],
            [ "no" => 7, "nama" => "Juli"],
            [ "no" => 8, "nama" => "Agustus"],
            [ "no" => 9, "nama" => "September"],
            [ "no" => 10, "nama" => "Oktober"],
            [ "no" => 11, "nama" => "November"],
            [ "no" => 12, "nama" => "Desember"],
        ];
        $data['tahun'] = [ date("Y"), date("Y")-1, date("Y")-2, date("Y")-3, date("Y")-4];
        $data['bulan1'] = $bulan1;
        $data['tahun1'] = $tahun1;
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['kasbon'] = $this->KaryawanModel->getKasbon($bulan1, $tahun1);
        return view('operator/kasbon/read', $data);
    }
}
