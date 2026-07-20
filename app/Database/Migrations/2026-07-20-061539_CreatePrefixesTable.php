<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrefixesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'prefix' => [
                'type' => 'VARCHAR',
                'constraint' => 3,
                'unique' => true,
            ],
            'operator_name' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'default' => 'MyMoney',
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('prefix');
        $this->forge->createTable('prefixes');
    }

    public function down()
    {
        $this->forge->dropTable('prefixes');
    }
}