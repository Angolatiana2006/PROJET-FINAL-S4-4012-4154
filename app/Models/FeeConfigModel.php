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
     */
    public function getFee($operationType, $amount)
    {
        $result = $this->where('operation_type', $operationType)
                       ->where('min_amount <=', $amount)
                       ->where('max_amount >=', $amount)
                       ->where('is_active', true)
                       ->first();
        
        return $result ? $result['fee_amount'] : 0;
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
     * Vérifie si une tranche existe déjà
     */
    public function rangeExists($operationType, $minAmount, $maxAmount, $excludeId = null)
    {
        $builder = $this->where('operation_type', $operationType)
                        ->where('is_active', true)
                        ->groupStart()
                            ->where('min_amount <=', $minAmount)
                            ->where('max_amount >=', $minAmount)
                        ->groupEnd()
                        ->orGroupStart()
                            ->where('min_amount <=', $maxAmount)
                            ->where('max_amount >=', $maxAmount)
                        ->groupEnd()
                        ->orGroupStart()
                            ->where('min_amount >=', $minAmount)
                            ->where('max_amount <=', $maxAmount)
                        ->groupEnd();

        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }

        return $builder->countAllResults() > 0;
    }

    /**
     * Active ou désactive une configuration
     */
    public function toggleStatus($id)
    {
        $config = $this->find($id);
        if ($config) {
            $newStatus = !$config['is_active'];
            return $this->update($id, ['is_active' => $newStatus]);
        }
        return false;
    }

    /**
     * Récupère le libellé d'un type d'opération
     */
    public function getTypeLabel($type)
    {
        return self::OPERATION_TYPES[$type] ?? $type;
    }

    /**
     * Récupère tous les types avec leurs libellés
     */
    public function getTypes()
    {
        return self::OPERATION_TYPES;
    }

    /**
     * Validation des données avant insertion
     */
    public function validateData($data)
    {
        $errors = [];

        // Vérifier que min_amount < max_amount
        if ($data['min_amount'] >= $data['max_amount']) {
            $errors[] = 'Le montant minimum doit être inférieur au montant maximum';
        }

        // Vérifier que les montants sont positifs
        if ($data['min_amount'] < 0 || $data['max_amount'] < 0) {
            $errors[] = 'Les montants doivent être positifs';
        }

        // Vérifier que le type est valide
        if (!isset(self::OPERATION_TYPES[$data['operation_type']])) {
            $errors[] = 'Type d\'opération invalide';
        }

        // Vérifier les chevauchements
        if ($this->rangeExists($data['operation_type'], $data['min_amount'], $data['max_amount'], $data['id'] ?? null)) {
            $errors[] = 'Une tranche existe déjà pour cette plage de montants';
        }

        return $errors;
    }
}