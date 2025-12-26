<?= $this->extend('template/layout_admin'); ?>
<?= $this->section('content'); ?>
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
                    <form action="<?= base_url('operator/penggajian/cari');?>" method="post">
                        <?= csrf_field();?>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="bulan" class="form-label">Bulan</label>
                                    <select name="bulan" id="bulan"
                                        class="form-control <?= !empty($rusak['bulan']) ? 'is-invalid' : ''; ?>">
                                        <option value="">-- Pilih Bulan --</option>
                                        <?php foreach($bulan as $row):?>
                                        <?php if($row['no'] ==  old('bulan', $bulan1)):?>
                                        <option value="<?= $row['no'];?>" selected><?= $row['nama'];?></option>
                                        <?php else:?>
                                        <option value="<?= $row['no'];?>"><?= $row['nama'];?></option>
                                        <?php endif;?>
                                        <?php endforeach;?>
                                    </select>
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['bulan']) ? validation_show_error('bulan') : ''; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label for="tahun" class="form-label">Tahun</label>
                                    <select name="tahun" id="tahun"
                                        class="form-control <?= !empty($rusak['tahun']) ? 'is-invalid' : ''; ?>">
                                        <option value="">-- Pilih Tahun --</option>
                                        <?php foreach($tahun as $row):?>
                                        <?php if($row ==  old('tahun', $tahun1)):?>
                                        <option value="<?= $row;?>" selected><?= $row;?></option>
                                        <?php else:?>
                                        <option value="<?= $row;?>"><?= $row;?></option>
                                        <?php endif;?>
                                        <?php endforeach;?>
                                    </select>
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['tahun']) ? validation_show_error('tahun') : ''; ?>
                                    </small>
                                </div>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-search mr-2"></i>Cari
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="font-weight-bold">Rekap Penggajian Dari
                        <?= tanggalindo(date($tahun1.'-'.$bulan1.'-'));?></div>
                    <div class="ml-auto">
                        <a class="btn btn-danger text-white"
                            href="<?= base_url('operator/penggajian/cetak/').$bulan1.'/'.$tahun1;?>">
                            <i class="fas fa-file-pdf mr-2"></i>PDF
                        </a>
                        <a class="btn btn-success text-white"
                            href="<?= base_url('operator/penggajian/excel/').$bulan1.'/'.$tahun1;?>">
                            <i class="fas fa-file-excel mr-2"></i>Excel
                        </a>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#add">
                            <i class="fas fa-plus mr-2"></i>Tambah
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered" id="table-1">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama</th>
                                    <th>Total Absen</th>
                                    <th>Gaji Pokok</th>
                                    <th>Tunjangan</th>
                                    <th>Lain - lain</th>
                                    <th>Lembur (Jam)</th>
                                    <th>Terlambat (Jam)</th>
                                    <th>Potongan</th>
                                    <th>Total</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;?>
                                <?php foreach ($penggajian as $row):?>
                                <tr>
                                    <td><?= $no++;?></td>
                                    <td><?= $row['name'];?></td>
                                    <td><?= $row['total_absensi'];?> (<?= $row['total_jam'];?> Jam)</td>
                                    <td><?= rupiah($row['gaji_pokok']);?></td>
                                    <td><?= rupiah($row['tunjangan']);?></td>
                                    <td><?= rupiah($row['lain_lain']);?></td>
                                    <td><?= $row['lembur'] ?? 0;?></td>
                                    <td><?= $row['terlambat'] ?? 0;?></td>
                                    <td><?= rupiah($row['potongan'] ?? 0);?></td>
                                    <td><?= rupiah($row['total']);?></td>
                                    <td>
                                        <div class="d-flex">
                                            <div>
                                                <button class="btn btn-warning btn-sm mr-1" data-toggle="modal"
                                                    data-target="#edit<?= $row["id"];?>">
                                                    <i class="fas fa-edit mr-1"></i>
                                                    <span>
                                                        Edit
                                                    </span>
                                                </button>
                                            </div>
                                            <div>
                                                <button class="btn btn-danger btn-sm"
                                                    data-confirm="Hapus Data|Apakah anda yakin ingin menghapus data gaji<?= $row['name'];?> ini ?"
                                                    data-confirm-yes="window.location.href='<?= base_url("operator/penggajian/delete/").$row["id"];?>'">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </div>
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
    <div class="modal fade" tabindex="-1" role="dialog" id="add">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="<?= base_url('operator/penggajian');?>" autocomplete="off">
                    <?= csrf_field();?>
                    <input type="hidden" name="bulan" value="<?= $bulan1;?>">
                    <input type="hidden" name="tahun" value="<?= $tahun1;?>">
                    <?php $gajiPerJam = $setting['gaji']; ?>
                    <input type="hidden" name="gaji" value="<?= $gajiPerJam; ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="guru" class="form-label">Karyawan</label>
                            <select name="guru" id="guru"
                                class="form-control <?= !empty($rusak['guru']) ? 'is-invalid' : ''; ?>">
                                <option value="">-- Pilih Karyawan --</option>
                                <?php foreach($guru as $row):?>
                                <?php $dataJam = $getabsensi2->getJam($row['id'], $bulan1, $tahun1); ?>
                                <?php if($row['id'] == old('guru')):?>
                                <option value="<?= $row['id'];?>" data-jam="<?= $dataJam;?>" selected>
                                    <?= $row['name'];?> - Hadir:
                                    <?= $getabsensi2->getHadir($row['id'], $bulan1, $tahun1);?> (<?= $dataJam;?> Jam)
                                </option>
                                <?php else:?>
                                <option value="<?= $row['id'];?>" data-jam="<?= $dataJam;?>">
                                    <?= $row['name'];?> - Hadir:
                                    <?= $getabsensi2->getHadir($row['id'], $bulan1, $tahun1);?> (<?= $dataJam;?> Jam)
                                </option>
                                <?php endif;?>
                                <?php endforeach;?>
                            </select>
                            <small class="invalid-feedback">
                                <?= !empty($rusak['guru']) ? validation_show_error('guru') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="gaji_pokok" class="form-label">Gaji Pokok (Gaji Harian x Total Hari)</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['gaji_pokok']) ? 'is-invalid' : ''; ?>"
                                id="gaji_pokok" name="gaji_pokok" autofocus value="<?= old('gaji_pokok'); ?>">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['gaji_pokok']) ? validation_show_error('gaji_pokok') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="tunjangan" class="form-label">Tunjangan</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['tunjangan']) ? 'is-invalid' : ''; ?>"
                                id="tunjangan" name="tunjangan" autofocus value="<?= old('tunjangan', 0); ?>">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['tunjangan']) ? validation_show_error('tunjangan') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="lain_lain" class="form-label">Lain Lain</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['lain_lain']) ? 'is-invalid' : ''; ?>"
                                id="lain_lain" name="lain_lain" autofocus value="<?= old('lain_lain', 0); ?>">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['lain_lain']) ? validation_show_error('lain_lain') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="lembur" class="form-label">Jam Lembur</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['lembur']) ? 'is-invalid' : ''; ?>"
                                id="lembur" name="lembur" autofocus value="<?= old('lembur', 0); ?>">
                            <small class="form-text text-muted">Bonus lembur: 350 gaji per jam</small>
                            <small class="invalid-feedback">
                                <?= !empty($rusak['lembur']) ? validation_show_error('lembur') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="terlambat" class="form-label">Jam Terlambat (Menit)</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['terlambat']) ? 'is-invalid' : ''; ?>"
                                id="terlambat" name="terlambat" autofocus value="<?= old('terlambat', 0); ?>">
                            <small class="form-text text-muted">Potongan terlambat: 500 per menit. <span id="terlambat_display" style="color: #007bff;"></span></small>
                            <small class="invalid-feedback">
                                <?= !empty($rusak['terlambat']) ? validation_show_error('terlambat') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="total" class="form-label">Total</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['total']) ? 'is-invalid' : ''; ?>" id="total"
                                name="total" autofocus value="<?= old('total'); ?>" readonly>
                            <small class="invalid-feedback">
                                <?= !empty($rusak['total']) ? validation_show_error('total') : ''; ?>
                            </small>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php foreach($penggajian as $row):?>
    <div class="modal fade" tabindex="-1" role="dialog" id="edit<?= $row["id"];?>">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="<?= base_url("operator/penggajian/update/").$row["id"];?>"
                    autocomplete="off">
                    <div class="modal-body">
                        <?= csrf_field();?>
                        <div class="form-group">
                            <label for="guru" class="form-label">Karyawan</label>
                            <div>
                                <?= $row['name'];?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gaji_pokok1" class="form-label">Gaji Pokok (Gaji harian x Total Hari)</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['gaji_pokok1']) ? 'is-invalid' : ''; ?>"
                                id="gaji_pokok1<?= $row["id"];?>" name="gaji_pokok1" autofocus
                                value="<?= old('gaji_pokok1', $row['gaji_pokok']); ?>">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['gaji_pokok1']) ? validation_show_error('gaji_pokok1') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="tunjangan1" class="form-label">Tunjangan</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['tunjangan1']) ? 'is-invalid' : ''; ?>"
                                id="tunjangan1<?= $row["id"];?>" name="tunjangan1" autofocus
                                value="<?= old('tunjangan1', $row['tunjangan']); ?>">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['tunjangan1']) ? validation_show_error('tunjangan1') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="lain_lain1" class="form-label">Lain Lain</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['lain_lain1']) ? 'is-invalid' : ''; ?>"
                                id="lain_lain1<?= $row["id"];?>" name="lain_lain1" autofocus
                                value="<?= old('lain_lain1', $row['lain_lain']); ?>">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['lain_lain1']) ? validation_show_error('lain_lain1') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="lembur1" class="form-label">Jam Lembur</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['lembur1']) ? 'is-invalid' : ''; ?>"
                                id="lembur1<?= $row["id"];?>" name="lembur1" autofocus
                                value="<?= old('lembur1', $row['lembur'] ?? 0); ?>">
                            <small class="form-text text-muted">Bonus lembur: 350 gaji per jam</small>
                            <small class="invalid-feedback">
                                <?= !empty($rusak['lembur1']) ? validation_show_error('lembur1') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="terlambat1" class="form-label">Jam Terlambat (Menit)</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['terlambat1']) ? 'is-invalid' : ''; ?>"
                                id="terlambat1<?= $row["id"];?>" name="terlambat1" autofocus
                                value="<?= old('terlambat1', $row['terlambat'] ?? 0); ?>">
                            <small class="form-text text-muted">Potongan terlambat: 500 per menit</small>
                            <small class="invalid-feedback">
                                <?= !empty($rusak['terlambat1']) ? validation_show_error('terlambat1') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="total1" class="form-label">Total</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['total1']) ? 'is-invalid' : ''; ?>"
                                id="total1<?= $row["id"];?>" name="total1" autofocus
                                value="<?= old('total1', $row['total']); ?>" readonly>
                            <small class="invalid-feedback">
                                <?= !empty($rusak['total1']) ? validation_show_error('total1') : ''; ?>
                            </small>
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
</div>
<script>
$(document).ready(function() {
    $('#guru').on('change', function() {
        var dataJam = $(this).find(':selected').data('jam');
        var gajiPerJam = parseInt('<?= $setting['gaji']; ?>') || 0;
        var totalJam = parseInt(dataJam) || 0;
        var gajiPokok = gajiPerJam * totalJam;
        $('#gaji_pokok').val(gajiPokok);
        
        // AJAX untuk ambil data terlambat otomatis
        var user_id = $(this).val();
        var bulan = $('input[name="bulan"]').val();
        var tahun = $('input[name="tahun"]').val();
        
        $.ajax({
            url: '<?= base_url('operator/penggajian/getTerlambatOtomatis'); ?>',
            type: 'POST',
            data: {
                user_id: user_id,
                bulan: bulan,
                tahun: tahun
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#terlambat').val(response.terlambat_menit);
                    $('#terlambat_display').text('(Otomatis: ' + response.display + ')');
                    calculateTotal();
                }
            }
        });
    });

    function calculateTotal() {
        var gajiPokok = parseInt($('#gaji_pokok').val()) || 0;
        var tunjangan = parseInt($('#tunjangan').val()) || 0;
        var lainLain = parseInt($('#lain_lain').val()) || 0;
        var lembur = parseInt($('#lembur').val()) || 0;
        var terlambat = parseInt($('#terlambat').val()) || 0;
        
        // Hitung bonus lembur
        var totalJam = parseInt('<?php echo isset($dataJam) ? $dataJam : 160; ?>') || 160;
        var gajiPerJam = totalJam > 0 ? gajiPokok / totalJam : 0;
        var bonusLembur = lembur * (gajiPerJam * 350);
        
        // Potongan terlambat: 500 per menit
        var potonganTerlambat = terlambat * 500;
        
        var total = gajiPokok + tunjangan + lainLain + bonusLembur - potonganTerlambat;
        $('#total').val(Math.round(total));
    }

    $('#gaji_pokok, #tunjangan, #lain_lain, #lembur, #terlambat').on('input', function() {
        calculateTotal();
    });

    <?php foreach($penggajian as $row):?>
    function calculateEditTotal<?= $row["id"];?>() {
        var gajiPokok = parseInt($('#gaji_pokok1<?= $row["id"];?>').val()) || 0;
        var tunjangan = parseInt($('#tunjangan1<?= $row["id"];?>').val()) || 0;
        var lainLain = parseInt($('#lain_lain1<?= $row["id"];?>').val()) || 0;
        var lembur = parseInt($('#lembur1<?= $row["id"];?>').val()) || 0;
        var terlambat = parseInt($('#terlambat1<?= $row["id"];?>').val()) || 0;
        
        // Hitung bonus lembur
        var totalJam = <?= $row['total_jam']; ?>;
        var gajiPerJam = totalJam > 0 ? gajiPokok / totalJam : 0;
        var bonusLembur = lembur * (gajiPerJam * 350);
        
        // Potongan terlambat: 500 per menit
        var potonganTerlambat = terlambat * 500;
        
        var total = gajiPokok + tunjangan + lainLain + bonusLembur - potonganTerlambat;
        $('#total1<?= $row["id"];?>').val(Math.round(total));
    }
    
    $('#gaji_pokok1<?= $row["id"];?>, #tunjangan1<?= $row["id"];?>, #lain_lain1<?= $row["id"];?>, #lembur1<?= $row["id"];?>, #terlambat1<?= $row["id"];?>').on('input',
        function() {
            calculateEditTotal<?= $row["id"];?>();
        });
    <?php endforeach;?>
});
</script>

<div class="bottom-nav">
    <div class="nav-container">
        <a href="<?= base_url('operator/monitoring');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-eye"></i></div>
            <div class="nav-label">Monitoring</div>
        </a>
        <a href="<?= base_url('operator/penggajian');?>" class="nav-item active">
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

<?= $this->endSection('content'); ?>