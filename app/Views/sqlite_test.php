<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test SQLite - Mobile Money Simulator</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            padding: 20px;
            background: #f8f9fa;
        }
        .container {
            max-width: 1200px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .status-badge {
            padding: 8px 15px;
            border-radius: 20px;
            font-weight: bold;
        }
        .status-success {
            background: #d4edda;
            color: #155724;
        }
        .status-error {
            background: #f8d7da;
            color: #721c24;
        }
        .card {
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 20px;
        }
        .card-header {
            background: #667eea;
            color: white;
            font-weight: bold;
        }
        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #6c757d;
        }
        .icon-success {
            color: #28a745;
            font-size: 2em;
        }
        .icon-error {
            color: #dc3545;
            font-size: 2em;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Header -->
    <div class="header text-center">
        <h1>📱 Mobile Money Simulator</h1>
        <p class="lead">Test de connexion SQLite</p>
    </div>

    <?php if ($status === 'success'): ?>
        <!-- Status Success -->
        <div class="alert alert-success">
            <div class="d-flex align-items-center">
                <span class="icon-success me-3">✅</span>
                <div>
                    <strong>Connexion SQLite réussie !</strong><br>
                    La base de données fonctionne correctement.
                </div>
            </div>
        </div>

        <!-- Database Info -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-database"></i> Informations SQLite
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Version SQLite :</strong>
                                <span class="badge bg-primary"><?= $version ?? 'Non disponible' ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Nombre de tables :</strong>
                                <span class="badge bg-info"><?= count($tables ?? []) ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <strong>Nombre d'utilisateurs :</strong>
                                <span class="badge bg-success"><?= count($users ?? []) ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-table"></i> Tables disponibles
                    </div>
                    <div class="card-body">
                        <?php if (!empty($tables)): ?>
                            <ul class="list-group">
                                <?php foreach ($tables as $table): ?>
                                    <li class="list-group-item">
                                        <i class="bi bi-table text-primary me-2"></i>
                                        <?= htmlspecialchars($table->name) ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p class="text-muted">Aucune table trouvée</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header">
                <i class="bi bi-people"></i> Liste des utilisateurs
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom d'utilisateur</th>
                                <th>Email</th>
                                <th>Date de création</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($user->id) ?></td>
                                        <td><?= htmlspecialchars($user->username) ?></td>
                                        <td><?= htmlspecialchars($user->email) ?></td>
                                        <td><?= htmlspecialchars($user->created_at) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Aucun utilisateur trouvé
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    <?php else: ?>
        <!-- Status Error -->
        <div class="alert alert-danger">
            <div class="d-flex align-items-center">
                <span class="icon-error me-3">❌</span>
                <div>
                    <strong>Erreur de connexion !</strong><br>
                    <?= htmlspecialchars($message ?? 'Erreur inconnue') ?>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Commands Section -->
    <div class="card">
        <div class="card-header">
            <i class="bi bi-terminal"></i> Commandes SQLite utiles
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h6>Commandes de base :</h6>
                    <ul class="list-unstyled">
                        <li><code>sqlite3 writable/db/mobile_money.db</code> - Ouvrir la DB</li>
                        <li><code>.tables</code> - Afficher les tables</li>
                        <li><code>.schema users</code> - Structure d'une table</li>
                        <li><code>SELECT * FROM users;</code> - Voir les données</li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h6>Commandes avancées :</h6>
                    <ul class="list-unstyled">
                        <li><code>.dump > backup.sql</code> - Sauvegarder</li>
                        <li><code>PRAGMA integrity_check;</code> - Vérifier l'intégrité</li>
                        <li><code>VACUUM;</code> - Optimiser la DB</li>
                        <li><code>.quit</code> - Quitter</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>Mobile Money Simulator - CodeIgniter 4 avec SQLite</p>
        <small class="text-muted">
            <?= date('Y-m-d H:i:s') ?>
        </small>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>