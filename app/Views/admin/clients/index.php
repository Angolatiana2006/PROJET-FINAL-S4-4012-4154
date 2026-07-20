<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- Statistiques 
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label">
                        <i class="fas fa-users"></i> Total clients
                    </p>
                    <h3 class="stat-number"><?= $stats['total'] ?? 0 ?></h3>
                </div>
               
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label">
                        <i class="fas fa-check-circle"></i> Actifs
                    </p>
                    <h3 class="stat-number" style="color: #2E7D32;"><?= $stats['active'] ?? 0 ?></h3>
                </div>
             
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label">
                        <i class="fas fa-pause-circle"></i> Suspendus
                    </p>
                    <h3 class="stat-number" style="color: #E65100;"><?= $stats['suspended'] ?? 0 ?></h3>
                </div>
               
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label">
                        <i class="fas fa-wallet"></i> Solde total
                    </p>
                    <h3 class="stat-number" style="color: #1565C0;">
                        <?= number_format($stats['total_balance'] ?? 0, 0) ?> Ar
                    </h3>
                  
                </div>
               
            </div>
        </div>
    </div>
</div>
-->

<!-- Top clients 
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5>
                    <i class="fas fa-trophy" style="color: #FFD700;"></i> Clients les plus actifs
                </h5>
            </div>
            <div class="admin-card-body">
                <?php if (empty($mostActive)): ?>
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-inbox"></i> Aucune donnée
                    </div>
                <?php else: ?>
                    <?php foreach ($mostActive as $index => $client): ?>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <span class="badge bg-<?= $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : ($index === 2 ? 'danger' : 'light')) ?> me-2">
                                    #<?= $index + 1 ?>
                                </span>
                                <strong><?= esc($client['full_name']) ?></strong>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-phone"></i> <?= $client['msisdn'] ?>
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-primary">
                                    <?= $client['transaction_count'] ?? 0 ?> transactions
                                </span>
                                <br>
                                <small class="text-muted">
                                    <?= number_format($client['balance'] ?? 0, 0) ?> Ar
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="admin-card">
            <div class="admin-card-header">
                <h5>
                    <i class="fas fa-crown" style="color: #FFD700;"></i> Clients les plus riches
                </h5>
            </div>
            <div class="admin-card-body">
                <?php if (empty($richest)): ?>
                    <div class="text-center py-3 text-muted">
                        <i class="fas fa-inbox"></i> Aucune donnée
                    </div>
                <?php else: ?>
                    <?php foreach ($richest as $index => $client): ?>
                        <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                            <div>
                                <span class="badge bg-<?= $index === 0 ? 'warning' : ($index === 1 ? 'secondary' : ($index === 2 ? 'danger' : 'light')) ?> me-2">
                                    #<?= $index + 1 ?>
                                </span>
                                <strong><?= esc($client['full_name']) ?></strong>
                                <br>
                                <small class="text-muted">
                                    <i class="fas fa-phone"></i> <?= $client['msisdn'] ?>
                                </small>
                            </div>
                            <div class="text-end">
                                <span class="badge" style="background: #2E7D32; color: white;">
                                    <?= number_format($client['balance'] ?? 0, 0) ?> Ar
                                </span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
                    -->

<!-- Liste des clients -->
<div class="admin-card">
    <div class="admin-card-header">
        <h5>
            <i class="fas fa-list"></i> Liste des clients
        </h5>
        <div>
            <span class="text-muted me-3" style="font-size: 13px;">
                <i class="fas fa-user"></i> <?= count($clients) ?> clients
            </span>
         
        </div>
    </div>
    <div class="admin-card-body">
        <?php if (empty($clients)): ?>
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox" style="font-size: 32px;"></i>
                <p class="mt-2">Aucun client enregistré</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-admin">
                    <thead>
                        <tr>
                          
                            <th>Numéro</th>
                            <th>Nom</th>
                            <th>Solde (Ar)</th>
                            <th>Statut</th>
                            <th>Dernière opération</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($clients as $client): ?>
                            <tr>
                            
                                <td>
                                    <span class="badge-prefix">
                                        <?= $client['msisdn'] ?>
                                    </span>
                                </td>
                                <td>
                                    <strong><?= esc($client['full_name']) ?></strong>
                                    <?php if (!empty($client['email'])): ?>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-envelope"></i> <?= esc($client['email']) ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: <?= $client['balance'] > 0 ? '#2E7D32' : '#C62828' ?>;">
                                        <?= number_format($client['balance'] ?? 0, 0) ?>
                                    </span>
                                    <?php if (($client['transaction_count'] ?? 0) > 0): ?>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-exchange-alt"></i> <?= $client['transaction_count'] ?> opérations
                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($client['status'] === 'active'): ?>
                                        <span class="badge-status active">
                                            <i class="fas fa-circle-check"></i> Actif
                                        </span>
                                    <?php elseif ($client['status'] === 'suspended'): ?>
                                        <span class="badge-status" style="background: #FFF3E0; color: #E65100;">
                                            <i class="fas fa-pause-circle"></i> Suspendu
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-status inactive">
                                            <i class="fas fa-ban"></i> Bloqué
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if (!empty($client['last_transaction'])): ?>
                                        <small>
                                            <?= date('d/m/Y H:i', strtotime($client['last_transaction']['created_at'])) ?>
                                            <br>
                                            <span class="text-muted">
                                                <?= ucfirst($client['last_transaction']['operation_type'] ?? '') ?>
                                                <?= number_format($client['last_transaction']['amount'] ?? 0, 0) ?> Ar
                                            </span>
                                        </small>
                                    <?php else: ?>
                                        <small class="text-muted">Aucune opération</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="d-flex gap-1">
                                      
                                      
                                       
                                        <button onclick="deleteClient(<?= $client['id'] ?>)" 
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

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function toggleClient(id) {
    if (confirm('Voulez-vous changer le statut de ce client ?')) {
        fetch(`<?= base_url('admin/clients/toggle') ?>/${id}`, {
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

function deleteClient(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
        fetch(`<?= base_url('admin/clients/delete') ?>/${id}`, {
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