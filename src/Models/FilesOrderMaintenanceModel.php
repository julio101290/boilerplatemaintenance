<?php

namespace julio101290\boilerplatemaintenance\Models;

use CodeIgniter\Model;

class FilesOrderMaintenanceModel extends Model {

    protected $table = 'files_maintenance';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id', 'idEmpresa', 'idOrdenMaintenance', 'fileRoute', 'description', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;



    /**
     * Files per Order Maintenance
     */
    public function mdlGetFilesOrdersPerRow($idEmpresas, $id) {

        $result = $this->db->table('files_maintenance a')
                ->select('a.id
                         ,a.idEmpresa
                         ,a.idOrdenMaintenance
                         ,a.description
                         ,a.fileRoute
                         ,a.created_at
                         ,a.updated_at
                         ,a.deleted_at')
                ->where("idOrdenMaintenance", $id)
                ->whereIn('a.idEmpresa', $idEmpresas);

        return $result;
    }
}
