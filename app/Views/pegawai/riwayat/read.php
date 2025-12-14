<?= $this->extend('template/layout_user') ?>
<?= $this->section('content') ?>
<?php $rusak = validation_errors();?>

<style>
    body { padding-bottom: 140px; }
    
    /* Bottom Navigation */
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(90deg, #1a1a2e 0%, #16213e 50%, #1a1a2e 100%);
        border-top: 1px solid #333;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 90px;
        box-shadow: 0 -4px 30px rgba(0, 0, 0, 0.3), inset 0 1px 0 rgba(255, 255, 255, 0.05);
        z-index: 1000;
        padding-bottom: env(safe-area-inset-bottom);
        animation: slideUpNav 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes slideUpNav {
        from {
            transform: translateY(100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .nav-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 0;
        width: 100%;
        height: 100%;
        max-width: 600px;
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex: 1;
        height: 90px;
        text-decoration: none;
        color: #888;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        font-size: 0.75rem;
        cursor: pointer;
    }

    .nav-item:hover:not(.nav-absensi) {
        color: #06b6d4;
        transform: translateY(-3px);
    }

    .nav-item.active:not(.nav-absensi)::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #4f46e5, #06b6d4);
        border-radius: 3px 3px 0 0;
    }

    .nav-icon {
        font-size: 1.3rem;
        margin-bottom: 0.3rem;
        transition: all 0.3s ease;
    }

    .nav-label {
        font-size: 0.7rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Absensi Button in Navbar */
    .nav-absensi {
        position: relative;
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        border-radius: 50%;
        color: white;
        font-size: 1.4rem;
        flex: none;
        box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
        transform: translateY(-15px);
        animation: bounceAbsensi 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes bounceAbsensi {
        0% {
            transform: translateY(30px);
            opacity: 0;
        }
        50% {
            transform: translateY(-20px);
        }
        100% {
            transform: translateY(-15px);
            opacity: 1;
        }
    }

    .nav-absensi::before {
        content: '';
        position: absolute;
        top: -5px;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        height: 10px;
        background: radial-gradient(ellipse at center, rgba(255, 107, 53, 0.3) 0%, transparent 70%);
        border-radius: 50%;
        animation: pulseGlow 2s ease-in-out infinite;
    }

    @keyframes pulseGlow {
        0%, 100% {
            opacity: 0.3;
            transform: translateX(-50%) scale(1);
        }
        50% {
            opacity: 0.6;
            transform: translateX(-50%) scale(1.2);
        }
    }

    .nav-absensi:hover {
        transform: translateY(-15px) scale(1.1);
        box-shadow: 0 12px 40px rgba(255, 107, 53, 0.6), 0 0 20px rgba(255, 107, 53, 0.3);
    }

    .nav-absensi:active {
        transform: translateY(-12px) scale(0.95);
    }

    .nav-absensi .nav-icon {
        margin-bottom: 0;
    }

    .nav-absensi .nav-label {
        font-size: 0.65rem;
        margin-top: 0.2rem;
        font-weight: 700;
        letter-spacing: 1px;
    }
</style>

<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="font-weight-bold"><?= $title; ?></div>
                            </div>
                            <div class="card-body">
                                <form method="post" action="<?= base_url("riwayat/cari");?>" autocomplete="off">
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
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="tahun" class="form-label">Tahun</label>
                                                <select name="tahun" id="tahun"
                                                    class="form-control <?= !empty($rusak['tahun']) ? 'is-invalid' : ''; ?>">
                                                    <option value="">-- Pilih Tahun --</option>
                                                    <?php foreach($tahun as $row):?>
                                                    <?php if($row ==  old('tahun', $tahun1)):?>
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
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-search mr-2"></i>Cari</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <?php if($cariabsen != "belum"):?>
                        <?php if(!empty($cariabsen)):?>
                        <div class="card">
                            <div class="card-header">
                                <div class="font-weight-bold">Rekap Presensi Bulan
                                    <?= tanggalindo(date($tahun1.'-'.$bulan1.'-'));?>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <?php foreach($cariabsen as $row):?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-info ml-2">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </td>
                                            <td>
                                                <?= tanggalindo($row['date']);?>
                                            </td>
                                            <td>
                                                <div class="badge badge-success">
                                                    <small>Masuk
                                                        :
                                                        <?= empty($row['image_in']) ? 'Belum Absen' : $row['hour_in'] ;?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="badge badge-danger">
                                                    <small>Pulang :
                                                        <?= empty($row['image_out']) ? 'Belum Absen' : $row['hour_out'] ;?></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php elseif(empty($cariabsen)):?>
                        <div class="card">
                            <div class="card-header">
                                <div class="font-weight-bold">Rekap Absensi Bulan
                                    <?= tanggalindo(date($tahun1.'-'.$bulan1.'-'));?>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="text-center">Maaf Data Rekap Absensi
                                    <?= tanggalindo(date($tahun1.'-'.$bulan1.'-'));?> Tidak Ditemukan</div>
                            </div>
                        </div>
                        <?php endif;?>
                        <?php else:?>
                        <div class="card">
                            <div class="card-header">
                                <div class="font-weight-bold">Rekap Presensi Bulan
                                    <?= tanggalindo(date($tahun1.'-'.$bulan1.'-'));?>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table">
                                        <?php foreach($cariabsen2 as $row):?>
                                        <tr>
                                            <td>
                                                <span class="badge badge-info ml-2">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                            </td>
                                            <td>
                                                <?= tanggalindo($row['date']);?>
                                            </td>
                                            <td>
                                                <div class="badge badge-success">
                                                    <small>Masuk
                                                        :
                                                        <?= empty($row['image_in']) ? 'Belum Absen' : $row['hour_in'] ;?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="badge badge-danger">
                                                    <small>Pulang :
                                                        <?= empty($row['image_out']) ? 'Belum Absen' : $row['hour_out'] ;?></small>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach;?>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Bottom Navigation dengan Absensi Terintegrasi -->
<div class="bottom-nav">
    <div class="nav-container">
        <a href="<?= base_url('home');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-home"></i></div>
            <div class="nav-label">Home</div>
        </a>
        <a href="<?= base_url('profil');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-user"></i></div>
            <div class="nav-label">Profil</div>
        </a>
        
        <a href="<?= base_url('absensi');?>" class="nav-item nav-absensi" title="Absensi Sekarang">
            <div class="nav-icon"><i class="fas fa-camera"></i></div>
            <div class="nav-label">Absensi</div>
        </a>
        
        <a href="<?= base_url('izin');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-file-alt"></i></div>
            <div class="nav-label">Izin</div>
        </a>
        <a href="<?= base_url('riwayat');?>" class="nav-item active">
            <div class="nav-icon"><i class="fas fa-history"></i></div>
            <div class="nav-label">Riwayat</div>
        </a>
    </div>
</div>

<?= $this->endSection('content') ?>