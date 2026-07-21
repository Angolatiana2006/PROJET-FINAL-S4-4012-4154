<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Messages -->
<?php if (session()->has('success')): ?>
    <div class="alert-custom alert-success d-flex align-items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <?= session('success') ?>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <div class="alert-custom alert-danger d-flex align-items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        <?= session('error') ?>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->has('errors')): ?>
    <?php foreach (session('errors') as $error): ?>
        <div class="alert-custom alert-danger d-flex align-items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            <?= $error ?>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Table -->
<div class="card-dashboard">
    <div class="card-header">
        <h5>
            <i class="fas fa-tower-cell"></i> Liste des préfixes
        </h5>
        <a href="<?= base_url('admin/prefixes/create') ?>" class="btn-primary-custom btn-sm">
            <i class="fas fa-plus-circle"></i> Ajouter
        </a>
    </div>
    <div class="card-body">
        <?php if (empty($prefixes)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h6>Aucun préfixe configuré</h6>
                <p class="text-muted small">Ajoutez votre premier préfixe pour commencer</p>
                <a href="<?= base_url('admin/prefixes/create') ?>" class="btn-primary-custom">
                    <i class="fas fa-plus-circle"></i> Ajouter un préfixe
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-admin">
                    <thead>
                        <tr>
                            <th>Préfixe</th>
                            <th>Opérateur</th>
                            <th>Statut</th>
                            <th>Création</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prefixes as $prefix): ?>
                            <tr>
                                <td>
                                    <span class="badge-prefix">
                                        <?= $prefix['prefix'] ?>
                                    </span>
                                </td>
                                <td>
                                    <i class="fas fa-building text-muted me-1"></i>
                                    <?= esc($prefix['operator_name']) ?>
                                </td>
                                <td>
                                    <?php if ($prefix['is_active']): ?>
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
                                    <i class="far fa-calendar-alt text-muted me-1"></i>
                                    <?= date('d/m/Y', strtotime($prefix['created_at'])) ?>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <button onclick="togglePrefix(<?= $prefix['id'] ?>)" 
                                                class="btn-action-icon btn-toggle <?= !$prefix['is_active'] ? 'inactive' : '' ?>"
                                                title="<?= $prefix['is_active'] ? 'Désactiver' : 'Activer' ?>">
                                            <i class="fas fa-<?= $prefix['is_active'] ? 'pause' : 'play' ?>"></i>
                                        </button>
                                        <button onclick="deletePrefix(<?= $prefix['id'] ?>)" 
                                                class="btn-action-icon btn-delete" 
                                                title="Supprimer">
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Toggle statut
function togglePrefix(id) {
    if (confirm('Voulez-vous changer le statut de ce préfixe ?')) {
        fetch(`<?= base_url('admin/prefixes/toggle') ?>/${id}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            alert('Erreur lors de la requête');
        });
    }
}

// Suppression
function deletePrefix(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce préfixe ?')) {
        fetch(`<?= base_url('admin/prefixes/delete') ?>/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert('Erreur: ' + data.message);
            }
        })
        .catch(error => {
            alert('Erreur lors de la requête');
        });
    }
}
</script>
<?= $this->endSection() ?>