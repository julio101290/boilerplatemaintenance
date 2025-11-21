<?php

namespace julio101290\boilerplatemaintenance\Models;

use CodeIgniter\Model;

class OrderMaintenenceModel extends Model {

    protected $table = 'ordermaintenance';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $beforeInsert = ['normalizeNulls'];
    protected $beforeUpdate = ['normalizeNulls'];
    protected $allowedFields = [
        'id',
        'idEmpresa',
        'folio',
        'idUser',
        'idProduct',
        'idEmpĺoye',
        'listProducts',
        'taxes',
        'IVARetenido',
        'idEmpĺoye',
        'subTotal',
        'total',
        'balance',
        'date',
        'dateVen',
        'generalObservations',
        'quoteTo',
        'delivaryTime',
        'created_at',
        'updated_at',
        'idQuote',
        'tipoComprobanteRD',
        'folioComprobanteRD',
        'RFCReceptor',
        'usoCFDI',
        'metodoPago',
        'formaPago',
        'razonSocialReceptor',
        'codigoPostalReceptor',
        'regimenFiscalReceptor',
        'idVehiculo',
        'idChofer',
        'idSucursal',
        'idArqueoCaja',
        'tipoVehiculo',
        'esFacturaGlobal',
        'periodicidad',
        'tasaCero',
        'mes',
        'anio',
        'tipoDocumentoRelacionado',
        'UUIDRelacion',
        'UUID'
    ];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
        'idEmpresa' => 'required|integer',
        'folio' => 'required|integer',
        'idUser' => 'required|integer',
        'idProduct' => 'required|integer',
        'listProducts' => 'required|string',
        'taxes' => 'required|decimal',
        'IVARetenido' => 'required|decimal',
        'ISRRetenido' => 'required|decimal',
        'subTotal' => 'required|decimal',
        'total' => 'required|decimal',
        'balance' => 'required|decimal',
        'date' => 'required|valid_date[Y-m-d]',
        'dateVen' => 'required|valid_date[Y-m-d]',
        'generalObservations' => 'string|max_length[512]',
        'quoteTo' => 'string|max_length[512]',
        'delivaryTime' => 'permit_empty|string|max_length[512]',
        'idQuote' => 'permit_empty|integer',
        'tipoComprobanteRD' => 'permit_empty|string|max_length[4]',
        'folioComprobanteRD' => 'permit_empty|integer',
        'RFCReceptor' => 'permit_empty|string|max_length[16]',
        'usoCFDI' => 'permit_empty|string|max_length[32]',
        'metodoPago' => 'permit_empty|string|max_length[32]',
        'formaPago' => 'permit_empty|string|max_length[32]',
        'razonSocialReceptor' => 'permit_empty|string|max_length[1024]',
        'codigoPostalReceptor' => 'permit_empty|string|max_length[5]',
        'regimenFiscalReceptor' => 'permit_empty|string|max_length[32]',
        'idVehiculo' => 'permit_empty|integer',
        'idChofer' => 'permit_empty|integer',
        'idSucursal' => 'required|integer',
        'idArqueoCaja' => 'permit_empty|integer',
        'tipoVehiculo' => 'permit_empty|string|max_length[64]',
        'esFacturaGlobal' => 'permit_empty|string|in_list[on,off]',
        'periodicidad' => 'permit_empty|string|max_length[8]',
        'tasaCero' => 'permit_empty|decimal',
        'mes' => 'permit_empty|string|max_length[8]',
        'anio' => 'permit_empty|string|max_length[4]',
        'tipoDocumentoRelacionado' => 'permit_empty|string|max_length[5]',
        'UUIDRelacion' => 'permit_empty|string|max_length[40]',
        'UUID' => 'required', // <-- cambiar si no es base64, sino regex UUID estándar
    ];
    protected $validationMessages = [
        'idEmpresa' => [
            'required' => 'El campo idEmpresa es obligatorio.',
            'integer' => 'El campo idEmpresa debe ser un número entero.',
        ],
        'folio' => [
            'required' => 'El folio es obligatorio.',
            'integer' => 'El folio debe ser un número entero.',
        ],
        // ... Puedes agregar más mensajes personalizados según necesites
        'UUID' => [
            'required' => 'El UUID es obligatorio.',
            'valid_base64' => 'El UUID debe ser un identificador válido.',
        // Si quieres validar UUID estándar, mejor regex o una función custom (ver abajo)
        ],
        'esFacturaGlobal' => [
            'in_list' => 'El valor de esFacturaGlobal debe ser "on" o "off".',
        ],
    ];
    protected $skipValidation = false;

    public function mdlGetOrderMaintenance($empresas, $params = []) {
        $dbDriver = $this->db->getPlatform();

        // Expresión compatible para nombre completo del cliente
        $nameExpression = $dbDriver === 'Postgre' ? "(b.firstname || ' ' || b.lastname) AS \"nameCustumer\"" : "CONCAT(b.firstname, ' ', b.lastname) AS nameCustumer";

        $builder = $this->db->table('ordermaintenance a')
                ->select("
            a.UUID,
            a.id,
            {$nameExpression},
            a.idProduct,
            a.idEmploye,
            d.fullname as nameemploye,
            a.folio,
            a.date,
            b.email AS correoCliente,
            a.dateVen,
            a.total,
            a.taxes,
            a.subTotal,
            a.balance,
            a.delivaryTime,
            a.generalObservations,
            a.idQuote,
            a.IVARetenido,
            a.ISRRetenido,
            a.tipoComprobanteRD,
            a.folioComprobanteRD,
            a.idSucursal,
            a.RFCReceptor,
            a.usoCFDI,
            a.metodoPago,
            a.formaPago,
            a.razonSocialReceptor,
            a.codigoPostalReceptor,
            a.regimenFiscalReceptor,
            a.idVehiculo,
            a.idChofer,
            a.tipoVehiculo,
            a.idArqueoCaja,
            a.tasaCero,
            a.esFacturaGlobal,
            a.periodicidad,
            a.mes,
            a.anio,
            a.tipoDocumentoRelacionado,
            a.UUIDRelacion,
            a.created_at,
            a.updated_at,
            a.deleted_at
        ")
                
                ->join('empresas c', 'a.idEmpresa = c.id', 'left')
                ->join('employes d', 'a.idEmploye = d.id', 'left')
                ->join('custumers b', 'a.idCustumer = b.id', 'left')
                ->whereIn('a.idEmpresa', $empresas);

        // Filtros por columna
        if (!empty($params['columns'])) {
            foreach ($params['columns'] as $col) {
                if (!empty($col['search']['value'])) {
                    $builder->like($col['data'], $col['search']['value']);
                }
            }
        }

        // Ordenamiento
        if (!empty($params['order'])) {
            foreach ($params['order'] as $ord) {
                $colIndex = $ord['column'];
                $dir = $ord['dir'] ?? 'asc';
                $colName = $params['columns'][$colIndex]['data'];
                $builder->orderBy($colName, $dir);
            }
        }

        // Paginación
        if (isset($params['length']) && $params['length'] != -1) {
            $builder->limit($params['length'], $params['start']);
        }
        

        $data = $builder->get()->getResultArray();
        
        
        // Total sin filtros
        $total = $this->db->table('ordermaintenance  a')
                ->join('empresas c', 'a.idEmpresa = c.id', 'left')
                ->whereIn('a.idEmpresa', $empresas)
                ->countAllResults();

        return [
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => count($data),
        ];
    }

    /**
     * Search by filters
     */
    public function mdlGetOrderMaintenanceFilters(
            $empresas, $from, $to, $allSells,
            $empresa = 0, $sucursal = 0, $cliente = 0,
            $params = []
    ) {
        $dbDriver = $this->db->getPlatform();

        $builder = $this->db->table('sells a')
                ->select("
            a.UUID,
            a.id,
            a.idProduct,
            a.idEmpĺoye,
            b.razonSocial,
            a.folio,
            a.date,
            b.email AS correoCliente,
            a.dateVen,
            a.total,
            a.taxes,
            a.subTotal,
            a.balance,
            a.delivaryTime,
            a.generalObservations,
            a.idQuote,
            a.IVARetenido,
            a.ISRRetenido,
            a.tipoComprobanteRD,
            a.folioComprobanteRD,
            a.idSucursal,
            a.RFCReceptor,
            a.usoCFDI,
            a.metodoPago,
            a.formaPago,
            a.razonSocialReceptor,
            a.codigoPostalReceptor,
            a.regimenFiscalReceptor,
            a.idVehiculo,
            a.idChofer,
            a.tipoVehiculo,
            a.idArqueoCaja,
            a.tasaCero,
            a.esFacturaGlobal,
            a.periodicidad,
            a.mes,
            a.anio,
            a.tipoDocumentoRelacionado,
            a.UUIDRelacion,
            a.created_at,
            a.updated_at,
            a.deleted_at
        ")
                ->join('custumers b', 'a.idCustumer = b.id', 'left')
                ->join('empresas c', 'a.idEmpresa = c.id', 'left')
                ->where('a.date >=', $from . ' 00:00:00')
                ->where('a.date <=', $to . ' 23:59:59')
                ->whereIn('a.idEmpresa', $empresas);

        if ($allSells !== 'true') {
            $builder->where('a.balance >', 0);
        }

        if ($empresa != '0') {
            $builder->where('a.idEmpresa', $empresa);
        }

        if ($sucursal != '0') {
            $builder->where('a.idSucursal', $sucursal);
        }

        if ($cliente != '0') {
            $builder->where('a.idCustumer', $cliente);
        }

        // 🔎 Filtro global (search[value])
        $search = $params['search']['value'] ?? '';
        if (!empty($search)) {
            $builder->groupStart();
            foreach ($params['columns'] as $col) {
                if (!empty($col['data'])) {
                    $builder->orLike($col['data'], $search);
                }
            }
            $builder->groupEnd();
        }

        // Filtros por columna
        if (!empty($params['columns'])) {
            foreach ($params['columns'] as $col) {
                if (!empty($col['search']['value'])) {
                    $builder->like($col['data'], $col['search']['value']);
                }
            }
        }

        // Ordenamiento
        if (!empty($params['order'])) {
            foreach ($params['order'] as $ord) {
                $colIndex = $ord['column'];
                $dir = $ord['dir'] ?? 'asc';
                $colName = $params['columns'][$colIndex]['data'];
                $builder->orderBy($colName, $dir);
            }
        }

        // Paginación
        if (isset($params['length']) && $params['length'] != -1) {
            $builder->limit($params['length'], $params['start']);
        }

        $data = $builder->get()->getResultArray();

        // Conteo total sin filtros
        $totalBuilder = $this->db->table(' ordermaintenance  a')
                ->join('empresas c', 'a.idEmpresa = c.id', 'left')
                ->where('a.date >=', $from . ' 00:00:00')
                ->where('a.date <=', $to . ' 23:59:59')
                ->whereIn('a.idEmpresa', $empresas);

        if ($allSells !== 'true') {
            $totalBuilder->where('a.balance >', 0);
        }

        if ($empresa != '0') {
            $totalBuilder->where('a.idEmpresa', $empresa);
        }

        if ($sucursal != '0') {
            $totalBuilder->where('a.idSucursal', $sucursal);
        }

        if ($cliente != '0') {
            $totalBuilder->where('a.idCustumer', $cliente);
        }

        $total = $totalBuilder->countAllResults();

        return [
            'data' => $data,
            'recordsTotal' => $total,
            'recordsFiltered' => $total, // Para precisión mayor, puedes duplicar lógica de filtro en $totalBuilder
        ];
    }

    /**
     * Obtener Cotización por UUID
     */
    public function mdlGetOrderMaintenanceUUID($uuid, $empresas) {
        $dbDriver = $this->db->getPlatform();

        $result = $this->db->table('ordermaintenance a')
                ->select("
            a.idProduct,
            a.idEmpĺoye,
            a.folio,
            a.quoteTo,
            a.UUID,
            a.idUser,
            a.id,
            a.idEmpresa,
            c.nombre AS nombreEmpresa,
            a.listProducts,
            a.date,
            a.dateVen,
            a.total,
            a.taxes,
            a.IVARetenido,
            a.ISRRetenido,
            a.subTotal,
            a.tipoComprobanteRD,
            a.folioComprobanteRD,
            a.idQuote,
            a.delivaryTime,
            a.generalObservations,
            a.RFCReceptor,
            a.usoCFDI,
            a.metodoPago,
            a.formaPago,
            a.razonSocialReceptor,
            a.codigoPostalReceptor,
            a.regimenFiscalReceptor,
            a.idSucursal,
            a.idVehiculo,
            a.idChofer,
            a.tipoVehiculo,
            a.idArqueoCaja,
            a.tasaCero,
            a.esFacturaGlobal,
            a.periodicidad,
            a.mes,
            a.anio,
            a.tipoDocumentoRelacionado,
            a.UUIDRelacion,
            a.created_at,
            a.updated_at,
            a.deleted_at
        ")
                ->join('empresas c', 'a.idEmpresa = c.id', 'left')
                ->where('a.UUID', $uuid)
                ->whereIn('a.idEmpresa', $empresas)
                ->get()
                ->getRowArray();

        return $result;
    }

    /**
     * 
     * @param type $idEmpresa
     * @param type $idSucursal
     * @param type $idProducto
     * Ventas Filtradas por Empresas, Sucursales y productos
     */
    public function mdlVentasPorProductos($idEmpresa = 0
            , $idSucursal = 0
            , $idProducto = 0
            , $from = null
            , $to = null
            , $idEmpresas = null
            , $idSucursales = null
            , $idProduct = 0
    ) {



        $result = $this->db->table('ordermaintenance  a
                                    , ordermaintenancedetails  b
                                    , empresas c
                                    , branchoffices d
                                    , products e
                                    ')
                ->select('a.id
                        ,a.idEmpresa
                        ,a.idSucursal
                        ,c.nombre as nombreEmpresa
                        ,d.name as nombreSucursal
                        ,e.firstname as nombreCliente
                        ,e.lastname as apellidoCliente
                        ,e.razonSocial as razonSocialCliente
                        ,a.folio
                        ,a.date
                        ,b.idProduct
                        ,b.description
                        ,b.codeProduct
                        ,b.cant
                        ,b.price
                        ,b.porcentTax
                        ,b.tax as impuestoProducto
                        ,b.total as totalProducto
                        
                        ,a.esFacturaGlobal
                        ,a.periodicidad
                        ,a.mes
                        ,a.anio
                        ,a.tipoDocumentoRelacionado
                        ,a.UUIDRelacion
                        
                        ,a.taxes
                        ,a.subTotal
                        ,b.neto')
                ->where('a.id', 'b.idSell', FALSE)
                ->where('a.idEmpresa', 'c.id', FALSE)
                ->where('a.idSucursal', 'd.id', FALSE)
                ->groupStart()
                ->where('\'0\'', $idEmpresa, true)
                ->orWhere('a.idEmpresa', $idEmpresa)
                ->groupEnd()
                ->groupStart()
                ->where('\'0\'', $idSucursal, true)
                ->orWhere('a.idSucursal', $idSucursal)
                ->groupEnd()
                ->groupStart()
                ->where('\'0\'', $idProduct, true)
                ->orWhere('a.idProduct', $idProduct)
                ->groupEnd()
                ->groupStart()
                ->where('\'0\'', $idProducto, true)
                ->orWhere('b.idProduct', $idProducto)
                ->groupEnd()
                ->where('a.date >=', $from . ' 00:00:00')
                ->where('a.date <=', $to . ' 23:59:59')
                ->whereIn('a.idEmpresa', $idEmpresas)
                ->whereIn('a.idSucursal', $idSucursales)
                ->where('a.idProduct', 'e.id', false);

        return $result;
    }

    protected function cleanData(array $data): array {
        $allowed = $this->allowedFields;

        // Campos que aceptan null
        $nullableFields = [
            'metodoPago', 'usoCFDI', 'formaPago', 'regimenFiscalReceptor', 'anio',
            'observacionesPago', 'tipoDocumentoRelacionado', 'UUIDRelacion',
            'idVehiculo', 'idChofer', 'tipoVehiculo',
        ];

        // Limpiar datos
        $cleaned = [];

        foreach ($data as $key => $value) {
            if (!in_array($key, $allowed)) {
                // Ignorar campos no permitidos
                continue;
            }

            // Normalizar null
            if (in_array($key, $nullableFields) && ($value === '' || strtolower($value) === 'null')) {
                $cleaned[$key] = null;
                continue;
            }

            // Normalizar booleanos estilo 'off' / 'on' (solo en campo esFacturaGlobal)
            if ($key === 'esFacturaGlobal') {
                $cleaned[$key] = ($value === 'on') ? 'on' : 'off';
                continue;
            }

            // Para decimales, convertir string numérico a float
            if (in_array($key, ['subTotal', 'taxes', 'IVARetenido', 'ISRRetenido', 'total', 'balance', 'tasaCero'])) {
                $cleaned[$key] = is_numeric($value) ? (float) $value : 0.0;
                continue;
            }

            // En general asignar valor tal cual
            $cleaned[$key] = $value;
        }

        return $cleaned;
    }

    protected function normalizeNulls(array $data): array {
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $key => $value) {
                // Si es string "null", o string vacío, poner null real
                if (is_string($value) && (trim($value) === '' || strtolower(trim($value)) === 'null')) {
                    $data['data'][$key] = null;
                }
            }

            // Reemplazar nulls críticos por default seguro
            $defaults = [
                'tipoComprobanteRD' => 'I',
                'usoCFDI' => 'G03',
                'formaPago' => '99',
                'metodoPago' => 'PUE',
                'regimenFiscalReceptor' => '601' // O el que uses por defecto
            ];

            foreach ($defaults as $key => $defaultValue) {
                if (!isset($data['data'][$key]) || $data['data'][$key] === null) {
                    $data['data'][$key] = $defaultValue;
                }
            }
        }

        return $data;
    }
}
