<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Stats -->
<div class="stats-grid mb-4">
    <?php foreach ($stats as $type => $stat): ?>
        <div class="stat-card">
            <div class="stat-top">
                <div>
                    <p class="stat-label">
                        <?php if ($type === 'deposit'): ?>
                            <i class="fas fa-arrow-down"></i>
                        <?php elseif ($type === 'withdrawal'): ?>
                            <i class="fas fa-arrow-up"></i>
                        <?php else: ?>
                            <i class="fas fa-right-left"></i>
                        <?php endif; ?>
                        <?= ucfirst($type) ?>
                    </p>
                    <h3 class="stat-number"><?= $stat['total'] ?></h3>
                    <span class="stat-period">
                        Frais: <?= number_format($stat['frais_min'], 0) ?> - <?= number_format($stat['frais_max'], 0) ?> Ar
                    </span>
                </div>
                <div class="stat-icon">
                    <?php if ($type === 'deposit'): ?>
                        <i class="fas fa-circle-down"></i>
                    <?php elseif ($type === 'withdrawal'): ?>
                        <i class="fas fa-circle-up"></i>
                    <?php else: ?>
                        <i class="fas fa-right-left"></i>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
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

<?php if (session()->has('errors')): ?>
    <?php foreach (session('errors') as $error): ?>
        <div class="alert-custom alert-danger d-flex align-items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Tableaux par type -->
<?php foreach ($types as $typeKey => $typeLabel): ?>
    <div class="card-dashboard mb-4">
        <div class="card-header">
            <h5>
                <?php if ($typeKey === 'deposit'): ?>
                    <i class="fas fa-circle-down" style="color: #10B981;"></i>
                <?php elseif ($typeKey === 'withdrawal'): ?>
                    <i class="fas fa-circle-up" style="color: #EF4444;"></i>
                <?php else: ?>
                    <i class="fas fa-right-left" style="color: #0ca950ff;"></i>
                <?php endif; ?>
                <?= $typeLabel ?>s
                <span class="badge" style="background: #E5E7EB; color: #1F2937; padding: 2px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; margin-left: 8px;">
                    <?= count($configs[$typeKey] ?? []) ?> tranches
                </span>
            </h5>
            <a href="<?= base_url("admin/fees/create?type={$typeKey}") ?>" class="btn-primary-custom btn-sm">
                <i class="fas fa-plus-circle"></i> Ajouter
            </a>
        </div>
        <div class="card-body">
            <?php if (empty($configs[$typeKey])): ?>
                <div class="text-center py-3 text-muted">
                    <i class="fas fa-inbox"></i> Aucune configuration pour ce type
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-admin">
                        <thead>
                            <tr>
                                <th>Montant min (Ar)</th>
                                <th>Montant max (Ar)</th>
                                <th>Frais (Ar)</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($configs[$typeKey] as $config): ?>
                                <tr>
                                    <td>
                                        <strong><?= number_format($config['min_amount'], 0) ?></strong>
                                    </td>
                                    <td>
                                        <strong><?= number_format($config['max_amount'], 0) ?></strong>
                                    </td>
                                    <td>
                                        <span class="badge-prefix" style="background: <?= $config['fee_amount'] == 0 ? '#10B981' : '#0ca950ff' ?>;">
                                            <?= number_format($config['fee_amount'], 0) ?> Ar
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="<?= base_url("admin/fees/edit/{$config['id']}") ?>" 
                                               class="btn-action-icon btn-edit" title="Modifier">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <button onclick="toggleFee(<?= $config['id'] ?>)" 
                                                    class="btn-action-icon btn-toggle" 
                                                    title="Activer/Désactiver">
                                                <i class="fas fa-pause"></i>
                                            </button>
                                            <button onclick="deleteFee(<?= $config['id'] ?>)" 
                                                    class="btn-action-icon btn-delete" title="Supprimer">
                                                <i class="fas fa-trash-can"></i>
                                            </button>
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
<?php endforeach; ?>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleFee(id) {
    if (confirm('Voulez-vous changer le statut de ce barème ?')) {
        fetch(`<?= base_url('admin/fees/toggle') ?>/${id}`, {
            method: 'POST',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => alert('Erreur lors de la requête'));
    }
}

function deleteFee(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce barème ?')) {
        fetch(`<?= base_url('admin/fees/delete') ?>/${id}`, {
            method: 'DELETE',
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => alert('Erreur lors de la requête'));
    }
}
</script>
<?= $this->endSection() ?>