<?= $this->extend('template/layout_admin') ?>
<?= $this->section('content') ?>
<?php $rusak = validation_errors();?>
<style>
    body { padding-bottom: 140px; }
    @keyframes slideUpNav {
        from { transform: translateY(100%); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }
    @keyframes pulseGlow {
        0%, 100% { opacity: 0.3; transform: scale(1); }
        50% { opacity: 0.6; transform: scale(1.2); }
    }
    
    /* Clickable Stats Card */
    .stat-card {
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
        border-radius: 12px;
        overflow: hidden;
    }
    
    .stat-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    }
    
    .stat-card-icon {
        font-size: 2.5rem;
        margin-bottom: 15px;
    }
    
    .stat-card-title {
        font-size: 0.95rem;
        font-weight: 600;
        color: rgba(255,255,255,0.8);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .stat-card-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin-top: 10px;
    }
    
    .stat-card-bg-workorder {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .stat-card-bg-pegawai {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }
    
    .stat-card-bg-izin {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }
    
    .stat-card-bg-kasbon {
        background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
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
        max-width: 700px;
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
    .nav-item:hover { color: #06b6d4; transform: translateY(-3px); }
    .nav-item.active { color: #06b6d4; }
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
            <div class="row">
                <!-- Workorder Card -->
                <div class="col-lg-3 col-sm-6 mb-4">
                    <a href="<?= base_url('operator/workorder');?>" class="card stat-card stat-card-bg-workorder" style="text-decoration: none; display: block; padding: 2rem; height: 100%;">
                        <div class="stat-card-icon">
                            <i class="fas fa-tasks"></i>
                        </div>
                        <div class="stat-card-title">Work Order</div>
                        <div class="stat-card-value"><?= $total_workorder;?></div>
                    </a>
                </div>
                
                <!-- Pegawai Card -->
                <div class="col-lg-3 col-sm-6 mb-4">
                    <a href="<?= base_url('operator/karyawan');?>" class="card stat-card stat-card-bg-pegawai" style="text-decoration: none; display: block; padding: 2rem; height: 100%;">
                        <div class="stat-card-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-card-title">Pegawai</div>
                        <div class="stat-card-value"><?= $total_pegawai;?></div>
                    </a>
                </div>
                
                <!-- Izin Card -->
                <div class="col-lg-3 col-sm-6 mb-4">
                    <a href="<?= base_url('operator/izin');?>" class="card stat-card stat-card-bg-izin" style="text-decoration: none; display: block; padding: 2rem; height: 100%;">
                        <div class="stat-card-icon">
                            <i class="fas fa-calendar-times"></i>
                        </div>
                        <div class="stat-card-title">Izin</div>
                        <div class="stat-card-value"><?= $total_izin;?></div>
                    </a>
                </div>
                
                <!-- Kasbon Card -->
                <div class="col-lg-3 col-sm-6 mb-4">
                    <a href="<?= base_url('operator/kasbon');?>" class="card stat-card stat-card-bg-kasbon" style="text-decoration: none; display: block; padding: 2rem; height: 100%;">
                        <div class="stat-card-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="stat-card-title">Kasbon</div>
                        <div class="stat-card-value"><?= $total_kasbon;?></div>
                    </a>
                </div>
                
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Penggajian <?= date('Y');?></h4>
                        </div>
                        <div class="card-body">
                            <canvas id="myChart2"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
var ctx = document.getElementById("myChart2").getContext('2d');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September",
            "Oktober", "November", "Desember"
        ],
        datasets: [{
            label: 'Data Penggajian',
            data: [
                <?php for ($i = 1; $i <= 12; $i++) {
                    echo $penggajian->getSum($i, date('Y')). ",";
                } ?>
            ],
            borderWidth: 2,
            backgroundColor: '#63ed7a',
            borderColor: '#63ed7a',
            borderWidth: 2.5,
            pointBackgroundColor: '#ffffff',
            pointRadius: 4
        }]
    },
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