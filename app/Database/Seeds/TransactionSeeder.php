<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TransactionSeeder extends Seeder
{
    public function run()
    {
        echo "📊 Insertion des transactions...\n";
        
        // Vider la table
        $this->db->table('transactions')->truncate();
        
        // Transactions cohérentes avec les 2 clients (ID 1 et 2)
        $transactions = [
            // ============================================
            // Client 1 (Rakoto Jean) - ID: 1
            // ============================================
            [
                'transaction_id' => 'TXN20260720001',
                'sender_id' => null,
                'receiver_id' => 1,
                'operation_type' => 'deposit',
                'amount' => 60000,
                'fee_amount' => 100,
                'total_amount' => 60100,
                'status' => 'completed',
                'description' => 'Dépôt en agence',
                'created_at' => '2026-07-20 09:00:00',
            ],
            [
                'transaction_id' => 'TXN20260720002',
                'sender_id' => 1,
                'receiver_id' => null,
                'operation_type' => 'withdrawal',
                'amount' => 3000,
                'fee_amount' => 50,
                'total_amount' => 3050,
                'status' => 'completed',
                'description' => 'Retrait en agence',
                'created_at' => '2026-07-20 10:30:00',
            ],
            [
                'transaction_id' => 'TXN20260720003',
                'sender_id' => 1,
                'receiver_id' => 2,
                'operation_type' => 'transfer',
                'amount' => 15000,
                'fee_amount' => 150,
                'total_amount' => 15150,
                'status' => 'completed',
                'description' => 'Transfert vers Rabe Marie',
                'created_at' => '2026-07-20 14:20:00',
            ],
            [
                'transaction_id' => 'TXN20260719001',
                'sender_id' => null,
                'receiver_id' => 1,
                'operation_type' => 'deposit',
                'amount' => 25000,
                'fee_amount' => 0,
                'total_amount' => 25000,
                'status' => 'completed',
                'description' => 'Dépôt gratuit',
                'created_at' => '2026-07-19 08:00:00',
            ],
            [
                'transaction_id' => 'TXN20260718001',
                'sender_id' => 1,
                'receiver_id' => null,
                'operation_type' => 'withdrawal',
                'amount' => 5000,
                'fee_amount' => 50,
                'total_amount' => 5050,
                'status' => 'completed',
                'description' => 'Retrait distributeur',
                'created_at' => '2026-07-18 09:30:00',
            ],
            
            // ============================================
            // Client 2 (Rabe Marie) - ID: 2
            // ============================================
            [
                'transaction_id' => 'TXN20260720004',
                'sender_id' => null,
                'receiver_id' => 2,
                'operation_type' => 'deposit',
                'amount' => 100000,
                'fee_amount' => 200,
                'total_amount' => 100200,
                'status' => 'completed',
                'description' => 'Dépôt en agence',
                'created_at' => '2026-07-20 08:00:00',
            ],
            [
                'transaction_id' => 'TXN20260720005',
                'sender_id' => 2,
                'receiver_id' => null,
                'operation_type' => 'withdrawal',
                'amount' => 7500,
                'fee_amount' => 100,
                'total_amount' => 7600,
                'status' => 'completed',
                'description' => 'Retrait en agence',
                'created_at' => '2026-07-20 11:15:00',
            ],
            [
                'transaction_id' => 'TXN20260720006',
                'sender_id' => 2,
                'receiver_id' => 1,
                'operation_type' => 'transfer',
                'amount' => 2000,
                'fee_amount' => 50,
                'total_amount' => 2050,
                'status' => 'completed',
                'description' => 'Transfert vers Rakoto Jean',
                'created_at' => '2026-07-20 16:45:00',
            ],
            [
                'transaction_id' => 'TXN20260719002',
                'sender_id' => 2,
                'receiver_id' => null,
                'operation_type' => 'withdrawal',
                'amount' => 12000,
                'fee_amount' => 200,
                'total_amount' => 12200,
                'status' => 'completed',
                'description' => 'Retrait en agence',
                'created_at' => '2026-07-19 12:00:00',
            ],
            [
                'transaction_id' => 'TXN20260718002',
                'sender_id' => null,
                'receiver_id' => 2,
                'operation_type' => 'deposit',
                'amount' => 45000,
                'fee_amount' => 50,
                'total_amount' => 45050,
                'status' => 'completed',
                'description' => 'Dépôt en ligne',
                'created_at' => '2026-07-18 15:00:00',
            ],
            
            // ============================================
            // Transferts entre les 2 clients
            // ============================================
            [
                'transaction_id' => 'TXN20260717001',
                'sender_id' => 1,
                'receiver_id' => 2,
                'operation_type' => 'transfer',
                'amount' => 25000,
                'fee_amount' => 200,
                'total_amount' => 25200,
                'status' => 'completed',
                'description' => 'Transfert vers Rabe Marie',
                'created_at' => '2026-07-17 10:00:00',
            ],
            [
                'transaction_id' => 'TXN20260717002',
                'sender_id' => 2,
                'receiver_id' => 1,
                'operation_type' => 'transfer',
                'amount' => 10000,
                'fee_amount' => 100,
                'total_amount' => 10100,
                'status' => 'completed',
                'description' => 'Transfert vers Rakoto Jean',
                'created_at' => '2026-07-17 14:30:00',
            ],
        ];
        
        // Insérer les transactions
        $totalInserted = 0;
        foreach ($transactions as $transaction) {
            try {
                $this->db->table('transactions')->insert($transaction);
                $totalInserted++;
            } catch (\Exception $e) {
                echo "⚠️ Erreur: " . $e->getMessage() . "\n";
            }
        }
        
        echo "✅ " . $totalInserted . " transactions insérées !\n";
        
        // Afficher un résumé
        $stats = $this->db->table('transactions')
                          ->select('operation_type, COUNT(*) as count, SUM(fee_amount) as total_fees')
                          ->groupBy('operation_type')
                          ->get()
                          ->getResultArray();
        
        echo "\n📊 Résumé des transactions :\n";
        foreach ($stats as $stat) {
            $typeLabel = match($stat['operation_type']) {
                'deposit' => 'Dépôts',
                'withdrawal' => 'Retraits',
                'transfer' => 'Transferts',
                default => $stat['operation_type']
            };
            echo "   - $typeLabel : {$stat['count']} transactions, Frais : " . number_format($stat['total_fees'], 0) . " Ar\n";
        }
        
        $totalFees = array_sum(array_column($stats, 'total_fees'));
        echo "\n💰 Total des frais : " . number_format($totalFees, 0) . " Ar\n";
        
        // Afficher les soldes des clients
        $clients = $this->db->table('clients')->get()->getResultArray();
        echo "\n👤 Soldes des clients :\n";
        foreach ($clients as $client) {
            echo "   - {$client['full_name']} ({$client['msisdn']}) : " . number_format($client['balance'], 0) . " Ar\n";
        }
    }
}