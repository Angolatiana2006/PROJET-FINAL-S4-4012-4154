<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExternalOperators extends Migration
{
    public function up()
    {
        // ============================================
        // 1. AJOUTER LES COLONNES À LA TABLE prefixes
        // ============================================
        $db = \Config\Database::connect();
        $fields = $db->getFieldData('prefixes');
        $existingColumns = array_column($fields, 'name');
        
        if (!in_array('is_external', $existingColumns)) {
            $this->forge->addColumn('prefixes', [
                'is_external' => [
                    'type' => 'BOOLEAN',
                    'default' => 0,
                ],
            ]);
        }
        
        if (!in_array('external_fee_percent', $existingColumns)) {
            $this->forge->addColumn('prefixes', [
                'external_fee_percent' => [
                    'type' => 'DECIMAL',
                    'constraint' => '5,2',
                    'default' => 0,
                ],
            ]);
        }
        
        if (!in_array('external_min_fee', $existingColumns)) {
            $this->forge->addColumn('prefixes', [
                'external_min_fee' => [
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'default' => 0,
                ],
            ]);
        }
        
        if (!in_array('external_max_fee', $existingColumns)) {
            $this->forge->addColumn('prefixes', [
                'external_max_fee' => [
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'default' => 0,
                ],
            ]);
        }

        // ============================================
        // 2. AJOUTER LES COLONNES À LA TABLE transactions
        // ============================================
        $fields = $db->getFieldData('transactions');
        $existingColumns = array_column($fields, 'name');
        
        if (!in_array('is_external', $existingColumns)) {
            $this->forge->addColumn('transactions', [
                'is_external' => [
                    'type' => 'BOOLEAN',
                    'default' => 0,
                ],
            ]);
        }
        
        if (!in_array('external_operator', $existingColumns)) {
            $this->forge->addColumn('transactions', [
                'external_operator' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => true,
                ],
            ]);
        }

        // ============================================
        // 3. CRÉER LA TABLE external_transactions
        // ============================================
        if (!$this->db->tableExists('external_transactions')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INTEGER',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'transaction_id' => [
                    'type' => 'INTEGER',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => false,
                ],
                'sender_id' => [
                    'type' => 'INTEGER',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => false,
                ],
                'receiver_msisdn' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'null' => false,
                ],
                'receiver_prefix' => [
                    'type' => 'VARCHAR',
                    'constraint' => 3,
                    'null' => false,
                ],
                'receiver_operator' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                    'null' => false,
                ],
                'amount' => [
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'null' => false,
                ],
                'base_fee' => [
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'null' => false,
                    'default' => 0,
                ],
                'external_fee' => [
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'null' => false,
                    'default' => 0,
                ],
                'total_fee' => [
                    'type' => 'DECIMAL',
                    'constraint' => '15,2',
                    'null' => false,
                ],
                'fee_percent' => [
                    'type' => 'DECIMAL',
                    'constraint' => '5,2',
                    'null' => false,
                    'default' => 0,
                ],
                'status' => [
                    'type' => 'VARCHAR',
                    'constraint' => 20,
                    'default' => 'completed',
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => false,
                    'default' => 'CURRENT_TIMESTAMP',
                ],
            ]);

            $this->forge->addKey('id', true);
            $this->forge->addKey('transaction_id');
            $this->forge->addKey('sender_id');
            $this->forge->addKey('receiver_prefix');
            $this->forge->addKey('created_at');
            
            $this->forge->addForeignKey('transaction_id', 'transactions', 'id', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('sender_id', 'clients', 'id', 'CASCADE', 'CASCADE');
            
            $this->forge->createTable('external_transactions');
        }
    }

    public function down()
    {
        // Supprimer la table external_transactions
        $this->forge->dropTable('external_transactions', true);
        
        // Supprimer les colonnes de transactions
        $this->forge->dropColumn('transactions', 'is_external');
        $this->forge->dropColumn('transactions', 'external_operator');
        
        // Supprimer les colonnes de prefixes
        $this->forge->dropColumn('prefixes', 'is_external');
        $this->forge->dropColumn('prefixes', 'external_fee_percent');
        $this->forge->dropColumn('prefixes', 'external_min_fee');
        $this->forge->dropColumn('prefixes', 'external_max_fee');
    }
}