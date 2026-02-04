<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class imageMaintenance extends Migration {

    public function up() {
        // Imagenesregistros
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'idEmpresa' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'idOrdenMaintenance' => ['type' => 'bigint', 'constraint' => 20, 'null' => true],
            'imageRoute' => ['type' => 'varchar', 'constraint' => 512, 'null' => true],
            'description' => ['type' => 'varchar', 'constraint' => 1024, 'null' => true],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('imageMaintenance', true);
    }

    public function down() {
        $this->forge->dropTable('imageMaintenance', true);
    }
}
