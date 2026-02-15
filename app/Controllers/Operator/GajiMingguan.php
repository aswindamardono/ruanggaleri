<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class GajiMingguan extends BaseController
{
    public function index()
    {
        $data['title'] = 'Gaji Mingguan';
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['guru'] = $this->KaryawanModel->getUserWithGuru();
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['getabsensi2'] = $this->AbsensiModel;
        $data['getizin'] = $this->UnableModel;
        
        // Default: Tampilkan data minggu ini
        $today = date('Y-m-d');
        $dayOfWeek = date('w', strtotime($today)); // 0=Sunday, 1=Monday, ...
        
        // Hitung tanggal awal minggu (Senin)
        $daysBack = ($dayOfWeek == 0) ? 6 : ($dayOfWeek - 1);
        $tanggal_mulai = date('Y-m-d', strtotime($today . ' -' . $daysBack . ' days'));
        $tanggal_selesai = date('Y-m-d', strtotime($tanggal_mulai . ' +6 days'));
        
        $data['tanggal_mulai'] = $tanggal_mulai;
        $data['tanggal_selesai'] = $tanggal_selesai;
        $data['gaji_mingguan'] = $this->getGajiMingguanData($tanggal_mulai, $tanggal_selesai);
        
        return view('operator/gaji_mingguan/read', $data);
    }

    private function getGajiMingguanData($tanggal_mulai, $tanggal_selesai)
    {
        // Ambil pengaturan gaji per jam
        $setting = $this->PengaturanModel->find(1);
        $gaji_per_jam_setting = $setting['gaji'] ?? 0;
        
        // Ambil semua karyawan dengan guru
        $karyawan = $this->KaryawanModel->getUserWithGuru();
        $gajiData = [];

        foreach ($karyawan as $row) {
            $user_id = $row['id'];
            
            // Hitung absensi dalam range tanggal
            $totalHadir = $this->AbsensiModel->getHadirRange($user_id, $tanggal_mulai, $tanggal_selesai);
            $totalJam = $this->AbsensiModel->getJamRange($user_id, $tanggal_mulai, $tanggal_selesai);
            $totalMenitTerlambat = $this->AbsensiModel->getTerlambatMenitRange($user_id, $tanggal_mulai, $tanggal_selesai);
            $totalMenitLembur = $this->AbsensiModel->getLemburMenitRange($user_id, $tanggal_mulai, $tanggal_selesai);
            
            // Default values
            $gaji_pokok = 0;
            $kasbon = 0;
            $lain_lain = 0;
            $bonus_lembur = 0;
            $potongan_terlambat = 0;
            $total = 0;
            
            if ($totalJam > 0) {
                // Hitung gaji pokok = jam * gaji per jam
                $gaji_pokok = $totalJam * $gaji_per_jam_setting;
                $bonus_lembur = $totalMenitLembur * 350;
                $potongan_terlambat = $totalMenitTerlambat * 500;
                $total = $gaji_pokok - $kasbon + $bonus_lembur - $potongan_terlambat;
            }
            
            $gajiData[] = [
                'id' => $user_id,
                'name' => $row['name'],
                'gaji_pokok' => $gaji_pokok,
                'total_absensi' => $totalHadir,
                'total_jam' => $totalJam,
                'kasbon' => $kasbon,
                'lembur' => $totalMenitLembur,
                'lain_lain' => $bonus_lembur,
                'terlambat' => $totalMenitTerlambat,
                'potongan' => $potongan_terlambat,
                'total' => $total,
            ];
        }
        
        return $gajiData;
    }

    public function cari()
    {
        $validate = $this->validate([
            'tanggal_mulai' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal mulai harus di isi!',
                ],
            ],
            'tanggal_selesai' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal selesai harus di isi!',
                ],
            ],
        ]);
        if (!$validate) {
            return redirect()->to(base_url('/operator/gaji-mingguan'))->withInput();
        }
        
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $tanggal_selesai = $this->request->getPost('tanggal_selesai');
        
        $data['title'] = 'Gaji Mingguan';
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['guru'] = $this->KaryawanModel->getUserWithGuru();
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['getabsensi2'] = $this->AbsensiModel;
        $data['getizin'] = $this->UnableModel;
        $data['tanggal_mulai'] = $tanggal_mulai;
        $data['tanggal_selesai'] = $tanggal_selesai;
        $data['gaji_mingguan'] = $this->getGajiMingguanData($tanggal_mulai, $tanggal_selesai);
        
        return view('operator/gaji_mingguan/read', $data);
    }

    public function getTerlambatOtomatisRange()
    {
        $user_id = $this->request->getPost('user_id');
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $tanggal_selesai = $this->request->getPost('tanggal_selesai');

        $totalMenitTerlambat = $this->AbsensiModel->getTerlambatMenitRange($user_id, $tanggal_mulai, $tanggal_selesai);
        $totalMenitLembur = $this->AbsensiModel->getLemburMenitRange($user_id, $tanggal_mulai, $tanggal_selesai);
        $totalJam = $this->AbsensiModel->getJamRange($user_id, $tanggal_mulai, $tanggal_selesai);
        
        // Ambil setting gaji
        $setting = $this->PengaturanModel->find(1);
        $gaji_per_jam_setting = $setting['gaji'] ?? 0;
        $gaji_pokok = $totalJam * $gaji_per_jam_setting;

        $jam = floor($totalMenitTerlambat / 60);
        $menit = $totalMenitTerlambat % 60;

        return $this->response->setJSON([
            'status' => 'success',
            'terlambat_menit' => $totalMenitTerlambat,
            'lembur_menit' => $totalMenitLembur,
            'terlambat_jam' => $jam,
            'terlambat_menit_sisa' => $menit,
            'display' => $jam > 0 ? sprintf('%d jam %d menit', $jam, $menit) : sprintf('%d menit', $menit),
            'gaji_pokok' => (int)$gaji_pokok,
            'total_jam' => $totalJam
        ]);
    }

    public function save()
    {
        $validate = $this->validate([
            'guru' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Karyawan harus di isi!',
                ],
            ],
            'tanggal_mulai' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal mulai harus di isi!',
                ],
            ],
            'tanggal_selesai' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tanggal selesai harus di isi!',
                ],
            ],
            'gaji_pokok' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Gaji Pokok harus di isi!',
                ],
            ],
            'kasbon' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kasbon harus di isi!',
                ],
            ],
            'lain_lain' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Lain Lain harus di isi!',
                ],
            ],
            'total' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Lain Lain harus di isi!',
                ],
            ],
            'lembur' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam Lembur harus di isi!',
                ],
            ],
            'terlambat' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam Terlambat harus di isi!',
                ],
            ],
        ]);

        if (!$validate) {
            return redirect()->to(base_url('/operator/gaji-mingguan'))->withInput();
        }
        
        $tanggal_mulai = $this->request->getPost('tanggal_mulai');
        $tanggal_selesai = $this->request->getPost('tanggal_selesai');
        $guru = $this->request->getPost('guru');
        
        $cek = $this->GajiMingguanModel->where(['tanggal_mulai' => $tanggal_mulai, 'tanggal_selesai' => $tanggal_selesai, 'user_id' => $guru])->first();
        if($cek !== null) {
            session()->setFlashdata('error', 'Data Gaji Mingguan gagal ditambahkan.');
            return redirect()->to(base_url('/operator/gaji-mingguan'));
        } else {
            $gaji_pokok = (int)$this->request->getPost('gaji_pokok');
            $kasbon = (int)$this->request->getPost('kasbon');
            $lain_lain = (int)$this->request->getPost('lain_lain');
            $lembur = (int)$this->request->getPost('lembur');
            $terlambat = (int)$this->request->getPost('terlambat');
            
            $total_jam = $this->AbsensiModel->getJamRange($guru, $tanggal_mulai, $tanggal_selesai);
            $gaji_per_jam = $total_jam > 0 ? $gaji_pokok / $total_jam : 0;
            
            $bonus_lembur = $lembur * ($gaji_per_jam * 1.5);
            $potongan_terlambat = $terlambat * 500;
            $total = $gaji_pokok - $kasbon + $lain_lain + $bonus_lembur - $potongan_terlambat;
            
            $this->GajiMingguanModel->insert([
                'user_id' => $guru,
                'tanggal_mulai' => $tanggal_mulai,
                'tanggal_selesai' => $tanggal_selesai,
                'total_jam' => $total_jam,
                'total_absensi' => $this->AbsensiModel->getHadirRange($guru, $tanggal_mulai, $tanggal_selesai),
                'gaji' => $this->request->getPost('gaji'),
                'gaji_pokok' => $gaji_pokok,
                'kasbon' => $kasbon,
                'lain_lain' => $lain_lain,
                'lembur' => $lembur,
                'terlambat' => $terlambat,
                'potongan' => (int)$potongan_terlambat,
                'total' => (int)$total,
                'admin_id' => session()->get('id'),
            ]);
            session()->setFlashdata('pesan', 'Data Gaji Mingguan berhasil ditambahkan.');
            return redirect()->to(base_url('/operator/gaji-mingguan'));
        }
    }

    public function delete($id)
    {
        $gajumingguan = $this->GajiMingguanModel->find($id);
        
        if (!$gajumingguan) {
            session()->setFlashdata('error', 'Data Gaji Mingguan tidak ditemukan.');
            return redirect()->to(base_url('/operator/gaji-mingguan'));
        }
        
        $this->GajiMingguanModel->delete($id);
        session()->setFlashdata('pesan', 'Data Gaji Mingguan berhasil dihapus.');
        return redirect()->to(base_url('/operator/gaji-mingguan'));
    }

    public function update($id)
    {
        $validate = $this->validate([
            'gaji_pokok1' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Gaji Pokok harus di isi!',
                ],
            ],
            'kasbon1' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Kasbon harus di isi!',
                ],
            ],
            'lain_lain1' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Lain Lain harus di isi!',
                ],
            ],
            'total1' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Lain Lain harus di isi!',
                ],
            ],
            'lembur1' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam Lembur harus di isi!',
                ],
            ],
            'terlambat1' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Jam Terlambat harus di isi!',
                ],
            ],
        ]);
        if (!$validate) {
            return redirect()->to(base_url('/operator/gaji-mingguan'))->withInput();
        }

        $gajumingguan = $this->GajiMingguanModel->find($id);
        
        if (!$gajumingguan) {
            session()->setFlashdata('error', 'Data Gaji Mingguan tidak ditemukan.');
            return redirect()->to(base_url('/operator/gaji-mingguan'));
        }
        
        $gaji_pokok = (int)$this->request->getPost('gaji_pokok1');
        $kasbon = (int)$this->request->getPost('kasbon1');
        $lain_lain = (int)$this->request->getPost('lain_lain1');
        $lembur = (int)$this->request->getPost('lembur1');
        $terlambat = (int)$this->request->getPost('terlambat1');
        
        $total_jam = $gajumingguan['total_jam'];
        $gaji_per_jam = $total_jam > 0 ? $gaji_pokok / $total_jam : 0;
        
        $bonus_lembur = $lembur * ($gaji_per_jam * 1.5);
        $potongan_terlambat = $terlambat * 500;
        $total = $gaji_pokok - $kasbon + $lain_lain + $bonus_lembur - $potongan_terlambat;

        $this->GajiMingguanModel->update($id, [
            'gaji_pokok' => $gaji_pokok,
            'kasbon' => $kasbon,
            'lain_lain' => $lain_lain,
            'lembur' => $lembur,
            'terlambat' => $terlambat,
            'potongan' => (int)$potongan_terlambat,
            'total' => (int)$total,
        ]);

        session()->setFlashdata('pesan', 'Data Gaji Mingguan berhasil diubah.');
        return redirect()->to(base_url('/operator/gaji-mingguan'));
    }

    public function cetak($tanggal_mulai, $tanggal_selesai)
    {
        $data['title'] = 'Laporan Gaji Mingguan '.$tanggal_mulai.' - '.$tanggal_selesai;
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['gaji_mingguan'] = $this->getGajiMingguanData($tanggal_mulai, $tanggal_selesai);
        $data['tanggal_mulai'] = $tanggal_mulai;
        $data['tanggal_selesai'] = $tanggal_selesai;
        return view('operator/gaji_mingguan/cetak', $data);
    }

    public function excel($tanggal_mulai, $tanggal_selesai)
    {
        $filename = 'Gaji Mingguan '. $tanggal_mulai .' - '. $tanggal_selesai;
        $gaji_mingguan = $this->getGajiMingguanData($tanggal_mulai, $tanggal_selesai);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Total Absen');
        $sheet->setCellValue('D1', 'Gaji Pokok');
        $sheet->setCellValue('E1', 'Lembur (Menit)');
        $sheet->setCellValue('F1', 'Tambahan');
        $sheet->setCellValue('G1', 'Terlambat (Menit)');
        $sheet->setCellValue('H1', 'Potongan');
        $sheet->setCellValue('I1', 'Total');

        $row = 2;
        $no = 1;
        $totalKeseluruhan = 0;
        foreach ($gaji_mingguan as $a) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $a['name']);
            $sheet->setCellValue('C' . $row, $a['total_absensi']);
            $sheet->setCellValue('D' . $row, $a['gaji_pokok']);
            $sheet->setCellValue('E' . $row, $a['lembur']);
            $sheet->setCellValue('F' . $row, $a['lain_lain']);
            $sheet->setCellValue('G' . $row, $a['terlambat']);
            $sheet->setCellValue('H' . $row, $a['potongan']);
            $sheet->setCellValue('I' . $row, $a['total']);
            
            $totalKeseluruhan += $a['total'];

            // Format currency untuk kolom Gaji Pokok, Tambahan, Potongan, Total
            $sheet->getStyle('D'.$row.':D'.$row)->getNumberFormat()->setFormatCode('[$Rp. ]#,##0');
            $sheet->getStyle('F'.$row.':F'.$row)->getNumberFormat()->setFormatCode('[$Rp. ]#,##0');
            $sheet->getStyle('H'.$row.':I'.$row)->getNumberFormat()->setFormatCode('[$Rp. ]#,##0');
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $row++;
        }

        // Set column widths
        $columnWidths = [5, 25, 15, 15, 18, 15, 18, 15, 15];
        foreach ($columnWidths as $key => $width) {
            $column = chr(65 + $key);
            $sheet->getColumnDimension($column)->setWidth($width);
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
            $sheet->getStyle($column . '1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($column . '1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle($column . '1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        $sheet->getStyle('A1:I1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:I' . ($row - 1))->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:I' . ($row - 1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:A' . ($row - 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:A' . ($row - 1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        
        // Add total row
        $sheet->mergeCells('A'.$row.':C'.$row);
        $sheet->setCellValue('A'.$row, "Total Gaji Mingguan");
        $sheet->setCellValue('I'.$row, $totalKeseluruhan);
        $sheet->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[$Rp. ]#,##0');
        $sheet->getStyle('A'.$row.':C'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A'.$row.':I'.$row)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A'.$row.':I'.$row)->getFont()->setBold(true);
        
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        ob_end_clean();
        ob_start();

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename=' . $filename . '.xlsx');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit();
    }
}
