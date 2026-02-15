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
                        <button class="btn btn-danger text-white" onclick="window.print()">
                            <i class="fas fa-print mr-2"></i>Cetak
                        </button>
                    </div>
                </div>
                <div class="card-body print-section">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h2><?= $setting['nama_sekolah'];?></h2>
                        <p><?= $setting['alamat_sekolah'];?></p>
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
                                <th>Kasbon</th>
                                <th>Lain - lain</th>
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
                                <td><?= rupiah($row['kasbon']);?></td>
                                <td><?= rupiah($row['lain_lain']);?></td>
                                <td><?= rupiah($row['total']);?></td>
                            </tr>
                            <?php $totalGaji += $row['total'];?>
                            <?php endforeach;?>
                            <tr style="font-weight: bold;">
                                <td colspan="6" style="text-align: right;">Total Gaji Mingguan:</td>
                                <td><?= rupiah($totalGaji);?></td>
                            </tr>
                        </tbody>
                    </table>
                    <div style="margin-top: 40px; display: flex; justify-content: space-around;">
                        <div style="text-align: center;">
                            <p>Mengetahui</p>
                            <p style="margin-top: 60px;">(...................................)</p>
                        </div>
                        <div style="text-align: center;">
                            <p>Tanggal: <?= date('d-m-Y');?></p>
                            <p style="margin-top: 60px;">(...................................)</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection(); ?>
