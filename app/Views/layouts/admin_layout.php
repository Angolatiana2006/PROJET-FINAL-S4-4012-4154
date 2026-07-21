<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Admin Mobile Money' ?></title>
    
    <!-- Bootstrap CSS Local -->
    <link href="<?= base_url('public/assets/css/bootstrap.min.css') ?>" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ============================================
           VARIABLES
           ============================================ */
        :root {
            --primary-color: #0ca950ff;
            --primary-light: #5cca8cff;
            --primary-dark: #085d2dff;
            --secondary-color: #10B981;
            --danger-color: #EF4444;
            --warning-color: #F59E0B;
            --info-color: #0ca950ff;
            --dark-color: #111827;
            --dark-secondary: #1F2937;
            --gray-50: #F9FAFB;
            --gray-100: #F3F4F6;
            --gray-200: #E5E7EB;
            --gray-300: #D1D5DB;
            --gray-400: #9CA3AF;
            --gray-500: #6B7280;
            --gray-600: #4B5563;
            --gray-700: #374151;
            --gray-800: #1F2937;
            --gray-900: #111827;
            --light-bg: #F3F4F6;
            --card-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 1px 2px rgba(0, 0, 0, 0.04);
            --card-shadow-hover: 0 4px 12px rgba(0, 0, 0, 0.08);
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --transition: all 0.2s ease;
            --sidebar-width: 240px;
        }
        
        /* ============================================
           BASE
           ============================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: var(--gray-100);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            color: var(--gray-900);
            display: flex;
            min-height: 100vh;
        }
        
        /* ============================================
           SIDEBAR
           ============================================ */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: white;
            border-right: 1px solid var(--gray-200);
            padding: 24px 16px;
            display: flex;
            flex-direction: column;
            z-index: 1000;
            overflow-y: auto;
        }
        
        .sidebar .logo {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 8px 24px;
            border-bottom: 1px solid var(--gray-200);
            margin-bottom: 24px;
        }
        
        .sidebar .logo .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            border-radius: var(--border-radius-sm);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }
        
        .sidebar .logo .logo-text {
            font-size: 18px;
            font-weight: 700;
            color: var(--gray-900);
            letter-spacing: -0.5px;
        }
        
        .sidebar .logo .logo-text span {
            color: var(--primary-color);
        }
        
        .sidebar .nav-section {
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--gray-400);
            padding: 16px 8px 8px;
        }
        
        .sidebar .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 12px;
            border-radius: var(--border-radius-sm);
            color: var(--gray-600);
            text-decoration: none;
            transition: var(--transition);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 2px;
        }
        
        .sidebar .nav-item:hover {
            background: var(--gray-100);
            color: var(--gray-900);
        }
        
        .sidebar .nav-item.active {
            background: var(--primary-color);
            color: white;
        }
        
        .sidebar .nav-item.active i {
            color: white;
        }
        
        .sidebar .nav-item i {
            width: 20px;
            text-align: center;
            font-size: 16px;
            color: var(--gray-400);
        }
        
        .sidebar .nav-item .badge-nav {
            margin-left: auto;
            background: var(--gray-200);
            color: var(--gray-600);
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
        }
        
        .sidebar .nav-item.active .badge-nav {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }
        
        .sidebar .sidebar-footer {
            margin-top: auto;
            padding-top: 16px;
            border-top: 1px solid var(--gray-200);
        }
        
        .sidebar .sidebar-footer .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 8px 12px;
            border-radius: var(--border-radius-sm);
            transition: var(--transition);
        }
        
        .sidebar .sidebar-footer .user-info:hover {
            background: var(--gray-100);
        }
        
        .sidebar .sidebar-footer .user-info .avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }
        
        .sidebar .sidebar-footer .user-info .user-details {
            flex: 1;
        }
        
        .sidebar .sidebar-footer .user-info .user-details .name {
            font-size: 14px;
            font-weight: 600;
            color: var(--gray-900);
        }
        
        .sidebar .sidebar-footer .user-info .user-details .role {
            font-size: 12px;
            color: var(--gray-500);
        }
        
        /* ============================================
           MAIN CONTENT
           ============================================ */
        .main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            padding: 24px 32px 32px;
            min-height: 100vh;
        }
        
        /* ============================================
           TOP BAR
           ============================================ */
        .topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding: 16px 20px;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
        }
        
        .topbar .page-title h1 {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            color: var(--gray-900);
        }
        
        .topbar .page-title p {
            font-size: 14px;
            color: var(--gray-500);
            margin: 2px 0 0;
        }
        
        .topbar .topbar-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .topbar .topbar-actions .btn-action {
            padding: 8px 16px;
            border-radius: var(--border-radius-sm);
            border: 1px solid var(--gray-200);
            background: white;
            color: var(--gray-600);
            font-size: 13px;
            font-weight: 500;
            transition: var(--transition);
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        
        .topbar .topbar-actions .btn-action:hover {
            background: var(--gray-50);
            border-color: var(--gray-300);
        }
        
        .topbar .topbar-actions .btn-action.btn-danger {
            border-color: #FCA5A5;
            color: #DC2626;
            background: #FEF2F2;
        }
        
        .topbar .topbar-actions .btn-action.btn-danger:hover {
            background: #FEE2E2;
            border-color: #F87171;
        }
        
        .topbar .topbar-actions .btn-action.btn-logout {
            border-color: var(--gray-200);
            color: var(--gray-600);
        }
        
        .topbar .topbar-actions .btn-action.btn-logout:hover {
            background: #FEF2F2;
            border-color: #FCA5A5;
            color: #DC2626;
        }
        
        /* ============================================
           CARD
           ============================================ */
        .card-dashboard {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--card-shadow);
            overflow: hidden;
            margin-bottom: 20px;
            transition: var(--transition);
            border: 1px solid var(--gray-200);
        }
        
        .card-dashboard:hover {
            box-shadow: var(--card-shadow-hover);
        }
        
        .card-dashboard .card-header {
            padding: 16px 20px;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: white;
        }
        
        .card-dashboard .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 15px;
            color: var(--gray-900);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .card-dashboard .card-header h5 i {
            color: var(--primary-color);
        }
        
        .card-dashboard .card-body {
            padding: 20px;
        }
        
        /* ============================================
           STATS CARDS
           ============================================ */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }
        
        .stat-card {
            background: white;
            border-radius: var(--border-radius);
            padding: 18px 20px;
            box-shadow: var(--card-shadow);
            border: 1px solid var(--gray-200);
            transition: var(--transition);
        }
        
        .stat-card:hover {
            box-shadow: var(--card-shadow-hover);
            border-color: var(--gray-300);
        }
        
        .stat-card .stat-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }
        
        .stat-card .stat-label {
            font-size: 13px;
            font-weight: 500;
            color: var(--gray-500);
            margin: 0;
        }
        
        .stat-card .stat-number {
            font-size: 26px;
            font-weight: 700;
            margin: 4px 0 2px;
            color: var(--gray-900);
            line-height: 1.2;
        }
        
        .stat-card .stat-period {
            font-size: 12px;
            color: var(--gray-400);
        }
        
        .stat-card .stat-icon {
            font-size: 24px;
            opacity: 0.15;
            color: var(--primary-color);
        }
        
        /* ============================================
           TABLE
           ============================================ */
        .table-admin {
            margin: 0;
            font-size: 14px;
        }
        
        .table-admin thead th {
            background: var(--gray-50);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            color: var(--gray-500);
            border-bottom: 1px solid var(--gray-200);
            padding: 10px 14px;
            white-space: nowrap;
        }
        
        .table-admin tbody td {
            padding: 12px 14px;
            vertical-align: middle;
            border-bottom: 1px solid var(--gray-200);
            font-size: 14px;
        }
        
        .table-admin tbody tr:hover {
            background: var(--gray-50);
        }
        
        .table-admin tbody tr:last-child td {
            border-bottom: none;
        }
        
        /* ============================================
           BADGES
           ============================================ */
        .badge-prefix {
            background: var(--primary-color);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            display: inline-block;
        }
        
        .badge-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }
        
        .badge-status.active {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .badge-status.inactive {
            background: #FEE2E2;
            color: #991B1B;
        }
        
        .badge-status.completed {
            background: #D1FAE5;
            color: #065F46;
        }
        
        .badge-status.pending {
            background: #FEF3C7;
            color: #92400E;
        }
        
        .badge-status.failed {
            background: #FEE2E2;
            color: #991B1B;
        }
        
        /* ============================================
           BUTTONS
           ============================================ */
        .btn-action-icon {
            width: 30px;
            height: 30px;
            border-radius: var(--border-radius-sm);
            border: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
        }
        
        .btn-action-icon:hover {
            transform: scale(1.05);
        }
        
        .btn-action-icon.btn-edit {
            background: #FEF3C7;
            color: #92400E;
        }
        
        .btn-action-icon.btn-edit:hover {
            background: #FDE68A;
        }
        
        .btn-action-icon.btn-toggle {
            background: #DBEAFE;
            color: #1E40AF;
        }
        
        .btn-action-icon.btn-toggle:hover {
            background: #BFDBFE;
        }
        
        .btn-action-icon.btn-delete {
            background: #FEE2E2;
            color: #991B1B;
        }
        
        .btn-action-icon.btn-delete:hover {
            background: #FCA5A5;
        }
        
        .btn-primary-custom {
            background: var(--primary-color);
            border: none;
            border-radius: var(--border-radius-sm);
            padding: 8px 18px;
            font-weight: 600;
            font-size: 13px;
            transition: var(--transition);
            color: white;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-primary-custom:hover {
            background: var(--primary-dark);
            color: white;
        }
        
        .btn-primary-custom.btn-sm {
            padding: 5px 14px;
            font-size: 12px;
        }
        
        .btn-secondary-custom {
            background: var(--gray-200);
            border: none;
            border-radius: var(--border-radius-sm);
            padding: 8px 18px;
            color: var(--gray-700);
            font-weight: 500;
            font-size: 13px;
            transition: var(--transition);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .btn-secondary-custom:hover {
            background: var(--gray-300);
        }
        
        /* ============================================
           ALERTS
           ============================================ */
        .alert-custom {
            border-radius: var(--border-radius-sm);
            border: none;
            padding: 12px 16px;
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }
        
        .alert-custom.alert-success {
            background: #D1FAE5;
            color: #065F46;
            border-left: 4px solid #10B981;
        }
        
        .alert-custom.alert-danger {
            background: #FEE2E2;
            color: #991B1B;
            border-left: 4px solid #EF4444;
        }
        
        /* ============================================
           SWITCH
           ============================================ */
        .switch {
            position: relative;
            display: inline-block;
            width: 40px;
            height: 22px;
            flex-shrink: 0;
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
            background: var(--gray-300);
            transition: var(--transition);
            border-radius: 34px;
        }
        
        .switch .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 3px;
            bottom: 3px;
            background: white;
            transition: var(--transition);
            border-radius: 50%;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        
        .switch input:checked + .slider {
            background: var(--primary-color);
        }
        
        .switch input:checked + .slider:before {
            transform: translateX(18px);
        }
        
        /* ============================================
           RESPONSIVE
           ============================================ */
        @media (max-width: 1200px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 992px) {
            .sidebar {
                width: 72px;
                padding: 16px 8px;
            }
            
            .sidebar .logo .logo-text,
            .sidebar .nav-item span,
            .sidebar .nav-item .badge-nav,
            .sidebar .nav-section,
            .sidebar .sidebar-footer .user-info .user-details {
                display: none;
            }
            
            .sidebar .logo {
                justify-content: center;
                padding: 0 0 16px;
            }
            
            .sidebar .nav-item {
                justify-content: center;
                padding: 12px;
                font-size: 18px;
            }
            
            .sidebar .nav-item i {
                font-size: 18px;
                margin: 0;
            }
            
            .sidebar .sidebar-footer .user-info {
                justify-content: center;
            }
            
            .main-content {
                margin-left: 72px;
                padding: 16px;
            }
            
            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 12px;
            }
        }
        
        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .topbar {
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
            }
            
            .topbar .topbar-actions {
                flex-wrap: wrap;
            }
            
            .sidebar {
                width: 60px;
                padding: 12px 6px;
            }
            
            .main-content {
                margin-left: 60px;
                padding: 12px;
            }
        }
        
        @media (max-width: 480px) {
            .sidebar {
                width: 56px;
                padding: 8px 4px;
            }
            
            .sidebar .nav-item {
                padding: 10px;
                font-size: 16px;
            }
            
            .main-content {
                margin-left: 56px;
                padding: 8px;
            }
            
            .stat-card .stat-number {
                font-size: 20px;
            }
        }
        
        /* ============================================
           ANIMATIONS
           ============================================ */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .card-dashboard {
            animation: fadeInUp 0.3s ease-out;
        }
        
        .stat-card {
            animation: fadeInUp 0.3s ease-out;
        }
        
        .stat-card:nth-child(2) {
            animation-delay: 0.05s;
        }
        
        .stat-card:nth-child(3) {
            animation-delay: 0.1s;
        }
        
        .stat-card:nth-child(4) {
            animation-delay: 0.15s;
        }
        
        /* Scrollbar personnalisée */
        ::-webkit-scrollbar {
            width: 6px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--gray-100);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--gray-300);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--gray-400);
        }
    </style>
