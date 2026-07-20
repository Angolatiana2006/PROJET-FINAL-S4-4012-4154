<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Mobile Money' ?></title>
    
    <!-- Bootstrap CSS Local -->
    <link href="<?= base_url('public/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('public/assets/css/admin.css') ?>" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* Style inspiré du design de l'image */
        :root {
            --primary-color: #6C63FF;
            --secondary-color: #4CAF50;
            --danger-color: #FF6B6B;
            --warning-color: #FFD93D;
            --dark-color: #2D3436;
            --light-bg: #F8F9FA;
            --card-shadow: 0 2px 16px rgba(0,0,0,0.08);
            --border-radius: 16px;
        }
        
        body {
            background: var(--light-bg);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--dark-color);
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        /* Header style wallet */
        .admin-header {
            background: linear-gradient(135deg, var(--primary-color), #5A52D5);
            border-radius: var(--border-radius);
            padding: 24px 30px;
            margin-bottom: 24px;
            color: white;
            box-shadow: var(--card-shadow);
        }
        
        .admin-header .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-header h1 {
            font-size: 22px;
            font-weight: 600;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .admin-header .header-actions {
            display: flex;
            gap: 12px;
            align-items: center;
        }
        
        .admin-header .btn-white {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 12px;
            padding: 8px 20px;
            transition: all 0.3s;
            font-size: 14px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .admin-header .btn-white:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        
        .admin-header .btn-white.active {
            background: rgba(255,255,255,0.3);
            border-color: white;
        }
        
        /* Navigation rapide dans le header */
        .header-nav {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid rgba(255,255,255,0.15);
        }
        
        .header-nav .nav-btn {
            background: rgba(255,255,255,0.12);
            color: white;
            border: 1px solid rgba(255,255,255,0.15);
            border-radius: 10px;
            padding: 6px 16px;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .header-nav .nav-btn:hover {
            background: rgba(255,255,255,0.25);
            color: white;
            transform: translateY(-1px);
        }
        
        .header-nav .nav-btn.active {
            background: rgba(255,255,255,0.25);
            border-color: white;
        }
        
        .header-nav .nav-btn i {
            font-size: 14px;
        }
        
        /* Card style */
        .admin-card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 24px;
        }
        
        .admin-card-header {
            padding: 18px 24px;
            border-bottom: 1px solid #EEF2F7;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .admin-card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 16px;
            color: var(--dark-color);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .admin-card-body {
            padding: 24px;
        }
        
        /* Stats cards */
        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 20px 24px;
            box-shadow: var(--card-shadow);
            transition: transform 0.2s;
        }
        
        .stat-card:hover {
            transform: translateY(-2px);
        }
        
        .stat-card .stat-icon {
            font-size: 28px;
            opacity: 0.7;
        }
        
        .stat-card .stat-number {
            font-size: 28px;
            font-weight: 700;
            margin: 8px 0 4px;
        }
        
        .stat-card .stat-label {
            font-size: 14px;
            color: #6C7A89;
            margin: 0;
        }
        
        /* Table style */
        .table-admin {
            margin: 0;
        }
        
        .table-admin thead th {
            background: #F8F9FA;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #6C7A89;
            border-bottom: 2px solid #EEF2F7;
            padding: 12px 16px;
        }
        
        .table-admin tbody td {
            padding: 14px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #EEF2F7;
            font-size: 14px;
        }
        
        .table-admin tbody tr:hover {
            background: #FAFBFC;
        }
        
        /* Badge styles */
        .badge-prefix {
            background: var(--primary-color);
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 15px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .badge-status {
            padding: 5px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
        }
        
        .badge-status.active {
            background: #E8F5E9;
            color: #2E7D32;
        }
        
        .badge-status.inactive {
            background: #FFEBEE;
            color: #C62828;
        }
        
        /* Button actions */
        .btn-action {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            font-size: 14px;
            cursor: pointer;
        }
        
        .btn-action.btn-edit {
            background: #FFF3E0;
            color: #E65100;
        }
        
        .btn-action.btn-edit:hover {
            background: #FFE0B2;
        }
        
        .btn-action.btn-toggle {
            background: #E3F2FD;
            color: #1565C0;
        }
        
        .btn-action.btn-toggle:hover {
            background: #BBDEFB;
        }
        
        .btn-action.btn-toggle.inactive-btn {
            background: #FFF3E0;
            color: #E65100;
        }
        
        .btn-action.btn-delete {
            background: #FFEBEE;
            color: #C62828;
        }
        
        .btn-action.btn-delete:hover {
            background: #FFCDD2;
        }
        
        /* Footer */
        .admin-footer {
            text-align: center;
            padding: 20px 0;
            color: #9EA7B3;
            font-size: 14px;
            border-top: 1px solid #EEF2F7;
            margin-top: 32px;
        }
        
        .admin-footer span {
            font-weight: 600;
            color: var(--primary-color);
        }
        
        /* Form styles */
        .form-control-custom {
            border-radius: 12px;
            border: 2px solid #EEF2F7;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        .form-control-custom:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(108, 99, 255, 0.1);
        }
        
        .form-control-custom.is-invalid {
            border-color: var(--danger-color);
        }
        
        .btn-primary-custom {
            background: var(--primary-color);
            border: none;
            border-radius: 12px;
            padding: 10px 28px;
            font-weight: 500;
            transition: all 0.3s;
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .btn-primary-custom:hover {
            background: #5A52D5;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(108, 99, 255, 0.3);
            color: white;
        }
        
        .btn-primary-custom.btn-sm {
            padding: 5px 14px;
            font-size: 13px;
        }
        
        .btn-secondary-custom {
            background: #EEF2F7;
            border: none;
            border-radius: 12px;
            padding: 10px 28px;
            color: var(--dark-color);
            font-weight: 500;
        }
        
        .btn-secondary-custom:hover {
            background: #E2E6EB;
        }
        
        .empty-state {
            padding: 48px 20px;
            text-align: center;
        }
        
        .empty-state .empty-icon {
            font-size: 48px;
            color: #D5DCE4;
            margin-bottom: 16px;
        }
        
        .empty-state h6 {
            color: #6C7A89;
            font-weight: 500;
        }
        
        /* Toggle switch */
        .switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 26px;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .switch .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: #D5DCE4;
            transition: .3s;
            border-radius: 34px;
        }
        
        .switch .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background: white;
            transition: .3s;
            border-radius: 50%;
        }
        
        .switch input:checked + .slider {
            background: var(--primary-color);
        }
        
        .switch input:checked + .slider:before {
            transform: translateX(22px);
        }
        
        /* Alert styles */
        .alert-custom {
            border-radius: 12px;
            border: none;
            padding: 14px 20px;
        }
        
        .alert-custom.alert-success {
            background: #E8F5E9;
            color: #2E7D32;
        }
        
        .alert-custom.alert-danger {
            background: #FFEBEE;
            color: #C62828;
        }
        
        /* Logout button */
        .btn-logout {
            background: rgba(255,255,255,0.15);
            color: white;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 10px;
            padding: 6px 14px;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-logout:hover {
            background: rgba(255,255,255,0.3);
            color: white;
        }
        
        @media (max-width: 768px) {
            .admin-header {
                padding: 16px 20px;
            }
            
            .admin-header .header-top {
                flex-direction: column;
                align-items: flex-start;
                gap: 8px;
            }
            
            .admin-header h1 {
                font-size: 18px;
            }
            
            .admin-card-body {
                padding: 16px;
            }
            
            .stat-card .stat-number {
                font-size: 22px;
            }
            
            .header-nav {
                gap: 6px;
            }
            
            .header-nav .nav-btn {
                font-size: 12px;
                padding: 4px 12px;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <!-- HEADER -->
        <header class="admin-header">
            <!-- Top line: Titre + Actions -->
            <div class="header-top">
                <div>
                    <h1>
                        <i class="fas fa-mobile-alt"></i>
                        <?= $header_title ?? 'Administration' ?>
                    </h1>
                </div>
                <div class="header-actions">
                    <?= $header_actions ?? '' ?>
                    <a href="<?= base_url('client/login') ?>" class="btn-logout" title="Déconnexion">
                        <i class="fas fa-sign-out-alt"></i>
                    </a>
                </div>
            </div>
            
            <!-- Navigation rapide -->
            <?php
                $currentSegment = service('uri')->getSegment(2); // 'dashboard', 'clients', 'prefixes', 'fees'
            ?>
            <nav class="header-nav">
                <a href="<?= base_url('admin/dashboard') ?>" 
                   class="nav-btn <?= $currentSegment === 'dashboard' ? 'active' : '' ?>">
                    <i class="fas fa-chart-pie"></i> Dashboard
                </a>
                <a href="<?= base_url('admin/clients') ?>" 
                   class="nav-btn <?= $currentSegment === 'clients' ? 'active' : '' ?>">
                    <i class="fas fa-users"></i> Clients
                </a>
                <a href="<?= base_url('admin/prefixes') ?>" 
                   class="nav-btn <?= $currentSegment === 'prefixes' ? 'active' : '' ?>">
                    <i class="fas fa-tower-cell"></i> Préfixes
                </a>
                <a href="<?= base_url('admin/fees') ?>" 
                   class="nav-btn <?= $currentSegment === 'fees' ? 'active' : '' ?>">
                    <i class="fas fa-coins"></i> Barèmes
                </a>
                <a href="<?= base_url('admin/external-operators') ?>" 
                   class="nav-btn <?= $currentSegment === 'external-operators' ? 'active' : '' ?>">
                    <i class="fas fa-coins"></i> les autres operateurs
                </a>
                
           
            </nav>
        </header>

        <!-- MAIN CONTENT -->
        <main>
            <?= $this->renderSection('content') ?>
        </main>

        <!-- FOOTER -->
        <footer class="admin-footer">
            ETU4012-4154
        </footer>
    </div>

    <!-- Bootstrap JS Local -->
    <script src="<?= base_url('public/assets/js/bootstrap.bundle.min.js') ?>"></script>
    
    <!-- Scripts section pour les pages -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>