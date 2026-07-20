<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transfert - Mobile Money</title>
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
            margin-bottom: 12px;
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
            background: #FFEBEE;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #FFCDD2;
        }

        .total-display .total-label {
            font-size: 14px;
            color: #C62828;
            font-weight: 500;
        }

        .total-display .total-value {
            font-size: 18px;
            font-weight: 700;
            color: #C62828;
        }

        .new-balance-display {
            background: #E8F5E9;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 16px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border: 1px solid #C8E6C9;
        }

        .new-balance-display .new-balance-label {
            font-size: 14px;
            color: #2E7D32;
            font-weight: 500;
        }

        .new-balance-display .new-balance-value {
            font-size: 18px;
            font-weight: 700;
            color: #2E7D32;
        }

        .info-text {
            font-size: 13px;
            color: #6C7A89;
            margin-top: 4px;
        }

        .receiver-info {
            background: #E3F2FD;
            border-radius: 10px;
            padding: 10px 14px;
            margin-bottom: 16px;
            font-size: 13px;
            color: #1565C0;
            display: none;
        }

        .receiver-info i {
            margin-right: 8px;
        }

        .multi-receiver {
            background: #F8F9FA;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 16px;
            border: 1px dashed #EEF2F7;
        }

        .multi-receiver .receiver-item {
            display: flex;
            gap: 8px;
            margin-bottom: 8px;
            align-items: center;
        }

        .multi-receiver .receiver-item input {
            flex: 1;
            padding: 8px 12px;
            border: 2px solid #EEF2F7;
            border-radius: 8px;
            font-size: 14px;
        }

        .multi-receiver .receiver-item .remove-btn {
            background: #FFEBEE;
            border: none;
            border-radius: 8px;
            color: #C62828;
            padding: 8px 12px;
            cursor: pointer;
            font-size: 14px;
        }

        .multi-receiver .add-receiver-btn {
            background: #E3F2FD;
            border: none;
            border-radius: 8px;
            color: #1565C0;
            padding: 8px 16px;
            cursor: pointer;
            font-size: 14px;
            width: 100%;
            margin-top: 4px;
        }

        .option-checkbox {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            padding: 12px;
            background: #F8F9FA;
            border-radius: 12px;
        }

        .option-checkbox input[type="checkbox"] {
            width: 20px;
            height: 20px;
            accent-color: var(--primary-color);
        }

        .option-checkbox .option-label {
            font-size: 14px;
            font-weight: 500;
            color: #2D3436;
        }

        .option-checkbox .option-desc {
            font-size: 12px;
            color: #6C7A89;
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
        <h1>Transfert d'argent</h1>
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

        <!-- Option : Inclure les frais de retrait -->
        <div class="option-checkbox">
            <input type="checkbox" name="include_fees" id="include_fees" onchange="toggleFeeInclusion()">
            <div>
                <div class="option-label">
                    <i class="fas fa-arrow-right"></i> Inclure les frais de retrait
                </div>
                <div class="option-desc">
                    Le destinataire recevra le montant total (frais inclus)
                </div>
            </div>
        </div>

        <form action="<?= base_url('client/do-transfer') ?>" method="POST" id="transferForm">
            <?= csrf_field() ?>
            
            <!-- Destinataires multiples -->
            <div class="form-group">
                <label>
                    <i class="fas fa-users"></i> Destinataires
                </label>
                <div class="multi-receiver" id="receiverContainer">
                    <div class="receiver-item">
                        <input type="text" 
                               class="form-control-custom" 
                               name="receiver_msisdn[]" 
                               placeholder="0332345678"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                               onkeyup="checkReceiver(this.value, 0)"
                               required>
                        <span class="remove-btn" onclick="removeReceiver(this)" style="display:none;">
                            <i class="fas fa-times"></i>
                        </span>
                    </div>
                    <div id="receiverInfo0" class="receiver-info" style="display: none;">
                        <i class="fas fa-user-check"></i>
                        Destinataire : <strong id="receiverName0">-</strong>
                    </div>
                </div>
                <button type="button" class="add-receiver-btn" onclick="addReceiver()">
                    <i class="fas fa-plus-circle"></i> Ajouter un destinataire
                </button>
                <small class="info-text">
                    <i class="fas fa-info-circle"></i> Uniquement les numéros du même opérateur
                </small>
            </div>

            <!-- Montant total -->
            <div class="form-group">
                <label>
                    <i class="fas fa-money-bill-wave"></i> Montant total à transférer (Ar)
                </label>
                <input type="text" 
                       class="form-control-custom" 
                       name="total_amount" 
                       id="total_amount"
                       placeholder="Ex: 30000"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                       onkeyup="calculateSplit()"
                       required>
                <small class="info-text">
                    <i class="fas fa-info-circle"></i> Le montant sera divisé entre tous les destinataires
                </small>
            </div>

            <!-- Montant par destinataire -->
            <div id="splitInfo" style="display: none;">
                <div class="fee-display">
                    <span class="fee-label">
                        <i class="fas fa-calculator"></i> Montant par destinataire
                    </span>
                    <span class="fee-value" id="perReceiverDisplay">0 Ar</span>
                </div>
                <div class="fee-display" style="background: #E8F5E9; border-color: #C8E6C9;">
                    <span class="fee-label" style="color: #2E7D32;">
                        <i class="fas fa-users"></i> Nombre de destinataires
                    </span>
                    <span class="fee-value" style="color: #2E7D32;" id="receiverCountDisplay">0</span>
                </div>
            </div>

            <!-- Affichage des frais -->
            <div id="feeContainer" style="display: none;">
                <div class="fee-display">
                    <span class="fee-label">
                        <i class="fas fa-coins"></i> Frais de transfert
                    </span>
                    <span class="fee-value" id="feeDisplay">0 Ar</span>
                </div>
                <div class="total-display">
                    <span class="total-label">
                        <i class="fas fa-arrow-right"></i> Total à débiter
                    </span>
                    <span class="total-value" id="totalDisplay">0 Ar</span>
                </div>
                <div class="new-balance-display">
                    <span class="new-balance-label">
                        <i class="fas fa-wallet"></i> Nouveau solde
                    </span>
                    <span class="new-balance-value" id="newBalanceDisplay">0 Ar</span>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="submitBtn">
                <i class="fas fa-right-left"></i> Transférer
            </button>
        </form>
    </div>

    <div class="text-center text-muted mt-4" style="font-size: 12px;">
        ETU4012-4154
    </div>
    
</div>

<script>
let receiverCount = 1;
let receiverData = {};

function addReceiver() {
    const container = document.getElementById('receiverContainer');
    const index = receiverCount;
    
    const item = document.createElement('div');
    item.className = 'receiver-item';
    item.innerHTML = `
        <input type="text" 
               class="form-control-custom" 
               name="receiver_msisdn[]" 
               placeholder="0332345678"
               oninput="this.value = this.value.replace(/[^0-9]/g, '')"
               onkeyup="checkReceiver(this.value, ${index})"
               required>
        <span class="remove-btn" onclick="removeReceiver(this)">
            <i class="fas fa-times"></i>
        </span>
    `;
    
    const info = document.createElement('div');
    info.id = `receiverInfo${index}`;
    info.className = 'receiver-info';
    info.style.display = 'none';
    info.innerHTML = `
        <i class="fas fa-user-check"></i>
        Destinataire : <strong id="receiverName${index}">-</strong>
    `;
    
    container.appendChild(item);
    container.appendChild(info);
    receiverCount++;
    
    calculateSplit();
}

function removeReceiver(element) {
    const item = element.closest('.receiver-item');
    const info = item.nextElementSibling;
    if (document.querySelectorAll('.receiver-item').length > 1) {
        item.remove();
        if (info && info.className === 'receiver-info') {
            info.remove();
        }
        receiverCount--;
        calculateSplit();
    }
}

function toggleFeeInclusion() {
    calculateSplit();
}

function checkReceiver(msisdn, index) {
    const info = document.getElementById(`receiverInfo${index}`);
    const name = document.getElementById(`receiverName${index}`);
    
    if (msisdn.length < 10) {
        if (info) info.style.display = 'none';
        return;
    }
    
    fetch(`<?= base_url('api/clients/search') ?>?q=${msisdn}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success' && data.data.length > 0) {
                const client = data.data.find(c => c.msisdn === msisdn);
                if (client) {
                    if (name) name.textContent = client.full_name;
                    if (info) info.style.display = 'block';
                    receiverData[index] = client;
                } else {
                    if (info) info.style.display = 'none';
                }
            } else {
                if (info) info.style.display = 'none';
            }
        })
        .catch(() => {
            if (info) info.style.display = 'none';
        });
}

function calculateSplit() {
    const totalAmount = document.getElementById('total_amount').value.replace(/[^0-9]/g, '');
    const numericTotal = parseFloat(totalAmount) || 0;
    const count = document.querySelectorAll('.receiver-item').length;
    const includeFees = document.getElementById('include_fees').checked;
    
    const splitInfo = document.getElementById('splitInfo');
    const perReceiverDisplay = document.getElementById('perReceiverDisplay');
    const receiverCountDisplay = document.getElementById('receiverCountDisplay');
    
    if (numericTotal > 0 && count > 0) {
        splitInfo.style.display = 'block';
        const perReceiver = numericTotal / count;
        perReceiverDisplay.textContent = Math.round(perReceiver).toLocaleString() + ' Ar';
        receiverCountDisplay.textContent = count;
    } else {
        splitInfo.style.display = 'none';
    }
    
    calculateFee(totalAmount, count, includeFees);
}

function calculateFee(amount, count, includeFees) {
    const feeContainer = document.getElementById('feeContainer');
    const feeDisplay = document.getElementById('feeDisplay');
    const totalDisplay = document.getElementById('totalDisplay');
    const newBalanceDisplay = document.getElementById('newBalanceDisplay');
    const submitBtn = document.getElementById('submitBtn');
    const currentBalance = <?= $client['balance'] ?>;
    
    const numericAmount = parseFloat(amount) || 0;
    
    if (numericAmount <= 0 || count === 0) {
        feeContainer.style.display = 'none';
        submitBtn.disabled = false;
        return;
    }
    
    // Vérifier qu'il y a au moins un destinataire valide
    let validReceivers = 0;
    for (let i = 0; i < count; i++) {
        const input = document.querySelectorAll('.receiver-item input')[i];
        if (input && input.value.length >= 10) {
            validReceivers++;
        }
    }
    
    if (validReceivers === 0) {
        feeContainer.style.display = 'none';
        return;
    }
    
    // Calcul des frais (basé sur le montant total)
    const perReceiver = numericAmount / count;
    
    // Si inclusion des frais, on calcule différemment
    let fee = 0;
    if (includeFees) {
        // Les frais sont calculés sur le montant total que le destinataire doit recevoir
        // Mais pour simplifier, on utilise la même logique
        fee = 50; // Frais fixe pour l'exemple (à adapter)
        // On pourrait utiliser l'API mais pour l'instant simplifié
    } else {
        // Appel AJAX pour calculer les frais
        fetch(`<?= base_url('api/fees/calculate') ?>?type=transfer&amount=${numericAmount}`)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    const fee = data.data.fee;
                    const total = data.data.total;
                    const newBalance = currentBalance - total;
                    
                    feeContainer.style.display = 'block';
                    
                    feeDisplay.textContent = fee.toLocaleString() + ' Ar';
                    totalDisplay.textContent = total.toLocaleString() + ' Ar';
                    
                    if (newBalance < 0) {
                        newBalanceDisplay.textContent = 'Solde insuffisant !';
                        newBalanceDisplay.style.color = '#C62828';
                        submitBtn.disabled = true;
                    } else {
                        newBalanceDisplay.textContent = newBalance.toLocaleString() + ' Ar';
                        newBalanceDisplay.style.color = '#2E7D32';
                        submitBtn.disabled = false;
                    }
                }
            })
            .catch(() => {
                feeContainer.style.display = 'none';
            });
    }
}
</script>

</body>
</html>