<?= $this->extend('layouts/admin_layout') ?>

<?= $this->section('content') ?>

<!-- ============================================
    STATISTIQUES GLOBALES
    ============================================ -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <p class="stat-label">
                    <i class="fas fa-coins"></i> Total gains
                </p>
                <h3 class="stat-number" style="color: #10B981;">
                    <?= number_format($periodStats['total_gains'] ?? 0, 0) ?> Ar
                </h3>
                <span class="stat-period"><?= $periodLabel ?></span>
            </div>
            <div class="stat-icon">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <p class="stat-label">
                    <i class="fas fa-exchange-alt"></i> Transactions
                </p>
                <h3 class="stat-number">
                    <?= number_format($periodStats['total_transactions'] ?? 0) ?>
                </h3>
                <span class="stat-period"><?= $periodLabel ?></span>
            </div>
            <div class="stat-icon">
                <i class="fas fa-list-ul"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <p class="stat-label">
                    <i class="fas fa-arrow-trend-up"></i> Volume total
                </p>
                <h3 class="stat-number" style="color: #0ca950ff;">
                    <?= number_format($periodStats['total_volume'] ?? 0, 0) ?> Ar
                </h3>
                <span class="stat-period"><?= $periodLabel ?></span>
            </div>
            <div class="stat-icon">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-top">
            <div>
                <p class="stat-label">
                    <i class="fas fa-arrow-right"></i> Frais moyen
                </p>
                <h3 class="stat-number" style="color: #F59E0B;">
                    <?= number_format($periodStats['avg_gain'] ?? 0, 0) ?> Ar
                </h3>
                <span class="stat-period"><?= $periodLabel ?></span>
            </div>
            <div class="stat-icon">
                <i class="fas fa-calculator"></i>
            </div>
        </div>
    </div>
</div>


<!-- ============================================
    GAINS PAR TYPE D'OPÉRATEUR (INTERNE vs EXTERNE)
    ============================================ -->
<div class="row g-3 mb-4">
    <!-- Gains Internes -->
    <div class="col-md-6">
        <div class="card-dashboard">
            <div class="card-header" style="background: linear-gradient(135deg, #065F46, #047857); color: white; border-radius: 12px 12px 0 0;">
                <h5 style="color: white;">
                    <i class="fas fa-phone"></i> Opérateur interne
                </h5>
                <span style="font-size: 12px; opacity: 0.8;">
                    <i class="fas fa-arrow-right"></i> Transferts entre clients
                </span>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <div class="stat-card" style="box-shadow: none; background: #F9FAFB; padding: 12px 16px; border: 1px solid #E5E7EB;">
                            <p class="stat-label" style="font-size: 12px;">Total gains</p>
                            <h4 style="color: #065F46; font-weight: 700; margin: 0;">
                                <?= number_format($internalStats['total_gains'] ?? 0, 0) ?> Ar
                            </h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card" style="box-shadow: none; background: #F9FAFB; padding: 12px 16px; border: 1px solid #E5E7EB;">
                            <p class="stat-label" style="font-size: 12px;">Transactions</p>
                            <h4 style="color: #1F2937; font-weight: 700; margin: 0;">
                                <?= number_format($internalStats['total_transactions'] ?? 0) ?>
                            </h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card" style="box-shadow: none; background: #F9FAFB; padding: 12px 16px; border: 1px solid #E5E7EB;">
                            <p class="stat-label" style="font-size: 12px;">Volume total</p>
                            <h4 style="color: #3B82F6; font-weight: 700; margin: 0;">
                                <?= number_format($internalStats['total_volume'] ?? 0, 0) ?> Ar
                            </h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card" style="box-shadow: none; background: #F9FAFB; padding: 12px 16px; border: 1px solid #E5E7EB;">
                            <p class="stat-label" style="font-size: 12px;">Frais moyen</p>
                            <h4 style="color: #F59E0B; font-weight: 700; margin: 0;">
                                <?= number_format($internalStats['avg_gain'] ?? 0, 0) ?> Ar
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gains Externes -->
    <div class="col-md-6">
        <div class="card-dashboard">
            <div class="card-header" style="background: linear-gradient(135deg, #0ca950ff, #0ca950ff); color: white; border-radius: 12px 12px 0 0;">
                <h5 style="color: white;">
                    <i class="fas fa-phone"></i> Autres opérateurs
                </h5>
                <span style="font-size: 12px; opacity: 0.8;">
                    <i class="fas fa-arrow-right"></i> Transferts vers externes
                </span>
            </div>
            <div class="card-body">
                <div class="row g-2">
                    <div class="col-6">
                        <div class="stat-card" style="box-shadow: none; background: #F9FAFB; padding: 12px 16px; border: 1px solid #E5E7EB;">
                            <p class="stat-label" style="font-size: 12px;">Total gains</p>
                            <h4 style="color: #0ca950ff; font-weight: 700; margin: 0;">
                                <?= number_format($externalStats['total_gains'] ?? 0, 0) ?> Ar
                            </h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card" style="box-shadow: none; background: #F9FAFB; padding: 12px 16px; border: 1px solid #E5E7EB;">
                            <p class="stat-label" style="font-size: 12px;">Transactions</p>
                            <h4 style="color: #1F2937; font-weight: 700; margin: 0;">
                                <?= number_format($externalStats['total_transactions'] ?? 0) ?>
                            </h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="stat-card" style="box-shadow: none; background: #F9FAFB; padding: 12px 16px; border: 1px solid #E5E7EB;">
                            <p class="stat-label" style="font-size: 12px;">Volume total</p>
                            <h4 style="color: #3B82F6; font-weight: 700; margin: 0;">
                                <?= number_format($externalStats['total_volume'] ?? 0, 0) ?> Ar
                            </h4>
                        </div>
                    </div>
              
                </div>
            </div>
        </div>
    </div>
