lancement du projet:
php spark migrate 
php spark db:seed DatabaseSeeder
php spark serve

-VERSION 2
*4154: cote Operateur
preparation structure du repertoire
PREFIXE:
    1) test connexion base SQLite
    2) Creation table prefixe 
    3) Creation pour migration pour table prefixe
    4) Creation Seed pour inserer test
    5) Creation controller: Admin/PrefixeController
       Creation model : PrefixeModel
       Creation Views: Admin/prefixes/index.php et create.php
       Creation routes: liste,creation
BARREME:
    1)creation table configuration des baremes
    2)Creation migration pour la table
    3)Creation Seed pour inserer donnees
    1) Creation controller: Admin/FeeController
       Creation model: FeeConfigModel
       Creation Views: Admin/fees/ index.php et create.php et edit.php
       Creation routes 

DASHBOARD: Stat,transactions recentes
    1)Controller: DashboardController.php
OPERATEURS EXTERNES
    1)Ajout colonnes à prefixes
    2)Migration: external_transactions
    3)Seed: Opérateurs externes de test (032, 031)
    4)Modele: PrefixModel et ExternalTransactionModel
      Controller: Admin/ExternalOperatorController.php
      VUe:admin/external_operators/index.php
          admin/external_operators/create.php
          admin/external_operators/edit.php




*4012: cote client


Base de données
 Table clients
 Table operations
 Table frais
 Table prefixes

Contrôleur Client
 Fonction Login - Se connecter avec son numéro
 Fonction CrediterSolde - Ajouter de l'argent
 Fonction DebiterSolde - Retirer de l'argent
 Fonction Transfert - Transférer vers plusieurs destinataires
 Fonction Historique - Voir les opérations
 Fonction Dashboard - Afficher le solde
 Fonction Logout - Se déconnecter

Modèles
 ClientModel -> Rechercher client par numéro
 OperationModel -> Ajouter de l'argent
 FraisModel -> Calculer les frais
 PrefixModel -> Vérifier autre opérateur

Vues pages 
 login.php
 dashboard.php
 depot.php
 retrait.php
 transfert.php
 historique.php

Layout//pour le css
 client_layout.php