</head>
<body>

<!-- ============================================
    SIDEBAR
    ============================================ -->
<aside class="sidebar">
    <!-- Logo -->
    <div class="logo">
        <div class="logo-icon">
            <i class="fas fa-mobile-alt"></i>
        </div>
        <span class="logo-text">Mobile<span>Money</span></span>
    </div>
    
    <!-- Navigation -->
    <nav>
        <div class="nav-section">Menu</div>
        
        <a href="<?= base_url('admin/dashboard') ?>" class="nav-item <?= service('uri')->getSegment(2) === 'dashboard' ? 'active' : '' ?>">
            <i class="fas fa-chart-pie"></i>
            <span>Dashboard</span>
        </a>
        
        <a href="<?= base_url('admin/clients') ?>" class="nav-item <?= service('uri')->getSegment(2) === 'clients' ? 'active' : '' ?>">
            <i class="fas fa-users"></i>
            <span>Clients</span>
           
        </a>
        
        <a href="<?= base_url('admin/prefixes') ?>" class="nav-item <?= service('uri')->getSegment(2) === 'prefixes' ? 'active' : '' ?>">
            <i class="fas fa-tower-cell"></i>
            <span>Préfixes</span>
        </a>
        
        <a href="<?= base_url('admin/fees') ?>" class="nav-item <?= service('uri')->getSegment(2) === 'fees' ? 'active' : '' ?>">
            <i class="fas fa-coins"></i>
            <span>Barèmes</span>
        </a>
        
        <a href="<?= base_url('admin/external-operators') ?>" class="nav-item <?= service('uri')->getSegment(2) === 'external-operators' ? 'active' : '' ?>">
            <i class="fas fa-phone"></i>
            <span>Opérateurs ext.</span>
        </a>
    </nav>
    
    <!-- Footer -->
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="avatar">
                <i class="fas fa-user"></i>
            </div>
            <div class="user-details">
                <div class="name">Administrateur</div>
                <div class="role">Admin</div>
            </div>
        </div>
    </div>
