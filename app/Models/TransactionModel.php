<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transaction_id', 'sender_id', 'receiver_id', 
        'operation_type', 'amount', 'fee_amount', 'total_amount',
        'status', 'description', 'created_at'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    // Types d'opérations
    const TYPE_DEPOSIT = 'deposit';
    const TYPE_WITHDRAWAL = 'withdrawal';
    const TYPE_TRANSFER = 'transfer';

    // Statuts
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETED = 'completed';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Génère un ID de transaction unique
     */
    public function generateTransactionId()
    {
        return 'TXN' . date('YmdHis') . rand(100, 999);
    }

    /**
     * Récupère les gains par période
     */
    public function getGainsByPeriod($period = 'today')
    {
        $builder = $this->where('status', self::STATUS_COMPLETED);

        switch ($period) {
            case 'today':
                $builder->where('DATE(created_at)', date('Y-m-d'));
                break;
            case 'week':
                $builder->where('created_at >=', date('Y-m-d', strtotime('-7 days')));
                break;
            case 'month':
                $builder->where('created_at >=', date('Y-m-d', strtotime('-30 days')));
                break;
            case 'year':
                $builder->where('created_at >=', date('Y-m-d', strtotime('-365 days')));
                break;
        }

        return $builder->select('
            SUM(fee_amount) as total_gains,
            COUNT(*) as total_transactions,
            SUM(amount) as total_volume,
            AVG(fee_amount) as avg_gain,
            MAX(fee_amount) as max_gain,
            MIN(fee_amount) as min_gain
        ')->first();
    }

    /**
     * Récupère les gains par type pour une période
     */
    public function getGainsByType($period = 'today')
    {
        $builder = $this->where('status', self::STATUS_COMPLETED);

        switch ($period) {
            case 'today':
                $builder->where('DATE(created_at)', date('Y-m-d'));
                break;
            case 'week':
                $builder->where('created_at >=', date('Y-m-d', strtotime('-7 days')));
                break;
            case 'month':
                $builder->where('created_at >=', date('Y-m-d', strtotime('-30 days')));
                break;
        }

        return $builder->select('
            operation_type,
            SUM(fee_amount) as total_gains,
            COUNT(*) as total_transactions,
            AVG(fee_amount) as avg_gain,
            SUM(amount) as total_volume
        ')->groupBy('operation_type')->findAll();
    }

    /**
     * Récupère les gains par jour (pour le graphique)
     */
    public function getGainsByDay($days = 7)
    {
        return $this->where('status', self::STATUS_COMPLETED)
                    ->where('created_at >=', date('Y-m-d', strtotime("-$days days")))
                    ->select('
                        DATE(created_at) as date,
                        SUM(fee_amount) as total_gains,
                        COUNT(*) as total_transactions
                    ')
                    ->groupBy('DATE(created_at)')
                    ->orderBy('date', 'ASC')
                    ->findAll();
    }

    /**
     * Récupère les transactions récentes
     */
    public function getRecentTransactions($limit = 10)
    {
        return $this->where('status', self::STATUS_COMPLETED)
                    ->orderBy('created_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Récupère les plus gros gains
     */
    public function getTopGains($limit = 5)
    {
        return $this->where('status', self::STATUS_COMPLETED)
                    ->where('fee_amount >', 0)
                    ->orderBy('fee_amount', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }

    /**
     * Récupère les statistiques globales
     */
    public function getGlobalStats()
    {
        return $this->where('status', self::STATUS_COMPLETED)
                    ->select('
                        COUNT(*) as total_transactions,
                        SUM(fee_amount) as total_gains,
                        SUM(amount) as total_volume,
                        AVG(fee_amount) as avg_gain,
                        MAX(fee_amount) as max_gain
                    ')
                    ->first();
    }

    /**
     * Récupère les statistiques par mois
     */
    public function getMonthlyStats($year = null)
    {
        $year = $year ?? date('Y');
        
        return $this->where('status', self::STATUS_COMPLETED)
                    ->where('strftime("%Y", created_at)', $year)
                    ->select('
                        strftime("%m", created_at) as month,
                        SUM(fee_amount) as total_gains,
                        COUNT(*) as total_transactions,
                        SUM(amount) as total_volume
                    ')
                    ->groupBy('strftime("%m", created_at)')
                    ->orderBy('month', 'ASC')
                    ->findAll();
    }

    /**
     * Crée une transaction
     */
    public function createTransaction($data)
    {
        $data['transaction_id'] = $data['transaction_id'] ?? $this->generateTransactionId();
        $data['total_amount'] = $data['amount'] + ($data['fee_amount'] ?? 0);
        $data['status'] = $data['status'] ?? self::STATUS_COMPLETED;
        
        return $this->insert($data);
    }

    /**
     * Récupère les opérations par type avec leurs frais
     */
    public function getOperationsWithFees($period = 'today')
    {
        $builder = $this->where('status', self::STATUS_COMPLETED)
                        ->where('fee_amount >', 0);

        switch ($period) {
            case 'today':
                $builder->where('DATE(created_at)', date('Y-m-d'));
                break;
            case 'week':
                $builder->where('created_at >=', date('Y-m-d', strtotime('-7 days')));
                break;
            case 'month':
                $builder->where('created_at >=', date('Y-m-d', strtotime('-30 days')));
                break;
        }

        return $builder->select('
            operation_type,
            COUNT(*) as count,
            SUM(fee_amount) as total_fees,
            AVG(fee_amount) as avg_fee,
            MAX(fee_amount) as max_fee,
            MIN(fee_amount) as min_fee
        ')->groupBy('operation_type')->findAll();
    }
}