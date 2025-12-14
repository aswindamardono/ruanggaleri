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
                    <div class="font-weight-bold"><?= $title;?></div>
                </div>
                <div class="card-body">
                    <form action="" method="post" autocomplete="off">
                        <?= csrf_field();?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="lokasi" class="form-label">Nama Lokasi</label>
                                    <input type="text"
                                        class="form-control <?= !empty($rusak['lokasi']) ? 'is-invalid' : ''; ?>"
                                        name="lokasi" id="lokasi" value="<?= old('lokasi', $lokasi['lokasi']);?>">
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['lokasi']) ? validation_show_error('lokasi') : ''; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-12 mb-3 font-weight-bold">
                                <div>Pengaturan Lokasi Absensi</div>
                            </div>
                            <div class=" col-lg-4">
                                <div class="form-group">
                                    <label for="lat" class="form-label">Latitude</label>
                                    <input type="text"
                                        class="form-control <?= !empty($rusak['lat']) ? 'is-invalid' : ''; ?>"
                                        name="lat" id="lat" value="<?= old('lat', $lokasi['lat']);?>">
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['lat']) ? validation_show_error('lat') : ''; ?>
                                    </small>
                                </div>
                            </div>
                            <div class=" col-lg-4">
                                <div class="form-group">
                                    <label for="long" class="form-label">Longtitude</label>
                                    <input type="text"
                                        class="form-control <?= !empty($rusak['long']) ? 'is-invalid' : ''; ?>"
                                        name="long" id="long" value="<?= old('long', $lokasi['long']);?>">
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['long']) ? validation_show_error('long') : ''; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="radius" class="form-label">Radius (Meter)</label>
                                    <input type="number"
                                        class="form-control <?= !empty($rusak['radius']) ? 'is-invalid' : ''; ?>"
                                        name="radius" id="radius" value="<?= old('radius', $lokasi['radius']);?>">
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['radius']) ? validation_show_error('radius') : ''; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="alamat" class="form-label">Alamat</label>
                                        <textarea name="alamat" id="alamat"
                                        class="form-control <?= !empty($rusak['alamat']) ? 'is-invalid' : ''; ?>"><?= old('alamat',$lokasi['address']);?></textarea>
                                        <small class="invalid-feedback">
                                            <?= !empty($rusak['alamat']) ? validation_show_error('alamat') : ''; ?>
                                        </small>
                                </div>
                            </div>
                            <div class="col-12 mb-3 font-weight-bold">
                                <div>Pengaturan Waktu Absensi</div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="jam_masuk" class="form-label">Jam Masuk (Menit)</label>
                                    <input type="time"
                                        class="form-control <?= !empty($rusak['jam_masuk']) ? 'is-invalid' : ''; ?>"
                                        name="jam_masuk" id="jam_masuk" value="<?= old('jam_masuk', $lokasi['jam_masuk']);?>">
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['jam_masuk']) ? validation_show_error('jam_masuk') : ''; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="jam_keluar" class="form-label">Jam Keluar (Menit)</label>
                                    <input type="time"
                                        class="form-control <?= !empty($rusak['jam_keluar']) ? 'is-invalid' : ''; ?>"
                                        name="jam_keluar" id="jam_keluar" value="<?= old('jam_keluar', $lokasi['jam_keluar']);?>">
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['jam_keluar']) ? validation_show_error('jam_keluar') : ''; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="sebelum_masuk" class="form-label">Waktu Absen Sebelum Masuk (Menit)</label>
                                    <input type="number"
                                        class="form-control <?= !empty($rusak['sebelum_masuk']) ? 'is-invalid' : ''; ?>"
                                        name="sebelum_masuk" id="sebelum_masuk" value="<?= old('sebelum_masuk', $lokasi['sebelum_masuk']);?>">
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['sebelum_masuk']) ? validation_show_error('sebelum_masuk') : ''; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="sebelum_pulang" class="form-label">Waktu Absen Sebelum Pulang (Menit)</label>
                                    <input type="number"
                                        class="form-control <?= !empty($rusak['sebelum_pulang']) ? 'is-invalid' : ''; ?>"
                                        name="sebelum_pulang" id="sebelum_pulang" value="<?= old('sebelum_pulang', $lokasi['sebelum_pulang']);?>">
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['sebelum_pulang']) ? validation_show_error('sebelum_pulang') : ''; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="setelah_pulang" class="form-label">Waktu Absen Setelah Pulang (Menit)</label>
                                    <input type="number"
                                        class="form-control <?= !empty($rusak['setelah_pulang']) ? 'is-invalid' : ''; ?>"
                                        name="setelah_pulang" id="setelah_pulang" value="<?= old('setelah_pulang', $lokasi['setelah_pulang']);?>">
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['setelah_pulang']) ? validation_show_error('setelah_pulang') : ''; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="mandor" class="form-label">Mandor</label>
                                    <select name="mandor" id="mandor"
                                        class="form-control <?= !empty($rusak['mandor']) ? 'is-invalid' : ''; ?>">
                                        <option value="">-- Pilih Jabatan --</option>
                                        <?php foreach($mandor as $row):?>
                                        <?php if($row['id'] ==  old('mandor', $lokasi['user_id'])):?>
                                        <option value="<?= $row['id'];?>" selected><?= $row['name'];?>
                                        </option>
                                        <?php else:?>
                                        <option value="<?= $row['id'];?>"><?= $row['name'];?>
                                        </option>
                                        <?php endif;?>
                                        <?php endforeach;?>
                                    </select>
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['mandor']) ? validation_show_error('mandor') : ''; ?>
                                    </small>
                                </div>
                            </div>                           
                        </div>
                        <div>
                            <button class="btn btn-primary">Update</button>
                            <a href="<?= base_url('operator/lokasi');?>" class="btn btn-info">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="font-weight-bold">Titik Lokasi Absen</div>
                </div>
                <div class="card-body">
                    <div id="maps" style="height: 500px; width: 100%;"></div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
var map = L.map('maps').setView([<?= $lokasi['lat'];?>, <?= $lokasi['long'];?>], 17);
var titleMap = " <?= $lokasi['address'];?>";

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

L.marker([<?= $lokasi['lat'];?>, <?= $lokasi['long'];?>]).addTo(map)
    .bindPopup(titleMap).openPopup();

var circle = L.circle([<?= $lokasi['lat'];?>, <?= $lokasi['long'];?>], {
    color: 'red',
    fillColor: '#f03',
    fillOpacity: 0.5,
    radius: <?= $lokasi['radius'];?>
}).addTo(map);
</script>

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
        <a href="<?= base_url('operator/lokasi');?>" class="nav-item active">
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