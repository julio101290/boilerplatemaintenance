<?php

namespace julio101290\boilerplatemaintenance\Models;

use CodeIgniter\Model;

class DepartamentsModel extends Model
{
    protected $table            = 'departaments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = ['id', 'idempresa', 'idsucursal', 'description', 'areamanager', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';
    protected $deletedField     = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function mdlGetDepartaments(array $idEmpresas)
    {
        return $this->db->table('departaments a')
            ->join('empresas b', 'a.idEmpresa = b.id')
            ->join('branchoffices c', 'a.idsucursal = c.id')
            ->join('users d', 'a.areamanager = d.id')
            ->select("a.id"
                    . ", a.idempresa"
                    . ", a.idsucursal"
                    . ", a.description"
                    . ", a.areamanager"
                    . ", d.username"
                    . ", c.name as nameSucursal"
                    . ", a.created_at"
                    . ", a.updated_at"
                    . ", a.deleted_at"
                    . ", b.nombre AS nombreEmpresa")
            ->whereIn('a.idEmpresa', $idEmpresas);
    }
}