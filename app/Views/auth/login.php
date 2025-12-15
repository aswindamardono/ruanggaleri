<?= $this->extend('template/layout_auth') ?>
<?= $this->section('content') ?>
<?php $rusak = validation_errors();?>

<style>
    :root {
        --primary-color: #4f46e5;
        --primary-dark: #4338ca;
        --secondary-color: #06b6d4;
    }

    body {
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        overflow-x: hidden;
    }

    .login-container {
        animation: slideInUp 0.8s ease-out;
        width: 100%;
        padding: 2rem 1rem;
        max-width: 500px;
        margin: 0 auto;
    }

    @media (max-width: 991px) {
        .login-container {
            max-width: 100%;
        }
    }

    @media (max-width: 576px) {
        .login-container {
            padding: 1.5rem 1rem;
            max-width: 100%;
        }
    }

    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .login-brand {
        animation: fadeIn 0.6s ease-out 0.2s backwards;
        text-align: center;
        margin-bottom: 2rem;
    }

    .login-brand img {
        width: 100px;
        height: 100px;
        object-fit: contain;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
        border-radius: 20px;
        transition: transform 0.3s ease;
    }

    .login-brand img:hover {
        transform: scale(1.05);
    }

    .login-title {
        animation: fadeIn 0.6s ease-out 0.3s backwards;
        color: white;
        font-size: 1.5rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 2rem;
    }

    .login-subtitle {
        animation: fadeIn 0.6s ease-out 0.35s backwards;
        color: rgba(255, 255, 255, 0.8);
        font-size: 0.95rem;
        margin-bottom: 2rem;
    }

    .login-card {
        animation: fadeIn 0.6s ease-out 0.4s backwards;
        background: white;
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        overflow: hidden;
    }

    .login-card .card-body {
        padding: 3rem 2.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        font-weight: 600;
        color: #333;
        margin-bottom: 0.8rem;
        display: block;
        font-size: 0.95rem;
    }

    .form-control {
        padding: 0.85rem 1.2rem;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        font-size: 0.95rem;
        transition: all 0.3s ease;
        background-color: #f9fafb;
    }

    .form-control:focus {
        background-color: white;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        outline: none;
    }

    .form-control.is-invalid {
        border-color: #dc2626;
        background-color: #fef2f2;
    }

    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
    }

    .invalid-feedback {
        color: #dc2626;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: block;
    }

    .d-block {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.8rem;
    }

    .d-block label {
        margin-bottom: 0;
    }

    .float-right a {
        color: var(--primary-color);
        text-decoration: none;
        font-size: 0.9rem;
        font-weight: 500;
        transition: color 0.3s ease;
    }

    .float-right a:hover {
        color: var(--secondary-color);
    }

    .btn-login {
        padding: 0.9rem 2rem;
        font-weight: 600;
        border-radius: 10px;
        border: none;
        background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
        color: white;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        letter-spacing: 0.5px;
        margin-top: 0.5rem;
    }

    .btn-login::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s ease, height 0.6s ease;
    }

    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px rgba(79, 70, 229, 0.3);
    }

    .btn-login:active {
        transform: translateY(0);
    }

    .btn-login:active::before {
        width: 300px;
        height: 300px;
    }

    .simple-footer {
        animation: fadeIn 0.6s ease-out 0.5s backwards;
        text-align: center;
        color: rgba(255, 255, 255, 0.7);
        margin-top: 2rem;
        font-size: 0.9rem;
    }

    @media (max-width: 576px) {
        .login-card .card-body {
            padding: 2rem 1.5rem;
        }

        .login-title {
            font-size: 1.25rem;
        }

        .login-brand img {
            width: 80px;
            height: 80px;
        }

        .login-container {
            padding: 1.5rem 1rem;
            max-width: 100%;
        }
    }
</style>

<div class="container login-container">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="login-brand">
                <img src="<?= base_url();?>assets/img/<?= $setting['logo_kantor'];?>" alt="logo">
            </div>
            
            <div class="login-title">
                <?= $setting['name_kantor'];?>
            </div>
            
            <div class="login-subtitle">
                Sistem Manajemen Absensi & Gaji Karyawan lokal
            </div>

            <div class="card login-card">
                <div class="card-body">
                    <form action="<?= base_url('login')?>" method="post" autocomplete="off">
                        <?= csrf_field();?>

                        <div class="form-group">
                            <label for="nik">
                                <i class="fas fa-id-card mr-2" style="color: var(--primary-color);"></i>NIK
                            </label>
                            <input id="nik" type="text" placeholder="Masukkan NIK Anda"
                                class="form-control <?= !empty($rusak['nik']) ? 'is-invalid' : ''; ?>" name="nik">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['nik']) ? validation_show_error('nik') : ''; ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="d-block">
                                <label for="password" style="color: #333;">
                                    <i class="fas fa-lock mr-2" style="color: var(--primary-color);"></i>Password
                                </label>
                            </div>
                            <input id="password" type="password" placeholder="Masukkan Password Anda"
                                class="form-control <?= !empty($rusak['password']) ? 'is-invalid' : ''; ?>"
                                name="password">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['password']) ? validation_show_error('password') : ''; ?>
                            </small>
                             <div class="float-right">
                                    <a href="<?= base_url('forgotpassword');?>" class="text-small">
                                        Lupa Password?
                                    </a>
                                </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-login btn-lg btn-block">
                                <i class="fas fa-sign-in-alt mr-2"></i> Login
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="simple-footer">
                Copyright &copy; <?= date('Y');?> <?= $setting['name_aplikasi'];?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection('content') ?>
