<?= $this->extend('template/layout_user') ?>
<?= $this->section('content') ?>

<style>
    body { padding-bottom: 140px; }
    @keyframes slideUpNav {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes bounceAbsensi {
        0% { transform: translateY(30px); opacity: 0; }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(-15px); opacity: 1; }
    }
    @keyframes pulseGlow {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(1.2); }
    }
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        height: 90px;
        background: linear-gradient(90deg, #1a1a2e 0%, #16213e 50%, #1a1a2e 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        box-shadow: 0 -4px 30px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.05);
        animation: slideUpNav 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        z-index: 1000;
        padding-bottom: env(safe-area-inset-bottom);
    }
    .nav-container {
        max-width: 600px;
        width: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        padding: 0 15px;
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
    }
    .nav-item:hover:not(.nav-absensi) {
        color: #06b6d4;
        transform: translateY(-3px);
    }
    .nav-item.active {
        color: #06b6d4;
    }
    .nav-item::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 0;
        background: linear-gradient(90deg, #4f46e5, #06b6d4);
        border-radius: 2px 2px 0 0;
        transition: height 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .nav-item.active::before {
        bottom: 0;
        height: 3px;
        background: linear-gradient(90deg, #4f46e5, #06b6d4);
    }
    .nav-item:hover:not(.nav-absensi)::before {
        height: 2px;
    }
    .nav-icon {
        font-size: 1.5rem;
        margin-bottom: 0.25rem;
    }
    .nav-label {
        font-size: 0.75rem;
        font-weight: 600;
    }
    .nav-absensi {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #ff6b35 0%, #f7931e 100%);
        border-radius: 50%;
        color: white !important;
        transform: translateY(-15px);
        animation: bounceAbsensi 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        box-shadow: 0 8px 25px rgba(255, 107, 53, 0.4);
        position: relative;
    }
    .nav-absensi::before {
        content: '';
        position: absolute;
        inset: -8px;
        background: radial-gradient(ellipse, rgba(255,107,53,0.3), transparent);
        border-radius: 50%;
        animation: pulseGlow 2s ease-in-out infinite;
        z-index: -1;
    }
    .nav-absensi:hover {
        transform: translateY(-15px) scale(1.1);
        box-shadow: 0 12px 35px rgba(255, 107, 53, 0.6);
    }
    .nav-absensi::after {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 50%;
        background: inherit;
        animation: pulseGlow 2s ease-in-out infinite;
        opacity: 0.3;
        z-index: -1;
    }
</style>

<div class="main-content">
    <section class="section">
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex flex-column">
                                <div class="font-weight-bold"><?= $title; ?></div>
                                <div>
                                    <b><?= $user['name'];?></b><br>
                                    <b><?= isset($lokasi['lokasi']) ? $lokasi['lokasi'] : 'Lokasi tidak ditemukan'; ?></b><br>
                                    <small><?= tanggalIndo(date("Y-m-d"));?> | <span id="jam"></span>
                                    </small>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 text-center">
                                        <div class="btn btn-outline-success rounded-circle mb-3 disabled hover">
                                            <i class=" fas fa-check my-2" style="font-size: 70px"></i>
                                        </div>
                                        <div class="mb-1">Terima Kasih, Sudah Absensi Masuk dan Pulang.</div>
                                        <div class="mb-3">Jangan Lupa Istirahat dan Tetap Semangat.</div>
                                        <div>
                                            <div class="badge badge-success">Masuk : <?= $absensi['hour_in'];?></div>
                                            <div class="badge badge-danger">Pulang : <?= $absensi['hour_out'];?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="<?= base_url();?>assets/js/absen.js"></script>

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
        <a href="<?= base_url('absensi');?>" class="nav-item nav-absensi">
            <div class="nav-icon"><i class="fas fa-camera"></i></div>
            <div class="nav-label">Absensi</div>
        </a>
        <a href="<?= base_url('izin');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-file-alt"></i></div>
            <div class="nav-label">Izin</div>
        </a>
        <a href="<?= base_url('riwayat');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-history"></i></div>
            <div class="nav-label">Riwayat</div>
        </a>
    </div>
</div>

<?= $this->endSection('content') ?>