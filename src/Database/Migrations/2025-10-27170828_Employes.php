<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Employes extends Migration {

    public function up() {
        // Employes
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'idEmpresa' => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'idBranchOffice' => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'idDepartament' => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'status' => ['type' => 'varchar', 'constraint' => 4, 'null' => true],
            'fullname' => ['type' => 'varchar', 'constraint' => 250, 'null' => true],
            'email' => ['type' => 'varchar', 'constraint' => 250, 'null' => true],
            'workstation' => ['type' => 'varchar', 'constraint' => 64, 'null' => true],
            'phone' => ['type' => 'varchar', 'constraint' => 16, 'null' => true],
            'ext' => ['type' => 'varchar', 'constraint' => 8, 'null' => true],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('employes', true);
    }

    public function down() {
        $this->forge->dropTable('employes', true);
    }
}
