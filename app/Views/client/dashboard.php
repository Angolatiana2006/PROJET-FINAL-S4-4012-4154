<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard Client' ?></title>
    <link href="<?= base_url('public/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #6C63FF;
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
            --success-color: #2E7D32;
            --danger-color: #C62828;
        }

        body {
            background: #F0F2F5;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 20px;
        }

        .client-container {
            max-width: 500px;
            margin: 0 auto;
        }

        /* Header */
        .client-header {
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border-radius: 20px;
            padding: 20px 24px;
            color: white;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .client-header .user-info h2 {
            font-size: 18px;
            font-weight: 600;
            margin: 0;
        }

        .client-header .user-info p {
            opacity: 0.8;
            margin: 2px 0 0;
            font-size: 13px;
        }

        .client-header .logout-btn {
            background: rgba(255,255,255,0.2);
            border: 1px solid rgba(255,255,255,0.3);
            color: white;
            border-radius: 10px;
            padding: 6px 14px;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s;
        }

        .client-header .logout-btn:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }

        /* Balance Card */
        .balance-card {
            background: white;
            border-radius: 20px;
            padding: 24px 30px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            text-align: center;
        }

        .balance-card .balance-label {
            color: #6C7A89;
            font-size: 14px;
            font-weight: 500;
        }

        .balance-card .balance-amount {
            font-size: 36px;
            font-weight: 700;
            color: #1A1A2E;
        }

        .balance-card .balance-amount .currency {
            font-size: 18px;
            color: #6C7A89;
        }

        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .quick-action-btn {
            background: white;
            border: none;
            border-radius: 16px;
            padding: 16px 12px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            transition: all 0.3s;
            text-decoration: none;
            color: #1A1A2E;
        }

        .quick-action-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            color: var(--primary-color);
        }

        .quick-action-btn .action-icon {
            font-size: 24px;
            margin-bottom: 6px;
            display: block;
        }

        .quick-action-btn .action-label {
            font-size: 12px;
            font-weight: 500;
        }

        /* Stats mini */
        .stats-mini {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-mini {
            background: white;
            border-radius: 16px;
            padding: 14px 18px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        }

        .stat-mini .stat-label {
            font-size: 12px;
            color: #6C7A89;
        }

        .stat-mini .stat-value {
            font-size: 18px;
            font-weight: 700;
        }

        /* Transactions */
        .transactions-card {
            background: white;
            border-radius: 20px;
            padding: 20px 24px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.08);
        }

        .transactions-card .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 12px;
            border-bottom: 1px solid #EEF2F7;
            margin-bottom: 12px;
        }

        .transactions-card .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 16px;
        }

        .transaction-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #F0F2F5;
        }

        .transaction-item:last-child {
            border-bottom: none;
        }

        .transaction-item .transaction-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .transaction-item .transaction-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            flex-shrink: 0;
        }

        .transaction-item .transaction-icon.deposit {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .transaction-item .transaction-icon.withdrawal {
            background: #FFEBEE;
            color: #C62828;
        }

        .transaction-item .transaction-icon.transfer {
            background: #E3F2FD;
            color: #1565C0;
        }

        .transaction-item .transaction-info .transaction-title {
            font-weight: 500;
            font-size: 14px;
        }

        .transaction-item .transaction-info .transaction-date {
            font-size: 12px;
            color: #6C7A89;
        }

        .transaction-item .transaction-amount {
            font-weight: 600;
            font-size: 14px;
            text-align: right;
        }

        .transaction-item .transaction-amount.positive {
            color: #2E7D32;
        }

        .transaction-item .transaction-amount.negative {
            color: #C62828;
        }

        .empty-state {
            text-align: center;
            padding: 20px 0;
            color: #6C7A89;
        }

        .empty-state i {
            font-size: 32px;
            margin-bottom: 8px;
            display: block;
        }

        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 12px 16px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-custom.alert-success {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .alert-custom.alert-danger {
            background: #FFEBEE;
            color: #C62828;
        }

        @media (max-width: 480px) {
            .client-container {
                padding: 0;
            }
            
            .balance-card .balance-amount {
                font-size: 28px;
            }
            
            .quick-actions {
                grid-template-columns: 1fr 1fr 1fr;
                gap: 8px;
            }
            
            .quick-action-btn {
                padding: 12px 8px;
            }
            
            .quick-action-btn .action-icon {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>

<div class="client-container">
    
    <!-- Header -->
    <header class="client-header">
        <div class="user-info">
            <h2>
                <i class="fas fa-user-circle"></i> <?= esc($client['full_name']) ?>
            </h2>
            <p>
                <i class="fas fa-phone"></i> <?= $client['msisdn'] ?>
            </p>
        </div>
        <a href="<?= base_url('client/login') ?>" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </header>

    <!-- Messages -->
    <?php if (session()->has('success')): ?>
        <div class="alert alert-custom alert-success mb-3">
            <i class="fas fa-check-circle"></i> <?= session('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session()->has('error')): ?>
        <div class="alert alert-custom alert-danger mb-3">
            <i class="fas fa-exclamation-circle"></i> <?= session('error') ?>
        </div>
    <?php endif; ?>

    <!-- Balance -->
    <div class="balance-card">
        <div class="balance-label">
            <i class="fas fa-wallet"></i> Solde disponible
        </div>
        <div class="balance-amount">
            <?= number_format($client['balance'], 0) ?> <span class="currency">Ar</span>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <a href="<?= base_url('client/deposit') ?>" class="quick-action-btn">
            <span class="action-icon">
                <i class="fas fa-arrow-down" style="color: #2E7D32;"></i>
            </span>
            <span class="action-label">Dépôt</span>
        </a>
        <a href="<?= base_url('client/withdrawal') ?>" class="quick-action-btn">
            <span class="action-icon">
                <i class="fas fa-arrow-up" style="color: #C62828;"></i>
            </span>
            <span class="action-label">Retrait</span>
        </a>
        <a href="<?= base_url('client/transfer') ?>" class="quick-action-btn">
            <span class="action-icon">
                <i class="fas fa-right-left" style="color: #1565C0;"></i>
            </span>
            <span class="action-label">Transfert</span>
        </a>
    </div>

    <!-- Stats mini -->
    <div class="stats-mini">
        <div class="stat-mini">
            <div class="stat-label">Total envoyé</div>
            <div class="stat-value" style="color: #C62828;">
                <?= number_format($totalSent ?? 0, 0) ?> Ar
            </div>
        </div>
        <div class="stat-mini">
            <div class="stat-label">Total reçu</div>
            <div class="stat-value" style="color: #2E7D32;">
                <?= number_format($totalReceived ?? 0, 0) ?> Ar
            </div>
        </div>
    </div>

    <!-- Transactions récentes -->
    <div class="transactions-card">
        <div class="card-header">
            <h5>
                <i class="fas fa-clock-rotate-left"></i> Transactions récentes
            </h5>
            <a href="<?= base_url('client/history') ?>" style="font-size: 13px; color: var(--primary-color); text-decoration: none;">
                Voir tout <i class="fas fa-chevron-right"></i>
            </a>
        </div>
        
        <?php if (empty($recentTransactions)): ?>
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <p>Aucune transaction</p>
            </div>
        <?php else: ?>
            <?php foreach (array_slice($recentTransactions, 0, 5) as $tx): ?>
                <?php 
                    $isSender = $tx['sender_id'] == $client['id'];
                    $type = $tx['operation_type'];
                    $icon = match($type) {
                        'deposit' => 'fa-arrow-down',
                        'withdrawal' => 'fa-arrow-up',
                        'transfer' => $isSender ? 'fa-arrow-up' : 'fa-arrow-down',
                        default => 'fa-circle'
                    };
                    $iconClass = match($type) {
                        'deposit' => 'deposit',
                        'withdrawal' => 'withdrawal',
                        'transfer' => $isSender ? 'withdrawal' : 'deposit',
                        default => 'transfer'
                    };
                    $amountClass = ($type === 'deposit' || (!$isSender && $type === 'transfer')) ? 'positive' : 'negative';
                    $sign = ($type === 'deposit' || (!$isSender && $type === 'transfer')) ? '+' : '-';
                    
                    $label = match($type) {
                        'deposit' => 'Dépôt',
                        'withdrawal' => 'Retrait',
                        'transfer' => $isSender ? 'Transfert envoyé' : 'Transfert reçu',
                        default => $type
                    };
                ?>
                <div class="transaction-item">
                    <div class="transaction-left">
                        <div class="transaction-icon <?= $iconClass ?>">
                            <i class="fas <?= $icon ?>"></i>
                        </div>
                        <div class="transaction-info">
                            <div class="transaction-title"><?= $label ?></div>
                            <div class="transaction-date">
                                <?= date('d/m/Y H:i', strtotime($tx['created_at'])) ?>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="transaction-amount <?= $amountClass ?>">
                            <?= $sign ?> <?= number_format($tx['amount'], 0) ?> Ar
                        </div>
                        <?php if ($tx['fee_amount'] > 0): ?>
                            <small class="text-muted">Frais: <?= number_format($tx['fee_amount'], 0) ?> Ar</small>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <div class="text-center text-muted mt-4" style="font-size: 12px;">
        ETU4012-4154
    </div>
    
</div>

</body>
</html>