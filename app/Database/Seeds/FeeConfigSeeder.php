<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FeeConfigSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('fee_configs')->truncate();
        $data = [
            // ========================================
            // RETRAITS (Withdrawal)
            // ========================================
            ['operation_type' => 'withdrawal', 'min_amount' => 0, 'max_amount' => 1000, 'fee_amount' => 50],
            ['operation_type' => 'withdrawal', 'min_amount' => 1001, 'max_amount' => 5000, 'fee_amount' => 50],
            ['operation_type' => 'withdrawal', 'min_amount' => 5001, 'max_amount' => 10000, 'fee_amount' => 100],
            ['operation_type' => 'withdrawal', 'min_amount' => 10001, 'max_amount' => 25000, 'fee_amount' => 200],
            ['operation_type' => 'withdrawal', 'min_amount' => 25001, 'max_amount' => 50000, 'fee_amount' => 400],
            ['operation_type' => 'withdrawal', 'min_amount' => 50001, 'max_amount' => 100000, 'fee_amount' => 800],
            ['operation_type' => 'withdrawal', 'min_amount' => 100001, 'max_amount' => 250000, 'fee_amount' => 1500],
            ['operation_type' => 'withdrawal', 'min_amount' => 250001, 'max_amount' => 500000, 'fee_amount' => 1500],
            ['operation_type' => 'withdrawal', 'min_amount' => 500001, 'max_amount' => 1000000, 'fee_amount' => 2500],
            ['operation_type' => 'withdrawal', 'min_amount' => 1000001, 'max_amount' => 2000000, 'fee_amount' => 3000],
            
            // ========================================
            // TRANSFERTS (Transfer)
            // ========================================
            ['operation_type' => 'transfer', 'min_amount' => 10, 'max_amount' => 1000, 'fee_amount' => 50],
            ['operation_type' => 'transfer', 'min_amount' => 1001, 'max_amount' => 5000, 'fee_amount' => 50],
            ['operation_type' => 'transfer', 'min_amount' => 5001, 'max_amount' => 10000, 'fee_amount' => 100],
            ['operation_type' => 'transfer', 'min_amount' => 10001, 'max_amount' => 25000, 'fee_amount' => 200],
            ['operation_type' => 'transfer', 'min_amount' => 25001, 'max_amount' => 50000, 'fee_amount' => 400],
            ['operation_type' => 'transfer', 'min_amount' => 50001, 'max_amount' => 100000, 'fee_amount' => 800],
            ['operation_type' => 'transfer', 'min_amount' => 100001, 'max_amount' => 250000, 'fee_amount' => 1500],
            ['operation_type' => 'transfer', 'min_amount' => 250001, 'max_amount' => 500000, 'fee_amount' => 1500],
            ['operation_type' => 'transfer', 'min_amount' => 500001, 'max_amount' => 1000000, 'fee_amount' => 2500],
            ['operation_type' => 'transfer', 'min_amount' => 1000001, 'max_amount' => 2000000, 'fee_amount' => 3000],
            
            // ========================================
            // DÉPÔTS (Deposit) - Souvent gratuits
            // ========================================
            ['operation_type' => 'deposit', 'min_amount' => 0, 'max_amount' => 100000, 'fee_amount' => 0],
            ['operation_type' => 'deposit', 'min_amount' => 100001, 'max_amount' => 500000, 'fee_amount' => 50],
            ['operation_type' => 'deposit', 'min_amount' => 500001, 'max_amount' => 1000000, 'fee_amount' => 100],
        ];

        $this->db->table('fee_configs')->insertBatch($data);
        
        echo "✅ Barèmes de frais insérés avec succès !\n";
        echo "📊 Total : " . count($data) . " configurations\n";
    }
}