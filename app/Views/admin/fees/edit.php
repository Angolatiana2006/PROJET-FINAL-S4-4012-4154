<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        
        <?php if (session()->has('error')): ?>
            <div class="alert-custom alert-danger d-flex align-items-center gap-2">
                <i class="fas fa-exclamation-circle"></i> <?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('errors')): ?>
            <?php foreach (session('errors') as $error): ?>
                <div class="alert-custom alert-danger d-flex align-items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="card-dashboard">
            <div class="card-header" style="background: linear-gradient(135deg, #F59E0B, #D97706); color: white; border-radius: 12px 12px 0 0;">
                <h5 style="color: white;">
                    <i class="fas fa-pen"></i> Modifier le barème
                </h5>
                <span style="font-size: 12px; opacity: 0.8;">
                    <i class="fas fa-hashtag"></i> ID: #<?= $config['id'] ?>
                </span>
            </div>
            <div class="card-body">
                <form action="<?= base_url("admin/fees/update/{$config['id']}") ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-tag" style="color: var(--primary-color);"></i> Type d'opération *
                        </label>
                        <select name="operation_type" class="form-control-custom" required>
                            <?php foreach ($types as $key => $label): ?>
                                <option value="<?= $key ?>" <?= old('operation_type', $config['operation_type']) == $key ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="fas fa-arrow-down" style="color: var(--primary-color);"></i> Montant minimum (Ar) *
                            </label>
                            <input type="number" 
                                   class="form-control-custom <?= session('errors.min_amount') ? 'is-invalid' : '' ?>" 
                                   name="min_amount" 
                                   value="<?= old('min_amount', $config['min_amount']) ?>"
                                   step="0.01"
                                   required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">
                                <i class="fas fa-arrow-up" style="color: var(--primary-color);"></i> Montant maximum (Ar) *
                            </label>
                            <input type="number" 
                                   class="form-control-custom <?= session('errors.max_amount') ? 'is-invalid' : '' ?>" 
                                   name="max_amount" 
                                   value="<?= old('max_amount', $config['max_amount']) ?>"
                                   step="0.01"
                                   required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-coins" style="color: var(--primary-color);"></i> Montant des frais (Ar) *
                        </label>
                        <input type="number" 
                               class="form-control-custom <?= session('errors.fee_amount') ? 'is-invalid' : '' ?>" 
                               name="fee_amount" 
                               value="<?= old('fee_amount', $config['fee_amount']) ?>"
                               step="0.01"
                               required>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <label class="switch">
                                <input type="checkbox" name="is_active" value="1" 
                                    <?= old('is_active', $config['is_active']) ? 'checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                            <div>
                                <span class="fw-semibold">
                                    <i class="fas fa-check-circle" style="color: #10B981;"></i> Actif
                                </span>
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Les barèmes inactifs ne seront pas appliqués
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end border-top pt-4">
                        <a href="<?= base_url('admin/fees') ?>" class="btn-secondary-custom">
                            <i class="fas fa-xmark"></i> Annuler
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <i class="fas fa-save"></i> Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        
        
    </div>
</div>

<?= $this->endSection() ?>