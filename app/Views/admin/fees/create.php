<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        
        <?php if (session()->has('error')): ?>
            <div class="alert alert-custom alert-danger d-flex align-items-center gap-2">
                <i class="fas fa-exclamation-circle"></i> <?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('errors')): ?>
            <?php foreach (session('errors') as $error): ?>
                <div class="alert alert-custom alert-danger d-flex align-items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="admin-card">
            <div class="admin-card-header" style="background: linear-gradient(135deg, #6C63FF, #5A52D5); color: white; border-radius: 16px 16px 0 0;">
                <h5 style="color: white;">
                    <i class="fas fa-plus-circle"></i> Nouveau barème
                </h5>
                <span style="font-size: 13px; opacity: 0.8;">
                    <i class="far fa-clock"></i> <?= date('d/m/Y') ?>
                </span>
            </div>
            <div class="admin-card-body">
                <form action="<?= base_url('admin/fees/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-tag text-primary"></i> Type d'opération *
                        </label>
                        <select name="operation_type" class="form-control form-control-custom" required>
                            <option value="">-- Sélectionner --</option>
                            <?php foreach ($types as $key => $label): ?>
                                <option value="<?= $key ?>" <?= old('operation_type') == $key ? 'selected' : '' ?>>
                                    <?= $label ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-arrow-down text-primary"></i> Montant minimum (Ar) *
                            </label>
                            <input type="number" 
                                   class="form-control form-control-custom <?= session('errors.min_amount') ? 'is-invalid' : '' ?>" 
                                   name="min_amount" 
                                   value="<?= old('min_amount') ?>"
                                   placeholder="0"
                                   step="0.01"
                                   required>
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">
                                <i class="fas fa-arrow-up text-primary"></i> Montant maximum (Ar) *
                            </label>
                            <input type="number" 
                                   class="form-control form-control-custom <?= session('errors.max_amount') ? 'is-invalid' : '' ?>" 
                                   name="max_amount" 
                                   value="<?= old('max_amount') ?>"
                                   placeholder="1000"
                                   step="0.01"
                                   required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-coins text-primary"></i> Montant des frais (Ar) *
                        </label>
                        <input type="number" 
                               class="form-control form-control-custom <?= session('errors.fee_amount') ? 'is-invalid' : '' ?>" 
                               name="fee_amount" 
                               value="<?= old('fee_amount') ?>"
                               placeholder="50"
                               step="0.01"
                               required>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <label class="switch">
                                <input type="checkbox" name="is_active" value="1" checked>
                                <span class="slider"></span>
                            </label>
                            <div>
                                <span class="fw-semibold">
                                    <i class="fas fa-check-circle text-success"></i> Actif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end border-top pt-4">
                        <a href="<?= base_url('admin/fees') ?>" class="btn btn-secondary-custom">
                            <i class="fas fa-xmark"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Info rapide -->
        <div class="admin-card mt-3" style="background: #F8F9FA;">
            <div class="admin-card-body">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-lightbulb text-warning" style="font-size: 20px;"></i>
                    <div>
                        <p class="mb-0 small">
                            <strong>Exemple :</strong> 
                            Retrait de 5 000 Ar → Frais = 50 Ar
                        </p>
                        <p class="mb-0 small text-muted">
                            <i class="fas fa-info-circle"></i> Les tranches ne doivent pas se chevaucher
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</div>

<?= $this->endSection() ?>