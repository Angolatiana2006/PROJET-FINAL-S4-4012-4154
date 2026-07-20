<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>




<!-- Statistiques globales -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label">
                        <i class="fas fa-coins"></i> Total gains
                    </p>
                    <h3 class="stat-number" style="color: #2E7D32;">
                        <?= number_format($periodStats['total_gains'] ?? 0, 0) ?> Ar
                    </h3>
                    <small class="text-muted">
                        <?= $periodLabel ?>
                    </small>
                </div>
                <div class="stat-icon text-success">
                    <i class="fas fa-money-bill-wave"></i>
                </div>
            </div>
        </div>
    </div>
    
    
    <div class="col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label">
                        <i class="fas fa-arrow-trend-up"></i> Volume total
                    </p>
                    <h3 class="stat-number" style="color: #1565C0;">
                        <?= number_format($periodStats['total_volume'] ?? 0, 0) ?> Ar
                    </h3>
                    <small class="text-muted">
                        <?= $periodLabel ?>
                    </small>
                </div>
                <div class="stat-icon text-info">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>
    
    
</div>







<!-- Détail par type 
<div class="row g-3 mb-4">
    <?php foreach ($gainsByType as $type): ?>
        <?php 
            $icon = match($type['operation_type']) {
                'deposit' => 'fa-arrow-down',
                'withdrawal' => 'fa-arrow-up',
                'transfer' => 'fa-right-left',
                default => 'fa-circle'
            };
            $color = match($type['operation_type']) {
                'deposit' => '#2E7D32',
                'withdrawal' => '#C62828',
                'transfer' => '#1565C0',
                default => '#6C63FF'
            };
            $label = $typeLabels[$type['operation_type']] ?? $type['operation_type'];
        ?>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <p class="stat-label">
                            <i class="fas <?= $icon ?>" style="color: <?= $color ?>;"></i> <?= $label ?>
                        </p>
                        <h3 class="stat-number" style="color: <?= $color ?>;">
                            <?= number_format($type['total_gains'] ?? 0, 0) ?> Ar
                        </h3>
                        <small class="text-muted">
                            <?= $type['total_transactions'] ?? 0 ?> opérations
                            <span class="mx-1">•</span>
                            Moy: <?= number_format($type['avg_gain'] ?? 0, 0) ?> Ar
                        </small>
                    </div>
                    <div class="stat-icon" style="color: <?= $color ?>;">
                        <i class="fas <?= $icon ?>"></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
        -->




<!-- Transactions récentes -->
<div class="admin-card">
    <div class="admin-card-header">
        <h5>
            <i class="fas fa-clock-rotate-left"></i> Transactions récentes
        </h5>
    
    </div>
    <div class="admin-card-body">
        <?php if (empty($recentTransactions)): ?>
            <div class="text-center py-4 text-muted">
                <i class="fas fa-inbox" style="font-size: 32px;"></i>
                <p class="mt-2">Aucune transaction récente</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-admin">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Type</th>
                            <th>Montant (Ar)</th>
                            <th>Frais (Ar)</th>
                            <th>Total (Ar)</th>
                            <th>Statut</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentTransactions as $tx): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        <?= $tx['transaction_id'] ?>
                                    </span>
                                </td>
                                <td>
                                    <?php 
                                        $label = $typeLabels[$tx['operation_type']] ?? $tx['operation_type'];
                                        $color = match($tx['operation_type']) {
                                            'deposit' => 'success',
                                            'withdrawal' => 'danger',
                                            'transfer' => 'primary',
                                            default => 'secondary'
                                        };
                                    ?>
                                    <span class="badge bg-<?= $color ?>">
                                        <?= $label ?>
                                    </span>
                                </td>
                                <td><?= number_format($tx['amount'], 0) ?></td>
                                <td>
                                    <?php if ($tx['fee_amount'] > 0): ?>
                                        <span style="color: #2E7D32; font-weight: 600;">
                                            <?= number_format($tx['fee_amount'], 0) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">0</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= number_format($tx['total_amount'], 0) ?></td>
                                <td>
                                    <?php if ($tx['status'] === 'completed'): ?>
                                        <span class="badge-status active">
                                            <i class="fas fa-circle-check"></i> Terminé
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-status inactive">
                                            <i class="fas fa-circle-xmark"></i> <?= ucfirst($tx['status']) ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?= date('d/m/Y H:i', strtotime($tx['created_at'])) ?>
                                    </small>
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
<!-- Chart.js pour le graphique -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Graphique des gains
const ctx = document.getElementById('gainsChart').getContext('2d');
const chartData = <?= json_encode($chartData) ?>;

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: chartData.labels,
        datasets: [{
            label: 'Gains (Ar)',
            data: chartData.values,
            backgroundColor: 'rgba(108, 99, 255, 0.6)',
            borderColor: 'rgba(108, 99, 255, 1)',
            borderWidth: 2,
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                display: false
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.parsed.y + ' Ar';
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' Ar';
                    }
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});
</script>
<?= $this->endSection() ?>