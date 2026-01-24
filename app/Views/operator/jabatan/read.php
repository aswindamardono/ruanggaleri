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
</script>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title;?></h1>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <div class="font-weight-bold">Data <?= $title;?></div>
                    <div class="ml-auto">
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
                                    <th>Singkatan</th>
                                    <th>Nama Jabatan</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $no = 1;?>
                                <?php foreach ($jabatan as $row):?>
                                <tr>
                                    <td><?= $no++;?></td>
                                    <td><?= $row['akronim'];?></td>
                                    <td><?= $row['name_jabatan'];?></td>
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
                                                    data-confirm="Hapus Data|Apakah anda yakin ingin menghapus data <?= $row['name_jabatan'];?> ini ?"
                                                    data-confirm-yes="window.location.href='<?= base_url("operator/jabatan/delete/").$row["id"];?>'">
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
                <form method="post" action="" autocomplete="off">
                    <?= csrf_field();?>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="singkatan" class="form-label">Singkatan</label>
                            <input type="text"
                                class="form-control <?= !empty($rusak['singkatan']) ? 'is-invalid' : ''; ?>"
                                id="singkatan" name="singkatan" autofocus value="<?= old('singkatan'); ?>">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['singkatan']) ? validation_show_error('singkatan') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="jabatan" class="form-label">Nama Jabatan</label>
                            <input type="text"
                                class="form-control  <?= !empty($rusak['jabatan']) ? 'is-invalid' : ''; ?>" id="jabatan"
                                name="jabatan" autofocus value="<?= old('jabatan'); ?>">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['jabatan']) ? validation_show_error('jabatan') : ''; ?>
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
    <?php foreach($jabatan as $row):?>
    <div class="modal fade" tabindex="-1" role="dialog" id="edit<?= $row["id"];?>">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Data</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="post" action="<?= base_url("operator/jabatan/update/").$row["id"];?>" autocomplete="off">
                    <div class="modal-body">
                        <?= csrf_field();?>
                        <div class="form-group">
                            <label for="singkatan1" class="form-label">Singkatan</label>
                            <input type="text"
                                class="form-control <?= !empty($rusak['singkatan1']) ? 'is-invalid' : ''; ?>"
                                id="singkatan1" name="singkatan1" autofocus
                                value="<?= old("singkatan1", $row['akronim']); ?>">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['singkatan1']) ? validation_show_error('singkatan1') : ''; ?>
                            </small>
                        </div>
                        <div class="form-group">
                            <label for="jabatan1" class="form-label">Nama Jabatan</label>
                            <input type="text"
                                class="form-control <?= !empty($rusak['jabatan1']) ? 'is-invalid' : ''; ?>"
                                id="jabatan1" name="jabatan1" autofocus
                                value="<?= old("jabatan1", $row['name_jabatan']); ?>">
                            <small class="invalid-feedback">
                                <?= !empty($rusak['jabatan1']) ? validation_show_error('jabatan1') : ''; ?>
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