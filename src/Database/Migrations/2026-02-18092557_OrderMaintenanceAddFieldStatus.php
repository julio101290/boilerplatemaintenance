<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class OrderMaintenanceAddStatus extends Migration {

    public function up() {
        $fields = [
            'status' => [
                'type' => 'SMALLINT',
                'null' => false,
                'default' => 1,
            ],
        ];

        $this->forge->addColumn('ordermaintenance', $fields);
    }

    public function down() {
        $this->forge->dropColumn('ordermaintenance', 'status');
    }
}
