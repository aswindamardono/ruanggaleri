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

    .checkbox-dropdown {
        position: relative;
        display: inline-block;
        width: 100%;
    }
    
    .checkbox-dropdown-toggle {
        display: block;
        width: 100%;
        padding: 0.375rem 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        background-color: white;
        cursor: pointer;
        min-height: 38px;
        text-align: left;
        font-size: 1rem;
        line-height: 1.5;
    }
    
    .checkbox-dropdown-toggle.is-invalid {
        border-color: #dc3545;
    }
    
    .checkbox-dropdown-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        background: white;
        border: 1px solid #dee2e6;
        border-top: none;
        max-height: 200px;
        overflow-y: auto;
        display: none;
        z-index: 1000;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .checkbox-dropdown-menu.show {
        display: block;
    }
    
    .checkbox-dropdown-item {
        padding: 8px 12px;
        display: flex;
        align-items: center;
        cursor: pointer;
        border-bottom: 1px solid #f0f0f0;
    }
    
    .checkbox-dropdown-item:hover {
        background-color: #f8f9fa;
    }
    
    .checkbox-dropdown-item input[type="checkbox"] {
        margin-right: 8px;
        cursor: pointer;
    }
    
    .checkbox-dropdown-item label {
        margin-bottom: 0;
        cursor: pointer;
        flex: 1;
    }
    
    .selected-items {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
    }
    
    .selected-item {
        background-color: #007bff;
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 0.875rem;
    }
</style>
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title;?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('operator/dashboard');?>">Dashboard</a></div>
                <div class="breadcrumb-item active"><a href="<?= base_url('operator/workorder');?>">Work Order</a></div>
                <div class="breadcrumb-item"><?= $title;?></div>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4><?= $title;?></h4>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('operator/workorder/update/' . $workorder['id']);?>" method="POST">
                        <?= csrf_field();?>
                        
                        <div class="form-group">
                            <label>Nama Pegawai <span class="text-danger">*</span></label>
                            <div class="checkbox-dropdown">
                                <button type="button" class="checkbox-dropdown-toggle <?= !empty($rusak['user_id']) ? 'is-invalid' : ''; ?>" id="dropdownToggle">
                                    <div class="selected-items" id="selectedDisplay">
                                        <span style="color: #999;">Pilih pegawai...</span>
                                    </div>
                                </button>
                                <div class="checkbox-dropdown-menu" id="dropdownMenu">
                                    <?php if (!empty($pegawai)): ?>
                                        <?php foreach ($pegawai as $p):?>
                                            <div class="checkbox-dropdown-item">
                                                <input type="checkbox" id="pegawai_<?= $p['id']; ?>" class="pegawai-checkbox" value="<?= $p['id']; ?>" data-name="<?= $p['name']; ?>">
                                                <label for="pegawai_<?= $p['id']; ?>">
                                                    <?= $p['name']; ?>
                                                </label>
                                            </div>
                                        <?php endforeach;?>
                                    <?php endif;?>
                                </div>
                            </div>
                            <input type="hidden" name="user_id" id="selectedValues">
                            <small class="invalid-feedback d-block">
                                <?= !empty($rusak['user_id']) ? validation_show_error('user_id') : ''; ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="tanggal">Tanggal <span class="text-danger">*</span></label>
                            <input type="date" class="form-control <?= !empty($rusak['tanggal']) ? 'is-invalid' : ''; ?>" 
                                   id="tanggal" name="tanggal" value="<?= old('tanggal', $workorder['tanggal']); ?>" required>
                            <small class="invalid-feedback d-block">
                                <?= !empty($rusak['tanggal']) ? validation_show_error('tanggal') : ''; ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                            <textarea class="form-control <?= !empty($rusak['keterangan']) ? 'is-invalid' : ''; ?>" 
                                      id="keterangan" name="keterangan" rows="4" required><?= old('keterangan', $workorder['keterangan']); ?></textarea>
                            <small class="invalid-feedback d-block">
                                <?= !empty($rusak['keterangan']) ? validation_show_error('keterangan') : ''; ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="<?= base_url('operator/workorder');?>" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropdownToggle = document.getElementById('dropdownToggle');
    const dropdownMenu = document.getElementById('dropdownMenu');
    const checkboxes = document.querySelectorAll('.pegawai-checkbox');
    const selectedDisplay = document.getElementById('selectedDisplay');
    const selectedValues = document.getElementById('selectedValues');
    
    // Data pegawai yang sudah dipilih sebelumnya
    const selectedIds = <?= json_encode(array_map(function($p) { return (int)$p['user_id']; }, $pegawai_terpilih)); ?>;

    // Initialize - check boxes yang sebelumnya dipilih
    checkboxes.forEach(checkbox => {
        if (selectedIds.includes(parseInt(checkbox.value))) {
            checkbox.checked = true;
        }
    });

    // Toggle dropdown
    dropdownToggle.addEventListener('click', function(e) {
        e.preventDefault();
        dropdownMenu.classList.toggle('show');
    });

    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.checkbox-dropdown')) {
            dropdownMenu.classList.remove('show');
        }
    });

    // Update display and hidden input when checkbox changes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedDisplay);
    });

    // Initial display
    updateSelectedDisplay();

    function updateSelectedDisplay() {
        const selected = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => ({
                id: cb.value,
                name: cb.dataset.name
            }));

        // Update display
        if (selected.length === 0) {
            selectedDisplay.innerHTML = '<span style="color: #999;">Pilih pegawai...</span>';
        } else {
            selectedDisplay.innerHTML = selected
                .map(item => `<span class="selected-item">${item.name}</span>`)
                .join('');
        }

        // Update hidden input for form submission
        if (selected.length > 0) {
            selectedValues.value = JSON.stringify(selected.map(s => s.id));
        } else {
            selectedValues.value = '';
        }
    }
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
