<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Statistiques -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <p class="stat-label"><i class="fas fa-phone"></i> Total opérateurs</p>
            <h3 class="stat-number"><?= count($operators) ?></h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <p class="stat-label"><i class="fas fa-check-circle"></i> Actifs</p>
            <h3 class="stat-number" style="color: #2E7D32;">
                <?php 
                    $active = array_filter($operators, function($op) { return $op['is_active']; });
                    echo count($active);
                ?>
            </h3>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <p class="stat-label"><i class="fas fa-percent"></i> Comm. moyenne</p>
            <h3 class="stat-number" style="color: #E65100;">
                <?php 
                    $avgFee = array_sum(array_column($operators, 'external_fee_percent')) / (count($operators) ?: 1);
                    echo number_format($avgFee, 2) . '%';
                ?>
            </h3>
        </div>
    </div>
</div>

<!-- Messages -->
<?php if (session()->has('success')): ?>
    <div class="alert alert-custom alert-success"><?= session('success') ?></div>
<?php endif; ?>

<!-- Tableau -->
<div class="admin-card">
    <div class="admin-card-header">
        <h5><i class="fas fa-list"></i> Liste des opérateurs externes</h5>
        <a href="<?= base_url('admin/external-operators/create') ?>" class="btn btn-primary-custom btn-sm">
            <i class="fas fa-plus-circle"></i> Ajouter
        </a>
    </div>
    <div class="admin-card-body">
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
                                <td><span class="badge-prefix"><?= $op['prefix'] ?></span></td>
                                <td><strong><?= esc($op['operator_name']) ?></strong></td>
                                <td>
                                    <span class="badge" style="background: #FFF3E0; color: #E65100; font-weight: 600;">
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
                                        <span class="badge-status active">Actif</span>
                                    <?php else: ?>
                                        <span class="badge-status inactive">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                        <a href="<?= base_url("admin/external-operators/edit/{$op['id']}") ?>" 
                                           class="btn-action btn-edit"><i class="fas fa-pen"></i></a>
                                        <button onclick="toggleOperator(<?= $op['id'] ?>)" 
                                                class="btn-action btn-toggle"><i class="fas fa-pause"></i></button>
                                        <button onclick="deleteOperator(<?= $op['id'] ?>)" 
                                                class="btn-action btn-delete"><i class="fas fa-trash-can"></i></button>
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
    if (confirm('Changer le statut ?')) {
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
    if (confirm('Supprimer cet opérateur ?')) {
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