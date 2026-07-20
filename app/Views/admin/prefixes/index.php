<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Stats 
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label">
                        <i class="fas fa-list-ul"></i> Total
                    </p>
                    <h3 class="stat-number"><?= $stats['total'] ?? 0 ?></h3>
                </div>
                <div class="stat-icon text-primary">
                    <i class="fas fa-tower-cell"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label">
                        <i class="fas fa-check-circle"></i> Actifs
                    </p>
                    <h3 class="stat-number" style="color: #2E7D32;"><?= $stats['active'] ?? 0 ?></h3>
                </div>
                <div class="stat-icon text-success">
                    <i class="fas fa-circle-check"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label">
                        <i class="fas fa-xmark-circle"></i> Inactifs
                    </p>
                    <h3 class="stat-number" style="color: #C62828;"><?= $stats['inactive'] ?? 0 ?></h3>
                </div>
                <div class="stat-icon text-danger">
                    <i class="fas fa-circle-xmark"></i>
                </div>
            </div>
        </div>
    </div>
</div>
-->

<!-- Messages -->
<?php if (session()->has('success')): ?>
    <div class="alert alert-custom alert-success d-flex align-items-center gap-2">
        <i class="fas fa-check-circle"></i>
        <?= session('success') ?>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->has('error')): ?>
    <div class="alert alert-custom alert-danger d-flex align-items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        <?= session('error') ?>
        <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if (session()->has('errors')): ?>
    <?php foreach (session('errors') as $error): ?>
        <div class="alert alert-custom alert-danger d-flex align-items-center gap-2">
            <i class="fas fa-exclamation-circle"></i>
            <?= $error ?>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- Table -->
<div class="admin-card">
    <div class="admin-card-header">
        <h5>
            Liste des prefixes
        </h5>
        <a href="<?= base_url('admin/prefixes/create') ?>" class="btn btn-primary-custom btn-sm">
            <i class="fas fa-plus-circle"></i> Ajouter
        </a>
    </div>
    <div class="admin-card-body">
        <?php if (empty($prefixes)): ?>
            <div class="empty-state">
                <div class="empty-icon">
                    <i class="fas fa-inbox"></i>
                </div>
                <h6>Aucun prefixe configure</h6>
                <p class="text-muted small">Ajoutez votre premier prefixe pour commencer</p>
                <a href="<?= base_url('admin/prefixes/create') ?>" class="btn btn-primary-custom">
                    <i class="fas fa-plus-circle"></i> Ajouter un prefixe
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-admin">
                    <thead>
                        <tr>
                            <th>Prefixe</th>
                            <th>Operateur</th>
                            <th>Statut</th>
                            <th>Creation</th>
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
                                        <span class="badge-status active">
                                            <i class="fas fa-circle-check"></i> Actif
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-status inactive">
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
                                    
                                        
                                        <!-- Supprimer -->
                                        <button onclick="deletePrefix(<?= $prefix['id'] ?>)" 
                                                class="btn-action btn-delete" 
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
    if (confirm('Voulez-vous changer le statut de ce prefixe ?')) {
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
            alert('Erreur lors de la requete');
        });
    }
}

// Suppression
function deletePrefix(id) {
    if (confirm('Etes-vous sur de vouloir supprimer ce prefixe ?')) {
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
            alert('Erreur lors de la requete');
        });
    }
}
</script>
<?= $this->endSection() ?>