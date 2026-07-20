<?php

namespace App\Models;

use CodeIgniter\Model;

class GainModel extends Model
{
    protected $table = 'operator_gains';
    protected $primaryKey = 'id';
    protected $allowedFields = ['transaction_id', 'operation_type', 'fee_amount', 'gain_date'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    /**
     * Récupère les gains par période
     */
    public function getGainsByPeriod($period = 'today')
    {
        $builder = $this;

        switch ($period) {
            case 'today':
                $builder->where('gain_date', date('Y-m-d'));
                break;
            case 'week':
                $builder->where('gain_date >=', date('Y-m-d', strtotime('-7 days')));
                break;
            case 'month':
                $builder->where('gain_date >=', date('Y-m-d', strtotime('-30 days')));
                break;
            case 'year':
                $builder->where('gain_date >=', date('Y-m-d', strtotime('-365 days')));
                break;
        }

        return $builder->select('
            SUM(fee_amount) as total_gains,
            COUNT(*) as total_transactions,
            AVG(fee_amount) as avg_gain,
            MAX(fee_amount) as max_gain,
            MIN(fee_amount) as min_gain
        ')->first();
    }

    /**
     * Récupère les gains par type
     */
    public function getGainsByType($period = 'today')
    {
        $builder = $this;

        switch ($period) {
            case 'today':
                $builder->where('gain_date', date('Y-m-d'));
                break;
            case 'week':
                $builder->where('gain_date >=', date('Y-m-d', strtotime('-7 days')));
                break;
            case 'month':
                $builder->where('gain_date >=', date('Y-m-d', strtotime('-30 days')));
                break;
        }

        return $builder->select('
            operation_type,
            SUM(fee_amount) as total_gains,
            COUNT(*) as total_transactions,
            AVG(fee_amount) as avg_gain
        ')->groupBy('operation_type')->findAll();
    }

    /**
     * Récupère les gains par jour
     */
    public function getGainsByDay($days = 7)
    {
        return $this->where('gain_date >=', date('Y-m-d', strtotime("-$days days")))
                    ->select('
                        gain_date as date,
                        SUM(fee_amount) as total_gains,
                        COUNT(*) as total_transactions
                    ')
                    ->groupBy('gain_date')
                    ->orderBy('gain_date', 'ASC')
                    ->findAll();
    }
}