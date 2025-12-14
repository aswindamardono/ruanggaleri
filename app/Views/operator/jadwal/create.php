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
                    <div class="font-weight-bold">Data <?= $title;?> <?=  $jadwal['name'];?></div>
                </div>
                <div class="card-body">
                    <p>
                        Keterangan : AM berlaku untuk jam 12.00 malam hingga 11.59 siang. dan PM berlaku untuk 12.00
                        siang
                        hingga 11.59 malam.
                    </p>
                    <?php if($create == "Submit"):?>
                    <form action="<?= base_url('operator/jadwal/save');?>" method="POST">
                        <?= csrf_field();?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Hari</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Keluar</th>
                                        <th>Jam Bekerja</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <input type="hidden" name="user_id" value="<?= $jadwal['id'];?>">
                                <tbody>
                                    <?php $no = 1;?>
                                    <?php foreach ($hari as $row):?>
                                    <tr>
                                        <td><?= $no++;?></td>
                                        <td>
                                            <?= hari2($row);?>
                                            <input type="hidden" name="hari[]" id="hari" class="form-control"
                                                value="<?= $row;?>">
                                        </td>
                                        <td>
                                            <input type="time" name="jam_masuk[]" id="jam_masuk" class="form-control">
                                        </td>
                                        <td>
                                            <input type="time" name="jam_keluar[]" id="jam_keluar" class="form-control">
                                        </td>
                                        <td>
                                            <input type="number" name="jam_mengajar[]" id="jam_mengajar"
                                                class="form-control">
                                        </td>
                                        <td>
                                            <select name="status[]" class="form-control">
                                                <option value="1">Aktif</option>
                                                <option value="0">Tidak Aktif</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <a href="<?= base_url('operator/jadwal');?>" class="btn btn-info">Kembali</a>
                    </form>
                    <?php elseif($create == "Update"):?>
                    <form action="<?= base_url('operator/jadwal/update');?>" method="POST">
                        <?= csrf_field();?>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Hari</th>
                                        <th>Jam Masuk</th>
                                        <th>Jam Keluar</th>
                                        <th>Jam Mengajar</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <input type="hidden" name="user_id" value="<?= $jadwal['id'];?>">
                                <tbody>
                                    <?php $no = 1;?>
                                    <?php foreach ($hari as $row):?>
                                    <tr>
                                        <td><?= $no++;?></td>
                                        <td>
                                            <?= hari2($row['hari']);?>
                                            <input type="hidden" name="hari[]" id="hari" class="form-control"
                                                value="<?= $row['hari'];?>">
                                        </td>
                                        <td>
                                            <input type="time" name="jam_masuk[]" id="jam_masuk"
                                                value="<?= $row['jam_masuk'];?>" class="form-control">
                                        </td>
                                        <td>
                                            <input type="time" name="jam_keluar[]" id="jam_keluar"
                                                value="<?= $row['jam_keluar'];?>" class="form-control">
                                        </td>
                                        <td>
                                            <input type="number" name="jam_mengajar[]" id="jam_mengajar"
                                                value="<?= $row['jam_mengajar'];?>" class="form-control">
                                        </td>
                                        <td>
                                            <select name="status[]" class="form-control">
                                                <option value="1" <?= ($row['status'] == 1) ? "selected" : ""; ?>>Aktif
                                                </option>
                                                <option value="0" <?= ($row['status'] == 0) ? "selected" : ""; ?>>Tidak
                                                    Aktif</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <?php endforeach;?>
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                        <a href="<?= base_url('operator/jadwal');?>" class="btn btn-info">Kembali</a>
                    </form>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </section>
</div>

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
        <a href="<?= base_url('operator/lokasi');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-map-marker-alt"></i></div>
            <div class="nav-label">Lokasi</div>
        </a>
        <a href="<?= base_url('operator/jadwal');?>" class="nav-item active">
            <div class="nav-icon"><i class="fas fa-calendar"></i></div>
            <div class="nav-label">Jadwal</div>
        </a>
    </div>
</div>

<?= $this->endSection('content') ?>