</div>



<!-- ============================================
    DÉTAIL PAR OPÉRATEUR EXTERNE
    ============================================ -->
<?php if (!empty($externalOperators)): ?>
<div class="card-dashboard mb-4">
    <div class="card-header">
        <h5>
            <i class="fas fa-list-ul"></i> Détail par opérateur externe
        </h5>
        <span class="text-muted" style="font-size: 12px;">
            <i class="fas fa-clock"></i> Toutes les données
        </span>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-admin">
                <thead>
                    <tr>
                        <th>Opérateur</th>
                        <th>Préfixe</th>
                        <th>Commission (%)</th>
                        <th>Montant envoyé</th>
                        <th>Commission prise</th>
                        <th>À reverser</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($externalOperators as $op): ?>
                        <tr>
                            <td>
                                <strong><?= esc($op['receiver_operator']) ?></strong>
                            </td>
                            <td>
                                <span class="badge-prefix"><?= $op['receiver_prefix'] ?></span>
                            </td>
                            <td>
                                <span class="badge" style="background: #FEF3C7; color: #92400E; font-weight: 600; padding: 4px 12px; border-radius: 20px;">
                                    <?= number_format($op['avg_fee_percent'] ?? 0, 2) ?>%
                                </span>
                            </td>
                            <td>
                                <?= number_format($op['total_amount'] ?? 0, 0) ?> Ar
                            </td>
                            <td style="color: #D97706; font-weight: 600;">
                                <?= number_format($op['total_external_fee'] ?? 0, 0) ?> Ar
                            </td>
                            <td style="color: #DC2626; font-weight: 600;">
                                <?= number_format(($op['total_amount'] ?? 0) - ($op['total_external_fee'] ?? 0), 0) ?> Ar
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ============================================
    TRANSACTIONS RÉCENTES
    ============================================ -->
<div class="card-dashboard">
    <div class="card-header">
        <h5>
            <i class="fas fa-clock-rotate-left"></i> Transactions récentes
        </h5>
        <span class="text-muted" style="font-size: 12px;">
            <i class="fas fa-rotate-right"></i> Mise à jour automatique
        </span>
    </div>
    <div class="card-body">
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
                                    <?php if (isset($tx['is_external']) && $tx['is_external']): ?>
                                        <span class="badge bg-info" style="font-size: 10px;">
                                            <i class="fas fa-phone"></i> Externe
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td><?= number_format($tx['amount'], 0) ?></td>
                                <td>
                                    <?php if ($tx['fee_amount'] > 0): ?>
                                        <span style="color: #065F46; font-weight: 600;">
                                            <?= number_format($tx['fee_amount'], 0) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">0</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= number_format($tx['total_amount'], 0) ?></td>
                                <td>
                                    <?php if ($tx['status'] === 'completed'): ?>
                                        <span class="badge-status completed">
                                            <i class="fas fa-circle-check"></i> Terminé
                                        </span>
                                    <?php elseif ($tx['status'] === 'pending'): ?>
                                        <span class="badge-status pending">
                                            <i class="fas fa-clock"></i> En attente
                                        </span>
                                    <?php else: ?>
                                        <span class="badge-status failed">
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
            backgroundColor: 'rgba(79, 70, 229, 0.6)',
            borderColor: 'rgba(79, 70, 229, 1)',
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