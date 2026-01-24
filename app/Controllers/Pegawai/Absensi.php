<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;

class Absensi extends BaseController
{
    public function index()
    {
        $cekAbsensi = $this->AbsensiModel->getAbsensi();
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['jadwal'] = $this->KaryawanModel->getUserJadwal(session()->get('id'));
        
        // Ambil jam dari jadwal, atau gunakan default jika tidak ada
        $jam_masuk = '08:00:00';
        $jam_keluar = '17:00:00';
        
        if (!empty($data['jadwal'])) {
            $jam_masuk = $data['jadwal']['jam_masuk'] ?? '08:00:00';
            $jam_keluar = $data['jadwal']['jam_keluar'] ?? '17:00:00';
        }
        
        $data['lokasi'] = [
            'lokasi' => '-',
            'lat' => 0,
            'long' => 0,
            'radius' => 0,
            'jam_masuk' => $jam_masuk,
            'jam_keluar' => $jam_keluar
        ];
        
        if ($cekAbsensi == null) {
            $data['title'] = 'Absensi Masuk';
            $data['user'] = $this->KaryawanModel->find(session()->get('id'));
            $data['izin'] = $this->UnableModel->check(session()->get('id'));
            return view('pegawai/absen/in', $data);
        } else {
            if ($cekAbsensi['image_out'] == null && $cekAbsensi['location_out'] == null) {
                $data['title'] = 'Absensi Pulang';
                $data['user'] = $this->KaryawanModel->find(session()->get('id'));
                return view('pegawai/absen/out', $data);
            } else {
                $data['title'] = 'Sudah Absensi';
                $data['absensi'] = $this->AbsensiModel->getAbsensi();
                $data['user'] = $this->KaryawanModel->find(session()->get('id'));
                return view('pegawai/absen/complete', $data);
            }
        }
    }
    
    public function masuk()
    {
        try {
            $image_data = $this->request->getPost('image');
            $image_path = 'assets/img/absensi/';
            $image_parts = explode(";base64,", $image_data);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file_name = uniqid() . '.'.$image_type;
            $file_path = $image_path . $file_name;

            $lokasi = $this->request->getPost('lokasi');
            $user_id = session()->get('id');
            // Fitur pembatasan area lokasi telah dihilangkan - karyawan dapat absen dari mana saja
            if (empty($lokasi)) {
                // Lokasi opsional jika fitur lokasi tidak aktif di device
                $lokasi = '0,0';
            }
            if (file_put_contents($file_path, $image_base64)) {
                        // Hitung terlambat_menit berdasarkan jadwal
                        $hari_ini = date('D');
                        $jam_masuk_actual = date('H:i:s');
                        $terlambat_menit = 0;
                        
                        // Ambil jadwal untuk hari ini menggunakan model
                        $db = \Config\Database::connect();
                        $jadwal = $db->table('jadwal')
                            ->where('hari', $hari_ini)
                            ->where('user_id', $user_id)
                            ->get()
                            ->getRowArray();
                        
                        if ($jadwal) {
                            // Gunakan helper function untuk hitung menit terlambat
                            $jam_masuk_jadwal = $jadwal['jam_masuk'];
                            $menit_selisih = menit_terlambat($jam_masuk_jadwal, $jam_masuk_actual);
                            
                            // Jika terlambat lebih dari 5 menit
                            if ($menit_selisih > 5) {
                                $terlambat_menit = $menit_selisih - 5;
                            }
                        }
                        
                        $this->AbsensiModel->insert([
                            'user_id' => $user_id,
                            'date' => date('Y-m-d'),
                            'hour_in' => date('H:i:s'),
                            'hour_out' => '00:00:00',
                            'image_in' => $file_name,
                            'image_out' => '',
                            'location_in' => $lokasi,
                            'location_out' => '',
                            'terlambat_menit' => $terlambat_menit,
                        ]);
                
                echo 'success|Absensi berhasil. Terima Kasih, Selamat Bekerja';
            }
        } catch (\Exception $e) {
            echo 'error1|Error: ' . $e->getMessage();
        }
    }

    public function distance($lat1, $lon1, $lat2, $lon2)
    {
        // Method ini tidak lagi digunakan karena fitur pembatasan lokasi telah dihilangkan
        // Disimpan untuk backward compatibility
        $theta = $lon1 - $lon2;
        $miles = (sin(deg2rad($lat1)) * sin(deg2rad($lat2))) + (cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta)));
        $miles = acos($miles);
        $miles = rad2deg($miles);
        $miles = $miles * 60 * 1.1515;
        $feet = $miles * 5280;
        $yards = $feet / 3;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return $meters;
    }

    /**
     * @deprecated Tidak lagi digunakan - fitur pembatasan lokasi telah dihilangkan
     */

    public function pulang()
    {
        $cekAbsensi = $this->AbsensiModel->getAbsensi();
        $image_data = $this->request->getPost('image');
        $image_path = 'assets/img/absensi/';
        $image_parts = explode(";base64,", $image_data);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file_name = uniqid() . '.'.$image_type;
        $file_path = $image_path . $file_name;
        $user_id = session()->get('id');
        $lokasi = $this->request->getPost('lokasi');
        // Fitur pembatasan area lokasi telah dihilangkan - karyawan dapat absen dari mana saja
        if (empty($lokasi)) {
            // Lokasi opsional jika fitur lokasi tidak aktif di device
            $lokasi = '0,0';
        }
        if (file_put_contents($file_path, $image_base64)) {
            $this->AbsensiModel->update($cekAbsensi['id'], [
                'hour_out' => date('H:i:s'),
                'image_out' => $file_name,
                'location_out' => $lokasi,
            ]);
            echo 'success|Terima Kasih, Hati - Hati Dijalan';
        }
    }
}
