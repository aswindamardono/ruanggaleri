<?php

namespace App\Controllers\Operator;

use App\Controllers\BaseController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class Penggajian extends BaseController
{
    public function index()
    {
        $data['title'] = 'Penggajian';
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
        $bulan1 = date("m");
        $tahun1 = date("Y");
        $data['bulan1'] = $bulan1;
        $data['tahun1'] = $tahun1;
        $data['guru'] = $this->KaryawanModel->getUserWithGuru();
        $data['penggajian'] = $this->KaryawanModel->getPenggajian($bulan1, $tahun1);
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['getabsensi2'] = $this->AbsensiModel;
        $data['getizin'] = $this->UnableModel;
        return view('operator/penggajian/read', $data);
    }

    public function getTerlambatOtomatis()
    {
        $user_id = $this->request->getPost('user_id');
        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');

        // Ambil total menit terlambat dari AbsensiModel
        $totalMenitTerlambat = $this->AbsensiModel->getTerlambatMenit($user_id, $bulan, $tahun);

        // Konversi menit ke jam (1 jam kerja = 120 menit untuk potongan 500 per menit)
        // Sesuai requirement: 1 menit = potongan 500
        // Jadi tidak perlu konversi, langsung bisa digunakan sebagai input jam terlambat
        // Tapi untuk display, kita konversi ke jam.menit format

        $jam = floor($totalMenitTerlambat / 60);
        $menit = $totalMenitTerlambat % 60;

        return $this->response->setJSON([
            'status' => 'success',
            'terlambat_menit' => $totalMenitTerlambat,
            'terlambat_jam' => $jam,
            'terlambat_menit_sisa' => $menit,
            'display' => $jam > 0 ? sprintf('%d jam %d menit', $jam, $menit) : sprintf('%d menit', $menit)
        ]);
    }

    public function cari()
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
            return redirect()->to(base_url('/operator/penggajian'))->withInput();
        }
        $bulan1 = $this->request->getPost('bulan');
        $tahun1 = $this->request->getPost('tahun');
        $data['title'] = 'Rekap Absensi';
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
        $data['guru'] = $this->KaryawanModel->getUserWithGuru();
        $data['penggajian'] = $this->KaryawanModel->getPenggajian($bulan1, $tahun1);
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['getabsensi2'] = $this->AbsensiModel;
        $data['getizin'] = $this->UnableModel;
        return view('operator/penggajian/read', $data);
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
            'gaji_pokok' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Gaji Pokok harus di isi!',
                ],
            ],
            'tunjangan' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tunjangan harus di isi!',
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
            return redirect()->to(base_url('/operator/penggajian'))->withInput();
        }
        $bulan = $this->request->getPost('bulan');
        $tahun = $this->request->getPost('tahun');
        $guru = $this->request->getPost('guru');
        $cek = $this->PenggajianModel->where(['bulan' => $bulan, 'tahun' => $tahun, 'user_id' => $guru])->first();
        if($cek !== null) {
            session()->setFlashdata('error', 'Data Penggajian gagal ditambahkan.');
            return redirect()->to(base_url('/operator/penggajian'));
        } else {
            // Hitung potongan gaji (terlambat) dan bonus lembur
            $gaji_pokok = (int)$this->request->getPost('gaji_pokok');
            $tunjangan = (int)$this->request->getPost('tunjangan');
            $lain_lain = (int)$this->request->getPost('lain_lain');
            $lembur = (int)$this->request->getPost('lembur');
            $terlambat = (int)$this->request->getPost('terlambat');
            
            // Hitungan: gaji per jam
            $total_jam = $this->AbsensiModel->getJam($guru, $bulan, $tahun);
            $gaji_per_jam = $total_jam > 0 ? $gaji_pokok / $total_jam : 0;
            
            // Bonus lembur: 1.5x gaji per jam
            $bonus_lembur = $lembur * ($gaji_per_jam * 1.5);
            
            // Potongan terlambat: 500 per menit
            // $terlambat dalam menit, jadi langsung kalikan dengan 500
            $potongan_terlambat = $terlambat * 500;
            
            // Total gaji final
            $total = $gaji_pokok + $tunjangan + $lain_lain + $bonus_lembur - $potongan_terlambat;
            
            $this->PenggajianModel->insert([
                'user_id' => $guru,
                'bulan' => $bulan,
                'tahun' => $tahun,
                'total_jam' => $total_jam,
                'total_absensi' => $this->AbsensiModel->getHadir($guru, $bulan, $tahun),
                'gaji' => $this->request->getPost('gaji'),
                'gaji_pokok' => $gaji_pokok,
                'tunjangan' => $tunjangan,
                'lain_lain' => $lain_lain,
                'lembur' => $lembur,
                'terlambat' => $terlambat,
                'potongan' => (int)$potongan_terlambat,
                'total' => (int)$total,
                'admin_id' => session()->get('id'),
            ]);
            session()->setFlashdata('pesan', 'Data Penggajian berhasil ditambahkan.');
            return redirect()->to(base_url('/operator/penggajian'));
        }
    }

    public function delete($id)
    {
        $this->PenggajianModel->delete($id);
        session()->setFlashdata('pesan', 'Data Penggajian berhasil dihapus.');
        return redirect()->to(base_url('/operator/penggajian'));
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
            'tunjangan1' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Tunjangan harus di isi!',
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
            return redirect()->to(base_url('/operator/penggajian'))->withInput();
        }

        // Ambil data penggajian lama untuk perhitungan
        $penggajian = $this->PenggajianModel->find($id);
        
        // Hitung potongan gaji (terlambat) dan bonus lembur
        $gaji_pokok = (int)$this->request->getPost('gaji_pokok1');
        $tunjangan = (int)$this->request->getPost('tunjangan1');
        $lain_lain = (int)$this->request->getPost('lain_lain1');
        $lembur = (int)$this->request->getPost('lembur1');
        $terlambat = (int)$this->request->getPost('terlambat1');
        
        // Hitungan: gaji per jam
        $total_jam = $penggajian['total_jam'];
        $gaji_per_jam = $total_jam > 0 ? $gaji_pokok / $total_jam : 0;
        
        // Bonus lembur: 1.5x gaji per jam
        $bonus_lembur = $lembur * ($gaji_per_jam * 1.5);
        
        // Potongan terlambat: 500 per menit
        $potongan_terlambat = $terlambat * 500;
        
        // Total gaji final
        $total = $gaji_pokok + $tunjangan + $lain_lain + $bonus_lembur - $potongan_terlambat;

        $this->PenggajianModel->update($id, [
            'gaji_pokok' => $gaji_pokok,
            'tunjangan' => $tunjangan,
            'lain_lain' => $lain_lain,
            'lembur' => $lembur,
            'terlambat' => $terlambat,
            'potongan' => (int)$potongan_terlambat,
            'total' => (int)$total,
        ]);

        session()->setFlashdata('pesan', 'Data Penggajian berhasil diubah.');
        return redirect()->to(base_url('/operator/penggajian'));
    }

    public function cetak($bulan, $tahun)
    {
        $data['title'] = 'Laporan Penggajian'.tanggalindo(date($tahun.'-'.$bulan.'-'));
        $data['user'] = $this->KaryawanModel->getUserAndJabatan(session()->get('id'));
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['penggajian'] = $this->KaryawanModel->getPenggajian($bulan, $tahun);
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        return view('operator/penggajian/cetak', $data);
    }

    public function pdf($bulan, $tahun)
    {
        $filename = 'Laporan Penggajian '.tanggalindo(date($tahun.'-'.$bulan.'-'));
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['penggajian'] = $this->KaryawanModel->getPenggajian($bulan, $tahun);
        $this->Dompdf->loadHtml(view('operator/cetak/penggajian', $data));
        $this->Dompdf->setPaper('A4', 'landscape');
        $this->Dompdf->render();
        $this->Dompdf->stream($filename, ['Attachment' => false]);
        exit();
    }
    
    public function slip($bulan, $tahun)
    {
        $filename = 'Slip Penggajian '.tanggalindo(date($tahun.'-'.$bulan.'-'));
        $data['setting'] = $this->PengaturanModel->find(1);
        $data['bulan'] = $bulan;
        $data['tahun'] = $tahun;
        $data['penggajian'] = $this->KaryawanModel->getPenggajian($bulan, $tahun);
        $this->Dompdf->loadHtml(view('operator/cetak/slip', $data));
        $this->Dompdf->setPaper('A4', 'potrait');
        $this->Dompdf->render();
        $this->Dompdf->stream($filename, ['Attachment' => false]);
        exit();
    }

    public function excel($bulan, $tahun)
    {
        $filename = 'Laporan Penggajian '. tanggalindo(date($tahun . '-' . $bulan . '-'));
        $penggajian = $this->KaryawanModel->getPenggajian($bulan, $tahun);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Nama');
        $sheet->setCellValue('C1', 'Jabatan');
        $sheet->setCellValue('D1', 'Total Absen');
        $sheet->setCellValue('E1', 'Total Jam');
        $sheet->setCellValue('F1', 'Gaji Pokok');
        $sheet->setCellValue('G1', 'Tunjangan');
        $sheet->setCellValue('H1', 'Lain - lain');
        $sheet->setCellValue('I1', 'Total');

        $row = 2;
        $no = 1;
        foreach ($penggajian as $a) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $a['name']);
            $sheet->setCellValue('C' . $row, $a['name_jabatan']);
            $sheet->setCellValue('D' . $row, $a['total_absensi']);
            $sheet->setCellValue('E' . $row, $a['total_jam']);
            $sheet->setCellValue('F' . $row, $a['gaji_pokok']);
            $sheet->setCellValue('G' . $row, $a['tunjangan']);
            $sheet->setCellValue('H' . $row, $a['lain_lain']);
            $sheet->setCellValue('I' . $row, '=SUM(F'.$row.':H'.$row.')');

            $sheet->getStyle('F'.$row.':I'.$row)->getNumberFormat()->setFormatCode('[$Rp. ]#,##0');
            $sheet->getStyle('A' . $row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('A' . $row)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $row++;
        }

        $columnWidths = [5, 30, 25, 15, 15, 15, 15, 15, 15];
        foreach ($columnWidths as $key => $width) {
            $column = chr(65 + $key); // A, B, C, ...
            $sheet->getColumnDimension($column)->setWidth($width);
            $sheet->getStyle($column . '1')->getFont()->setBold(true);
            $sheet->getStyle($column .
            '1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle($column .
            '1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            $sheet->getStyle($column .
            '1')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        }

        $sheet->getStyle('A1:B1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1:B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1:B1')->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->getStyle('A2:I' . ($row - 1))->getAlignment()->setWrapText(true);
        $sheet->getStyle('A2:I' . ($row -
        1))->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A2:A' . ($row -
        1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A2:A' . ($row -
        1))->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
        $sheet->mergeCells('A'.$row.':H'.$row);
        $sheet->setCellValue('A'.$row, "Total Penggajian");
        $sheet->setCellValue('I'.$row, '=SUM(I2:I'.($row-1).')');
        $sheet->getStyle('I'.$row)->getNumberFormat()->setFormatCode('[$Rp. ]#,##0.00');
        $sheet->getStyle('A'.$row.':H'.$row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
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