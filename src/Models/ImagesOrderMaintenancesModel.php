<?php

namespace julio101290\boilerplatemaintenance\Models;

use CodeIgniter\Model;

class ImagesOrderMaintenancesModel extends Model {

    protected $table = 'imageMaintenance';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id', 'idEmpresa', 'idOrdenMaintenance', 'imageRoute', 'description', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function mdlGetImagenesOrders($idEmpresas) {

        $result = $this->db->table('imageMaintenance a')
                ->select('a.id
                          ,a.idOrdenMaintenance
                          ,a.imageRoute
                          ,a.description
                          ,a.created_at
                          ,a.updated_at
                          ,a.deleted_at')
                ->where("id", $idEmpresas)
                ->whereIn('a.idEmpresa', $idEmpresas);

        return $result;
    }

    /**
     * Imagenes Por Registro
     */
    public function mdlGetImagesOrdersPerRow($idEmpresas,$id) {

        $result = $this->db->table('imageMaintenance a')
                ->select('a.id
                         ,a.idOrdenMaintenance
                         ,a.imageRoute
                         ,a.description
                         ,a.created_at
                         ,a.updated_at
                         ,a.deleted_at')
                ->where("idRegistro", $id)
                ->whereIn('a.idEmpresa', $idEmpresas);

        return $result;
    }
}