</aside>

<!-- ============================================
    MAIN CONTENT
    ============================================ -->
<main class="main-content">
    
    <!-- Top Bar -->
    <header class="topbar">
        <div class="page-title">
            <h1><?= $header_title ?? 'Administration' ?></h1>
            <p><i class="far fa-clock"></i> <?= date('d/m/Y H:i') ?></p>
        </div>
        <div class="topbar-actions">
            <?= $header_actions ?? '' ?>
            <button onclick="confirmReset()" class="btn-action btn-danger">
                <i class="fas fa-trash-alt"></i> Réinitialiser
            </button>
            <a href="<?= base_url('client/login') ?>" class="btn-action btn-logout">
                <i class="fas fa-sign-out-alt"></i> Quitter
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <?= $this->renderSection('content') ?>

</main>

<!-- Bootstrap JS Local -->
<script src="<?= base_url('public/assets/js/bootstrap.bundle.min.js') ?>"></script>

<!-- Scripts section pour les pages -->
<?= $this->renderSection('scripts') ?>

<!-- Script de réinitialisation -->
<script>
function confirmReset() {
    if (confirm('⚠️ Êtes-vous sûr de vouloir réinitialiser toutes les données ?\n\nCette action supprimera :\n- Toutes les transactions\n- Toutes les transactions externes\n\nCette action est irréversible !')) {
        if (confirm('✅ Confirmation finale : Voulez-vous vraiment continuer ?')) {
            resetData();
        }
    }
}

function resetData() {
    const btn = document.querySelector('.btn-danger');
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Suppression...';
    }
    
    fetch('<?= base_url("admin/dashboard/reset") ?>', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('✅ ' + data.message);
            location.reload();
        } else {
            alert('❌ ' + data.message);
        }
    })
    .catch(error => {
        alert('❌ Erreur: ' + error);
    })
    .finally(() => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-trash-alt"></i> Réinitialiser';
        }
    });
}
</script>

</body>
</html>