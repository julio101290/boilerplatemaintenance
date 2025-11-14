<?php

namespace julio101290\boilerplatemaintenance\Models;

use CodeIgniter\Model;

class ProductEmployesModel extends Model {

    protected $table = 'employes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id'
        , 'idProduct'
        , 'idEmploye'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function mdlGetProductsEmployes(array $idEmpresas) {
        return $this->db->table('employes a')
                        ->select("
            a.fullname,
            CASE 
                WHEN EXISTS (
                    SELECT 1
                    FROM productsEmployes b
                    WHERE b.idEmploye = a.id
                ) THEN 'on'
                ELSE 'off'
            END AS status
        ")
                        ->whereIn('a.idEmpresa', $idEmpresas)
                        ->where('a.status', 1);
    }
}
