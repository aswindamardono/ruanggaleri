<?= $this->extend('template/layout_user') ?>
<?= $this->section('content') ?>

<style>
    :root {
        --primary: #4f46e5;
        --secondary: #06b6d4;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
    }

    body {
        background: #f8fafc;
        padding-bottom: 140px;
    }

    .main-content {
        padding: 1rem;
    }

    /* Welcome Section */
    .welcome-section {
        background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        border-radius: 20px;
        padding: 2rem 1.5rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(79, 70, 229, 0.2);
        animation: slideInDown 0.6s ease-out;
    }

    .welcome-greeting {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .welcome-subtitle {
        font-size: 0.95rem;
        opacity: 0.9;
        margin-bottom: 1.5rem;
    }

    .welcome-avatar {
        width: 50px;
        height: 50px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    /* Attendance Status Cards */
    .attendance-cards {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .attendance-card {
        border-radius: 16px;
        padding: 1.5rem;
        color: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 140px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.6s ease-out;
    }

    .attendance-card.check-in {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }

    .attendance-card.check-out {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    }

    .attendance-label {
        font-size: 0.85rem;
        opacity: 0.9;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }

    .attendance-time {
        font-size: 1.8rem;
        font-weight: 700;
    }

    .attendance-status {
        font-size: 0.8rem;
        opacity: 0.8;
        margin-top: 0.5rem;
    }

    .attendance-image {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }

    /* Summary Section */
    .summary-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
        margin-top: 0.5rem;
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 2rem;
    }

    .summary-card {
        background: white;
        border-radius: 14px;
        padding: 1.2rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .summary-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.12);
    }

    .summary-icon {
        font-size: 1.5rem;
        margin-bottom: 0.5rem;
    }

    .summary-label {
        font-size: 0.8rem;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .summary-value {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .summary-card.hadir .summary-value { color: var(--success); }
    .summary-card.izin .summary-value { color: var(--primary); }
    .summary-card.sakit .summary-value { color: var(--danger); }
    .summary-card.terlambat .summary-value { color: var(--warning); }
    .summary-card.logout { background: linear-gradient(135deg, #c1c1c1 0%, #d57c7c 100%); cursor: pointer; }
    .summary-card.logout .summary-icon { color: white; }
    .summary-card.logout .summary-label { color: rgba(255, 255, 255, 0.9); }
    .summary-card.logout .summary-value { color: white; }

    /* Attendance History */
    .attendance-history {
        background: white;
        border-radius: 14px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .history-title {
        font-size: 1rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 1rem;
    }

    .history-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        border-bottom: 1px solid #e2e8f0;
        gap: 1rem;
    }

    .history-item:last-child {
        border-bottom: none;
    }

    .history-date {
        flex: 1;
        font-weight: 600;
        color: #1e293b;
    }

    .history-times {
        display: flex;
        gap: 0.5rem;
        flex: 1;
    }

    .time-badge {
        font-size: 0.75rem;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        white-space: nowrap;
    }

    .time-badge.in {
        background: #d1fae5;
        color: #065f46;
    }

    .time-badge.out {
        background: #fee2e2;
        color: #7f1d1d;
    }

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

    /* Animations */
    @keyframes slideInDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    /* Responsive */
    @media (max-width: 768px) {
        .summary-cards {
            grid-template-columns: repeat(2, 1fr);
        }

        .attendance-cards {
            grid-template-columns: 1fr 1fr;
        }

        .main-content {
            padding: 0.75rem;
        }

        .welcome-section {
            padding: 1.5rem 1.25rem;
        }

        .fab-absensi {
            width: 65px;
            height: 65px;
            font-size: 1.5rem;
            bottom: 95px;
            right: 1rem;
        }
    }

    @media (max-width: 480px) {
        body {
            padding-bottom: 100px;
        }

        .bottom-nav {
            height: 70px;
        }

        .nav-item {
            height: 70px;
        }

        .nav-label {
            font-size: 0.7rem;
        }

        .welcome-greeting {
            font-size: 1.25rem;
        }

        .fab-absensi {
            bottom: 85px;
            right: 0.75rem;
        }
    }
</style>

<div class="main-content">
    <!-- Welcome Section -->
    <div class="welcome-section">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                <div class="welcome-greeting"><?= selamat(date("H"));?>, <?= $user['name'];?>! 👋</div>
                <div class="welcome-subtitle"><?= $user['name_jabatan'];?> • <?= $user['akronim'];?></div>
            </div>
            <div class="welcome-avatar">
                <i class="fas fa-user"></i>
            </div>
        </div>
    </div>

    <!-- Attendance Status -->
    <div class="attendance-cards">
        <div class="attendance-card check-in">
            <div class="attendance-image">
                <?php if(empty($absensi['image_in'])):?>
                    <i class="fas fa-clock"></i>
                <?php endif;?>
            </div>
            <div class="attendance-label">Masuk</div>
            <div class="attendance-time">
                <?= empty($absensi['image_in']) ? '--:--' : $absensi['hour_in'] ;?>
            </div>
            <div class="attendance-status">
                <?= empty($absensi['image_in']) ? 'Belum Absen' : '✓ Sudah' ;?>
            </div>
        </div>

        <div class="attendance-card check-out">
            <div class="attendance-image">
                <?php if(empty($absensi['image_out'])):?>
                    <i class="fas fa-clock"></i>
                <?php endif;?>
            </div>
            <div class="attendance-label">Pulang</div>
            <div class="attendance-time">
                <?= empty($absensi['image_out']) ? '--:--' : $absensi['hour_out'] ;?>
            </div>
            <div class="attendance-status">
                <?= empty($absensi['image_out']) ? 'Belum Absen' : '✓ Sudah' ;?>
            </div>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="summary-title">Rekap Absensi Bulan <?= tanggalindo(date('Y-m-'));?></div>
    <div class="summary-cards">
        <div class="summary-card hadir">
            <div class="summary-icon"><i class="fas fa-check"></i></div>
            <div class="summary-label">Hadir</div>
            <div class="summary-value"><?= $hadir;?></div>
        </div>
        <div class="summary-card izin">
            <div class="summary-icon"><i class="fas fa-calendar"></i></div>
            <div class="summary-label">Izin</div>
            <div class="summary-value"><?= $izin;?></div>
        </div>
        <div class="summary-card terlambat">
            <div class="summary-icon"><i class="fas fa-clock"></i></div>
            <div class="summary-label">Terlambat</div>
            <div class="summary-value"><?= $terlambat;?></div>
        </div>
        <form method="get" action="<?= base_url('logout');?>" style="width: 100%;">
            <button type="submit" style="background: none; border: none; padding: 0; cursor: pointer; width: 100%;">
                <div class="summary-card logout">
                    <div class="summary-icon"><i class="fas fa-power-off"></i></div>
                    <div class="summary-label">LOG OUT</div>
                    <div class="summary-value">Keluar</div>
                </div>
            </button>
        </form>
    </div>

    <!-- Attendance History -->
    <div class="attendance-history">
        <div class="history-title">📋 Riwayat Absensi Bulan Ini</div>
        <?php foreach($absensi_bulan as $row):?>
        <div class="history-item">
            <div class="history-date">
                <i class="fas fa-calendar-alt mr-2" style="color: var(--primary);"></i>
                <?= tanggalindo($row['date']);?>
            </div>
            <div class="history-times">
                <span class="time-badge in">
                    Masuk: <?= empty($row['image_in']) ? '-' : $row['hour_in'] ;?>
                </span>
                <span class="time-badge out">
                    Pulang: <?= empty($row['image_out']) ? '-' : $row['hour_out'] ;?>
                </span>
            </div>
        </div>
        <?php endforeach;?>
    </div>
</div>

<!-- Bottom Navigation dengan Absensi Terintegrasi -->
<div class="bottom-nav">
    <div class="nav-container">
        <a href="<?= base_url('home');?>" class="nav-item active">
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
        <a href="<?= base_url('riwayat');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-history"></i></div>
            <div class="nav-label">Riwayat</div>
        </a>
    </div>
</div>

<!-- Modal Workorder Baru -->
<div class="modal fade" id="workorderModal" tabindex="-1" role="dialog" aria-labelledby="workorderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="workorderModalLabel">📋 Work Order Baru</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?php if (!empty($new_workorder)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-bell"></i> Anda menerima tugas baru dari operator!
                    </div>
                    <div class="workorder-details">
                        <div class="detail-row">
                            <label class="detail-label">📅 Tanggal:</label>
                            <span class="detail-value"><?= date('d F Y', strtotime($new_workorder['tanggal'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <label class="detail-label">📝 Keterangan:</label>
                            <p class="detail-value" style="white-space: pre-wrap; margin-top: 8px;"><?= nl2br($new_workorder['keterangan']); ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-secondary">
                        Tidak ada work order baru untuk Anda saat ini.
                    </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <a href="<?= base_url('workorder'); ?>" class="btn btn-primary">Lihat Semua Tugas</a>
            </div>
        </div>
    </div>
</div>

<style>
    .workorder-details {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border-left: 4px solid #007bff;
    }
    
    .detail-row {
        margin-bottom: 15px;
    }
    
    .detail-label {
        font-weight: 600;
        color: #333;
        display: block;
        margin-bottom: 5px;
    }
    
    .detail-value {
        color: #666;
        font-size: 0.95rem;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if (!empty($new_workorder)): ?>
            // Tampilkan modal jika ada workorder baru
            var workorderModal = new bootstrap.Modal(document.getElementById('workorderModal'), {
                backdrop: 'static',
                keyboard: false
            });
            workorderModal.show();
        <?php endif; ?>
    });
</script>

<?= $this->endSection('content') ?>