<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Departaments extends Migration {

    public function up() {
        // Departaments
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'idempresa' => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'idsucursal' => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'description' => ['type' => 'varchar', 'constraint' => 250, 'null' => true],
            'areamanager' => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('departaments', true);
    }

    public function down() {
        $this->forge->dropTable('departaments', true);
    }
}
