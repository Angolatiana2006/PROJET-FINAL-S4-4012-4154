<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Stats 
<div class="row g-3 mb-4">
    <?php foreach ($stats as $type => $stat): ?>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label">
                            <?php if ($type === 'deposit'): ?>
                                <i class="fas fa-arrow-down"></i>
                            <?php elseif ($type === 'withdrawal'): ?>
                                <i class="fas fa-arrow-up"></i>
                            <?php else: ?>
                                <i class="fas fa-arrows-left-right"></i>
                            <?php endif; ?>
                            <?= ucfirst($type) ?>
                        </p>
                        <h3 class="stat-number"><?= $stat['total'] ?></h3>
                        <small class="text-muted">
                            Frais: <?= number_format($stat['frais_min'], 0) ?> - <?= number_format($stat['frais_max'], 0) ?> Ar
                        </small>
                    </div>
                    <div class="stat-icon text-primary">
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
        </div>
    <?php endforeach; ?>
</div>
                        -->

<!-- Messages -->
<?php if (session()->has('success')): ?>
    <div class="alert alert-custom alert-success d-flex align-items-center gap-2">
        <i class="fas fa-check-circle"></i> <?= session('success') ?>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <div class="alert alert-custom alert-danger d-flex align-items-center gap-2">
        <i class="fas fa-exclamation-circle"></i> <?= session('error') ?>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->has('errors')): ?>
    <?php foreach (session('errors') as $error): ?>
        <div class="alert alert-custom alert-danger d-flex align-items-center gap-2">
            <i class="fas fa-exclamation-circle"></i> <?= $error ?>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Tableaux par type -->
<?php foreach ($types as $typeKey => $typeLabel): ?>
    <div class="admin-card mb-4">
        <div class="admin-card-header">
            <h5>
                <?php if ($typeKey === 'deposit'): ?>
                    <i class="fas fa-circle-down text-success"></i>
                <?php elseif ($typeKey === 'withdrawal'): ?>
                    <i class="fas fa-circle-up text-danger"></i>
                <?php else: ?>
                    <i class="fas fa-right-left text-primary"></i>
                <?php endif; ?>
                <?= $typeLabel ?>s
                <span class="badge bg-light text-dark ms-2">
                    <?= count($configs[$typeKey] ?? []) ?> tranches
                </span>
            </h5>
            <a href="<?= base_url("admin/fees/create?type={$typeKey}") ?>" class="btn btn-primary-custom btn-sm">
                <i class="fas fa-plus-circle"></i> Ajouter
            </a>
        </div>
        <div class="admin-card-body">
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
                                        <span class="badge-prefix" style="background: <?= $config['fee_amount'] == 0 ? '#28a745' : '#6C63FF' ?>;">
                                            <?= number_format($config['fee_amount'], 0) ?> Ar
                                        </span>
                                    </td>
                                 
                                    <td>
                                        <div class="d-flex justify-content-center gap-1">
                                            <a href="<?= base_url("admin/fees/edit/{$config['id']}") ?>" 
                                               class="btn-action btn-edit" title="Modifier">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                        
                                            <button onclick="deleteFee(<?= $config['id'] ?>)" 
                                                    class="btn-action btn-delete" title="Supprimer">
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