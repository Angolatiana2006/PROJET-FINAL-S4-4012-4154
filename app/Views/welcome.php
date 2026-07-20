<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile Money Simulator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
            background: #f8f9fa;
        }
        .jumbotron {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        .card {
            transition: transform 0.3s;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-icon {
            font-size: 3em;
            color: #667eea;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="jumbotron text-center">
        <h1>📱 Mobile Money Simulator</h1>
        <p class="lead">Plateforme de simulation de services Mobile Money</p>
        <hr class="my-4 bg-light">
        <p>Développé avec CodeIgniter 4 et SQLite</p>
    </div>

    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="card-icon">🗄️</div>
                    <h5 class="card-title">Test SQLite</h5>
                    <p class="card-text">Tester la connexion à la base de données SQLite</p>
                    <a href="<?= base_url('sqlite-test') ?>" class="btn btn-primary">
                        Accéder au test
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="card-icon">⚡</div>
                    <h5 class="card-title">Commandes SQLite</h5>
                    <p class="card-text">Commandes utiles pour gérer SQLite</p>
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#commandsModal">
                        Voir les commandes
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-md-4 mb-3">
            <div class="card text-center h-100">
                <div class="card-body">
                    <div class="card-icon">📊</div>
                    <h5 class="card-title">Statistiques</h5>
                    <p class="card-text">Voir les statistiques de la base de données</p>
                    <a href="<?= base_url('sqlite-test') ?>" class="btn btn-info text-white">
                        Voir les stats
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <i class="bi bi-info-circle"></i> Informations du projet
                </div>
                <div class="card-body">
                    <ul>
                        <li><strong>Framework :</strong> CodeIgniter 4</li>
                        <li><strong>Base de données :</strong> SQLite 3</li>
                        <li><strong>Frontend :</strong> Bootstrap 5</li>
                        <li><strong>Langage :</strong> PHP 7.4+</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal des commandes -->
<div class="modal fade" id="commandsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Commandes SQLite</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Commandes de base :</h6>
                        <ul>
                            <li><code>sqlite3 writable/db/mobile_money.db</code></li>
                            <li><code>.tables</code> - Liste des tables</li>
                            <li><code>.schema users</code> - Structure</li>
                            <li><code>SELECT * FROM users;</code></li>
                            <li><code>.quit</code> - Quitter</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Commandes avancées :</h6>
                        <ul>
                            <li><code>.dump > backup.sql</code></li>
                            <li><code>PRAGMA integrity_check;</code></li>
                            <li><code>VACUUM;</code> - Optimisation</li>
                            <li><code>.databases</code> - Info base</li>
                        </ul>
                    </div>
                </div>
                <hr>
                <h6>Chemins importants :</h6>
                <ul>
                    <li><strong>Base de données :</strong> <code>writable/db/mobile_money.db</code></li>
                    <li><strong>Configuration :</strong> <code>app/Config/Database.php</code></li>
                    <li><strong>Migration :</strong> <code>app/Database/Migrations/</code></li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>