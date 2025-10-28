<?php

namespace julio101290\boilerplatemaintenance\Models;

use CodeIgniter\Model;

class EmployesModel extends Model {

    protected $table = 'employes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id', 'idEmpresa', 'idBranchOffice', 'idDepartament', 'status', 'fullname', 'email', 'workstation', 'phone', 'ext'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function mdlGetEmployes(array $idEmpresas) {
        return $this->db->table('employes a')
                        ->join('empresas b', 'a.idEmpresa = b.id')
                        ->join('branchoffices c', 'a.idBranchOffice = c.id')
                        ->join('departaments d', 'a.idDepartament = d.id')
                        ->select("a.id"
                                . ", a.idEmpresa"
                                . ", a.idBranchOffice"
                                . ", a.idDepartament"
                                . ", a.status"
                                . ", a.fullname"
                                . ", a.email"
                                . ", a.workstation"
                                . ", a.phone"
                                . ", a.ext"
                                . ", d.description as descripcionDepartament"
                                . ", c.name as nameBranchoffice"
                                . ", b.nombre as nombreEmpresa")
                        ->whereIn('a.idEmpresa', $idEmpresas);
    }
}
