<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        * { margin: 0; padding: 0; }
        body { font-family: 'Century Gothic', Arial, sans-serif; font-size: 12px; padding: 20px; }
        .container { max-width: 800px; margin: 0 auto; }
        .slip { page-break-after: always; margin-bottom: 40px; border: 1px solid #333; padding: 30px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 3px solid #333; padding-bottom: 10px; }
        .header-left { display: inline-block; text-align: left; margin-right: 20px; }
        .header-left img { height: 50px; }
        .header-right { display: inline-block; text-align: right; vertical-align: top; }
        .header h2 { font-size: 18px; font-weight: bold; margin-bottom: 5px; }
        .header p { font-size: 11px; margin-bottom: 2px; }
        .title { text-align: center; font-weight: bold; font-size: 14px; margin: 20px 0; }
        .info-row { display: flex; margin-bottom: 10px; }
        .info-label { width: 150px; font-weight: bold; }
        .info-value { flex: 1; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table th, table td { border: 1px solid #333; padding: 8px; text-align: left; }
        table th { background-color: #f0f0f0; font-weight: bold; }
        .amount { text-align: right; padding-right: 10px; }
        .total-row { background-color: #f0f0f0; font-weight: bold; }
        .signature { display: flex; justify-content: space-around; margin-top: 40px; text-align: center; }
        .signature-box { width: 180px; }
        .signature-box p { margin: 5px 0; }
        .signature-line { border-top: 1px solid #000; margin-top: 60px; }
        .footer { text-align: center; font-size: 10px; margin-top: 20px; color: #666; }
    </style>
</head>
<body>
<div class="container">
    <?php foreach($gaji_mingguan as $row): ?>
    <div class="slip">
        <div class="header">
            <div>
                <img src="<?= generateBase64Image($setting['path'].$setting['logo_kantor']);?>" width="150px" alt="">
            </div>
            <!-- <div>
                <h2><?= $setting['nama_sekolah'] ?? 'PT. Ruang Galeri';?></h2>
                <p><?= $setting['alamat_sekolah'] ?? '';?></p>
            </div> -->
        </div>

        <div class="title">SLIP GAJI MINGGUAN</div>

        <div class="info-row">
            <div class="info-label">Nama Karyawan</div>
            <div class="info-value">: <?= $row['name'];?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Periode</div>
            <div class="info-value">: <?= tanggalindo($tanggal_mulai);?> s/d <?= tanggalindo($tanggal_selesai);?></div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal</div>
            <div class="info-value">: <?= tanggalindo(date('Y-m-d'));?></div>
        </div>

        <table>
            <tr>
                <th>Keterangan</th>
                <th class="amount">Jumlah</th>
            </tr>
            <tr>
                <td>Gaji Pokok</td>
                <td class="amount"><?= rupiah($row['gaji_pokok']);?></td>
            </tr>
            <tr>
                <td>Lembur (<?= $row['lembur'];?> menit)</td>
                <td class="amount"><?= rupiah($row['lain_lain']);?></td>
            </tr>
            <!-- <tr>
                <td>Tambahan</td>
                <td class="amount"><?= rupiah($row['lain_lain']);?></td>
            </tr> -->
            <tr>
                <td>Terlambat (<?= $row['terlambat'];?> menit)</td>
                <td class="amount">-<?= rupiah($row['potongan']);?></td>
            </tr>
            <tr class="total-row">
                <td>Total Gaji</td>
                <td class="amount"><?= rupiah($row['total']);?></td>
            </tr>
        </table>

        <div class="signature">
            <!-- <div class="signature-box">
                <p>Mengetahui</p>
                <div class="signature-line"></div>
                <p style="margin-top: 10px; font-size: 11px;">HR/Payroll</p>
            </div>
            <div class="signature-box">
                <p>Diterima Oleh</p>
                <div class="signature-line"></div>
                <p style="margin-top: 10px; font-size: 11px;"><?= $row['name'];?></p>
            </div> -->
            <div class="signature-box">
                <p>Direktur</p>
                <img src="<?= generateBase64Image($setting['path'].$setting['image_ttd']);?>" width="200px" alt="">
                <div class="signature-line"></div>
                <div><?= $setting['name_ttd'];?></div>
                <!-- <p style="margin-top: 10px; font-size: 11px;">Tanda Tangan</p> -->
            </div>
        </div>

        <div class="footer">
            Slip gaji ini adalah dokumen resmi. Mohon disimpan dengan baik.
        </div>
    </div>
    <?php endforeach; ?>
</div>
</body>
</html>
