<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dépôt - Mobile Money</title>
    <link href="<?= base_url('public/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #6C63FF;
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
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

        .card-form {
            background: white;
            border-radius: 20px;
            padding: 24px 30px;
            box-shadow: 0 2px 16px rgba(0,0,0,0.08);
        }

        .card-form .balance-info {
            text-align: center;
            padding: 12px;
            background: #F8F9FA;
            border-radius: 12px;
            margin-bottom: 20px;
        }

        .card-form .balance-info .label {
            font-size: 13px;
            color: #6C7A89;
        }

        .card-form .balance-info .amount {
            font-size: 24px;
            font-weight: 700;
            color: #1A1A2E;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-group label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #2D3436;
            margin-bottom: 6px;
        }

        .form-group label i {
            color: var(--primary-color);
            margin-right: 8px;
        }

        .form-control-custom {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #EEF2F7;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s;
            background: #FAFBFC;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 4px rgba(108, 99, 255, 0.1);
        }

        .btn-submit {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
        }

        .btn-submit i {
            margin-right: 10px;
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

        .fee-display {
            background: #FFF3E0;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #FFE0B2;
        }

        .fee-display .fee-label {
            font-size: 14px;
            color: #E65100;
            font-weight: 500;
        }

        .fee-display .fee-value {
            font-size: 18px;
            font-weight: 700;
            color: #E65100;
        }

        .fee-display .fee-value.free {
            color: #2E7D32;
        }

        .total-display {
            background: #E8F5E9;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #C8E6C9;
        }

        .total-display .total-label {
            font-size: 14px;
            color: #2E7D32;
            font-weight: 500;
        }

        .total-display .total-value {
            font-size: 18px;
            font-weight: 700;
            color: #2E7D32;
        }

        .info-text {
            font-size: 13px;
            color: #6C7A89;
            margin-top: 4px;
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
        <h1>Dépôt d'argent</h1>
    </div>

    <!-- Messages -->
    <?php if (session()->has('error')): ?>
        <div class="alert alert-custom alert-danger mb-3">
            <i class="fas fa-exclamation-circle"></i> <?= session('error') ?>
        </div>
    <?php endif; ?>

    <!-- Formulaire -->
    <div class="card-form">
        
        <!-- Solde actuel -->
        <div class="balance-info">
            <div class="label">Solde actuel</div>
            <div class="amount"><?= number_format($client['balance'], 0) ?> Ar</div>
        </div>

        <form action="<?= base_url('client/do-deposit') ?>" method="POST" id="depositForm">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label>
                    <i class="fas fa-money-bill-wave"></i> Montant à déposer (Ar)
                </label>
                <input type="text" 
                       class="form-control-custom" 
                       name="amount" 
                       id="amount"
                       placeholder="Ex: 10000"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                       onkeyup="calculateFee(this.value)"
                       required>
                <small class="info-text">
                    <i class="fas fa-info-circle"></i> Entrez uniquement des chiffres
                </small>
            </div>

            <!-- Affichage des frais -->
            <div id="feeContainer" style="display: none;">
                <div class="fee-display">
                    <span class="fee-label">
                        <i class="fas fa-coins"></i> Frais de dépôt
                    </span>
                    <span class="fee-value" id="feeDisplay">0 Ar</span>
                </div>
                <div class="total-display">
                    <span class="total-label">
                        <i class="fas fa-arrow-right"></i> Total à débiter
                    </span>
                    <span class="total-value" id="totalDisplay">0 Ar</span>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-arrow-down"></i> Déposer
            </button>
        </form>
    </div>

    <div class="text-center text-muted mt-4" style="font-size: 12px;">
        ETU4012-4154
    </div>
    
</div>

<script>
function calculateFee(amount) {
    const feeContainer = document.getElementById('feeContainer');
    const feeDisplay = document.getElementById('feeDisplay');
    const totalDisplay = document.getElementById('totalDisplay');
    
    // Nettoyer le montant (enlever tout sauf les chiffres)
    amount = amount.replace(/[^0-9]/g, '');
    const numericAmount = parseFloat(amount);
    
    if (isNaN(numericAmount) || numericAmount <= 0) {
        feeContainer.style.display = 'none';
        return;
    }
    
    // Appel AJAX pour calculer les frais
    fetch(`<?= base_url('api/fees/calculate') ?>?type=deposit&amount=${numericAmount}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                const fee = data.data.fee;
                const total = data.data.total;
                
                feeContainer.style.display = 'block';
                
                if (fee === 0) {
                    feeDisplay.textContent = '0 Ar (Gratuit)';
                    feeDisplay.className = 'fee-value free';
                } else {
                    feeDisplay.textContent = fee.toLocaleString() + ' Ar';
                    feeDisplay.className = 'fee-value';
                }
                
                totalDisplay.textContent = total.toLocaleString() + ' Ar';
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            feeContainer.style.display = 'none';
        });
}
</script>

</body>
</html>