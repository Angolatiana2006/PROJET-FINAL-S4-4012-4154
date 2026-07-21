<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePromotionsTable extends Migration
{
    public function up()
    {
        this->forge-> addFields([
            'id' => [
                'type' => 'INTEGER',
                'auto_increment' => true,
            ],
            'nom' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'pourcentage' => [
                'type' => 'DECIMAL',
                'constraint' => '5,20',
                'default' => 0,
            ],
            'actif' => [
                'type' => 'BOOLEAN',
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
              'updtae_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ]
            
              ]);
              $this->forge->addKey('id',true);
              $this->forge->createTable('promotions');
    }

    
}
