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
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <div class="font-weight-bold">Biodata</div>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <img src="<?= base_url();?>assets/img/user/<?= $user['image'];?>"
                                        class="img-fluid mb-2" alt="" width="150px">
                                    <div class="font-weight-bold"><?= $user['name'];?></div>
                                    <small><?= $user['name_jabatan'];?> (<?= $user['akronim'];?>)</small>
                                    <div>
                                        <small><?= $user['email'];?> | <?= $user['phone'];?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <div class="font-weight-bold"><?= $title; ?></div>
                            </div>
                            <div class="card-body">
                                <form method="post" action="" autocomplete="off" enctype="multipart/form-data">
                                    <?= csrf_field();?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="image" class="form-label">Foto</label>
                                                <input type="file" name="image" id="image" class="dropify">
                                                <small class="text-danger">
                                                    <?= !empty($rusak['image']) ? validation_show_error('image') : ''; ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="ktp" class="form-label">KTP</label>
                                                <input type="file" name="ktp" id="ktp" class="dropify">
                                                <small class="text-danger">
                                                    <?= !empty($rusak['ktp']) ? validation_show_error('ktp') : ''; ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="name" class="form-label">Nama</label>
                                                <input type="text"
                                                    class="form-control <?= !empty($rusak['name']) ? 'is-invalid' : ''; ?>"
                                                    name="name" id="name" value="<?= old('name', $user['name']);?>">
                                                <small class="invalid-feedback">
                                                    <?= !empty($user['name']) ? validation_show_error('name') : ''; ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="nik" class="form-label">NIK [Nomor Induk Kependudukan] (harap sesuaikan NIK anda untuk keperluan login)</label>
                                                <input type="nik" class="form-control" name="nik" id="nik"
                                                    value="<?= $user['nik'];?>">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="email" class="form-label">Email (harap sesuaikan email anda untuk keperluan jika lupa password)</label>
                                                <input type="email" class="form-control" name="email" id="email"
                                                    value="<?= $user['email'];?>">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="phone" class="form-label">No Handphone</label>
                                                <input type="text"
                                                    class="form-control <?= !empty($rusak['phone']) ? 'is-invalid' : ''; ?>"
                                                    name="phone" id="phone" value="<?= old('phone',$user['phone']);?>" readonly>
                                                <small class="invalid-feedback">
                                                    <?= !empty($rusak['phone']) ? validation_show_error('phone') : ''; ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <textarea name="alamat" id="alamat"
                                                    class="form-control <?= !empty($rusak['alamat']) ? 'is-invalid' : ''; ?>"><?= old('alamat',$user['alamat']);?></textarea>
                                                <small class="invalid-feedback">
                                                    <?= !empty($rusak['alamat']) ? validation_show_error('alamat') : ''; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <div class="font-weight-bold">Ubah Password</div>
                            </div>
                            <div class="card-body">
                                <form action="<?= base_url('profil/changepassword/');?>" method="post"
                                    autocomplete="off">
                                    <?= csrf_field();?>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="old" class="form-label">Password Lama</label>
                                                <input type="password"
                                                    class="form-control <?= !empty($rusak['old']) ? 'is-invalid' : ''; ?>"
                                                    name="old" id="old">
                                                <small class="invalid-feedback">
                                                    <?= !empty($rusak['old']) ? validation_show_error('old') : ''; ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="password" class="form-label">Password Baru</label>
                                                <input type="password"
                                                    class="form-control <?= !empty($rusak['password']) ? 'is-invalid' : ''; ?>"
                                                    name="password" id="password">
                                                <small class="invalid-feedback">
                                                    <?= !empty($rusak['password']) ? validation_show_error('password') : ''; ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="confirm" class="form-label">Konfirmasi Password</label>
                                                <input type="password"
                                                    class="form-control <?= !empty($rusak['confirm']) ? 'is-invalid' : ''; ?>"
                                                    name="confirm" id="confirm">
                                                <small class="invalid-feedback">
                                                    <?= !empty($rusak['confirm']) ? validation_show_error('confirm') : ''; ?>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <button class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>
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
        <a href="<?= base_url('profil');?>" class="nav-item active">
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

<?= $this->endSection('content') ?>