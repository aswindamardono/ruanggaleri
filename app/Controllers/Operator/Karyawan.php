<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;

class Karyawan extends BaseController
{
    public function index()
    {
        $data['title'] = 'Karyawan';
        $data['karyawan'] = $this->KaryawanModel->getUserWithJabatan();
        $data['JabatanModel'] = $this->JabatanModel;
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['setting'] = $this->PengaturanModel->find(1);
        return view('operator/karyawan/read', $data);
    }

    public function create()
    {
        $data['title'] = 'Tambah Karyawan';
        $data['jabatan'] = $this->JabatanModel->findAll();
        $data['role'] = ['Mandor', 'Pegawai', 'Operator'];
        $data['status'] = ['Aktif', 'Tidak Aktif'];
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['setting'] = $this->PengaturanModel->find(1);
        return view('operator/karyawan/create', $data);
    }

    public function save()
    {
        $validate = $this->validate([
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama harus di isi!',
                ],
            ],
            // 'email' => [
            //      'rules' => 'required|is_unique[users.email]',
            //      'errors' => [
            //          'required' => 'Email harus di isi!',
            //          'is_unique' => 'Email sudah ada, silakan masukkan nama karyawan yang berbeda!',
            //      ],
            // ],
            'nik' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'NIK harus di isi!',
                ],
            ],
            // 'phone' => [
            //      'rules' => 'required',
            //      'errors' => [
            //          'required' => 'No Handphone harus di isi!',
            //      ],
            // ],
            'jabatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jabatan harus di isi!',
                ],
            ],
            // 'lokasi' => [
            //      'rules' => 'required',
            //      'errors' => [
            //          'required' => 'Jika bukan Mandor Lokasi harus di pilih!',
            //      ],
            // ],
            'role' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Role harus di isi!',
                ],
            ],
            'status' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Status harus di isi!',
                ],
            ],
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password harus di isi!',
                ],
            ],
            'confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'matches' => 'Konfirmasi Password tidak sesuai dengan password!',
                    'required' => 'Konfirmasi Password harus di isi!',
                ],
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat harus di isi!',
                ],
            ],
        ]);

        if (!$validate) {
            return redirect()->to(base_url('/operator/karyawan/create'))->withInput();
        }

        $password = $this->request->getPost('password');
        $salt = base64_encode(random_bytes(16));
        $passwordHash = hash('sha256', $password.$salt);

        // Insert Data Karyawan
        $this->KaryawanModel->insert([
            'name' =>  $this->request->getPost('name'),
            'email' =>  $this->request->getPost('email'),
            'nik' =>  $this->request->getPost('nik'),
            'phone' =>  $this->request->getPost('phone'),
            'jabatan_id' =>  $this->request->getPost('jabatan'),
            'lokasi_id' =>  0,
            'role' =>  $this->request->getPost('role'),
            'is_active' =>  $this->request->getPost('status'),
            'alamat' =>  $this->request->getPost('alamat'),
            'password' => $passwordHash,
            'salt' => $salt,
        ]);

        session()->setFlashdata('pesan', 'Data Karyawan berhasil ditambahkan.');
        return redirect()->to(base_url('/operator/karyawan'));
    }
    
    public function delete($id)
    {
        $karyawan = $this->KaryawanModel->find($id);
        if($karyawan){        
            $image = $karyawan['image'];
            if ($image && file_exists(ROOTPATH.'public/assets/img/user/'.$image)) {
                if ($image != "default.jpg") {
                    unlink(ROOTPATH.'public/assets/img/user/'.$image);
                }
            }
            $this->KaryawanModel->delete($id);
            session()->setFlashdata('pesan', 'Data Karyawan berhasil dihapus.');
            return redirect()->to(base_url('/operator/karyawan'));
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Karyawan';
        $data['karyawan'] = $this->KaryawanModel->find($id);
        $data['jabatan'] = $this->JabatanModel->findAll();
        $data['role'] = ['Mandor', 'Pegawai', 'Operator'];
        $data['status'] = ['Aktif', 'Tidak Aktif'];
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['setting'] = $this->PengaturanModel->find(1);
        return view('operator/karyawan/update', $data);
    }

    public function update($id)
    {
        $validate = $this->validate([
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama harus di isi!',
                ],
            ],
            'email' => [
                // 'rules' => 'required',
                'errors' => [
                    'required' => 'Email harus di isi!',
                ],
            ],
            'nik' => [
                'rules' => 'required|numeric',
                'errors' => [
                    'required' => 'NIK harus di isi!',
                ],
            ],
            'phone' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'No Handphone harus di isi!',
                ],
            ],
            'jabatan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jabatan harus di isi!',
                ],
            ],
            'role' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Role harus di isi!',
                ],
            ],
            'status' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Status harus di isi!',
                ],
            ],
            'alamat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Alamat harus di isi!',
                ],
            ],
            'ktp' => [
                'rules' => 'is_image[ktp]|max_size[ktp,5120]',
                'errors' => [
                    'is_image' => 'File KTP harus berupa gambar atau PDF!',
                    'max_size' => 'Ukuran file KTP tidak boleh lebih dari 5MB!',
                ],
            ],
        ]);
        if (!$validate) {
            return redirect()->to(base_url('/operator/karyawan/edit/'.$id))->withInput();
        }

        $ktp = $this->request->getFile('ktp');
        $dataUpdate = [
            'name' =>  $this->request->getPost('name'),
            'email' =>  $this->request->getPost('email'),
            'nik' =>  $this->request->getPost('nik'),
            'phone' =>  $this->request->getPost('phone'),
            'jabatan_id' =>  $this->request->getPost('jabatan'),
            'lokasi_id' =>  0,
            'role' =>  $this->request->getPost('role'),
            'is_active' =>  $this->request->getPost('status'),
            'alamat' =>  $this->request->getPost('alamat'),
        ];

        // Handle KTP file upload
        if ($ktp && $ktp->isValid() && !$ktp->hasMoved()) {
            $user = $this->KaryawanModel->find($id);
            $oldKtp = $user['ktp'];
            
            // Delete old KTP if exists
            if ($oldKtp && file_exists('assets/img/user/' . $oldKtp)) {
                unlink('assets/img/user/' . $oldKtp);
            }

            // Upload new KTP
            $newKtpName = $ktp->getRandomName();
            $ktp->move('assets/img/user/', $newKtpName);
            $dataUpdate['ktp'] = $newKtpName;
        }

        $this->KaryawanModel->update($id, $dataUpdate);
        session()->setFlashdata('pesan', 'Data Karyawan berhasil di ubah');
        return redirect()->to(base_url('/operator/karyawan'));
    }

    public function changepassword($id)
    {
        $validate = $this->validate([
            'password' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Password harus di isi!',
                ],
            ],
            'confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'matches' => 'Konfirmasi Password tidak sesuai dengan password!',
                    'required' => 'Konfirmasi Password harus di isi!',
                ],
            ],
        ]);
        if (!$validate) {
            return redirect()->to(base_url('/operator/karyawan/edit/'.$id))->withInput();
        }
        $password = $this->request->getPost('password');
        $salt = base64_encode(random_bytes(16));
        $passwordHash = hash('sha256', $password.$salt);
        $this->KaryawanModel->update($id, [
            'password' => $passwordHash,
            'salt' => $salt,
        ]);
        session()->setFlashdata('pesan', 'Data Password Karyawan berhasil di ubah');
        return redirect()->to(base_url('/operator/karyawan'));
    }
}