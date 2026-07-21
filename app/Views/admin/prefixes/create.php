<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        
        <!-- Messages -->
        <?php if (session()->has('error')): ?>
            <div class="alert-custom alert-danger d-flex align-items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                <?= session('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('errors')): ?>
            <?php foreach (session('errors') as $error): ?>
                <div class="alert-custom alert-danger d-flex align-items-center gap-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= $error ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <!-- Formulaire -->
        <div class="card-dashboard">
            <div class="card-header" style="background: linear-gradient(135deg, #4F46E5, #3730A3); color: white; border-radius: 12px 12px 0 0;">
                <h5 style="color: white;">
                    <i class="fas fa-plus-circle"></i> Nouveau préfixe
                </h5>
                <span style="font-size: 12px; opacity: 0.8;">
                    <i class="far fa-clock"></i> <?= date('d/m/Y') ?>
                </span>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/prefixes/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label for="prefix" class="form-label">
                            <i class="fas fa-hashtag" style="color: var(--primary-color);"></i> Préfixe *
                        </label>
                        <input type="text" 
                               class="form-control-custom <?= session('errors.prefix') ? 'is-invalid' : '' ?>" 
                               id="prefix" 
                               name="prefix" 
                               value="<?= old('prefix') ?>"
                               placeholder="Ex: 033"
                               maxlength="3"
                               pattern="[0-9]{3}"
                               required>
                        <div class="form-text">
                            <i class="fas fa-info-circle"></i> 3 chiffres uniquement (ex: 033, 034, 037)
                        </div>
                        <?php if (session('errors.prefix')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.prefix') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <label for="operator_name" class="form-label">
                            <i class="fas fa-building" style="color: var(--primary-color);"></i> Nom de l'opérateur *
                        </label>
                        <input type="text" 
                               class="form-control-custom <?= session('errors.operator_name') ? 'is-invalid' : '' ?>" 
                               id="operator_name" 
                               name="operator_name" 
                               value="<?= old('operator_name') ?>"
                               placeholder="Ex: Orange Money"
                               required>
                        <?php if (session('errors.operator_name')): ?>
                            <div class="invalid-feedback">
                                <?= session('errors.operator_name') ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-3">
                            <label class="switch">
                                <input type="checkbox" 
                                       id="is_active" 
                                       name="is_active"
                                       value="1"
                                       <?= old('is_active') ? 'checked' : 'checked' ?>>
                                <span class="slider"></span>
                            </label>
                            <div>
                                <span class="fw-semibold">
                                    <i class="fas fa-check-circle" style="color: #10B981;"></i> Actif
                                </span>
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i> Les préfixes inactifs ne seront pas acceptés
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end border-top pt-4">
                        <a href="<?= base_url('admin/prefixes') ?>" class="btn-secondary-custom">
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