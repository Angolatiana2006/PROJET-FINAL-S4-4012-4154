<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ExternalOperatorSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['prefix' => '032', 'operator_name' => 'Airtel Money', 'is_active' => 1, 'is_external' => 1, 'external_fee_percent' => 2.50],
            ['prefix' => '031', 'operator_name' => 'Telma Money', 'is_active' => 1, 'is_external' => 1, 'external_fee_percent' => 2.00],
            ['prefix' => '035', 'operator_name' => 'Telma Money', 'is_active' => 0, 'is_external' => 1, 'external_fee_percent' => 2.00],
        ];

        foreach ($data as $item) {
            $exists = $this->db->table('prefixes')
                               ->where('prefix', $item['prefix'])
                               ->countAllResults();
            
            if ($exists == 0) {
                $this->db->table('prefixes')->insert($item);
                echo "✅ Ajouté : {$item['operator_name']} ({$item['prefix']})\n";
            } else {
                echo "⏭️ Déjà existant : {$item['operator_name']} ({$item['prefix']})\n";
            }
        }
    }
}