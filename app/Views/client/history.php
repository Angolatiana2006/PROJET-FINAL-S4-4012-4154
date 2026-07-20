<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique - Mobile Money</title>
    <link href="<?= base_url('public/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #6C63FF;
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

        .header-back {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .header-back a {
            color: #1A1A2E;
            text-decoration: none;
            font-size: 20px;
        }

        .header-back h1 {
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }

        .filter-tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
            overflow-x: auto;
            padding-bottom: 4px;
        }

        .filter-tabs .tab-btn {
            padding: 6px 16px;
            border: 2px solid #EEF2F7;
            border-radius: 20px;
            background: white;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s;
            white-space: nowrap;
            color: #2D3436;
        }

        .filter-tabs .tab-btn:hover {
            border-color: var(--primary-color);
        }

        .filter-tabs .tab-btn.active {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .transaction-item {
            background: white;
            border-radius: 16px;
            padding: 14px 18px;
            margin-bottom: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .transaction-item .left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .transaction-item .icon {
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .transaction-item .icon.deposit {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .transaction-item .icon.withdrawal {
            background: #FFEBEE;
            color: #C62828;
        }

        .transaction-item .icon.transfer-in {
            background: #E8F5E9;
            color: #2E7D32;
        }

        .transaction-item .icon.transfer-out {
            background: #FFEBEE;
            color: #C62828;
        }

        .transaction-item .info .title {
            font-weight: 500;
            font-size: 14px;
        }

        .transaction-item .info .date {
            font-size: 12px;
            color: #6C7A89;
        }

        .transaction-item .info .description {
            font-size: 12px;
            color: #9EA7B3;
        }

        .transaction-item .right {
            text-align: right;
        }

        .transaction-item .right .amount {
            font-weight: 600;
            font-size: 15px;
        }

        .transaction-item .right .amount.positive {
            color: #2E7D32;
        }

        .transaction-item .right .amount.negative {
            color: #C62828;
        }

        .transaction-item .right .fee {
            font-size: 12px;
            color: #6C7A89;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            background: white;
            border-radius: 16px;
        }

        .empty-state i {
            font-size: 48px;
            color: #D5DCE4;
            margin-bottom: 12px;
            display: block;
        }

        .empty-state h5 {
            color: #2D3436;
            font-weight: 600;
        }

        .empty-state p {
            color: #6C7A89;
            font-size: 14px;
        }

        .stat-summary {
            background: white;
            border-radius: 16px;
            padding: 14px 18px;
            margin-bottom: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.06);
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
            text-align: center;
        }

        .stat-summary .stat-item .label {
            font-size: 12px;
            color: #6C7A89;
        }

        .stat-summary .stat-item .value {
            font-size: 18px;
            font-weight: 700;
        }

        .stat-summary .stat-item .value.positive {
            color: #2E7D32;
        }

        .stat-summary .stat-item .value.negative {
            color: #C62828;
        }

        @media (max-width: 480px) {
            .client-container {
                padding: 0;
            }
        }
    </style>
</head>
<body>

<div class="client-container">
    
    <!-- Header -->
    <div class="header-back">
        <a href="<?= base_url('client/dashboard') ?>">
            <i class="fas fa-arrow-left"></i>
        </a>
        <h1>Historique des transactions</h1>
    </div>

    <?php 
        // Calculer les totaux
        $totalSent = 0;
        $totalReceived = 0;
        foreach ($transactions as $tx) {
            if ($tx['sender_id'] == $clientId) {
                $totalSent += $tx['amount'] + $tx['fee_amount'];
            } else if ($tx['receiver_id'] == $clientId) {
                $totalReceived += $tx['amount'];
            }
        }
    ?>

    <!-- Résumé -->
    <div class="stat-summary">
        <div class="stat-item">
            <div class="label">Total envoyé</div>
            <div class="value negative"><?= number_format($totalSent, 0) ?> Ar</div>
        </div>
        <div class="stat-item">
            <div class="label">Total reçu</div>
            <div class="value positive"><?= number_format($totalReceived, 0) ?> Ar</div>
        </div>
    </div>

    <!-- Transactions -->
    <?php if (empty($transactions)): ?>
        <div class="empty-state">
            <i class="fas fa-inbox"></i>
            <h5>Aucune transaction</h5>
            <p>Vous n'avez pas encore effectué d'opérations</p>
        </div>
    <?php else: ?>
        <?php foreach ($transactions as $tx): ?>
            <?php 
                $isSender = $tx['sender_id'] == $clientId;
                $type = $tx['operation_type'];
                
                if ($type === 'deposit') {
                    $iconClass = 'deposit';
                    $icon = 'fa-arrow-down';
                    $title = 'Dépôt';
                    $amountClass = 'positive';
                    $sign = '+';
                } elseif ($type === 'withdrawal') {
                    $iconClass = 'withdrawal';
                    $icon = 'fa-arrow-up';
                    $title = 'Retrait';
                    $amountClass = 'negative';
                    $sign = '-';
                } elseif ($type === 'transfer') {
                    if ($isSender) {
                        $iconClass = 'transfer-out';
                        $icon = 'fa-arrow-up';
                        $title = 'Transfert envoyé';
                        $amountClass = 'negative';
                        $sign = '-';
                    } else {
                        $iconClass = 'transfer-in';
                        $icon = 'fa-arrow-down';
                        $title = 'Transfert reçu';
                        $amountClass = 'positive';
                        $sign = '+';
                    }
                }
            ?>
            <div class="transaction-item">
                <div class="left">
                    <div class="icon <?= $iconClass ?>">
                        <i class="fas <?= $icon ?>"></i>
                    </div>
                    <div class="info">
                        <div class="title"><?= $title ?></div>
                        <div class="date"><?= date('d/m/Y H:i', strtotime($tx['created_at'])) ?></div>
                        <?php if (!empty($tx['description'])): ?>
                            <div class="description"><?= esc($tx['description']) ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="right">
                    <div class="amount <?= $amountClass ?>">
                        <?= $sign ?> <?= number_format($tx['amount'], 0) ?> Ar
                    </div>
                    <?php if ($tx['fee_amount'] > 0): ?>
                        <div class="fee">Frais: <?= number_format($tx['fee_amount'], 0) ?> Ar</div>
                    <?php endif; ?>
                    <?php if ($tx['status'] !== 'completed'): ?>
                        <div class="fee" style="color: #C62828;">
                            <?= ucfirst($tx['status']) ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="text-center text-muted mt-4" style="font-size: 12px;">
        ETU4012-4154
    </div>
    
</div>

</body>
</html>