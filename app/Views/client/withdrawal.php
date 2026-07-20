<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Retrait - Mobile Money</title>
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
            background: linear-gradient(135deg, #FF6B6B, #C62828);
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
            box-shadow: 0 8px 24px rgba(198, 40, 40, 0.4);
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

        .amount-presets {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
            margin-bottom: 16px;
        }

        .amount-presets .preset-btn {
            padding: 8px;
            border: 2px solid #EEF2F7;
            border-radius: 10px;
            background: white;
            cursor: pointer;
            transition: all 0.3s;
            font-weight: 600;
            font-size: 14px;
            color: #2D3436;
        }

        .amount-presets .preset-btn:hover {
            border-color: #FF6B6B;
            background: #FFEBEE;
        }

        .amount-presets .preset-btn.active {
            border-color: #FF6B6B;
            background: #FF6B6B;
            color: white;
        }

        .fee-info {
            background: #FFF3E0;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 16px;
            font-size: 13px;
            color: #E65100;
        }

        .fee-info i {
            margin-right: 8px;
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
        <h1>Retrait d'argent</h1>
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

        <div class="fee-info">
            <i class="fas fa-info-circle"></i>
            Des frais peuvent s'appliquer selon le montant retiré
        </div>

        <form action="<?= base_url('client/do-withdrawal') ?>" method="POST">
            <?= csrf_field() ?>
            
            <!-- Montants prédéfinis -->
            <div class="amount-presets">
                <button type="button" class="preset-btn" onclick="setAmount(1000)">1 000</button>
                <button type="button" class="preset-btn" onclick="setAmount(5000)">5 000</button>
                <button type="button" class="preset-btn" onclick="setAmount(10000)">10 000</button>
                <button type="button" class="preset-btn" onclick="setAmount(25000)">25 000</button>
                <button type="button" class="preset-btn" onclick="setAmount(50000)">50 000</button>
                <button type="button" class="preset-btn" onclick="setAmount(100000)">100 000</button>
            </div>

            <div class="form-group">
                <label>
                    <i class="fas fa-money-bill-wave"></i> Montant à retirer (Ar)
                </label>
                <input type="number" 
                       class="form-control-custom" 
                       name="amount" 
                       id="amount"
                       placeholder="Ex: 10000"
                       min="1"
                       step="100"
                       required>
            </div>

            <button type="submit" class="btn-submit">
                <i class="fas fa-arrow-up"></i> Retirer
            </button>
        </form>
    </div>

    <div class="text-center text-muted mt-4" style="font-size: 12px;">
        ETU4012-4154
    </div>
    
</div>

<script>
function setAmount(amount) {
    document.getElementById('amount').value = amount;
    
    const btns = document.querySelectorAll('.preset-btn');
    btns.forEach(btn => {
        btn.classList.remove('active');
        if (btn.textContent.replace(/\s/g, '') === amount.toString()) {
            btn.classList.add('active');
        }
    });
}
</script>

</body>
</html>