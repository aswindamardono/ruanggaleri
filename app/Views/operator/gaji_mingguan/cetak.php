<?= $this->extend('template/layout_admin'); ?>
<?= $this->section('content'); ?>
<style>
    body { padding-bottom: 0; }
    .print-section {
        padding: 20px;
    }
</style>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title;?></h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <div class="font-weight-bold">Laporan Gaji Mingguan</div>
                    <div class="ml-auto">
                        <a href="<?= base_url('operator/gaji-mingguan/slip/').$tanggal_mulai.'/'.$tanggal_selesai;?>" class="btn btn-info text-white" target="_blank">
                            <i class="fas fa-file-pdf mr-2"></i>Slip PDF
                        </a>
                        <!-- <button class="btn btn-success text-white" onclick="window.print()">
                            <i class="fas fa-print mr-2"></i>Cetak
                        </button> -->
                    </div>
                </div>
                <div class="card-body print-section">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h2><?= $setting['nama_sekolah'] ?? 'PT. Ruang Galeri';?></h2>
                        <p><?= $setting['alamat_sekolah'] ?? '';?></p>
                    </div>
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h4><?= $title;?></h4>
                        <p>Periode: <?= tanggalindo($tanggal_mulai);?> - <?= tanggalindo($tanggal_selesai);?></p>
                    </div>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th>Nama</th>
                                <th>Total Absen</th>
                                <th>Gaji Pokok</th>
                                <th>Lembur</th>
                                <th>Tambahan</th>
                                <th>Terlambat</th>
                                <th>Potongan</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1; $totalGaji = 0;?>
                            <?php foreach($gaji_mingguan as $row):?>
                            <tr>
                                <td><?= $no++;?></td>
                                <td><?= $row['name'];?></td>
                                <td><?= $row['total_absensi'];?></td>
                                <td><?= rupiah($row['gaji_pokok']);?></td>
                                <td><?= $row['lembur'];?> Menit</td>
                                <td><?= rupiah($row['lain_lain']);?></td>
                                <td><?= $row['terlambat'];?> Menit</td>
                                <td><?= rupiah($row['potongan']);?></td>
                                <td><?= rupiah($row['total']);?></td>
                            </tr>
                            <?php $totalGaji += $row['total'];?>
                            <?php endforeach;?>
                            <tr style="font-weight: bold;">
                                <td colspan="8" style="text-align: right;">Total Gaji Mingguan:</td>
                                <td><?= rupiah($totalGaji);?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="margin-top: 40px; display: flex; justify-content: space-around;">
                        <div style="text-align: center;">
                            <p> </p>
                            <p style="margin-top: 60px;"> </p>
                        </div>
                        <div style="text-align: center;">
                            <p>Tanggal: <?= date('d-m-Y');?></p>
                            <p>Mengetahui</p>
                            <p style="margin-top: 60px;">(Direktur)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
