<?php

namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    protected $table = 'clients';
    protected $primaryKey = 'id';
    protected $allowedFields = ['msisdn', 'full_name', 'email', 'balance', 'pin_code', 'status', 'user_id'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Statuts possibles
    const STATUS_ACTIVE = 'active';
    const STATUS_SUSPENDED = 'suspended';
    const STATUS_BLOCKED = 'blocked';

    /**
     * Récupère tous les clients avec leurs statistiques
     */
    public function getClientsWithStats()
    {
        $clients = $this->findAll();
        
        foreach ($clients as &$client) {
            // Récupérer la dernière transaction
            $lastTransaction = $this->getLastTransaction($client['id']);
            $client['last_transaction'] = $lastTransaction;
            
            // Récupérer le nombre de transactions
            $client['transaction_count'] = $this->getTransactionCount($client['id']);
            
            // Récupérer le total des frais générés
            $client['total_fees'] = $this->getTotalFees($client['id']);
        }
        
        return $clients;
    }

    /**
     * Récupère la dernière transaction d'un client
     */
    public function getLastTransaction($clientId)
    {
        $db = \Config\Database::connect();
        $result = $db->table('transactions')
                     ->where('sender_id', $clientId)
                     ->orWhere('receiver_id', $clientId)
                     ->orderBy('created_at', 'DESC')
                     ->limit(1)
                     ->get()
                     ->getRowArray();
        
        return $result;
    }

    /**
     * Récupère le nombre de transactions d'un client
     */
    public function getTransactionCount($clientId)
    {
        $db = \Config\Database::connect();
        return $db->table('transactions')
                  ->where('sender_id', $clientId)
                  ->orWhere('receiver_id', $clientId)
                  ->where('status', 'completed')
                  ->countAllResults();
    }

    /**
     * Récupère le total des frais générés par un client
     */
    public function getTotalFees($clientId)
    {
        $db = \Config\Database::connect();
        $result = $db->table('transactions')
                     ->select('SUM(fee_amount) as total')
                     ->where('sender_id', $clientId)
                     ->where('status', 'completed')
                     ->get()
                     ->getRowArray();
        
        return $result['total'] ?? 0;
    }

    /**
     * Récupère les statistiques globales des clients
     */
    public function getGlobalStats()
    {
        $total = $this->countAllResults();
        $active = $this->where('status', self::STATUS_ACTIVE)->countAllResults();
        $suspended = $this->where('status', self::STATUS_SUSPENDED)->countAllResults();
        $blocked = $this->where('status', self::STATUS_BLOCKED)->countAllResults();
        
        $totalBalance = $this->selectSum('balance')->get()->getRowArray()['balance'] ?? 0;
        $avgBalance = $total > 0 ? $totalBalance / $total : 0;
        
        return [
            'total' => $total,
            'active' => $active,
            'suspended' => $suspended,
            'blocked' => $blocked,
            'total_balance' => $totalBalance,
            'avg_balance' => $avgBalance,
        ];
    }

    /**
     * Récupère les clients par statut
     */
    public function getByStatus($status)
    {
        return $this->where('status', $status)->findAll();
    }

    /**
     * Recherche des clients
     */
    public function search($keyword)
    {
        return $this->like('msisdn', $keyword)
                    ->orLike('full_name', $keyword)
                    ->orLike('email', $keyword)
                    ->findAll();
    }

    /**
     * Met à jour le solde d'un client - VERSION CORRIGÉE AVEC LOGS
     */
    public function updateBalance($clientId, $amount)
    {
        // Récupérer le client
        $client = $this->find($clientId);
        
        if (!$client) {
            log_message('error', "❌ updateBalance: Client $clientId non trouvé");
            return false;
        }
        
        // Calculer le nouveau solde
        $oldBalance = (float) $client['balance'];
        $newBalance = $oldBalance + (float) $amount;
        
        log_message('debug', "📊 updateBalance - Client: $clientId, Ancien: $oldBalance, Montant: $amount, Nouveau: $newBalance");
        
        // Mettre à jour
        $result = $this->update($clientId, ['balance' => $newBalance]);
        
        if ($result) {
            log_message('debug', "✅ updateBalance - Mise à jour réussie pour le client $clientId");
        } else {
            log_message('error', "❌ updateBalance - Échec de la mise à jour pour le client $clientId");
            log_message('error', "❌ Dernière erreur DB: " . print_r($this->errors(), true));
        }
        
        return $result;
    }

    /**
     * Met à jour le solde d'un client - Version alternative avec SQL direct
     */
    public function updateBalanceDirect($clientId, $amount)
    {
        $db = \Config\Database::connect();
        $query = "UPDATE clients SET balance = balance + ? WHERE id = ?";
        $result = $db->query($query, [(float) $amount, (int) $clientId]);
        
        if ($result) {
            log_message('debug', "✅ updateBalanceDirect - Mise à jour SQL réussie pour le client $clientId");
            return true;
        } else {
            log_message('error', "❌ updateBalanceDirect - Échec de la mise à jour SQL pour le client $clientId");
            return false;
        }
    }

    /**
     * Vérifie si un client a suffisamment de solde
     */
    public function hasSufficientBalance($clientId, $amount)
    {
        $client = $this->find($clientId);
        return $client && $client['balance'] >= $amount;
    }

    /**
     * Active ou désactive un client
     */
    public function toggleStatus($id)
    {
        $client = $this->find($id);
        if ($client) {
            $newStatus = $client['status'] === self::STATUS_ACTIVE ? self::STATUS_SUSPENDED : self::STATUS_ACTIVE;
            return $this->update($id, ['status' => $newStatus]);
        }
        return false;
    }

    /**
     * Récupère les clients les plus actifs
     */
    public function getMostActive($limit = 5)
    {
        $db = \Config\Database::connect();
        return $db->table('clients c')
                  ->select('c.id, c.msisdn, c.full_name, c.balance, c.status, COUNT(t.id) as transaction_count')
                  ->join('transactions t', 'c.id = t.sender_id OR c.id = t.receiver_id', 'left')
                  ->where('t.status', 'completed')
                  ->groupBy('c.id')
                  ->orderBy('transaction_count', 'DESC')
                  ->limit($limit)
                  ->get()
                  ->getResultArray();
    }

    /**
     * Récupère les clients par solde (les plus riches)
     */
    public function getRichest($limit = 5)
    {
        return $this->orderBy('balance', 'DESC')->limit($limit)->findAll();
    }

    public function getClientByTelephone($telephone)

    {
        return $this->where('telephone', $telephone)->first();
    }

    public function debiterSolde($clientId, $montant)

    {
        $sql = "UPDATE clients SET solde = solde + ? WHERE id = ? ";
        return $this->db->query($sql, [$montant, $clientId]);
    }

    public function crediterSolde($clientId, $montant)

    {
        $sql = "UPDATE clients SET solde = solde + ? WHERE id = ? ";
        return $this->db->query($sql, [$montant, $clientId]);
    }

    public function debiterEpargne($clientId, $montant)

    {
        $sql = "UPDATE clients SET epargne = epargne - ? WHERE id = ? ";
        return $this->db->query($sql, [$montant, $clientId]);
    }

    public function crediterEpargne($clientId, $montant)

    {
        $sql = "UPDATE clients SET epargne = epargne + ? WHERE id = ? ";
        return $this->db->query($sql, [$montant, $clientId]);
    }





}