<?= $this->extend('template/layout_admin') ?>
<?= $this->section('content') ?>
<?php $rusak = validation_errors();?>
<style>
    body { padding-bottom: 140px; }
    @keyframes slideUpNav { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .bottom-nav { position: fixed; bottom: 0; left: 0; right: 0; height: 90px; background: linear-gradient(90deg, #1a1a2e 0%, #16213e 50%, #1a1a2e 100%); display: flex; justify-content: center; align-items: center; box-shadow: 0 -4px 30px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.05); animation: slideUpNav 0.6s cubic-bezier(0.34, 1.56, 0.64, 1); z-index: 1000; padding-bottom: env(safe-area-inset-bottom); }
    .nav-container { max-width: 700px; width: 100%; display: flex; justify-content: center; align-items: center; height: 100%; padding: 0 15px; }
    .nav-item { display: flex; flex-direction: column; align-items: center; justify-content: center; flex: 1; height: 90px; text-decoration: none; color: #888; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); position: relative; }
    .nav-item:hover { color: #06b6d4; transform: translateY(-3px); }
    .nav-item.active { color: #06b6d4; }
    .nav-item::before { content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 0; background: linear-gradient(90deg, #4f46e5, #06b6d4); border-radius: 2px 2px 0 0; transition: height 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); }
    .nav-item.active::before { height: 3px; background: linear-gradient(90deg, #4f46e5, #06b6d4); }
    .nav-icon { font-size: 1.5rem; margin-bottom: 0.25rem; }
    .nav-label { font-size: 0.75rem; font-weight: 600; }
</style>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title;?></h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <div class="font-weight-bold">Rekap Data <?= $title;?></div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="table-1">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Jumlah</th>
                                    <th>Persetujuan</th>
                                    <th width="15%">Aksi</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;?>
                                <?php foreach ($kasbon as $row):?>
                                <tr>
                                    <td><?= $no++;?></td>
                                    <td><?= tanggalindo($row['created_at']);?></td>
                                    <td><?= $row['name'];?></td>
                                    <td><?= $row['name_jabatan'];?> (<?= $row['akronim'];?>)</td>
                                    <td>Rp <?= number_format($row['nominal'], 0, ',', '.');?></td>
                                    <td>
                                        <?php if($row["persetujuan"] == 1):?>
                                        <span class="badge badge-success">
                                            Disetujui
                                        </span>
                                        <?php elseif($row["persetujuan"] == 2):?>
                                        <span class="badge badge-danger">
                                            Ditolak
                                        </span>
                                        <?php elseif($row["persetujuan"] == 0):?>
                                        <span class="badge badge-warning">
                                            Pending
                                        </span>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <div class="d-flex">
                                            <button class="btn btn-warning btn-sm mr-1" data-toggle="modal"
                                                data-target="#edit<?= $row["id"];?>">
                                                <i class="fas fa-info mr-1"></i>
                                                <span>
                                                    Cek
                                                </span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php foreach($kasbon as $row):?>
<div class="modal fade" tabindex="-1" role="dialog" id="edit<?= $row["id"];?>">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cek Data <?= $title;?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="<?= base_url("operator/kasbon/updatekasbon/").$row["id"];?>" autocomplete="off">
                <div class="modal-body">
                    <?= csrf_field();?>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="keterangan" class="form-label">Nama</label>
                                <p><?= $row['name'];?></p>
                            </div>
                            <div class="form-group">
                                <label for="keterangan" class="form-label">Jabatan</label>
                                <p><?= $row['name_jabatan'];?> (<?= $row['akronim'];?>)</p>
                            </div>
                            <div class="form-group">
                                <label for="keterangan" class="form-label">Jumlah Kasbon</label>
                                <p>Rp <?= number_format($row['nominal'], 0, ',', '.');?></p>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label for="keterangan" class="form-label">Tanggal Pengajuan</label>
                                <p><?= tanggalindo($row['created_at']);?></p>
                            </div>
                            <div class="form-group">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <p><?= $row['keterangan'];?></p>
                            </div>
                            <div class="form-group">
                                <label for="persetujuan" class="form-label">Persetujuan</label>
                                <div class="btn-group btn-group-toggle btn-block" data-toggle="buttons">
                                    <label
                                        class="btn btn-lg btn-outline-success w-50 <?= (old('persetujuan', $row["persetujuan"]) == 1) ? "active" : ""; ?>">
                                        <input type="radio" name="persetujuan" value="1"
                                            <?= (old('persetujuan', $row["persetujuan"]) == 1) ? "checked active" : ""; ?>>
                                        Disetujui
                                    </label>
                                    <label
                                        class="btn btn-lg btn-outline-danger w-50 <?= (old('persetujuan', $row["persetujuan"]) == 2) ? "active" : ""; ?>">
                                        <input type="radio" name="persetujuan" value="2"
                                            <?= (old('persetujuan', $row["persetujuan"]) == 2) ? "active" : ""; ?>>
                                        Ditolak
                                    </label>
                                </div>
                                <small class="text-danger">
                                    <?= !empty($rusak['persetujuan']) ? validation_show_error('persetujuan') : ''; ?>
                                </small>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach;?>

<div class="bottom-nav">
    <div class="nav-container">
        <a href="<?= base_url('operator/monitoring');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-eye"></i></div>
            <div class="nav-label">Monitoring</div>
        </a>
        <a href="<?= base_url('operator/penggajian');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-money-bill"></i></div>
            <div class="nav-label">Penggajian</div>
        </a>
        <a href="<?= base_url('operator/karyawan');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-users"></i></div>
            <div class="nav-label">Karyawan</div>
        </a>
        <a href="<?= base_url('operator/workorder');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-fire"></i></div>
            <div class="nav-label">Work Order</div>
        </a>
        <a href="<?= base_url('operator/jadwal');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-calendar"></i></div>
            <div class="nav-label">Jadwal</div>
        </a>
    </div>
</div>

<?= $this->endSection('content') ?>
