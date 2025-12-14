<?php

namespace App\Controllers\Pegawai;

use App\Controllers\BaseController;

class Absensi extends BaseController
{
    public function index()
    {
        $cekAbsensi = $this->AbsensiModel->getAbsensi();
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['lokasi'] = $this->LokasiModel->getLokasiJadwalUser(session()->get('id'));        
        if ($cekAbsensi == null) {
            $data['title'] = 'Absensi Masuk';
            $data['user'] = $this->KaryawanModel->find(session()->get('id'));
            $data['jadwal'] = $this->KaryawanModel->getUserJadwal(session()->get('id'));
            $data['izin'] = $this->UnableModel->check(session()->get('id'));
            // print_r($data['lokasi']);exit();
            return view('pegawai/absen/in', $data);
        } else {
            if ($cekAbsensi['image_out'] == null && $cekAbsensi['location_out'] == null) {
                $data['title'] = 'Absensi Pulang';
                $data['user'] = $this->KaryawanModel->find(session()->get('id'));
                $data['jadwal'] = $this->KaryawanModel->getUserJadwal(session()->get('id'));
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
            // print_r($user_id);
            // var_dump($lokasi);
            if (empty($lokasi)) {
                echo 'error1|Lokasi tidak terdeteksi pastikan perangkat anda lokasinya aktif';
            } else {
                $setting = $this->PengaturanModel->find(1);
                $lok = $this->LokasiModel->getLokasiJadwalUser($user_id);        
                $lokasi_user = explode(",", $lokasi);
                // print_r($lok);
                // var_dump($lok); 
                $lat_user = $lokasi_user[0];
                $long_user = $lokasi_user[1];
                $lat_setting = $lok['lat'];
                $long_setting = $lok['long'];
                $jarak = $this->distance($lat_user, $long_user, $lat_setting, $long_setting);
                // var_dump($jarak); 
                $radius = round($jarak);

                if ($radius > $lok['radius']) {
                    $selisih = $radius - $lok['radius'];
                    if ($selisih < 1000) {
                        echo 'error2|Maaf anda tidak bisa absen karena berada '.$selisih.' meter diluar jangkauan ';
                    } else {
                        $selisih2 = round($selisih/1000, 2);
                        echo 'error2|Maaf anda tidak bisa absen karena berada '.$selisih2.' km diluar jangkauan ';
                    }
                } else {
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
                }
            }
        } catch (\Exception $e) {
            echo 'error1|Error: ' . $e->getMessage();
        }
    }

    public function distance($lat1, $lon1, $lat2, $lon2)
    {
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
        if (empty($lokasi)) {
            echo 'error1|Lokasi tidak terdeteksi pastikan perangkat anda lokasinya aktif';
        } else {
            $setting = $this->PengaturanModel->find(1);
            $lok = $this->LokasiModel->getLokasiJadwalUser($user_id);         
            $lokasi_user = explode(",", $lokasi);
            $lat_user = $lokasi_user[0];
            $long_user = $lokasi_user[1];
            $lat_setting = $lok['lat'];
            $long_setting = $lok['long'];
            $jarak = $this->distance($lat_user, $long_user, $lat_setting, $long_setting);
            $radius = round($jarak);

            if ($radius > $lok['radius']) {
                $selisih = $radius - $lok['radius'];
                echo 'error2|Maaf anda tidak bisa absen karena berada '.$selisih.' meter diluar jangkauan ';
            } else {
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
    }
}
