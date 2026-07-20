<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Client - Mobile Money</title>
    <link href="<?= base_url('public/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #6C63FF;
            --gradient-start: #667eea;
            --gradient-end: #764ba2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 400px;
            animation: fadeInUp 0.6s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-card {
            background: white;
            border-radius: 24px;
            padding: 40px 32px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .login-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .login-header .logo-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, var(--gradient-start), var(--gradient-end));
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 16px;
            font-size: 32px;
            color: white;
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
        }

        .login-header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1A1A2E;
            margin-bottom: 4px;
        }

        .login-header p {
            color: #6C7A89;
            font-size: 14px;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
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

        .input-group-custom {
            position: relative;
        }

        .input-group-custom .input-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #B0BEC5;
            font-size: 16px;
            transition: color 0.3s;
        }

        .input-group-custom input {
            width: 100%;
            padding: 12px 16px 12px 44px;
            border: 2px solid #EEF2F7;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
            background: #FAFBFC;
        }

        .input-group-custom input:focus {
            outline: none;
            border-color: var(--primary-color);
            background: white;
            box-shadow: 0 0 0 4px rgba(108, 99, 255, 0.1);
        }

        .input-group-custom input.is-invalid {
            border-color: #FF6B6B;
            box-shadow: 0 0 0 4px rgba(255, 107, 107, 0.1);
        }

        .btn-login {
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
            margin-top: 8px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login i {
            margin-right: 10px;
        }

        .login-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #EEF2F7;
        }

        .login-footer .demo-msisdn {
            background: #F8F9FA;
            border-radius: 12px;
            padding: 12px;
            border: 1px solid #EEF2F7;
            transition: all 0.3s;
            cursor: pointer;
            margin-top: 8px;
        }

        .login-footer .demo-msisdn:hover {
            border-color: var(--primary-color);
            background: white;
        }

        .login-footer .demo-msisdn .demo-label {
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--primary-color);
        }

        .login-footer .demo-msisdn .demo-number {
            font-weight: 600;
            font-size: 16px;
            color: #2D3436;
            margin: 4px 0;
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
            .login-card {
                padding: 28px 20px;
            }
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-card">
        
        <!-- Header -->
        <div class="login-header">
            <div class="logo-icon">
                <i class="fas fa-mobile-alt"></i>
            </div>
            <h1>Mobile Money</h1>
            <p>Connectez-vous avec votre numéro</p>
        </div>

        <!-- Messages -->
        <?php if (session()->has('success')): ?>
            <div class="alert alert-custom alert-success mb-3">
                <i class="fas fa-check-circle"></i>
                <?= session('success') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            <div class="alert alert-custom alert-danger mb-3">
                <i class="fas fa-exclamation-circle"></i>
                <?= session('error') ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire -->
        <form action="<?= base_url('client/do-login') ?>" method="POST">
            <?= csrf_field() ?>
            
            <div class="form-group">
                <label>
                    <i class="fas fa-phone"></i> Numéro de téléphone
                </label>
                <div class="input-group-custom">
                    <input type="tel" 
                           name="msisdn" 
                           value="<?= old('msisdn') ?>"
                           placeholder="Ex: 0331234567"
                           required>
                    <span class="input-icon">
                        <i class="fas fa-phone"></i>
                    </span>
                </div>
                <small class="text-muted" style="display: block; margin-top: 4px;">
                    <i class="fas fa-info-circle"></i> Entrez votre numéro sans espaces
                </small>
            </div>

            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt"></i> Se connecter
            </button>

            
        </form>

                 <a href="<?= base_url('admin/dashboard') ?>" class="btn-admin">
            <i class="fas fa-user-shield"></i> Se connecter en tant qu'administrateur
        </a>

        <!-- Footer -->
        <div class="login-footer">
            <p class="text-muted" style="font-size: 13px; margin: 0;">
                <i class="fas fa-info-circle"></i> Numéros de démonstration
            </p>
            
            <div class="demo-msisdn" onclick="fillMsisdn('0331234567')">
                <div class="demo-label">
                    <i class="fas fa-user"></i> Client 1
                </div>
                <div class="demo-number">033 12 34 567</div>
            </div>

            <div class="demo-msisdn" onclick="fillMsisdn('0332345678')">
                <div class="demo-label">
                    <i class="fas fa-user"></i> Client 2
                </div>
                <div class="demo-number">033 23 45 678</div>
            </div>

          
        </div>

    </div>
</div>

<script>
function fillMsisdn(number) {
    document.querySelector('input[name="msisdn"]').value = number.replace(/\s/g, '');
    
    // Effet visuel
    const cards = document.querySelectorAll('.demo-msisdn');
    cards.forEach(card => {
        card.style.borderColor = '#6C63FF';
        card.style.background = '#F0EEFF';
        setTimeout(() => {
            card.style.borderColor = '#EEF2F7';
            card.style.background = '#F8F9FA';
        }, 500);
    });
}
</script>

</body>
</html>