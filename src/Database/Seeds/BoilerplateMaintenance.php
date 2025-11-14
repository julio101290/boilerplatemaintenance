<?php

namespace julio101290\boilerplatemaintenance\Database\Seeds;

use CodeIgniter\Config\Services;
use CodeIgniter\Database\Seeder;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

/**
 * Class BoilerplateSeeder.
 */
class BoilerplateMaintenance extends Seeder {

    /**
     * @var Authorize
     */
    protected $authorize;

    /**
     * @var Db
     */
    protected $db;

    /**
     * @var Users
     */
    protected $users;

    public function __construct() {
        $this->authorize = Services::authorization();
        $this->db = \Config\Database::connect();
        $this->users = new UserModel();
    }

    public function run() {


        // Permission
        $this->authorize->createPermission('departaments-permission', 'Permiso para la lista de departaments');

        $this->authorize->createPermission('productsemploye-permission', 'Permiso ASignar un producto a un empleado');

        // Assign Permission to user
        $this->authorize->addPermissionToUser('departaments-permission', 1);

        $this->authorize->addPermissionToUser('productsemploye-permission', 1);

        // Permission
        $this->authorize->createPermission('employes-permission', 'Permiso para la lista de empleados');

        // Assign Permission to user
        $this->authorize->addPermissionToUser('employes-permission', 1);

        $this->authorize->addPermissionToUser('employes-permission', 1);
    }

    public function down() {
        //
    }
}
