<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;

class Lokasi extends BaseController
{   
    public function index()
    {
        $data['title'] = 'Lokasi';
        // $data['lokasi'] = $this->LokasiModel->findAll();
        $data['lokasi'] = $this->LokasiModel->getLokasiWithMandor();
        // $data['user'] = $this->KaryawanModel->getJabatanUser(1);
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['setting'] = $this->PengaturanModel->find(1);
        return view('operator/lokasi/read', $data);
    }

    public function create()
    {
        $data['title'] = 'Tambah Lokasi';
        $data['mandor'] = $this->KaryawanModel->getJabatanUser(1);                      
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['lokasi'] = $this->LokasiModel->findAll();
        return view('operator/lokasi/create', $data);
    }

    public function save()
    {
        $validate = $this->validate([
            'lokasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Lokasi harus di isi!',
                ],
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat harus di isi!',
                ],
            ],
            'long' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Longtitude harus di isi!',
                ],
            ],
            'lat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Latitude harus di isi!',
                ],
            ],
            'radius' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Radius harus di isi!',
                ],
            ],
            'sebelum_masuk' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Sebelum Masuk harus di isi!',
                ],
            ],
            'sebelum_pulang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Sebelum Pulang harus di isi!',
                ],
            ],
            'setelah_pulang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Setelah Pulang harus di isi!',
                ],
            ],   
            'jam_masuk' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam Masuk harus di isi!',
                ],
            ],
            'jam_keluar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam Pulang harus di isi!',
                ],
            ],            
            'mandor' => [
                'rules' => 'required|is_unique[lokasi.user_id]',
                'errors' => [
                    'required' => 'Mandor harus di pilih!',
                    'is_unique' => 'Mandor sudah ada, silakan masukkan nama mandor yang berbeda!',
                ],
            ],
        ]);
            
        if (!$validate) {
            return redirect()->to(base_url('/operator/lokasi/create'))->withInput();
        }
        $this->LokasiModel->insert([
            'user_id' =>  $this->request->getPost('mandor'),
            'lokasi' =>  $this->request->getPost('lokasi'),
            'address' =>  $this->request->getPost('alamat'),
            'long' =>  $this->request->getPost('long'),
            'lat' =>  $this->request->getPost('lat'),
            'radius' =>  $this->request->getPost('radius'),
            'jam_masuk' => $this->request->getPost('jam_masuk'),
            'jam_keluar' => $this->request->getPost('jam_keluar'),
            'sebelum_masuk' =>  $this->request->getPost('sebelum_masuk'),
            'sebelum_pulang' =>  $this->request->getPost('sebelum_pulang'),
            'setelah_pulang' =>  $this->request->getPost('setelah_pulang'),          
        ]);

        $hari = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        $user_id = $this->request->getPost('mandor');        
        $lokasi = $this->request->getPost('lokasi');
        $alamat = $this->request->getPost('alamat');
        $long = $this->request->getPost('long');
        $lat = $this->request->getPost('lat');
        $radius = $this->request->getPost('radius');
        // $hari = $haris;
        $jam_masuk = $this->request->getPost('jam_masuk');
        $jam_keluar = $this->request->getPost('jam_keluar');
        $jam_mengajar = '6';
        $status = '1';
        $data = [];
        for ($i = 0; $i < 7; $i++) {
            $data[] = [
                'user_id' => $user_id,
                'hari' => $hari[$i],
                'jam_masuk' => $jam_masuk,
                'jam_keluar' => $jam_keluar,
                'jam_mengajar' => '6',
                'status' => '1',
                'status_backup' => '1',
            ];
        };
        $this->JadwalModel->insertBatch($data);

        session()->setFlashdata('pesan', 'Data Lokasi berhasil ditambahkan.');
        return redirect()->to(base_url('/operator/lokasi'));
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Lokasi';        
        $data['lokasi'] = $this->LokasiModel->find($id);
        $data['mandor'] = $this->KaryawanModel->getJabatanUser(1);                
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['setting'] = $this->PengaturanModel->find(1);
        return view('operator/lokasi/update', $data);
    }

    public function update($id)
    {
        $validate = $this->validate([
            'lokasi' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama Lokasi harus di isi!',
                ],
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat harus di isi!',
                ],
            ],
            'long' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Longtitude harus di isi!',
                ],
            ],
            'lat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Latitude harus di isi!',
                ],
            ],
            'radius' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Radius harus di isi!',
                ],
            ],
            'sebelum_masuk' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Sebelum Masuk harus di isi!',
                ],
            ],
            'sebelum_pulang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Sebelum Pulang harus di isi!',
                ],
            ],
            'setelah_pulang' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Setelah Pulang harus di isi!',
                ],
            ],   
            'jam_masuk' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam Masuk harus di isi!',
                ],
            ],
            'jam_keluar' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam Pulang harus di isi!',
                ],
            ],            
            // 'mandor' => [
            //     'rules' => 'required|is_unique[lokasi.user_id]',
            //     'errors' => [
            //         'required' => 'Mandor harus di pilih!',
            //         'is_unique' => 'Mandor sudah ada, silakan masukkan nama mandor yang berbeda!',
            //     ],
            // ],
        ]);
        if (!$validate) {
            return redirect()->to(base_url('/operator/lokasi/edit/'.$id))->withInput();
        }
        
        $this->LokasiModel->update($id,[
            'user_id' =>  $this->request->getPost('mandor'),
            'lokasi' =>  $this->request->getPost('lokasi'),
            'address' =>  $this->request->getPost('alamat'),
            'long' =>  $this->request->getPost('long'),
            'lat' =>  $this->request->getPost('lat'),
            'radius' =>  $this->request->getPost('radius'),
            'jam_masuk' => $this->request->getPost('jam_masuk'),
            'jam_keluar' => $this->request->getPost('jam_keluar'),
            'sebelum_masuk' =>  $this->request->getPost('sebelum_masuk'),
            'sebelum_pulang' =>  $this->request->getPost('sebelum_pulang'),
            'setelah_pulang' =>  $this->request->getPost('setelah_pulang'), 
        ]);
       
        $hari = ['Sun','Mon','Tue','Wed','Thu','Fri','Sat'];
        $user_id = $this->request->getPost('mandor');        
        $lokasi = $this->request->getPost('lokasi');
        $alamat = $this->request->getPost('alamat');
        $long = $this->request->getPost('long');
        $lat = $this->request->getPost('lat');
        $radius = $this->request->getPost('radius');
        // $hari = $hari;
        $jam_masuk = $this->request->getPost('jam_masuk');
        $jam_keluar = $this->request->getPost('jam_keluar');
        $jam_mengajar = '6';
        $status = '1';

        $this->JadwalModel->set([
            'jam_masuk' => $this->request->getPost('jam_masuk'),
            'jam_keluar' => $this->request->getPost('jam_keluar'),
        ])->where('user_id', $user_id)->update();
        
        $this->JadwalModel->set([
            'jam_masuk' => $this->request->getPost('jam_masuk'),
            'jam_keluar' => $this->request->getPost('jam_keluar'),
        ])->where('lokasi_id', $id)->update();

        //$this->JadwalModel->where('lokasi_id', $id)->updateBatch($data2, 'hari');

        session()->setFlashdata('pesan', 'Data Lokasi berhasil diubah.');
        return redirect()->to(base_url('/operator/lokasi'));
    }

    public function delete($id)
    {
        $lokasi = $this->LokasiModel->find($id);
        if ($lokasi) {
            $user_id = $lokasi['user_id'];
            $this->JadwalModel->where('user_id', $user_id)->delete();
            $this->LokasiModel->delete($id);
            session()->setFlashdata('pesan', 'Data Lokasi berhasil dihapus.');
        } else {
            session()->setFlashdata('pesan_error', 'Data Lokasi tidak ditemukan.');
        }
        return redirect()->to(base_url('/operator/lokasi'));
    }
}