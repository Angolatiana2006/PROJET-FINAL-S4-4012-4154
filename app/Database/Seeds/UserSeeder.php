<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Vider la table avant d'insérer
        $this->db->table('users')->truncate();
        
        // OU supprimer les données existantes
        // $this->db->table('users')->emptyTable();
        
        // Utilisateurs
        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@mobilemoney.com',
                'password' => password_hash('password123', PASSWORD_DEFAULT),
                'role' => 'admin',
            ],
            [
                'username' => 'user1',
                'email' => 'user1@example.com',
                'password' => password_hash('pass456', PASSWORD_DEFAULT),
                'role' => 'client',
            ],
            [
                'username' => 'client1',
                'email' => 'client1@mobilemoney.com',
                'password' => password_hash('client123', PASSWORD_DEFAULT),
                'role' => 'client',
            ],
            [
                'username' => 'client2',
                'email' => 'client2@mobilemoney.com',
                'password' => password_hash('client456', PASSWORD_DEFAULT),
                'role' => 'client',
            ],
        ];

        $this->db->table('users')->insertBatch($users);
        echo "✅ Utilisateurs insérés avec succès !\n";
    }
}