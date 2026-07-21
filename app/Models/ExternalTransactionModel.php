<?php

namespace App\Models;

use CodeIgniter\Model;

class ExternalTransactionModel extends Model
{
    protected $table = 'external_transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transaction_id', 'sender_id', 'receiver_msisdn', 
        'receiver_prefix', 'receiver_operator', 'amount',
        'base_fee', 'external_fee', 'total_fee', 'fee_percent', 'status'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = null;

    /**
     * Récupère les statistiques des transferts externes
     */
    public function getExternalStats($period = 'today')
    {
        $builder = $this->where('status', 'completed');

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
            COUNT(*) as total_transactions,
            SUM(amount) as total_amount,
            SUM(base_fee) as total_base_fee,
            SUM(external_fee) as total_external_fee,
            SUM(total_fee) as total_fee,
            AVG(external_fee) as avg_external_fee
        ')->first();
    }

 /**
 * Récupère les statistiques par opérateur externe
 */
public function getStatsByOperator($period = 'all')
{
    $db = \Config\Database::connect();
    
    $sql = "SELECT 
                receiver_prefix,
                receiver_operator,
                COUNT(*) as total_transactions,
                SUM(amount) as total_amount,
                SUM(base_fee) as total_base_fee,
                SUM(external_fee) as total_external_fee,
                SUM(total_fee) as total_fee,
                AVG(fee_percent) as avg_fee_percent
            FROM external_transactions
            GROUP BY receiver_prefix
            ORDER BY total_amount DESC";
    
    return $db->query($sql)->getResultArray();
}

    /**
     * Récupère les montants à reverser par opérateur
     */
    public function getReversalAmounts($period = 'today')
    {
        $builder = $this->where('status', 'completed');

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
            receiver_prefix,
            receiver_operator,
            SUM(amount) as total_to_reverse,
            SUM(external_fee) as total_commission,
            COUNT(*) as total_transactions
        ')->groupBy('receiver_prefix')->orderBy('total_to_reverse', 'DESC')->findAll();
    }

    /**
     * Crée une transaction externe
     */
    public function createExternalTransaction($data)
    {
        return $this->insert($data);
    }
}