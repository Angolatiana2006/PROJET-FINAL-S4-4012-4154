<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Statistiques -->
<div class="stats-grid mb-4">
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <p class="stat-label">
                    <i class="fas fa-phone"></i> Total opérateurs
                </p>
                <h3 class="stat-number"><?= count($operators) ?></h3>
            </div>
            <div class="stat-icon">
                <i class="fas fa-phone"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <p class="stat-label">
                    <i class="fas fa-check-circle"></i> Actifs
                </p>
                <h3 class="stat-number" style="color: #10B981;">
                    <?php 
                        $active = array_filter($operators, function($op) { return $op['is_active']; });
                        echo count($active);
                    ?>
                </h3>
            </div>
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <p class="stat-label">
                    <i class="fas fa-percent"></i> Comm. moyenne
                </p>
                <h3 class="stat-number" style="color: #F59E0B;">
                    <?php 
                        $avgFee = array_sum(array_column($operators, 'external_fee_percent')) / (count($operators) ?: 1);
                        echo number_format($avgFee, 2) . '%';
                    ?>
                </h3>
            </div>
            <div class="stat-icon">
                <i class="fas fa-percent"></i>
            </div>
        </div>
    </div>
</div>

<!-- Messages -->
<?php if (session()->has('success')): ?>
    <div class="alert-custom alert-success d-flex align-items-center gap-2">
        <i class="fas fa-check-circle"></i> <?= session('success') ?>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <div class="alert-custom alert-danger d-flex align-items-center gap-2">
        <i class="fas fa-exclamation-circle"></i> <?= session('error') ?>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Tableau -->
<div class="card-dashboard">
    <div class="card-header">
        <h5>
            <i class="fas fa-list"></i> Liste des opérateurs externes
        </h5>
        <a href="<?= base_url('admin/external-operators/create') ?>" class="btn-primary-custom btn-sm">
            <i class="fas fa-plus-circle"></i> Ajouter
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($operators)): ?>
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox" style="font-size: 32px;"></i>
                <p>Aucun opérateur externe configuré</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-admin">
                    <thead>
                        <tr>
                            <th>Préfixe</th>
                            <th>Opérateur</th>
                            <th>Commission</th>
                            <th>Min/Max</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($operators as $op): ?>
                            <tr>
                                <td>
                                    <span class="badge-prefix"><?= $op['prefix'] ?></span>
                                </td>
                                <td>
                                    <strong><?= esc($op['operator_name']) ?></strong>
                                </td>
                                <td>
                                    <span class="badge" style="background: #FEF3C7; color: #92400E; font-weight: 600; padding: 4px 12px; border-radius: 20px;">
                                        <?= number_format($op['external_fee_percent'], 2) ?>%
                                    </span>
                                </td>
                                <td>
                                    <?php if ($op['external_min_fee'] > 0 || $op['external_max_fee'] > 0): ?>
                                        <small>
                                            <?php if ($op['external_min_fee'] > 0): ?>
                                                Min: <?= number_format($op['external_min_fee'], 0) ?> Ar
                                            <?php endif; ?>
                                            <?php if ($op['external_max_fee'] > 0): ?>
                                                Max: <?= number_format($op['external_max_fee'], 0) ?> Ar
                                            <?php endif; ?>
                                        </small>
                                    <?php else: ?>
                                        <small class="text-muted">Aucune limite</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($op['is_active']): ?>
                                        <span class="badge-status completed">
                                            <i class="fas fa-circle-check"></i> Actif
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-status failed">
                                            <i class="fas fa-circle-xmark"></i> Inactif
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="<?= base_url("admin/external-operators/edit/{$op['id']}") ?>" 
                                           class="btn-action-icon btn-edit"><i class="fas fa-pen"></i></a>
                                        <button onclick="toggleOperator(<?= $op['id'] ?>)" 
                                                class="btn-action-icon btn-toggle"><i class="fas fa-pause"></i></button>
                                        <button onclick="deleteOperator(<?= $op['id'] ?>)" 
                                                class="btn-action-icon btn-delete"><i class="fas fa-trash-can"></i></button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>



<script>
function toggleOperator(id) {
    if (confirm('Changer le statut de cet opérateur ?')) {
        fetch(`<?= base_url('admin/external-operators/toggle') ?>/${id}`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') location.reload();
            else alert('Erreur: ' + data.message);
        });
    }
}

function deleteOperator(id) {
    if (confirm('Supprimer cet opérateur externe ?')) {
        fetch(`<?= base_url('admin/external-operators/delete') ?>/${id}`, {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') location.reload();
            else alert('Erreur: ' + data.message);
        });
    }
}
</script>

<?= $this->endSection() ?>