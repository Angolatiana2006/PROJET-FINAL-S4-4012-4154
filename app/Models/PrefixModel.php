<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixModel extends Model
{
    protected $table = 'prefixes';
    protected $primaryKey = 'id';
    protected $allowedFields = ['prefix', 'operator_name', 'is_active'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Récupère tous les préfixes actifs
     */
    public function getActivePrefixes()
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

        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
        ];
    }

    /**
     * Valide le format d'un préfixe
     */
    public function validatePrefix($prefix)
    {
        // Doit être 3 chiffres
        if (!preg_match('/^[0-9]{3}$/', $prefix)) {
            return false;
        }
        return true;
    }
}