<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEpargneToClients extends Migration
{
    public function up()
    {
      this->forge-> addColumn('Clients', [
            'epargne' => [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0,
                'after' => 'solde',
            ],
            'pourcentage_epargne' => [
                'type' => 'DECIMAL',
                'constraint' => '5.2',
                'default' => 0,
                'after' => 'epargne',
            ]
            ]);
              //
    }
     public function down()
     {
        $this->forge->dropcolumn('clients', 'epargne');
        $this->forge->dropcolumn('clients', 'pourcentage_epargne');
     }

    
}
