<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $this->db->table('clients')->truncate();
        
        $clients = [
            [
                'msisdn' => '0331234567',
                'full_name' => 'Rakoto Jean',
                'email' => 'rakoto.jean@email.com',
                'balance' => 150000.00,
                'pin_code' => '1234',
                'status' => 'active',
                'user_id' => 2,
            ],
            [
                'msisdn' => '0332345678',
                'full_name' => 'Rabe Marie',
                'email' => 'rabe.marie@email.com',
                'balance' => 250000.00,
                'pin_code' => '5678',
                'status' => 'active',
                'user_id' => 3,
            ],
        ];

        $this->db->table('clients')->insertBatch($clients);
        echo "✅ 2 clients insérés avec succès !\n";
        
        // Afficher les clients
        $clientsList = $this->db->table('clients')->get()->getResultArray();
        echo "\n📋 Liste des clients :\n";
        foreach ($clientsList as $client) {
            echo "   - ID: {$client['id']} | {$client['msisdn']} | {$client['full_name']} | Solde: " . number_format($client['balance'], 0) . " Ar\n";
        }
    }
}