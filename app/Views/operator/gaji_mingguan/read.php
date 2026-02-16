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
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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
                    <form action="<?= base_url('operator/gaji-mingguan/cari');?>" method="post">
                        <?= csrf_field();?>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label for="daterange" class="form-label">Range Tanggal</label>
                                    <input type="text" name="daterange" id="daterange" class="form-control" style="cursor: pointer;">
                                    <input type="hidden" name="tanggal_mulai" id="tanggal_mulai" value="<?= old('tanggal_mulai', $tanggal_mulai); ?>">
                                    <input type="hidden" name="tanggal_selesai" id="tanggal_selesai" value="<?= old('tanggal_selesai', $tanggal_selesai); ?>">
                                    <small class="invalid-feedback">
                                        <?= !empty($rusak['tanggal_mulai']) ? validation_show_error('tanggal_mulai') : ''; ?>
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
                    <div class="font-weight-bold">Rekap Gaji Mingguan Dari <?= tanggalindo($tanggal_mulai);?> s/d <?= tanggalindo($tanggal_selesai);?></div>
                    <div class="ml-auto">
                        <a class="btn btn-success text-white" href="<?= base_url('operator/gaji-mingguan/excel/').$tanggal_mulai.'/'.$tanggal_selesai;?>">
                            <i class="fas fa-file-excel mr-2"></i>Excel
                        </a>
                        <a class="btn btn-info text-white" href="<?= base_url('operator/gaji-mingguan/cetak/').$tanggal_mulai.'/'.$tanggal_selesai;?>">
                            <i class="fas fa-print mr-2"></i>Cetak
                        </a>
                        <!-- <button class="btn btn-primary" data-toggle="modal" data-target="#add">
                            <i class="fas fa-plus mr-2"></i>Tambah
                        </button> -->
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
                                    <th>Lembur (Menit)</th>
                                    <th>Tambahan</th>
                                    <th>Terlambat (Menit)</th>
                                    <th>Potongan</th>
                                    <th>Total</th>
                                    <!-- <th>Aksi</th> -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;?>
                                <?php foreach ($gaji_mingguan as $row):?>
                                <tr>
                                    <td><?= $no++;?></td>
                                    <td><?= $row['name'];?></td>
                                    <td><?= $row['total_absensi'];?></td>
                                    <td><?= rupiah($row['gaji_pokok']);?></td>
                                    <td><?= $row['lembur'] ?? 0;?></td>
                                    <td><?= rupiah($row['lain_lain']);?></td>
                                    <td><?= $row['terlambat'] ?? 0;?></td>
                                    <td><?= rupiah($row['potongan'] ?? 0);?></td>
                                    <td><?= rupiah($row['total']);?></td>
                                    <!-- <td>
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
                                                    data-confirm="Hapus Data|Apakah anda yakin ingin menghapus data gaji mingguan <?= $row['name'];?> ini ?"
                                                    data-confirm-yes="window.location.href='<?= base_url('operator/gaji-mingguan/delete/').$row['id'];?>'">
                                                    <i class="fas fa-trash mr-1"></i>Hapus
                                                </button>
                                            </div>
                                        </div>
                                    </td> -->
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
                <form method="post" action="<?= base_url('operator/gaji-mingguan');?>" autocomplete="off">
                    <?= csrf_field();?>
                    <input type="hidden" name="tanggal_mulai" id="hidden_tanggal_mulai" value="<?= $tanggal_mulai;?>">
                    <input type="hidden" name="tanggal_selesai" id="hidden_tanggal_selesai" value="<?= $tanggal_selesai;?>">
                    <?php $gajiPerJam = $setting['gaji']; ?>
                    <input type="hidden" name="gaji" value="<?= $gajiPerJam; ?>">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="guru" class="form-label">Karyawan</label>
                            <select name="guru" id="guru"
                                class="form-control <?= !empty($rusak['guru']) ? 'is-invalid' : ''; ?>">
                                <option value="">-- Pilih Karyawan --</option>
                                <?php foreach($guru as $row):?>
                                <?php $dataJam = $getabsensi2->getJamRange($row['id'], $tanggal_mulai, $tanggal_selesai); ?>
                                <?php if($row['id'] == old('guru')):?>
                                <option value="<?= $row['id'];?>" data-jam="<?= $dataJam;?>" selected>
                                    <?= $row['name'];?> - Hadir:
                                    <?= $getabsensi2->getHadirRange($row['id'], $tanggal_mulai, $tanggal_selesai);?> (<?= $dataJam;?>)
                                </option>
                                <?php else:?>
                                <option value="<?= $row['id'];?>" data-jam="<?= $dataJam;?>">
                                    <?= $row['name'];?> - Hadir:
                                    <?= $getabsensi2->getHadirRange($row['id'], $tanggal_mulai, $tanggal_selesai);?> (<?= $dataJam;?>)
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
                            <label for="kasbon" class="form-label">Kasbon</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['kasbon']) ? 'is-invalid' : ''; ?>"
                                id="kasbon" name="kasbon" autofocus value="<?= old('kasbon', 0); ?>">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['kasbon']) ? validation_show_error('kasbon') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="lembur" class="form-label">Jam Lembur</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['lembur']) ? 'is-invalid' : ''; ?>"
                                id="lembur" name="lembur" autofocus value="<?= old('lembur', 0); ?>">
                            <small class="form-text text-muted">Bonus lembur: 350 gaji per menit. <span id="lembur_display" style="color: #007bff;"></span></small>
                            <small class="invalid-feedback">
                                <?= !empty($rusak['lembur']) ? validation_show_error('lembur') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="lain_lain" class="form-label">Tambahan</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['lain_lain']) ? 'is-invalid' : ''; ?>"
                                id="lain_lain" name="lain_lain" autofocus value="<?= old('lain_lain', 0); ?>">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['lain_lain']) ? validation_show_error('lain_lain') : ''; ?>
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
    <?php foreach($gaji_mingguan as $row):?>
    <div class="modal fade" tabindex="-1" role="dialog" id="edit<?= $row["id"];?>">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="<?= base_url('operator/gaji-mingguan/update/').$row["id"];?>"
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
                            <label for="kasbon1" class="form-label">Kasbon</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['kasbon1']) ? 'is-invalid' : ''; ?>"
                                id="kasbon1<?= $row["id"];?>" name="kasbon1" autofocus
                                value="<?= old('kasbon1', $row['kasbon']); ?>">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['kasbon1']) ? validation_show_error('kasbon1') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="lembur1" class="form-label">Jam Lembur</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['lembur1']) ? 'is-invalid' : ''; ?>"
                                id="lembur1<?= $row["id"];?>" name="lembur1" autofocus
                                value="<?= old('lembur1', $row['lembur'] ?? 0); ?>">
                            <small class="form-text text-muted">Bonus lembur: 350 gaji per menit</small>
                            <small class="invalid-feedback">
                                <?= !empty($rusak['lembur1']) ? validation_show_error('lembur1') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="lain_lain1" class="form-label">Tambahan</label>
                            <input type="number"
                                class="form-control <?= !empty($rusak['lain_lain1']) ? 'is-invalid' : ''; ?>"
                                id="lain_lain1<?= $row["id"];?>" name="lain_lain1" autofocus
                                value="<?= old('lain_lain1', $row['lain_lain']); ?>">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['lain_lain1']) ? validation_show_error('lain_lain1') : ''; ?>
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
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
$(document).ready(function() {
    // Initialize DateRangePicker
    var tanggal_mulai = moment('<?= $tanggal_mulai; ?>');
    var tanggal_selesai = moment('<?= $tanggal_selesai; ?>');
    
    $('#daterange').daterangepicker({
        locale: {
            format: 'DD/MM/YYYY',
            applyLabel: 'Apply',
            cancelLabel: 'Cancel',
            fromLabel: 'From',
            toLabel: 'To',
            monthNames: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            daysOfWeek: ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab']
        },
        startDate: tanggal_mulai,
        endDate: tanggal_selesai
    }, function(start, end) {
        $('#tanggal_mulai').val(start.format('YYYY-MM-DD'));
        $('#tanggal_selesai').val(end.format('YYYY-MM-DD'));
        $('#hidden_tanggal_mulai').val(start.format('YYYY-MM-DD'));
        $('#hidden_tanggal_selesai').val(end.format('YYYY-MM-DD'));
        $('#daterange').val(start.format('DD/MM/YYYY') + ' - ' + end.format('DD/MM/YYYY'));
    });
    
    // Set initial display
    $('#daterange').val(tanggal_mulai.format('DD/MM/YYYY') + ' - ' + tanggal_selesai.format('DD/MM/YYYY'));

    $('#guru').on('change', function() {
        var dataJam = $(this).find(':selected').data('jam');
        var gajiPerJam = parseInt('<?= $setting['gaji']; ?>') || 0;
        var totalJam = parseInt(dataJam) || 0;
        var gajiPokok = gajiPerJam * totalJam;
        $('#gaji_pokok').val(gajiPokok);
        
        var user_id = $(this).val();
        var tanggal_mulai = $('#hidden_tanggal_mulai').val();
        var tanggal_selesai = $('#hidden_tanggal_selesai').val();
        
        $.ajax({
            url: '<?= base_url('operator/gaji-mingguan/getTerlambatOtomatisRange'); ?>',
            type: 'POST',
            data: {
                user_id: user_id,
                tanggal_mulai: tanggal_mulai,
                tanggal_selesai: tanggal_selesai
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#terlambat').val(response.terlambat_menit);
                    $('#terlambat_display').text('(Otomatis: ' + response.display + ')');
                    
                    $('#lembur').val(response.lembur_menit);
                    $('#lembur_display').text('(Otomatis: ' + response.lembur_menit + ' menit)');
                    
                    calculateTotal();
                }
            }
        });
    });

    function calculateTotal() {
        var gajiPokok = parseInt($('#gaji_pokok').val()) || 0;
        var kasbon = parseInt($('#kasbon').val()) || 0;
        var lainLain = parseInt($('#lain_lain').val()) || 0;
        var lembur = parseInt($('#lembur').val()) || 0;
        var terlambat = parseInt($('#terlambat').val()) || 0;
        
        var tambahan = lembur * 350;
        $('#lain_lain').val(tambahan);
        
        lainLain = tambahan;
        
        var potonganTerlambat = terlambat * 500;
        
        var total = gajiPokok - kasbon + lainLain - potonganTerlambat;
        $('#total').val(Math.round(total));
    }

    $('#gaji_pokok, #kasbon, #lain_lain, #lembur, #terlambat').on('input', function() {
        calculateTotal();
    });

    <?php foreach($gaji_mingguan as $row):?>
    function calculateEditTotal<?= $row["id"];?>() {
        var gajiPokok = parseInt($('#gaji_pokok1<?= $row["id"];?>').val()) || 0;
        var kasbon = parseInt($('#kasbon1<?= $row["id"];?>').val()) || 0;
        var lainLain = parseInt($('#lain_lain1<?= $row["id"];?>').val()) || 0;
        var lembur = parseInt($('#lembur1<?= $row["id"];?>').val()) || 0;
        var terlambat = parseInt($('#terlambat1<?= $row["id"];?>').val()) || 0;
        
        var tambahan = lembur * 350;
        $('#lain_lain1<?= $row["id"];?>').val(tambahan);
        
        lainLain = tambahan;
        
        var potonganTerlambat = terlambat * 500;
        
        var total = gajiPokok - kasbon + lainLain - potonganTerlambat;
        $('#total1<?= $row["id"];?>').val(Math.round(total));
    }

    $('#gaji_pokok1<?= $row["id"];?>, #kasbon1<?= $row["id"];?>, #lain_lain1<?= $row["id"];?>, #lembur1<?= $row["id"];?>, #terlambat1<?= $row["id"];?>').on('input',
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
        <a href="<?= base_url('operator/penggajian');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-money-bill"></i></div>
            <div class="nav-label">Penggajian</div>
        </a>
        <a href="<?= base_url('operator/gaji-mingguan');?>" class="nav-item active">
            <div class="nav-icon"><i class="fas fa-dollar-sign"></i></div>
            <div class="nav-label">Gaji Mingguan</div>
        </a>
        <a href="<?= base_url('operator/karyawan');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-users"></i></div>
            <div class="nav-label">Karyawan</div>
        </a>
        <a href="<?= base_url('operator/workorder');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-fire"></i></div>
            <div class="nav-label">Work Order</div>
        </a>
    </div>
</div>
<?= $this->endSection(); ?>
