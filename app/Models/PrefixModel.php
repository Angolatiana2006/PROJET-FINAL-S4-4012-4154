<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixModel extends Model
{
    protected $table = 'prefixes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['prefix', 'operator_name', 'is_active', 'is_external', 'external_fee_percent', 'external_min_fee', 'external_max_fee'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Récupère tous les préfixes actifs
     */
    public function getActivePrefixes()
{
    return $this->where('is_active', true)
                ->where('is_external', 0)
                ->findAll();
}


public function getAllPrefixes()
{
    return $this->where('is_active', true)->findAll();
}
    /**
     * Récupère un préfixe par son code
     */
    public function getByPrefix($prefix)
    {
        return $this->where('prefix', $prefix)->first();
    }

    /**
     * Vérifie si un préfixe existe et est actif
     */
    public function isValidPrefix($prefix)
    {
        $result = $this->where('prefix', $prefix)
                       ->where('is_active', true)
                       ->first();
        return !empty($result);
    }

    // ============================================
    // NOUVELLES MÉTHODES POUR OPÉRATEURS EXTERNES
    // ============================================

    /**
     * Récupère tous les préfixes externes (autres opérateurs)
     */
    public function getExternalPrefixes()
    {
        return $this->where('is_external', true)
                    ->where('is_active', true)
                    ->orderBy('operator_name', 'ASC')
                    ->findAll();
    }

    /**
     * Récupère tous les préfixes internes (mon opérateur)
     */
    public function getInternalPrefixes()
    {
        return $this->where('is_external', false)
                    ->where('is_active', true)
                    ->findAll();
    }

    /**
     * Vérifie si un numéro appartient à un autre opérateur
     */
    public function isExternalNumber($msisdn)
    {
        $prefix = substr($msisdn, 0, 3);
        $result = $this->where('prefix', $prefix)
                       ->where('is_external', true)
                       ->where('is_active', true)
                       ->first();
        return !empty($result);
    }

    /**
     * Récupère les informations d'un opérateur externe par préfixe
     */
    public function getExternalOperator($prefix)
    {
        return $this->where('prefix', $prefix)
                    ->where('is_external', true)
                    ->first();
    }

    /**
     * Récupère la commission pour un opérateur externe
     */
    public function getExternalFeePercent($prefix)
    {
        $result = $this->where('prefix', $prefix)
                       ->where('is_external', true)
                       ->first();
        return $result ? (float) $result['external_fee_percent'] : 0;
    }

    /**
     * Calcule la commission externe pour un montant
     */
    public function calculateExternalFee($prefix, $amount)
    {
        $operator = $this->getExternalOperator($prefix);
        if (!$operator) {
            return 0;
        }
        
        $percent = (float) $operator['external_fee_percent'];
        $fee = $amount * ($percent / 100);
        
        // Appliquer les limites min et max si définies
        if (!empty($operator['external_min_fee']) && $fee < $operator['external_min_fee']) {
            $fee = $operator['external_min_fee'];
        }
        if (!empty($operator['external_max_fee']) && $fee > $operator['external_max_fee']) {
            $fee = $operator['external_max_fee'];
        }
        
        return round($fee, 2);
    }

    /**
     * Active ou désactive un préfixe
     */
    public function toggleStatus($id)
    {
        $prefix = $this->find($id);
        if ($prefix) {
            $newStatus = !$prefix['is_active'];
            return $this->update($id, ['is_active' => $newStatus]);
        }
        return false;
    }

    /**
     * Récupère les statistiques des préfixes
     */
    public function getStats()
    {
        $total = $this->countAllResults();
        $active = $this->where('is_active', true)->countAllResults();
        $inactive = $total - $active;
        $external = $this->where('is_external', true)->countAllResults();
        $internal = $total - $external;

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'external' => $external,
            'internal' => $internal,
        ];
    }

    /**
     * Valide le format d'un préfixe
     */
    public function validatePrefix($prefix)
    {
        return preg_match('/^[0-9]{3}$/', $prefix);
    }

    
}