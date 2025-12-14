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
                    <div class="font-weight-bold">Data <?= $title; ?></div>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= base_url("operator/monitoring/cari");?>" autocomplete="off">
                        <?= csrf_field();?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="bulan" class="form-label">Tanggal</label>
                                    <input type="date"
                                        class="form-control <?= !empty($rusak['tanggal']) ? 'is-invalid' : ''; ?>"
                                        name="tanggal" id="tanggal" value="<?= old('tanggal', $tanggal);?>">
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['tanggal']) ? validation_show_error('tanggal') : ''; ?>
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
            <div class="card">
                <div class="card-header">
                    <div class="font-weight-bold">Rekap Data <?= $title;?> <?= tanggalindo($tanggal);?> (terlambat 5 menit dianggap tidak terlambat)
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="table-1">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>Jam Masuk</th>
                                    <th>Foto</th>
                                    <th>Jam Keluar</th>
                                    <th>Foto</th>
                                    <th>Keterangan</th>
                                    <th>Lokasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;?>
                                <?php foreach ($monitoring as $row):?>
                                <tr>
                                    <td><?= $no++;?></td>
                                    <td><?= $row['name'];?></td>
                                    <td><?= $row['name_jabatan'];?> (<?= $row['akronim'];?>)</td>
                                    <td>
                                        <div
                                            class="badge <?= empty($row['image_in']) ? 'badge-danger' : 'badge-success' ;?>">
                                            <small><?= empty($row['image_in']) ? 'Belum Absen' : $row['hour_in'] ;?></small>
                                        </div>
                                    </td>
                                    <td><img src="<?= base_url('assets/img/absensi/').$row['image_in'];?>" width="100px"
                                            alt=""></td>
                                    <td>
                                        <div
                                            class="badge <?= empty($row['image_out']) ? 'badge-danger' : 'badge-success' ;?>">
                                            <small><?= empty($row['image_out']) ? 'Belum Absen' : $row['hour_out'] ;?></small>
                                        </div>
                                    </td>
                                    <td><img src="<?= base_url('assets/img/absensi/').$row['image_out'];?>"
                                            width="100px" alt=""></td>
                                    <td>
                                        <div
                                            class="badge <?= empty($row['hour_in'] <= $row['jam_masuk']) ? 'badge-danger' : 'badge-success' ;?>">
                                            <small>
                                                <?= empty($row['hour_in'] <= $row['jam_masuk']) ? 'Terlambat '.jam_terlambat($row['jam_masuk'], $row['hour_in']) : 'Tepat Waktu' ;?></small>
                                        </div>
                                    </td>

                                    <td>
                                        <div>
                                            <button class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#edit<?= $row["id"];?>">
                                                <i class="fas fa-map mr-1"></i>
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
<?php foreach($monitoring as $row):?>
<div class="modal fade" tabindex="-1" role="dialog" id="edit<?= $row["id"];?>">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lokasi Presensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="map<?= $row["id"];?>" style="height: 200px; width: 100%;"></div>
                <script>
                var map<?= $row["id"];?> = L.map('map<?= $row["id"];?>').setView([<?= $setting['lat'];?>,
                    <?= $setting['long'];?>
                ], 13);
                var titleMap<?= $row["id"];?> = "<?= $row['name'];?>";

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map<?= $row["id"];?>);

                L.marker([<?= $row["location_in"];?>]).addTo(map<?= $row["id"];?>)
                    .bindPopup(titleMap<?= $row["id"];?>).openPopup();

                var circle = L.circle([<?= $setting['lat'];?>, <?= $setting['long'];?>], {
                    color: 'red',
                    fillColor: '#f03',
                    fillOpacity: 0.5,
                    radius: <?= $setting['radius'];?>
                }).addTo(map);
                </script>
            </div>
        </div>
    </div>
</div>
<?php endforeach;?>

<div class="bottom-nav">
    <div class="nav-container">
        <a href="<?= base_url('operator/monitoring');?>" class="nav-item active">
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