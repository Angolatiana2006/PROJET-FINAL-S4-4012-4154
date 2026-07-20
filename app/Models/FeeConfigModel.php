<?php

namespace App\Models;

use CodeIgniter\Model;

class FeeConfigModel extends Model
{
    protected $table = 'fee_configs';
    protected $primaryKey = 'id';
    protected $allowedFields = ['operation_type', 'min_amount', 'max_amount', 'fee_amount', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Types d'opérations disponibles
    const OPERATION_TYPES = [
        'deposit' => 'Dépôt',
        'withdrawal' => 'Retrait',
        'transfer' => 'Transfert'
    ];

    /**
     * Récupère les frais pour un montant et un type d'opération
     * VERSION SIMPLIFIÉE - REQUÊTE SQL DIRECTE
     */
    public function getFee($operationType, $amount)
    {
        $db = \Config\Database::connect();
        
        // Requête SQL directe pour éviter tout problème
        $sql = "SELECT fee_amount FROM fee_configs 
                WHERE operation_type = ? 
                AND ? BETWEEN min_amount AND max_amount 
                AND is_active = 1 
                LIMIT 1";
        
        $result = $db->query($sql, [$operationType, $amount])->getRow();
        
        if ($result) {
            return (float) $result->fee_amount;
        }
        
        // Si aucune tranche trouvée, retourner 0
        return 0;
    }

    /**
     * Récupère la configuration complète pour un montant
     */
    public function getFeeConfig($operationType, $amount)
    {
        $db = \Config\Database::connect();
        
        $sql = "SELECT * FROM fee_configs 
                WHERE operation_type = ? 
                AND ? BETWEEN min_amount AND max_amount 
                AND is_active = 1 
                LIMIT 1";
        
        return $db->query($sql, [$operationType, $amount])->getRowArray();
    }

    /**
     * Récupère toutes les configurations actives par type
     */
    public function getActiveByType($operationType)
    {
        return $this->where('operation_type', $operationType)
                    ->where('is_active', true)
                    ->orderBy('min_amount', 'ASC')
                    ->findAll();
    }

    /**
     * Récupère toutes les configurations par type
     */
    public function getByType($operationType)
    {
        return $this->where('operation_type', $operationType)
                    ->orderBy('min_amount', 'ASC')
                    ->findAll();
    }

    /**
     * Récupère les statistiques par type
     */
    public function getStatsByType()
    {
        $stats = [];
        foreach (array_keys(self::OPERATION_TYPES) as $type) {
            $configs = $this->where('operation_type', $type)->where('is_active', true)->findAll();
            $stats[$type] = [
                'total' => count($configs),
                'frais_min' => !empty($configs) ? min(array_column($configs, 'fee_amount')) : 0,
                'frais_max' => !empty($configs) ? max(array_column($configs, 'fee_amount')) : 0,
            ];
        }
        return $stats;
    }

    /**
     * Récupère les types d'opérations
     */
    public function getTypes()
    {
        return self::OPERATION_TYPES;
    }

    /**
     * Récupère le libellé d'un type
     */
    public function getTypeLabel($type)
    {
        return self::OPERATION_TYPES[$type] ?? $type;
    }
}