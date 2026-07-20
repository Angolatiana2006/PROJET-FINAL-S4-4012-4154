<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        echo "========================================\n";
        echo "  DÉBUT DE L'INITIALISATION DE LA BASE  \n";
        echo "========================================\n\n";
        
        // 1. Utilisateurs
        $this->call('UserSeeder');
        echo "\n";
        
        // 2. Clients
        $this->call('ClientSeeder');
        echo "\n";
        
        // 3. Préfixes
        $this->call('PrefixSeeder');
        echo "\n";
        
        // 4. Barèmes de frais
        $this->call('FeeConfigSeeder');
        echo "\n";
        
        // 5. Transactions
        $this->call('TransactionSeeder');
        echo "\n";
        
        echo "========================================\n";
        echo "  ✅ INITIALISATION TERMINÉE AVEC SUCCÈS  \n";
        echo "========================================\n";
    }
}