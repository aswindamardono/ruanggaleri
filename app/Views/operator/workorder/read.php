<?= $this->extend('template/layout_admin') ?>
<?= $this->section('content') ?>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title;?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('operator/dashboard');?>">Dashboard</a></div>
                <div class="breadcrumb-item"><?= $title;?></div>
            </div>
        </div>

        <?php if (session()->getFlashdata('pesan')) : ?>
            <div class="alert alert-<?= session()->getFlashdata('alert') ?> alert-dismissible show fade" role="alert">
                <?= session()->getFlashdata('pesan') ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <a href="<?= base_url('operator/workorder/create');?>" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Work Order
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-md">
                            <thead>
                                <tr>
                                    <th width="5%">No</th>
                                    <th>Nama Pegawai</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th width="15%">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($workorder)):?>
                                    <?php $no = 1; ?>
                                    <?php foreach ($workorder as $row):?>
                                    <tr>
                                        <td><?= $no++; ?></td>
                                        <td>
                                            <?php foreach ($row['pegawai_list'] as $peg): ?>
                                                <span class="badge badge-info"><?= $peg['name']; ?></span><br>
                                            <?php endforeach; ?>
                                        </td>
                                        <td><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                                        <td><?= $row['keterangan']; ?></td>
                                        <td>
                                            <a href="<?= base_url('operator/workorder/edit/' . $row['id']);?>" class="btn btn-sm btn-warning">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                            <a href="<?= base_url('operator/workorder/delete/' . $row['id']);?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                                <i class="fas fa-trash"></i> Hapus
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="text-center">Data tidak ditemukan</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
