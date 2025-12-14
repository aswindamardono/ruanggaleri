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
                    <div class="font-weight-bold">Data <?= $title;?></div>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('operator/absensi/cari');?>" method="post">
                        <?= csrf_field();?>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="bulan" class="form-label">Bulan</label>
                                    <select name="bulan" id="bulan"
                                        class="form-control <?= !empty($rusak['bulan']) ? 'is-invalid' : ''; ?>">
                                        <option value="">-- Pilih Bulan --</option>
                                        <?php foreach($bulan as $row):?>
                                        <?php if($row['no'] ==  old('bulan', !empty($bulan1) ?? $bulan1)):?>
                                        <option value="<?= $row['no'];?>" selected>
                                            <?= $row['nama'];?>
                                        </option>
                                        <?php else:?>
                                        <option value="<?= $row['no'];?>">
                                            <?= $row['nama'];?>
                                        </option>
                                        <?php endif;?>
                                        <?php endforeach;?>
                                    </select>
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['bulan']) ? validation_show_error('bulan') : ''; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="tahun" class="form-label">Tahun</label>
                                    <select name="tahun" id="tahun"
                                        class="form-control <?= !empty($rusak['tahun']) ? 'is-invalid' : ''; ?>">
                                        <option value="">-- Pilih Tahun --</option>
                                        <?php foreach($tahun as $row):?>
                                        <?php if($row ==  old('tahun', !empty($tahun1) ?? $tahun1)):?>
                                        <option value="<?= $row;?>" selected>
                                            <?= $row;?>
                                        </option>
                                        <?php else:?>
                                        <option value="<?= $row;?>">
                                            <?= $row;?>
                                        </option>
                                        <?php endif;?>
                                        <?php endforeach;?>
                                    </select>
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['tahun']) ? validation_show_error('tahun') : ''; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="guru" class="form-label">Pegawai</label>
                                    <select name="guru" id="guru"
                                        class="form-control <?= !empty($rusak['guru']) ? 'is-invalid' : ''; ?>">
                                        <option value="">-- Pilih Pegawai --</option>
                                        <?php foreach($guru as $row):?>
                                        <?php if($row['id'] ==  old('guru', !empty($user_guru) ?? $user_guru['id'])):?>
                                        <option value="<?= $row['id'];?>" selected>
                                            <?= $row['name'];?>
                                        </option>
                                        <?php else:?>
                                        <option value="<?= $row['id'];?>">
                                            <?= $row['name'];?>
                                        </option>
                                        <?php endif;?>
                                        <?php endforeach;?>
                                    </select>
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['guru']) ? validation_show_error('guru') : ''; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search mr-2"></i>Cari</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <?php if($absensi != 'Belum'):?>
            <?php if(!empty($absensi)):?>
            <div class="card">
                <div class="card-header">
                    <div class="font-weight-bold">Absensi Dari <?= $user_guru['name'];?>
                        <?= tanggalindo(date($tahun1.'-'.$bulan1.'-'));?>
                    </div>
                    <div class="ml-auto">
                        <a class="btn btn-danger text-white" .
                            href="<?= base_url('operator/absensi/cetak/').$bulan1.'/'.$tahun1.'/'.$user_guru['id'];?>">
                            <i class="fas fa-file-pdf mr-2"></i>PDF
                        </a>
                        <a class="btn btn-success text-white"
                            href="<?= base_url('operator/absensi/excel/').$bulan1.'/'.$tahun1.'/'.$user_guru['id'];?>">
                            <i class="fas fa-file-excel mr-2"></i>Excel
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="table-1">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Foto</th>
                                    <th>Jam Keluar</th>
                                    <th>Foto</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;?>
                                <?php foreach ($absensi as $row):?>
                                <tr>
                                    <td><?= $no++;?></td>
                                    <td><?= tanggalindo($row['date']);?></td>
                                    <td>
                                        <div
                                            class="badge <?= empty($row['image_in']) ? 'badge-danger' : 'badge-success' ;?>">
                                            <small><?= empty($row['image_in']) ? 'Belum Absen' : $row['hour_in'] ;?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if(!empty($row['image_in'])):?>
                                        <img src="<?= base_url('assets/img/absensi/').$row['image_in'];?>" width="100px"
                                            alt="">
                                        <?php else:?>
                                        <div class="badge badge-danger">
                                            <small>Belum Absen</small>
                                        </div>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <div
                                            class="badge <?= empty($row['image_out']) ? 'badge-danger' : 'badge-success' ;?>">
                                            <small><?= empty($row['image_out']) ? 'Belum Absen' : $row['hour_out'] ;?></small>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if(!empty($row['image_out'])):?>
                                        <img src="<?= base_url('assets/img/absensi/').$row['image_out'];?>"
                                            width="100px" alt="">
                                        <?php else:?>
                                        <div class="badge badge-danger">
                                            <small>Belum Absen</small>
                                        </div>
                                        <?php endif;?>
                                    </td>
                                    <td>
                                        <div
                                            class="badge <?= empty($row['hour_in'] <= $row['jam_masuk']) ? 'badge-danger' : 'badge-success' ;?>">
                                            <small>
                                                <?= empty($row['hour_in'] <= $row['jam_masuk']) ? 'Terlambat '.jam_terlambat($row['jam_masuk'], $row['hour_in']) : 'Tepat Waktu' ;?></small>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php elseif(empty($absensi)):?>
            <div class="card">
                <div class="card-header">
                    <div class="font-weight-bold">Absensi Dari <?= $user_guru['name'];?>
                        <?= tanggalindo(date($tahun1.'-'.$bulan1.'-'));?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="text-center">Maaf Data Absensi <?= $user_guru['name'];?>
                        <?= tanggalindo(date($tahun1.'-'.$bulan1.'-'));?> Tidak Ditemukan</div>
                </div>
            </div>
            <?php endif;?>
            <?php endif;?>
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
        <a href="<?= base_url('operator/jadwal');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-calendar"></i></div>
            <div class="nav-label">Jadwal</div>
        </a>
    </div>
</div>

<?= $this->endSection('content') ?>