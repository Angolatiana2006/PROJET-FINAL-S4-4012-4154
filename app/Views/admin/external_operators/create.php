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
            <div class="card-header" style="background: linear-gradient(135deg, #4F46E5, #3730A3); color: white; border-radius: 12px 12px 0 0;">
                <h5 style="color: white;">
                    <i class="fas fa-plus-circle"></i> Nouvel opérateur externe
                </h5>
                <span style="font-size: 12px; opacity: 0.8;">
                    <i class="far fa-clock"></i> <?= date('d/m/Y') ?>
                </span>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/external-operators/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-hashtag" style="color: var(--primary-color);"></i> Préfixe *
                        </label>
                        <input type="text" 
                               class="form-control-custom <?= session('errors.prefix') ? 'is-invalid' : '' ?>" 
                               name="prefix" 
                               placeholder="Ex: 032" 
                               maxlength="3" 
                               required>
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i> 3 chiffres uniquement (ex: 032, 031, 034)
                        </div>
                        <?php if (session('errors.prefix')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.prefix') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-building" style="color: var(--primary-color);"></i> Nom de l'opérateur *
                        </label>
                        <input type="text" 
                               class="form-control-custom <?= session('errors.operator_name') ? 'is-invalid' : '' ?>" 
                               name="operator_name" 
                               placeholder="Ex: Airtel Money" 
                               required>
                        <?php if (session('errors.operator_name')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.operator_name') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-percent" style="color: var(--primary-color);"></i> Commission (%) *
                        </label>
                        <input type="number" 
                               class="form-control-custom <?= session('errors.external_fee_percent') ? 'is-invalid' : '' ?>" 
                               name="external_fee_percent" 
                               placeholder="Ex: 2.50" 
                               step="0.01" 
                               min="0" 
                               max="100" 
                               required>
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i> Pourcentage prélevé sur chaque transfert vers cet opérateur
                        </div>
                        <?php if (session('errors.external_fee_percent')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.external_fee_percent') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-arrow-down" style="color: var(--primary-color);"></i> Commission min (Ar)
                                </label>
                                <input type="number" 
                                       class="form-control-custom" 
                                       name="external_min_fee" 
                                       placeholder="Ex: 50" 
                                       step="0.01" 
                                       min="0">
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Optionnel
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">
                                    <i class="fas fa-arrow-up" style="color: var(--primary-color);"></i> Commission max (Ar)
                                </label>
                                <input type="number" 
                                       class="form-control-custom" 
                                       name="external_max_fee" 
                                       placeholder="Ex: 500" 
                                       step="0.01" 
                                       min="0">
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Optionnel
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <label class="switch">
                                <input type="checkbox" name="is_active" value="1" checked>
                                <span class="slider"></span>
                            </label>
                            <div>
                                <span class="fw-semibold">
                                    <i class="fas fa-check-circle" style="color: #10B981;"></i> Actif
                                </span>
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Les opérateurs inactifs ne seront pas disponibles
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end border-top pt-4">
                        <a href="<?= base_url('admin/external-operators') ?>" class="btn-secondary-custom">
                            <i class="fas fa-xmark"></i> Annuler
                        </a>
                        <button type="submit" class="btn-primary-custom">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
       
        
    </div>
</div>

<?= $this->endSection() ?>