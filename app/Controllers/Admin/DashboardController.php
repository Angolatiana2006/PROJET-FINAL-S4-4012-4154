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
        // Période sélectionnée
        $period = $this->request->getGet('period') ?? 'today';
        
        // Statistiques globales
        $globalStats = $this->transactionModel->getGlobalStats();
        
        // Gains par période
        $periodStats = $this->transactionModel->getGainsByPeriod($period);
        
        // Gains par type
        $gainsByType = $this->transactionModel->getGainsByType($period);
        
        // Gains par jour (pour le graphique)
        $gainsByDay = $this->transactionModel->getGainsByDay(7);
        
        // Transactions récentes
        $recentTransactions = $this->transactionModel->getRecentTransactions(10);
        
        // Top gains
        $topGains = $this->transactionModel->getTopGains(5);
        
        // Statistiques mensuelles
        $monthlyStats = $this->transactionModel->getMonthlyStats();

        // Statistiques internes et externes
$internalStats = $this->getInternalStats($period);
$externalStats = $this->getExternalStats($period);
$externalOperators = $this->externalTransactionModel->getStatsByOperator($period);

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
            'header_actions' => '
                <a href="'.base_url('admin/dashboard?period=today').'" class="btn btn-white '.($period == 'today' ? 'active' : '').'">
                    <i class="fas fa-calendar-day"></i> Aujourd\'hui
                </a>
                <a href="'.base_url('admin/dashboard?period=week').'" class="btn btn-white '.($period == 'week' ? 'active' : '').'">
                    <i class="fas fa-calendar-week"></i> Semaine
                </a>
                <a href="'.base_url('admin/dashboard?period=month').'" class="btn btn-white '.($period == 'month' ? 'active' : '').'">
                    <i class="fas fa-calendar-alt"></i> Mois
                </a>
                <a href="'.base_url('admin/dashboard?period=year').'" class="btn btn-white '.($period == 'year' ? 'active' : '').'">
                    <i class="fas fa-calendar-year"></i> Année
                </a>
            ',
            'period' => $period,
            'periodLabel' => $this->getPeriodLabel($period),
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

    /**
     * Libellé de la période
     */
    private function getPeriodLabel($period)
    {
        $labels = [
            'today' => "Aujourd'hui",
            'week' => 'Cette semaine',
            'month' => 'Ce mois',
            'year' => 'Cette année'
        ];
        return $labels[$period] ?? $period;
    }

    /**
 * Récupère les statistiques des transferts internes
 */
private function getInternalStats($period)
{
    $db = \Config\Database::connect();
    $builder = $db->table('transactions t')
                  ->where('t.status', 'completed')
                  ->where('t.operation_type', 'transfer')
                  ->where('t.is_external', 0);

    switch ($period) {
        case 'today':
            $builder->where('DATE(t.created_at)', date('Y-m-d'));
            break;
        case 'week':
            $builder->where('t.created_at >=', date('Y-m-d', strtotime('-7 days')));
            break;
        case 'month':
            $builder->where('t.created_at >=', date('Y-m-d', strtotime('-30 days')));
            break;
        case 'year':
            $builder->where('t.created_at >=', date('Y-m-d', strtotime('-365 days')));
            break;
    }

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
 * Récupère les statistiques des transferts externes
 */
private function getExternalStats($period)
{
    $db = \Config\Database::connect();
    $builder = $db->table('transactions t')
                  ->where('t.status', 'completed')
                  ->where('t.operation_type', 'transfer')
                  ->where('t.is_external', 1);

    switch ($period) {
        case 'today':
            $builder->where('DATE(t.created_at)', date('Y-m-d'));
            break;
        case 'week':
            $builder->where('t.created_at >=', date('Y-m-d', strtotime('-7 days')));
            break;
        case 'month':
            $builder->where('t.created_at >=', date('Y-m-d', strtotime('-30 days')));
            break;
        case 'year':
            $builder->where('t.created_at >=', date('Y-m-d', strtotime('-365 days')));
            break;
    }

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
}