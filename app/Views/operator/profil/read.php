h<?= $this->extend('template/layout_admin') ?>
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
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control" name="email" id="email"
                                                    value="<?= $user['email'];?>" readonly>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="phone" class="form-label">No Handphone</label>
                                                <input type="text"
                                                    class="form-control <?= !empty($rusak['phone']) ? 'is-invalid' : ''; ?>"
                                                    name="phone" id="phone" value="<?= old('phone', $user['phone']);?>">
                                                <small class="invalid-feedback">
                                                    <?= !empty($rusak['phone']) ? validation_show_error('phone') : ''; ?>
                                                </small>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="alamat" class="form-label">Alamat</label>
                                                <textarea name="alamat"
                                                    class="form-control <?= !empty($rusak['alamat']) ? 'is-invalid' : ''; ?>"><?= old('alamat', $user['alamat']);?></textarea>
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
                                <form action="<?= base_url('operator/profil/changepassword/');?>" method="post"
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
        <a href="<?= base_url('operator/workorder');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-fire"></i></div>
            <div class="nav-label">Work Order</div>
        </a>
        <a href="<?= base_url('operator/jadwal');?>" class="nav-item">
            <div class="nav-icon"><i class="fas fa-calendar"></i></div>
            <div class="nav-label">Jadwal</div>
        </a>
    </div>
</div>

<?= $this->endSection('content') ?>