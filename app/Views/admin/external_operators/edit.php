<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        
        <!-- Messages d'erreur -->
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
            <div class="admin-card-header" style="background: linear-gradient(135deg, #FF8A65, #E64A19); color: white; border-radius: 16px 16px 0 0;">
                <h5 style="color: white;">
                    <i class="fas fa-pen"></i> Modifier : <?= esc($operator['operator_name']) ?>
                </h5>
                <span style="font-size: 13px; opacity: 0.8;">
                    <i class="fas fa-hashtag"></i> ID: #<?= $operator['id'] ?>
                </span>
            </div>
            <div class="admin-card-body">
                <form action="<?= base_url("admin/external-operators/update/{$operator['id']}") ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-hashtag text-primary"></i> Préfixe *
                        </label>
                        <input type="text" 
                               class="form-control form-control-custom <?= session('errors.prefix') ? 'is-invalid' : '' ?>" 
                               name="prefix" 
                               value="<?= old('prefix', $operator['prefix']) ?>" 
                               maxlength="3" 
                               placeholder="Ex: 032"
                               required>
                        <div class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> 3 chiffres uniquement
                        </div>
                        <?php if (session('errors.prefix')): ?>
                            <div class="invalid-feedback"><?= session('errors.prefix') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-building text-primary"></i> Nom de l'opérateur *
                        </label>
                        <input type="text" 
                               class="form-control form-control-custom <?= session('errors.operator_name') ? 'is-invalid' : '' ?>" 
                               name="operator_name" 
                               value="<?= old('operator_name', esc($operator['operator_name'])) ?>" 
                               placeholder="Ex: Airtel Money"
                               required>
                        <?php if (session('errors.operator_name')): ?>
                            <div class="invalid-feedback"><?= session('errors.operator_name') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-percent text-primary"></i> Commission (%) *
                        </label>
                        <input type="number" 
                               class="form-control form-control-custom <?= session('errors.external_fee_percent') ? 'is-invalid' : '' ?>" 
                               name="external_fee_percent" 
                               value="<?= old('external_fee_percent', $operator['external_fee_percent']) ?>" 
                               step="0.01" 
                               min="0" 
                               max="100" 
                               placeholder="Ex: 2.50"
                               required>
                        <div class="form-text text-muted">
                            <i class="fas fa-info-circle"></i> Pourcentage prélevé sur chaque transfert
                        </div>
                        <?php if (session('errors.external_fee_percent')): ?>
                            <div class="invalid-feedback"><?= session('errors.external_fee_percent') ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-arrow-down text-primary"></i> Commission min (Ar)
                                </label>
                                <input type="number" 
                                       class="form-control form-control-custom" 
                                       name="external_min_fee" 
                                       value="<?= old('external_min_fee', $operator['external_min_fee'] ?? 0) ?>" 
                                       step="0.01" 
                                       min="0"
                                       placeholder="Ex: 50">
                                <div class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Optionnel
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-arrow-up text-primary"></i> Commission max (Ar)
                                </label>
                                <input type="number" 
                                       class="form-control form-control-custom" 
                                       name="external_max_fee" 
                                       value="<?= old('external_max_fee', $operator['external_max_fee'] ?? 0) ?>" 
                                       step="0.01" 
                                       min="0"
                                       placeholder="Ex: 500">
                                <div class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Optionnel
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="d-flex align-items-center gap-3">
                            <label class="switch">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" <?= old('is_active', $operator['is_active']) ? 'checked' : '' ?>>
                                <span class="slider"></span>
                            </label>
                            <div>
                                <span class="fw-semibold">
                                    <i class="fas fa-check-circle text-success"></i> Actif
                                </span>
                                <div class="form-text text-muted">
                                    <i class="fas fa-info-circle"></i> Les opérateurs inactifs ne seront pas disponibles
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end border-top pt-4">
                        <a href="<?= base_url('admin/external-operators') ?>" class="btn btn-secondary-custom">
                            <i class="fas fa-xmark"></i> Annuler
                        </a>
                        <button type="submit" class="btn btn-primary-custom">
                            <i class="fas fa-save"></i> Mettre à jour
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
                            Transfert de 10 000 Ar vers <?= esc($operator['operator_name']) ?> 
                            (commission <?= number_format($operator['external_fee_percent'], 2) ?>%)
                        </p>
                        <p class="mb-0 small text-muted">
                            <i class="fas fa-calculator"></i> 
                            Commission = 10 000 × <?= number_format($operator['external_fee_percent'], 2) ?>% = 
                            <?= number_format(10000 * $operator['external_fee_percent'] / 100, 0) ?> Ar 
                            (en plus des frais de base)
                        </p>
                        <?php if (!empty($operator['external_min_fee']) || !empty($operator['external_max_fee'])): ?>
                            <p class="mb-0 small text-muted">
                                <i class="fas fa-info-circle"></i>
                                Avec limites : 
                                <?php if (!empty($operator['external_min_fee'])): ?>
                                    min <?= number_format($operator['external_min_fee'], 0) ?> Ar
                                <?php endif; ?>
                                <?php if (!empty($operator['external_max_fee'])): ?>
                                    max <?= number_format($operator['external_max_fee'], 0) ?> Ar
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>