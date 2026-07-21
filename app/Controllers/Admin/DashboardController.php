<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\TransactionModel;
use App\Models\GainModel;
use App\Models\ExternalTransactionModel;

class DashboardController extends BaseController
{
    protected $transactionModel;
    protected $gainModel;

    public function __construct()
    {
        $this->transactionModel = new TransactionModel();
        $this->gainModel = new GainModel();
        $this->externalTransactionModel = new ExternalTransactionModel();
    }

    /**
     * Dashboard principal - Situation des gains
     */
    public function index()
    {
        // Période fixe : toutes les données
        $period = 'all';
        
        // Statistiques globales
        $globalStats = $this->transactionModel->getGlobalStats();
        
        // Gains totaux sans filtre de période
        $periodStats = $this->getAllStats();
        
        // Gains par type
        $gainsByType = $this->transactionModel->getGainsByType('all');
        
        // Gains par jour (pour le graphique) - les 7 derniers jours
        $gainsByDay = $this->transactionModel->getGainsByDay(7);
        
        // Transactions récentes
        $recentTransactions = $this->transactionModel->getRecentTransactions(10);
        
        // Top gains
        $topGains = $this->transactionModel->getTopGains(5);
        
        // Statistiques mensuelles
        $monthlyStats = $this->transactionModel->getMonthlyStats();

        // Statistiques internes et externes (toutes les données)
        $internalStats = $this->getInternalStats('all');
        $externalStats = $this->getExternalStats('all');
        $externalOperators = $this->externalTransactionModel->getStatsByOperator('all');

        // Labels pour les types d'opérations
        $typeLabels = [
            'deposit' => 'Dépôt',
            'withdrawal' => 'Retrait',
            'transfer' => 'Transfert'
        ];

        // Préparer les données pour le graphique
        $chartData = [
            'labels' => [],
            'values' => []
        ];
        foreach ($gainsByDay as $day) {
            $chartData['labels'][] = date('d/m', strtotime($day['date']));
            $chartData['values'][] = (float) $day['total_gains'];
        }

        $data = [
            'title' => 'Situation des gains',
            'header_title' => 'Gains de l\'opérateur',
            'header_actions' => '', // Pas de filtres
            'period' => $period,
            'periodLabel' => 'Toutes les données',
            'globalStats' => $globalStats,
            'periodStats' => $periodStats,
            'gainsByType' => $gainsByType,
            'typeLabels' => $typeLabels,
            'recentTransactions' => $recentTransactions,
            'topGains' => $topGains,
            'monthlyStats' => $monthlyStats,
            'chartData' => $chartData,
            'internalStats' => $internalStats,
            'externalStats' => $externalStats,
            'externalOperators' => $externalOperators,
        ];

        return view('admin/dashboard/index', $data);
    }

