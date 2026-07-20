<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PrefixSeeder extends Seeder
{
    public function run()
    {

        $this->db->table('prefixes')->truncate();
        $data = [
            [
                'prefix'        => '033',
                'operator_name' => 'Airtel Money',
                'is_active'     => true,
            ],
            [
                'prefix'        => '038',
                'operator_name' => 'Telma Mobile',
                'is_active'     => true,
            ],
            [
                'prefix'        => '032',
                'operator_name' => 'Orange Money',
                'is_active'     => true,
            ],
      
        ];

        $this->db->table('prefixes')->insertBatch($data);
        
        echo "✅ Préfixes insérés avec succès !\n";
    }
}