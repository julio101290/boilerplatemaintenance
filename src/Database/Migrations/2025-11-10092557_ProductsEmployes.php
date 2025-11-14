<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ProductsEmployes extends Migration {

    public function up() {
        // Employes
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'idProduct' => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'idEmploye' => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('productsEmployes', true);
    }

    public function down() {
        $this->forge->dropTable('productsEmployes', true);
    }
}
