<?php

namespace julio101290\boilerplatelocations\Models;

use CodeIgniter\Model;

class UbicacionesModel extends Model {

    protected $table = 'ubicaciones';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id'
        , 'idEmpresa'
        , 'calle'
        , 'descripcion'
        , 'numInterior'
        , 'numExterior'
        , 'colonia'
        , 'localidad'
        , 'referencia'
        , 'municipio'
        , 'estado'
        , 'pais'
        , 'codigoPostal'
        , 'RFCRemitenteDestinatario'
        , 'nombreRazonSocial'
        , 'created_at'
        , 'updated_at'
        , 'deleted_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
        'codigoPostal' => 'required|regex_match[/^[0-9]{5}$/]',
        'colonia' => 'required|numeric',
        'localidad' => 'required|numeric',
        'municipio' => 'required|numeric',
        'pais' => 'required|regex_match[/^[a-zA-Z]{3}$/]',
        'estado' => 'required|regex_match[/^[a-zA-Z]{3}$/]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function mdlGetUbicacionesServerSide($idEmpresas, $searchValue, $orderColumn, $orderDir, $start, $length) {
        $builder = $this->db->table('ubicaciones a')
                ->select('a.id
                ,a.RFCRemitenteDestinatario
                ,a.nombreRazonSocial
                ,a.idEmpresa
                ,a.calle
                ,a.descripcion
                ,a.numInterior
                ,a.numExterior
                ,a.colonia
                ,a.localidad
                ,a.referencia
                ,a.municipio
                ,a.estado
                ,a.pais
                ,a.codigoPostal
                ,a.created_at
                ,a.updated_at
                ,a.deleted_at
                ,b.nombre AS nombreEmpresa')
                ->join('empresas b', 'a.idEmpresa = b.id')
                ->whereIn('a.idEmpresa', $idEmpresas);

        // Total sin filtros
        $total = $builder->countAllResults(false);

        // Búsqueda
        if ($searchValue) {
            $builder->groupStart()
                    ->like('a.nombreRazonSocial', $searchValue)
                    ->orLike('a.RFCRemitenteDestinatario', $searchValue)
                    ->orLike('b.nombre', $searchValue)
                    ->groupEnd();
        }

        // Total con filtros
        $filtered = $builder->countAllResults(false);

        // Ordenamiento
        if ($orderColumn && $orderDir) {
            $builder->orderBy($orderColumn, $orderDir);
        }

        // Paginación
        $builder->limit($length, $start);

        // Datos
        $data = $builder->get()->getResultArray();

        return [
            'total' => $total,
            'filtered' => $filtered,
            'data' => $data
        ];
    }
}