    /**
     * Récupère toutes les statistiques sans filtre de période
     */
    private function getAllStats()
    {
        $db = \Config\Database::connect();
        $result = $db->table('transactions')
                     ->where('status', 'completed')
                     ->select('
                         SUM(fee_amount) as total_gains,
                         COUNT(*) as total_transactions,
                         SUM(amount + fee_amount) as total_volume,
                         AVG(fee_amount) as avg_gain
                     ')
                     ->get()
                     ->getRowArray();

        return [
            'total_gains' => (float) ($result['total_gains'] ?? 0),
            'total_transactions' => (int) ($result['total_transactions'] ?? 0),
            'total_volume' => (float) ($result['total_volume'] ?? 0),
            'avg_gain' => (float) ($result['avg_gain'] ?? 0),
        ];
    }

    /**
     * Récupère les statistiques des transferts internes (toutes les données)
     */
    private function getInternalStats($period = 'all')
    {
        $db = \Config\Database::connect();
        $builder = $db->table('transactions t')
                      ->where('t.status', 'completed')
                      ->where('t.operation_type', 'transfer')
                      ->where('t.is_external', 0);

        // Pas de filtre de période

        $result = $builder->select('
            SUM(t.fee_amount) as total_gains,
            COUNT(*) as total_transactions,
            SUM(t.amount + t.fee_amount) as total_volume,
            AVG(t.fee_amount) as avg_gain
        ')->get()->getRowArray();

        return [
            'total_gains' => (float) ($result['total_gains'] ?? 0),
            'total_transactions' => (int) ($result['total_transactions'] ?? 0),
            'total_volume' => (float) ($result['total_volume'] ?? 0),
            'avg_gain' => (float) ($result['avg_gain'] ?? 0),
        ];
    }

    /**
     * Récupère les statistiques des transferts externes (toutes les données)
     */
    /**
 * Récupère les statistiques des transferts externes
 */
private function getExternalStats($period = 'all')
{
    $db = \Config\Database::connect();
    $builder = $db->table('transactions t')
                  ->where('t.status', 'completed')
                  ->where('t.operation_type', 'transfer')
                  ->where('t.is_external', 1);

    $result = $builder->select('
        SUM(t.fee_amount) as total_gains,
        COUNT(*) as total_transactions,
        SUM(t.amount + t.fee_amount) as total_volume,
        AVG(t.fee_amount) as avg_gain
    ')->get()->getRowArray();

    return [
        'total_gains' => (float) ($result['total_gains'] ?? 0),
        'total_transactions' => (int) ($result['total_transactions'] ?? 0),
        'total_volume' => (float) ($result['total_volume'] ?? 0),
        'avg_gain' => (float) ($result['avg_gain'] ?? 0),
    ];
}

    /**
 * Réinitialise les données des tables transactions et external_transactions
 */
public function resetData()
{
    // Pour les tests, on accepte toutes les requêtes
    // À REMETTRE EN PLACE POUR LA PRODUCTION
    /*
    if (!session()->has('user_id') || session()->get('role') !== 'admin') {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Non autorisé'
        ]);
    }
    */
    
    // Vérification simplifiée pour les tests
    // Si vous êtes connecté en tant que client, vous pouvez aussi réinitialiser
    if (!session()->has('client_id') && !session()->has('user_id')) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Veuillez vous connecter'
        ]);
    }

    $db = \Config\Database::connect();
    
    try {
        $db->transStart();
        
        $db->table('external_transactions')->truncate();
        $db->table('transactions')->truncate();
        
        $db->query("DELETE FROM sqlite_sequence WHERE name='transactions'");
        $db->query("DELETE FROM sqlite_sequence WHERE name='external_transactions'");
        
        $db->transComplete();
        
        log_message('info', '🗑️ Données réinitialisées');
        
        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Toutes les données ont été réinitialisées avec succès'
        ]);
        
    } catch (\Exception $e) {
        $db->transRollback();
        log_message('error', '❌ Erreur lors de la réinitialisation: ' . $e->getMessage());
        
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Erreur lors de la réinitialisation: ' . $e->getMessage()
        ]);
    }
}

    /**
     * Exporte les données en CSV
     */
    public function export()
    {
        $period = $this->request->getGet('period') ?? 'today';
        
        $transactions = $this->transactionModel
            ->where('status', 'completed')
            ->orderBy('created_at', 'DESC')
            ->findAll();

        $filename = "gains_operateur_" . date('Y-m-d') . ".csv";
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        
        $output = fopen('php://output', 'w');
        
        // Entête
        fputcsv($output, [
            'ID', 'Transaction ID', 'Type', 'Montant (Ar)', 
            'Frais (Ar)', 'Total (Ar)', 'Statut', 'Description', 'Date'
        ]);
        
        // Données
        foreach ($transactions as $row) {
            fputcsv($output, [
                $row['id'],
                $row['transaction_id'],
                $row['operation_type'],
                $row['amount'],
                $row['fee_amount'],
                $row['total_amount'],
                $row['status'],
                $row['description'],
                $row['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
}