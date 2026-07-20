<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="admin-card">
            <div class="admin-card-header" style="background: linear-gradient(135deg, #6C63FF, #5A52D5); color: white;">
                <h5 style="color: white;"><i class="fas fa-plus-circle"></i> Nouvel opérateur externe</h5>
            </div>
            <div class="admin-card-body">
                <form action="<?= base_url('admin/external-operators/store') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label>Préfixe *</label>
                        <input type="text" class="form-control" name="prefix" placeholder="Ex: 032" maxlength="3" required>
                    </div>

                    <div class="mb-3">
                        <label>Nom de l'opérateur *</label>
                        <input type="text" class="form-control" name="operator_name" placeholder="Ex: Airtel Money" required>
                    </div>

                    <div class="mb-3">
                        <label>Commission (%) *</label>
                        <input type="number" class="form-control" name="external_fee_percent" placeholder="Ex: 2.50" step="0.01" min="0" max="100" required>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Commission minimale (Ar)</label>
                                <input type="number" class="form-control" name="external_min_fee" placeholder="Ex: 50" step="0.01" min="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label>Commission maximale (Ar)</label>
                                <input type="number" class="form-control" name="external_max_fee" placeholder="Ex: 500" step="0.01" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                            <label class="form-check-label">Actif</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2 justify-content-end">
                        <a href="<?= base_url('admin/external-operators') ?>" class="btn btn-secondary">Annuler</a>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>