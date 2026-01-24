<?= $this->extend('template/layout_admin') ?>
<?= $this->section('content') ?>
<?php $rusak = validation_errors();?>
<style>
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
                    <form action="<?= base_url('operator/workorder/save');?>" method="POST">
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
                                   id="tanggal" name="tanggal" value="<?= old('tanggal'); ?>" required>
                            <small class="invalid-feedback d-block">
                                <?= !empty($rusak['tanggal']) ? validation_show_error('tanggal') : ''; ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="keterangan">Keterangan <span class="text-danger">*</span></label>
                            <textarea class="form-control <?= !empty($rusak['keterangan']) ? 'is-invalid' : ''; ?>" 
                                      id="keterangan" name="keterangan" rows="4" required><?= old('keterangan'); ?></textarea>
                            <small class="invalid-feedback d-block">
                                <?= !empty($rusak['keterangan']) ? validation_show_error('keterangan') : ''; ?>
                            </small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
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
<?= $this->endSection() ?>
