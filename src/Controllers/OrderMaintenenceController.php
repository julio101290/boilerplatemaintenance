<?php

namespace julio101290\boilerplatemaintenance\Controllers;

use App\Controllers\BaseController;
use julio101290\boilerplateproducts\Models\ProductsModel;
use \App\Models\UserModel;
use julio101290\boilerplatelog\Models\LogModel;
use julio101290\boilerplatemaintenance\Models\OrderMaintenenceModel;
use julio101290\boilerplatemaintenance\Models\OrderMaintenanceDetailsModel;
use julio101290\boilerplatestorages\Models\StoragesModel;
use CodeIgniter\API\ResponseTrait;
use julio101290\boilerplatecompanies\Models\EmpresasModel;
use julio101290\boilerplatecustumers\Models\CustumersModel;
use julio101290\boilerplatesells\Models\PaymentsModel;
use julio101290\boilerplatecomprobanterd\Models\Comprobantes_rdModel;
use julio101290\boilerplatevehicles\Models\VehiculosModel;
use julio101290\boilerplatedrivers\Models\ChoferesModel;
use julio101290\boilerplatevehicles\Models\TipovehiculoModel;
use julio101290\boilerplatebranchoffice\Models\BranchofficesModel;
use julio101290\boilerplatecashtonnage\Models\ArqueoCajaModel;
use julio101290\boilerplateinventory\Models\SaldosModel;
use julio101290\boilerplatesells\Models\EnlacexmlModel;
use julio101290\boilerplateCFDI\Models\XmlModel;
use julio101290\boilerplateCFDI\Controllers\XmlController;
use julio101290\boilerplatesuppliers\Controllers\ProveedoresController;
use julio101290\boilerplateproducts\Models\{
    FieldsExtraProductosModel
};
use julio101290\boilerplatemaintenance\Models\ProductEmployesModel;
use julio101290\boilerplateinventory\Models\DataExtraFieldsBalanceModel;
use julio101290\boilerplatemaintenance\Models\EmployesModel;

class OrderMaintenenceController extends BaseController {

    use ResponseTrait;

    protected $log;
    protected $orderMaintenance;
    protected $storages;
    protected $orderDetailsMaintenance;
    protected $sucursales;
    protected $empresa;
    protected $user;
    protected $custumer;
    protected $payments;
    protected $products;
    protected $quotes;
    protected $comprobantesRD;
    protected $vehiculos;
    protected $choferes;
    protected $tiposVehiculo;
    protected $arqueoCaja;
    protected $saldos;
    protected $xmlEnlace;
    protected $enlaceXML;
    protected $xml;
    protected $xmlController;
    protected $suppliers;
    protected $fieldsExtraProductos;
    protected $fieldsExtraValues;
    protected $productsEmploye;
    protected $employe;

    public function __construct() {
        $this->log = new LogModel();

        $this->orderMaintenance = new OrderMaintenenceModel();
        $this->orderDetailsMaintenance = new OrderMaintenanceDetailsModel();
        $this->empresa = new EmpresasModel();
        $this->user = new UserModel();
        $this->custumer = new CustumersModel();
        $this->payments = new PaymentsModel();
        $this->products = new ProductsModel();
        $this->comprobantesRD = new Comprobantes_rdModel();
        $this->vehiculos = new VehiculosModel();
        $this->choferes = new ChoferesModel();
        $this->tiposVehiculo = new TipovehiculoModel();
        $this->sucursales = new BranchofficesModel();
        $this->arqueoCaja = new ArqueoCajaModel();
        $this->saldos = new SaldosModel();
        $this->xmlEnlace = new EnlacexmlModel();
        $this->enlaceXML = new EnlacexmlModel();
        $this->xml = new XmlModel();
        $this->xmlController = new XmlController();
        $this->suppliers = new \julio101290\boilerplatesuppliers\Models\ProveedoresModel();
        $this->fieldsExtraProductos = new FieldsExtraProductosModel();
        $this->fieldsExtraValues = new DataExtraFieldsBalanceModel();
        $this->employe = new EmployesModel();

        $this->productsEmploye = new ProductEmployesModel();
        helper('menu');
        helper('utilerias');
    }

    public function index() {


        $auth = service('authentication');

        if (!$auth->check()) {

            return redirect()->route('admin');
        }


        helper('auth');

        $idUser = user()->id;

        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        if ($this->request->isAJAX()) {


            $params = [
                'draw' => $this->request->getGet('draw'),
                'start' => $this->request->getGet('start'),
                'length' => $this->request->getGet('length'),
                'order' => $this->request->getGet('order'),
                'columns' => $this->request->getGet('columns'),
            ];

            $datos = $this->orderMaintenance->mdlGetOrderMaintenance($empresasID, $params);

            return $this->response->setJSON([
                        'draw' => intval($params['draw']),
                        'recordsTotal' => $datos['recordsTotal'],
                        'recordsFiltered' => $datos['recordsFiltered'],
                        'data' => $datos['data'],
            ]);
        }




        $tiposVehiculo = $this->tiposVehiculo->mdlGetTipovehiculoArray($empresasID);

        $titulos["title"] = lang('ordersMaintenance.title');
        $titulos["subtitle"] = lang('ordersMaintenance.subtitle');

        return view('julio101290\boilerplatemaintenance\Views\listOrderMaintenance', $titulos);
    }

    public function ordersMaintenanceFilters($desdeFecha, $hastaFecha, $todas, $empresa, $sucursal, $cliente) {


        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }


        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        if ($this->request->isAJAX()) {

            $params = [
                'draw' => $this->request->getGet('draw'),
                'start' => $this->request->getGet('start'),
                'length' => $this->request->getGet('length'),
                'order' => $this->request->getGet('order'),
                'columns' => $this->request->getGet('columns'),
            ];

            $datos = $this->sells->mdlGetSellsFilters(
                    $empresasID, $desdeFecha, $hastaFecha, $todas,
                    $empresa, $sucursal, $cliente,
                    $params
            );

            return $this->response->setJSON([
                        'draw' => intval($params['draw']),
                        'recordsTotal' => $datos['recordsTotal'],
                        'recordsFiltered' => $datos['recordsFiltered'],
                        'data' => $datos['data'],
            ]);
        }
    }

    public function orderMaintenanceListFilters($desdeFecha, $hastaFecha, $todas, $empresa, $sucursal, $cliente) {


        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }


        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        if ($this->request->isAJAX()) {

            $params = [
                'draw' => $this->request->getGet('draw'),
                'start' => $this->request->getGet('start'),
                'length' => $this->request->getGet('length'),
                'order' => $this->request->getGet('order'),
                'columns' => $this->request->getGet('columns'),
            ];

            $datos = $this->sells->mdlGetSellsFilters($empresasID, $desdeFecha, $hastaFecha, $todas, $empresa, $sucursal, $cliente, $params);

            return $this->response->setJSON([
                        'draw' => intval($params['draw']),
                        'recordsTotal' => $datos['recordsTotal'],
                        'recordsFiltered' => $datos['recordsFiltered'],
                        'data' => $datos['data'],
            ]);
        }

        $titulos["desdeFecha"] = $desdeFecha;
        $titulos["hastaFecha"] = $hastaFecha;
        $titulos["todas"] = $todas;
        $titulos["empresa"] = $empresa;
        $titulos["sucursal"] = $sucursal;
        $titulos["cliente"] = $cliente;

        return view('listOrderMaintenance', $titulos);
    }

    /**
     * 
     * @param type $desdeFecha
     * @param type $hastaFecha
     * @param type $todas
     * @return type
     * 
     * Get Report Sells per products
     */
    public function sellsReport($idEmpresa = 0
            , $idSucursal = 0
            , $idProducto = 0
            , $from = null
            , $to = null
            , $cliente = 0) {


        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }


        helper('auth');

        $idUser = user()->id;

        /**
         * Vemos las Empresa a la que tiene acceso
         */
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        /**
         * Vemos a las sucursales a las que tiene accesio
         */
        $sucursales = $this->sucursales->mdlSucursalesPorUsuario($idUser);

        if (count($sucursales) == "0") {

            $sucursalesID[0] = "0";
        } else {

            $sucursalesID = array_column($sucursales, "id");
        }


        if ($this->request->isAJAX()) {


            // Parámetros DataTables
            $draw = intval($this->request->getVar('draw'));
            $start = intval($this->request->getVar('start'));
            $length = intval($this->request->getVar('length'));
            $searchValue = $this->request->getVar('search')['value'] ?? '';
            $order = $this->request->getVar('order');
            $columns = $this->request->getVar('columns');

            // Parámetros personalizados para filtrar
            // Obtener query base sin paginar
            $queryBuilder = $this->sells->mdlVentasPorProductos(
                    $idEmpresa, $idSucursal, $idProducto,
                    $from, $to,
                    $empresasID, $sucursalesID,
                    $cliente
            );

            // Total registros sin filtros de búsqueda
            $recordsTotal = $queryBuilder->countAllResults(false); // false para no resetear la query
            // Aplicar búsqueda global si viene
            if (!empty($searchValue)) {
                $queryBuilder->groupStart();
                foreach ($columns as $col) {
                    if ($col['searchable'] == 'true') {
                        // El campo a buscar (puede venir en data o name)
                        $field = $col['data'];
                        $queryBuilder->orLike($field, $searchValue);
                    }
                }
                $queryBuilder->groupEnd();
            }

            // Total registros filtrados (con búsqueda)
            $recordsFiltered = $queryBuilder->countAllResults(false);

            // Aplicar orden
            if (!empty($order)) {
                foreach ($order as $ord) {
                    $colIdx = intval($ord['column']);
                    $dir = $ord['dir'] === 'asc' ? 'ASC' : 'DESC';

                    if (isset($columns[$colIdx]) && $columns[$colIdx]['orderable'] == 'true') {
                        $orderColumn = $columns[$colIdx]['data'];
                        $queryBuilder->orderBy($orderColumn, $dir);
                    }
                }
            }

            // Aplicar paginación
            if ($length != -1) { // -1 = sin límite
                $queryBuilder->limit($length, $start);
            }

            // Obtener datos
            $data = $queryBuilder->get()->getResultArray();

            // Armar respuesta
            $response = [
                "draw" => $draw,
                "recordsTotal" => $recordsTotal,
                "recordsFiltered" => $recordsFiltered,
                "data" => $data
            ];

            return $this->response->setJSON($response);
        }
    }

    public function generaPDFDesdeVenta($uuidVenta) {

        // buscamos el id de la venta

        $datosVenta = $this->sells->select("*")->where("UUID", $uuidVenta)->first();

        //Buscamo el uuid del xml en xml enlazados

        $enlaceXML = $this->enlaceXML->select("*")
                        ->where("idDocumento", $datosVenta["id"])
                        ->where("tipo", "ven")->first();

        $archivo = $this->xmlController->generarPDF($enlaceXML["uuidXML"], true);

        echo $archivo;
        $this->response->setHeader("Content-Type", "application/pdf");
    }

    public function newOrderMaintenance() {
        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }

        $authorize = $auth = service('authorization');
        $permisoAgregarArticulo = $authorize->hasPermission('capturaarticulodesdeventa', $idUser);

        $fechaActual = fechaMySQLADateHTML5(fechaHoraActual());

        $idMax = "0";

        $titulos["idMax"] = $idMax;
        $titulos["idOrderMaintenance"] = $idMax;
        $titulos["folio"] = "0";
        $titulos["fecha"] = $fechaActual;
        $titulos["userName"] = $userName;
        $titulos["idUser"] = $idUser;
        $titulos["contact"] = "";
        $titulos["idQuote"] = "0";
        $titulos["codeCustumer"] = "";
        $titulos["observations"] = "";
        $titulos["taxes"] = "0.00";
        $titulos["IVARetenido"] = "0.00";
        $titulos["ISRRetenido"] = "0.00";
        $titulos["subTotal"] = "0.00";
        $titulos["total"] = "0.00";
        $titulos["formaPago"] = $this->catalogosSAT->formasDePago40()->searchByField("texto", "%%", 99999);
        $titulos["usoCFDI"] = $this->catalogosSAT->usosCfdi40()->searchByField("texto", "%%", 99999);
        $titulos["metodoPago"] = $this->catalogosSAT->metodosDePago40()->searchByField("texto", "%%", 99999);
        $titulos["regimenFiscal"] = $this->catalogosSAT->regimenesFiscales40()->searchByField("texto", "%%", 99999);

        $titulos["RFCReceptor"] = "";
        $titulos["regimenFiscalReceptor"] = "";
        $titulos["usoCFDIReceptor"] = "";
        $titulos["metodoPagoReceptor"] = "";
        $titulos["formaPagoReceptor"] = "";
        $titulos["razonSocialReceptor"] = "";
        $titulos["codigoPostalReceptor"] = "";

        $titulos["permisoAgregarArticulo"] = $permisoAgregarArticulo;

        $titulos["folioComprobanteRD"] = "0";

        $titulos["uuid"] = generaUUID();

        $titulos["uuidRelacion"] = "";

        $tiposVehiculo = $this->tiposVehiculo->mdlGetTipovehiculoArray($empresasID);

        $titulos["title"] = lang('newOrderMaintenance.title');
        $titulos["subtitle"] = lang('newOrderMaintenance.subtitle');
        $titulos["tiposVehiculo"] = $tiposVehiculo;

        $titulos["totalExento"] = "0";

        return view('julio101290\boilerplatemaintenance\Views\newOrderMaintenance', $titulos);
    }

    public function reportSellsProducts() {
        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }




        $titulos["title"] = lang('newSell.sellsReportsTitle');
        $titulos["subtitle"] = lang('newSell.sellsReportsSubTitle');

        return view('julio101290\boilerplatesells\Views\reportSellsProducts', $titulos);
    }

    public function getXMLEnlazados($uuidVenta) {

        try {

            $datosVenta = $this->sells->select("*")->where("UUID", $uuidVenta)->first();

            if (isset($datosVenta)) {

                $request = service('request');
                $db = \Config\Database::connect();

                $columns = ['a.id', 'a.idDocumento', 'a.uuidXML', 'a.tipo', 'a.importe', 'c.status', 'c.archivoXML', 'a.created_at', 'a.updated_at', 'a.deleted_at'];

                // === FROM y JOIN ===
                $builder = $db->table('enlacexml a');
                $builder->join('xml c', 'c.uuidTimbre = a.uuidXML');

                // === WHERE principal ===
                $builder->where('a.idDocumento', $datosVenta["id"]);

                // === Total sin filtro ===
                $total = $builder->countAllResults(false); // no reset
                // === Búsqueda global ===
                $searchValue = $request->getPost('search')['value'] ?? '';
                if ($searchValue) {
                    $builder->groupStart();
                    foreach ($columns as $col) {
                        $builder->orLike($col, $searchValue);
                    }
                    $builder->groupEnd();
                }

                // === Total filtrado ===
                $filtered = $builder->countAllResults(false);

                // === Ordenamiento ===
                $orderColumnIndex = $request->getPost('order')[0]['column'] ?? 0;
                $orderColumn = $columns[$orderColumnIndex] ?? 'a.id';
                $orderDir = $request->getPost('order')[0]['dir'] ?? 'asc';
                $builder->orderBy($orderColumn, $orderDir);

                // === Paginación ===
                $length = $request->getPost('length') ?? 10;
                $start = $request->getPost('start') ?? 0;
                $builder->limit($length, $start);

                // === Ejecutar y devolver ===
                $query = $builder->get();
                $data = $query->getResultArray();

                return $this->response->setJSON([
                            'draw' => intval($request->getPost('draw')),
                            'recordsTotal' => $total,
                            'recordsFiltered' => $filtered,
                            'data' => $data
                ]);
            } else {

                $datos = $this->enlaceXML
                        ->select('id,idDocumento,uuidXML,tipo,importe')
                        ->where('idDocumento', 0)
                        ->findAll();

                return $this->response->setJSON([
                            'data' => $datos
                ]);
            }
        } catch (Exception $ex) {

            return $ex->getMessage();
        }
    }

    /**
     * Get Last Code
     */
    public function getLastCode() {

        $idEmpresa = $this->request->getPost("idEmpresa");
        $idSucursal = $this->request->getPost("idSucursal");
        $result = $this->sells->selectMax("folio")
                ->where("idEmpresa", $idEmpresa)
                ->where("idSucursal", $idSucursal)
                ->first();

        if ($result["folio"] == null) {

            $result["folio"] = 1;
        } else {

            $result["folio"] = $result["folio"] + 1;
        }

        echo json_encode($result);
    }

    /**
     * Get Last Code
     */
    public function getLastCodeInterno($idEmpresa, $idSucursal) {


        $result = $this->orderMaintenance->selectMax("folio")
                ->where("idEmpresa", $idEmpresa)
                ->where("idSucursal", $idSucursal)
                ->first();

        if ($result["folio"] == null) {

            $result["folio"] = 1;
        } else {

            $result["folio"] = $result["folio"] + 1;
        }

        return $result["folio"];
    }

    /*
     * Editar Cotizacion
     */

    public function editOrder($uuid) {

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }


        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }

        $authorize = $auth = service('authorization');
        $permisoAgregarArticulo = $authorize->hasPermission('capturaarticulodesdeventa', $idUser);

        $order = $this->orderMaintenance->mdlGetOrderMaintenanceUUID($uuid, $empresasID);

        $listProducts = json_decode($order["listProducts"], true);

        $titulos["idOrder"] = $order["id"];
        $titulos["folio"] = $order["folio"];
        $titulos["idCustumer"] = $order["idCustumer"];

        $order["nameCustumer"] = "";

        $dataSupplier = $this->suppliers->where("id", $order["idCustumer"])->first();

        $titulos["nameCustumer"] = "Seleccione  Proveedor";

        if (isset($dataSupplier)) {

            $titulos["nameCustumer"] = $dataSupplier["firstname"];
        }


        $titulos["idEmpresa"] = $order["idEmpresa"];
        $titulos["nombreEmpresa"] = $order["nombreEmpresa"];

        $titulos["idUser"] = $idUser;
        $titulos["userName"] = $userName;
        $titulos["listProducts"] = $listProducts;
        $titulos["idProduct"] = $order["idProduct"];

        $dataProduct = $this->saldos->where("id", $order["idProduct"])->first();

        if (isset($dataProduct)) {

            $titulos["nameProduct"] = $dataProduct["lote"];
        }


        $titulos["taxes"] = number_format($order["taxes"], 2, ".");
        $titulos["IVARetenido"] = number_format($order["IVARetenido"], 2, ".");
        $titulos["ISRRetenido"] = number_format($order["ISRRetenido"], 2, ".");
        $titulos["subTotal"] = number_format($order["subTotal"], 2, ".");
        $titulos["total"] = number_format($order["total"], 2, ".");
        $titulos["fecha"] = $order["date"];
        $titulos["dateVen"] = $order["dateVen"];
        $titulos["quoteTo"] = $order["quoteTo"];
        $titulos["observations"] = $order["generalObservations"];
        $titulos["uuid"] = $order["UUID"];
        $titulos["idQuote"] = $order["idQuote"];
        $titulos["formaPago"] = $this->catalogosSAT->formasDePago40()->searchByField("texto", "%%", 99999);
        $titulos["usoCFDI"] = $this->catalogosSAT->usosCfdi40()->searchByField("texto", "%%", 99999);
        $titulos["metodoPago"] = $this->catalogosSAT->metodosDePago40()->searchByField("texto", "%%", 99999);
        $titulos["regimenFiscal"] = $this->catalogosSAT->regimenesFiscales40()->searchByField("texto", "%%", 99999);

        $titulos["RFCReceptor"] = $order["RFCReceptor"];
        $titulos["regimenFiscalReceptor"] = $order["regimenFiscalReceptor"];
        $titulos["usoCFDIReceptor"] = $order["usoCFDI"];
        $titulos["metodoPagoReceptor"] = $order["metodoPago"];
        $titulos["formaPagoReceptor"] = $order["formaPago"];
        $titulos["razonSocialReceptor"] = $order["razonSocialReceptor"];
        $titulos["codigoPostalReceptor"] = $order["codigoPostalReceptor"];
        $titulos["permisoAgregarArticulo"] = $permisoAgregarArticulo;

        $titulos["totalExento"] = $order["tasaCero"];

        $titulos["idVehiculo"] = $order["idVehiculo"];

        $titulos["uuidRelacion"] = $order["UUIDRelacion"];

        $datosVehiculo = $this->vehiculos->select("*")->where("id", $order["idVehiculo"])->first();

        $titulos["vehiculoNombre"] = $order["idVehiculo"];
        $datosVehiculo = $this->vehiculos->select("*")->where("id", $order["idVehiculo"])->first();

        if (isset($datosVehiculo["descripcion"])) {

            $titulos["vehiculoNombre"] = $order["tipoVehiculo"] . " " . $datosVehiculo["placas"] . " " . $datosVehiculo["descripcion"];
        } else {

            $titulos["vehiculoNombre"] = "Seleccione Vehiculo";
        }


        $titulos["idChofer"] = $order["idChofer"];

        $datosChofer = $this->choferes->select("*")->where("id", $order["idChofer"])->first();

        if (isset($datosChofer["nombre"])) {

            $titulos["choferNombre"] = $datosChofer["nombre"] . " " . $datosChofer["Apellido"];
        } else {

            $titulos["choferNombre"] = "Seleccione Chofer";
        }


        $titulos["tipoVehiculo"] = $order["tipoVehiculo"];
        $tiposVehiculo = $this->tiposVehiculo->mdlGetTipovehiculoArray($empresasID);

        $titulos["tiposVehiculo"] = $tiposVehiculo;

        $titulos["idSucursal"] = $order["idSucursal"];
        $sucursal = $this->sucursales->select("*")->where("id", $titulos["idSucursal"])->first();
        $titulos["nombreSucursal"] = $sucursal["key"] . " " . $sucursal["name"];

        if (isset($order["tipoComprobanteRD"]) && is_numeric($order["tipoComprobanteRD"]) && $order["tipoComprobanteRD"] > 0) {

            $comprobante = $this->comprobantesRD->find($order["tipoComprobanteRD"]);
            $titulos["folioComprobanteRD"] = $order["folioComprobanteRD"];
            $titulos["tipoComprobanteRDID"] = $comprobante["id"];
            $titulos["tipoComprobanteRDNombre"] = $comprobante["nombre"];
            $titulos["tipoComprobanteRDPrefijo"] = $comprobante["prefijo"];
        } else {

            $titulos["folioComprobanteRD"] = "0";
            $titulos["tipoComprobanteRDID"] = "0";
            $titulos["tipoComprobanteRDNombre"] = "0";
            $titulos["tipoComprobanteRDPrefijo"] = "0";
        }
        $titulos["title"] = "Editar Orden de Mantenimiento";
        $titulos["subtitle"] = "Edición de Ordenes de Mantenimiento";

        return view('julio101290\boilerplatemaintenance\Views\newOrderMaintenance', $titulos);
    }

    /*
     * Save or Update
     */

    public function save() {

        $auth = service('authentication');

        if (!$auth->check()) {
            $this->session->set('redirect_url', current_url());
            return redirect()->route('admin');
        }

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $datos = $this->request->getPost();

        $this->orderMaintenance->db->transBegin();

        $existsOrder = $this->orderMaintenance->where("UUID", $datos["UUID"])->countAllResults();

        $listProducts = json_decode($datos["listProducts"], true);

        $datosSucursal = $this->sucursales->find($datos["idSucursal"]);

        /**
         * if is new order
         */
        if ($existsOrder == 0) {


            $ultimoFolio = $this->getLastCodeInterno($datos["idEmpresa"], $datos["idSucursal"]);

            $empresa = $this->empresa->find($datos["idEmpresa"]);

            if ($datos["tipoComprobanteRD"] != "")
                $comprobante = $this->comprobantesRD->find($datos["tipoComprobanteRD"]);

            if ($empresa["facturacionRD"] == "on") {


                if ($datos["tipoComprobanteRD"] == "") {

                    $this->orderMaintenance->db->transRollback();

                    echo "No se selecciono tipo comprobante";
                    return;
                }


                if ($datos["folioComprobanteRD"] == "") {

                    $this->orderMaintenance->db->transRollback();

                    echo "No hay folio Comprobante";
                    return;
                }


                if ($datos["folioComprobanteRD"] > $comprobante["folioFinal"]) {

                    $this->orderMaintenance->db->transRollback();

                    echo "Se agotaron los folio son hasta  $comprobante[folioFinal] y van en $datos[folioComprobanteRD]";
                    return;
                }

                if ($datos["folioComprobanteRD"] < $comprobante["folioInicial"]) {

                    $this->orderMaintenance->db->transRollback();

                    echo "Folio fuera de rango  $comprobante[folioInicial] y van en $datos[folioComprobanteRD]";
                    return;
                }


                if ($datos["date"] < $comprobante["desdeFecha"]) {

                    $this->orderMaintenance->db->transRollback();

                    echo "fecha fuera de rango limite inferior $comprobante[desdeFecha] fecha venta $datos[date]";
                    return;
                }


                if ($datos["date"] > $comprobante["hastaFecha"]) {

                    $this->orderMaintenance->db->transRollback();

                    echo "fecha fuera de rango,  limite superior $comprobante[desdeFecha]  fecha venta $datos[date]";
                    return;
                }
            }


            $datos["folio"] = $ultimoFolio;

            $datos["balance"] = $datos["total"] - ($datos["importPayment"] - $datos["importBack"]);

            try {

                $datos1 = array_intersect_key($datos, array_flip($this->orderMaintenance->allowedFields));
                $datos1["tipoComprobanteRD"] = "";
                if ($this->orderMaintenance->insert($datos1) === false) {

                    $errores = $this->orderMaintenance->errors();

                    $listErrors = "";

                    foreach ($errores as $field => $error) {

                        $listErrors .= $error . " ";
                    }

                    echo $listErrors;

                    return;
                }

                $idOrderInserted = $this->orderMaintenance->getInsertID();

                // save datail

                foreach ($listProducts as $key => $value) {

                    $datosDetalle["idOrder"] = $idOrderInserted;
                    $datosDetalle["idProduct"] = $value["idProduct"];
                    $datosDetalle["description"] = $value["description"];
                    $datosDetalle["unidad"] = $value["unidad"];
                    $datosDetalle["codeProduct"] = $value["codeProduct"];
                    $datosDetalle["cant"] = $value["cant"];
                    $datosDetalle["price"] = $value["price"];
                    $datosDetalle["porcentTax"] = $value["porcentTax"];

                    $datosDetalle["porcentIVARetenido"] = $value["porcentIVARetenido"];
                    $datosDetalle["porcentISRRetenido"] = $value["porcentISRRetenido"];
                    $datosDetalle["IVARetenido"] = $value["IVARetenido"];
                    $datosDetalle["ISRRetenido"] = $value["ISRRetenido"];

                    $datosDetalle["claveProductoSAT"] = $value["claveProductoSAT"];
                    $datosDetalle["claveUnidadSAT"] = $value["claveUnidadSAT"];

                    $datosDetalle["lote"] = $value["lote"];
                    $datosDetalle["idAlmacen"] = $value["idAlmacen"];

                    $datosDetalle["tax"] = $value["tax"];
                    $datosDetalle["total"] = $value["total"];
                    $datosDetalle["importeExento"] = $value["importeExento"];
                    $datosDetalle["neto"] = $value["neto"];

                    $datosDetalle["predial"] = $value["predial"];

                    //Valida Stock
                    $products = $this->products->find($datosDetalle["idProduct"]);

                    if ($this->orderDetailsMaintenance->save($datosDetalle) === false) {

                        echo "error al insertar el producto $datosDetalle[idProducto]";

                        $this->orderDetailsMaintenance->db->transRollback();
                        return;
                    }
                }









                $datosBitacora["description"] = "Se guardo la orden de mantenimiento con los siguientes datos" . json_encode($datos);
                $datosBitacora["user"] = $userName;

                $this->log->save($datosBitacora);

                $this->orderDetailsMaintenance->db->transCommit();
                echo "Guardado Correctamente";
            } catch (\PHPUnit\Framework\Exception $ex) {


                echo "Error al guardar " . $ex->getMessage();
            }
        } else {




            $backOrder = $this->orderMaintenance->where("UUID", $datos["UUID"])->first();
            $listProductsBack = json_decode($backOrder["listProducts"], true);

            $datos["folio"] = $backOrder["folio"];

            $datos["balance"] = $datos["total"];

            if ($this->orderMaintenance->update($backOrder["id"], $datos) == false) {

                $errores = $this->orderMaintenance->errors();
                $listError = "";
                foreach ($errores as $field => $error) {

                    $listError .= $error . " ";
                }

                echo $listError;

                return;
            } else {



                //DEJAMOS EL STOCK COMO ESTABA ANTES

                foreach ($listProductsBack as $key => $value) {

                    //BUSCAMOS STOCK DEL PRODUCTO
                    $products = $this->products->find($value["idProduct"]);
                }

                $this->orderDetailsMaintenance->select("*")->where("idSell", $backOrder["id"])->delete();
                $this->orderDetailsMaintenance->purgeDeleted();
                foreach ($listProducts as $key => $value) {

                    $datosDetalle["idOrder"] = $backOrder["id"];
                    $datosDetalle["idProduct"] = $value["idProduct"];
                    $datosDetalle["description"] = $value["description"];
                    $datosDetalle["unidad"] = $value["unidad"];
                    $datosDetalle["codeProduct"] = $value["codeProduct"];
                    $datosDetalle["cant"] = $value["cant"];
                    $datosDetalle["price"] = $value["price"];
                    $datosDetalle["porcentTax"] = $value["porcentTax"];

                    $datosDetalle["porcentIVARetenido"] = $value["porcentIVARetenido"];
                    $datosDetalle["porcentISRRetenido"] = $value["porcentISRRetenido"];
                    $datosDetalle["IVARetenido"] = $value["IVARetenido"];
                    $datosDetalle["ISRRetenido"] = $value["ISRRetenido"];

                    $datosDetalle["claveProductoSAT"] = $value["claveProductoSAT"];
                    $datosDetalle["claveUnidadSAT"] = $value["claveUnidadSAT"];
                    $datosDetalle["lote"] = $value["lote"];
                    $datosDetalle["idAlmacen"] = $value["idAlmacen"];

                    $datosDetalle["tax"] = $value["tax"];
                    $datosDetalle["total"] = $value["total"];
                    $datosDetalle["neto"] = $value["neto"];

                    if ($this->orderDetailsMaintenance->save($datosDetalle) === false) {

                        $errores = $this->orderDetailsMaintenanceDetail->errors();
                        $listError = "";
                        foreach ($errores as $field => $error) {

                            $listError .= $error . " ";
                        }

                        echo "error al insertar el producto $datosDetalle[idProduct] $errores";

                        $this->orderDetailsMaintenance->db->transRollback();
                        return;
                    }
                }


                $datosBitacora["description"] = "Se actualizo" . json_encode($datos) .
                        " Los datos anteriores son" . json_encode($backOrder);
                $datosBitacora["user"] = $userName;
                $this->log->save($datosBitacora);

                echo "Actualizado Correctamente";
                $this->orderMaintenance->db->transCommit();
                return;
            }
        }

        return;
    }

    public function delete($id) {
        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }


        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }



        /**
         * 
         */
        if ($this->orderMaintenance->select("*")->whereIn("idEmpresa", $empresasID)->where("id", $id)->countAllResults() == 0) {

            return $this->failNotFound('Acceso Prohibido');
        }








        $this->orderMaintenance->db->transBegin();

        $infoOrder = $this->orderMaintenance->find($id);

        if (!$found = $this->orderMaintenance->delete($id)) {
            $this->orderMaintenance->db->transRollback();
            return $this->failNotFound('Error al eliminar');
        }

        //Borramos quotesdetails

        if ($this->orderDetailsMaintenance->select("*")->where("idOrderMaintenanceDetails", $id)->delete() === false) {

            $this->orderDetailsMaintenance->db->transRollback();
            return $this->failNotFound('Error al eliminar el detalle');
        }

        $this->orderDetailsMaintenance->purgeDeleted();
        $this->orderMaintenance->purgeDeleted();

        $datosBitacora["description"] = 'Se elimino el Registro' . json_encode($infoOrder);

        $this->log->save($datosBitacora);

        $this->orderMaintenance->db->transCommit();
        return $this->respondDeleted($found, 'Eliminado Correctamente');
    }

    /**
     * Descarga XML
     */
    public function descargaAcuseCancelacion($uuid) {

        $datosXML = $this->xml->select("*")->where("uuidTimbre", $uuid)->find();

        $this->response->setHeader("Content-Type", "text/xml");
        echo $datosXML[0]["acuseCancelacion"];
    }

    /**
     * Funcion para enlazar venta con XML Put in Sells
     *      */
    public function enlazaVenta() {

        $auth = service('authentication');

        if (!$auth->check()) {
            $this->session->set('redirect_url', current_url());

            echo "No se ha iniciado Session";
            return;
        }

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $request = service('request');
        $postData = $request->getPost();

        //Buscamos los datos de la venta
        $venta = $this->sells->select("*")->where("UUID", $postData["uuidVenta"])->first();

        $xml = $this->xml->select("*")->where("uuidTimbre", $postData["uuidTimbre"])->first();

        $datos["idDocumento"] = $venta["id"];
        $datos["uuidXML"] = $postData["uuidTimbre"];
        $datos["tipo"] = "ven";
        $datos["importe"] = $xml["total"];

        if ($this->enlaceXML->save($datos) === false) {

            $errores = $this->enlaceXML->errors();

            $listErrors = "";

            foreach ($errores as $field => $error) {

                $listErrors .= $error . " ";
            }

            echo $listErrors;

            return;
        }


        /**
         * Registramos en bitacora
         */
        $datosBitacora["description"] = "Se enlazo el XML $postData[uuidTimbre] con la venta $postData[uuidVenta]" . json_encode($datos);
        $datosBitacora["user"] = $userName;

        $this->log->save($datosBitacora);

        echo "Guardado Correctamente";
    }

    public function xmlSinAsignar($tipo) {


        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }

        $empresasRFC = array_column($titulos["empresas"], "rfc");

        if ($this->request->isAJAX()) {

            $params = [
                'draw' => $this->request->getGet('draw'),
                'start' => $this->request->getGet('start'),
                'length' => $this->request->getGet('length'),
                'order' => $this->request->getGet('order'),
                'columns' => $this->request->getGet('columns'),
                'search' => $this->request->getGet('search'),
            ];

            $datos = $this->xml->mdlXMLSinAsignar($empresasID, $tipo, $params);

            return $this->response->setJSON([
                        'draw' => intval($params['draw']),
                        'recordsTotal' => $datos['recordsTotal'],
                        'recordsFiltered' => $datos['recordsFiltered'],
                        'data' => $datos['data'],
            ]);
        }
    }

    /**
     * Reporte Consulta
     */
    public function report($uuid, $isMail = 0) {

        $pdf = new PDFLayoutOrders(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $dataOrders = $this->orderMaintenance->where("UUID", $uuid)->first();

        $listProducts = json_decode($dataOrders["listProducts"], true);

        $user = $this->user->where("id", $dataOrders["idUser"])->first()->toArray();

        $custumer = $this->suppliers->where("id", $dataOrders["idCustumer"])->where("deleted_at", null)->first();

        $assignentProductEmploye = $this->productsEmploye->where("idProduct", $dataOrders["idProduct"])->first();

        if ($assignentProductEmploye) {

            $dataEmploye = $this->employe->where("id", $assignentProductEmploye["idEmploye"])->first();
        }

        if (!$assignentProductEmploye) {

            $nameEmploye = "";
        } else {

            $nameEmploye = $dataEmploye["fullname"];
        }



        if (!$custumer) {
            $custumer["firstname"] = "";
            $custumer["lastname"] = "";
            $custumer["email"] = "";
        }

        $datosEmpresa = $this->empresa->select("*")->where("id", $dataOrders["idEmpresa"])->first();
        $datosEmpresaObj = $this->empresa->select("*")->where("id", $dataOrders["idEmpresa"])->asObject()->first();

        $pdf->nombreDocumento = lang('ordersMaintenance.name');
        $pdf->direccion = $datosEmpresaObj->direccion;

        if ($datosEmpresaObj->logo == NULL || $datosEmpresaObj->logo == "") {
            $pdf->logo = ROOTPATH . "public/images/logo/default.png";
        } else {
            $pdf->logo = ROOTPATH . "public/images/logo/" . $datosEmpresaObj->logo;
        }

        $pdf->folio = str_pad($dataOrders["folio"], 5, "0", STR_PAD_LEFT);

        // set document information
        $pdf->nombreEmpresa = $datosEmpresa["nombre"];
        $pdf->direccion = $datosEmpresa["direccion"];
        $pdf->usuario = "";
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor($user["username"]);
        $pdf->SetTitle('CI4JCPOS');
        $pdf->SetSubject('CI4JCPOS');
        $pdf->SetKeywords('CI4JCPOS, PDF, PHP, CodeIgniter, CESARSYSTEMS.COM.MX');

        // set default header data and fonts
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // margins & page setup
        $pdf->SetMargins(PDF_MARGIN_LEFT, 35, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // add a page
        $pdf->AddPage();

        // base font: small and consistent
        $pdf->SetFont('helvetica', '', 9);

        // line width fine
        $pdf->SetLineWidth(0.1);

        $pdf->SetY(45);
        // Labels
        $cliente = lang('newOrderMaintenance.custumer') . " ";
        $fecha = lang('newOrderMaintenance.date') . "";
        $fechaVencimiento = lang('newOrderMaintenance.expirationDate') . "";

        $atencionA = lang('newOrderMaintenance.quoteTo') . ":";
        $observaciones = lang('newOrderMaintenance.sellsObservations') . ":";
        $vendedor = lang('newOrderMaintenance.seller') . "";
        $vigencia = lang('newOrderMaintenance.validity') . "";
        $codigo = lang('newOrderMaintenance.fields.code') . "";
        $descripcion = lang('newOrderMaintenance.fields.description') . "";
        $cantidad = lang('newOrderMaintenance.fields.amount') . "";
        $precio = lang('newOrderMaintenance.fields.price') . "";
        $lblSubtotal = lang('newOrderMaintenance.subTotal') . "";
        $lblTotal = lang('newOrderMaintenance.fields.total') . "";

        $lblIvaRetenido = lang('newOrderMaintenance.VATWithholding') . "";
        $lblISRRetenido = lang('newOrderMaintenance.ISRWithholding') . "";

        $lblMsgThanks = "";
        $lblMsgOrderNote = lang('newOrderMaintenance.msgOrderNote');
        $lblUUIDocument = lang('newOrderMaintenance.documendUUID');
        $tipoDocumento = "";

        // Header block (compact)
        $bloque2 = <<<EOF
        <table style="font-size:9px; padding:0px 6px; width:100%;">
            <tr>
               <td style="width:50%; background-color:#2c3e50; padding:5px; font-weight:bold; color:#fff;">{$atencionA}</td>
               <td style="width:50%; background-color:#2c3e50; padding:5px; font-weight:bold; color:#fff;">{$observaciones}</td>
            </tr>
            <tr>
                <td style="padding:4px;">
                    {$cliente}: {$custumer['firstname']} {$custumer['lastname']}<br>
                    Tel: 000<br>
                    E-Mail: {$custumer['email']}
                </td>
                <td style="padding:4px;">
                    {$dataOrders['generalObservations']}<br>
                    {$tipoDocumento}<br>
                    Próx. Mant.: {$fechaVencimiento}
                </td>
            </tr>
            <tr>
                <td style="width:25%; background-color:#2c3e50; padding:5px; font-weight:bold; color:#fff;">{$vendedor}</td>
                <td style="width:24%; background-color:#2c3e50; padding:5px; font-weight:bold; color:#fff;">{$fecha}</td>
                <td style="width:30%; background-color:#2c3e50; padding:5px; font-weight:bold; color:#fff;">{$fechaVencimiento}</td>
                <td style="width:21%; background-color:#2c3e50; padding:5px; font-weight:bold; color:#fff;">{$vigencia}</td>
            </tr>
            <tr>
                <td style="padding:4px;">{$user['firstname']} {$user['lastname']}</td>
                <td style="padding:4px;">{$dataOrders['date']}</td>
                <td style="padding:4px;">{$dataOrders['dateVen']}</td>
                <td style="padding:4px;">{$nameEmploye}</td>
            </tr>
            <tr>
                <td colspan="4" style="border-bottom:1px solid #ddd; padding-top:6px;"></td>
            </tr>
        </table>
    EOF;

        $pdf->writeHTML($bloque2, false, false, false, false, '');

        // --------------------------------------------------
        // Características del Artículo / Producto principal (FORMATO 2-COLUMNAS COMPACTO)
        // --------------------------------------------------
        $idProductPrincipal = $dataOrders['idProduct'] ?? null;

        if (!empty($idProductPrincipal)) {
            $db = \Config\Database::connect();
            $builder = $db->table('data_extra_fields_balance AS dav');
            $builder->select('fep.description, dav.value');
            $builder->join('fieldsextraproductos AS fep', 'fep.id = dav.idField');
            $builder->where('dav.idProduct', $idProductPrincipal);
            $builder->where('dav.value !=', '');
            $builder->where('fep.deleted_at', null);
            $builder->where('dav.deleted_at', null);
            $builder->orderBy('fep.id', 'ASC');

            $extras = $builder->get()->getResultArray();

            if (!empty($extras)) {
                $pdf->Ln(3);
                $pdf->SetFont('helvetica', 'B', 9);

                // Prepare two-column rows: label1,value1,label2,value2
                $pairs = [];
                foreach ($extras as $ex) {
                    $pairs[] = [
                        'label' => ucwords(str_replace('_', ' ', $ex['description'])),
                        'value' => (string) $ex['value']
                    ];
                }

                $rowsHtml = '';
                $totalPairs = count($pairs);
                for ($i = 0; $i < $totalPairs; $i += 2) {
                    $left = $pairs[$i];
                    $right = ($i + 1 < $totalPairs) ? $pairs[$i + 1] : null;

                    $leftLabel = htmlspecialchars($left['label'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    $leftValue = htmlspecialchars($left['value'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

                    if ($right) {
                        $rightLabel = htmlspecialchars($right['label'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                        $rightValue = htmlspecialchars($right['value'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
                    } else {
                        $rightLabel = '';
                        $rightValue = '';
                    }

                    $rowsHtml .= "
                    <tr>
                        <td style=\"width:18%; padding:4px 6px; border:1px solid #eee; background-color:#fafafa; font-weight:bold;\">{$leftLabel}</td>
                        <td style=\"width:32%; padding:4px 6px; border:1px solid #eee;\">{$leftValue}</td>
                        <td style=\"width:18%; padding:4px 6px; border:1px solid #eee; background-color:#fafafa; font-weight:bold;\">{$rightLabel}</td>
                        <td style=\"width:32%; padding:4px 6px; border:1px solid #eee;\">{$rightValue}</td>
                    </tr>
                ";
                }

                $extraFieldsHtml = <<<EOF
            <table cellpadding="0" cellspacing="3" style="width:100%; font-size:8.5px; border-collapse:separate; margin-top:4px;">
                <tr>
                    <td colspan="4" style="background-color:#2c3e50; color:#fff; padding:6px 8px; font-weight:bold;">Características del Artículo / Producto</td>
                </tr>
                {$rowsHtml}
            </table>
            EOF;

                $pdf->writeHTML($extraFieldsHtml, true, false, false, false, '');
                $pdf->Ln(4);
                $pdf->SetFont('helvetica', '', 9);
            }
        }

        // --------------------------------------------------
        // Tabla de productos (UN SOLO TABLE con todas las filas)
        // --------------------------------------------------
        // Cabecera + inicio de tabla
        $productosTable = <<<EOF
    <table style="font-size:9px; padding:2px 6px; width:100%; border-collapse:collapse; border:0;">
        <thead>
            <tr>
                <th style="width:100px; background-color:#2c3e50; padding:6px; font-weight:bold; color:#fff; text-align:center; border:1px solid #d6d6d6;">{$codigo}</th>
                <th style="width:200px; background-color:#2c3e50; padding:6px; font-weight:bold; color:#fff; text-align:left; border:1px solid #d6d6d6;">{$descripcion}</th>
                <th style="width:60px; background-color:#2c3e50; padding:6px; font-weight:bold; color:#fff; text-align:center; border:1px solid #d6d6d6;">{$cantidad}</th>
                <th style="width:80px; background-color:#2c3e50; padding:6px; font-weight:bold; color:#fff; text-align:right; border:1px solid #d6d6d6;">{$precio}</th>
                <th style="width:100px; background-color:#2c3e50; padding:6px; font-weight:bold; color:#fff; text-align:right; border:1px solid #d6d6d6;">{$lblSubtotal}</th>
                <th style="width:100px; background-color:#2c3e50; padding:6px; font-weight:bold; color:#fff; text-align:right; border:1px solid #d6d6d6;">{$lblTotal}</th>
            </tr>
        </thead>
        <tbody>
    EOF;

        // Agregar filas dentro del mismo tbody
        $contador = 0;
        foreach ($listProducts as $key => $value) {

            $rowBg = ($contador % 2 == 0) ? '#ffffff' : '#fbfbfb';
            $precio = number_format($value["price"], 2, ".");
            $subTotal = number_format($value["total"], 2, ".");
            $total = number_format($value["neto"], 2, ".");
            $code = htmlspecialchars($value['codeProduct'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $desc = htmlspecialchars($value['description'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
            $cant = htmlspecialchars($value['cant'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');

            $productosTable .= <<<EOF
            <tr>
                <td style="background-color:{$rowBg}; padding:6px; border:1px solid #eee; width:100px; text-align:center; vertical-align:top;">{$code}</td>
                <td style="background-color:{$rowBg}; padding:6px; border:1px solid #eee; width:200px; text-align:left; vertical-align:top; line-height:1.12;">{$desc}</td>
                <td style="background-color:{$rowBg}; padding:6px; border:1px solid #eee; width:60px; text-align:center; vertical-align:top;">{$cant}</td>
                <td style="background-color:{$rowBg}; padding:6px; border:1px solid #eee; width:80px; text-align:right; vertical-align:top;">{$precio}</td>
                <td style="background-color:{$rowBg}; padding:6px; border:1px solid #eee; width:100px; text-align:right; vertical-align:top;">{$subTotal}</td>
                <td style="background-color:{$rowBg}; padding:6px; border:1px solid #eee; width:100px; text-align:right; vertical-align:top;">{$total}</td>
            </tr>
        EOF;

            $contador++;
        }

        // Cierre de tabla
        $productosTable .= '
        </tbody>
    </table>
    ';

        // Escribimos la tabla de productos (una sola vez)
        $pdf->writeHTML($productosTable, false, false, false, false, '');

        // --------------------------------------------------
        // Totales (bordes finos, sin cajas gruesas)
        // --------------------------------------------------
        $pdf->Setx(43);
        $subTotal = number_format($dataOrders["subTotal"], 2, ".");
        $impuestos = number_format($dataOrders["taxes"], 2, ".");
        $total = number_format($dataOrders["total"], 2, ".");
        $IVARetenido = number_format($dataOrders["IVARetenido"], 2, ".");
        $ISRRetenido = number_format($dataOrders["ISRRetenido"], 2, ".");

        $bloqueIVARetenido = '';
        if ($IVARetenido > 0) {
            $bloqueIVARetenido = <<<EOF
            <tr>
                <td style="width:340px; text-align:right; border:0;"></td>
                <td style="width:100px; text-align:right; padding:6px 4px; border-top:1px solid #eee;">{$lblIvaRetenido}:</td>
                <td style="width:100px; text-align:right; padding:6px 4px; border-top:1px solid #eee;">{$IVARetenido}</td>
            </tr>
        EOF;
        }

        $bloqueISRRetenido = '';
        if ($ISRRetenido > 0) {
            $bloqueISRRetenido = <<<EOF
            <tr>
                <td style="width:340px; text-align:right; border:0;"></td>
                <td style="width:100px; text-align:right; padding:6px 4px; border-top:1px solid #eee;">{$lblISRRetenido}:</td>
                <td style="width:100px; text-align:right; padding:6px 4px; border-top:1px solid #eee;">{$ISRRetenido}</td>
            </tr>
        EOF;
        }

        $bloque5 = <<<EOF
      <table style="font-size:9px; text-align:right; padding:6px 4px; border-top:0;">
          <tr>
              <td colspan="3" text-align:right; padding:6px 4px; border-top:0;""></td>
          </tr>
          <tr>
              <td style="width:340px; text-align:right; padding:6px 4px; "></td>
              <td style="width:100px; text-align:right; padding:6px 4px; ">{$lblSubtotal}:</td>
              <td style="width:100px; text-align:right; ">{$subTotal}</td>
          </tr>
          <tr>
              <td style="width:340px; text-align:right;"></td>
              <td style="width:100px; text-align:right; solid #eee; ">IVA:</td>
              <td style="width:100px; text-align:right; ">{$impuestos}</td>
          </tr>
          {$bloqueIVARetenido}
          {$bloqueISRRetenido}
          <tr>
              <td style="width:340px; text-align:right; "></td>
              <td style="width:100px; text-align:right; padding:8px 4px; border-top:1px solid #ccc; font-weight:bold;">{$lblTotal}:</td>
              <td style="width:100px; text-align:right; padding:8px 6px; border-top:1px solid #ccc; font-weight:bold;">$ {$total}</td>
          </tr>
      </table>
      <br>
      <div style="font-size:10pt; text-align:center; font-weight:bold;">{$lblMsgThanks}</div>
      <br><br>
      <div style="font-size:8.5pt; text-align:left;">{$lblUUIDocument}: {$dataOrders['UUID']}</div>
      <div style="font-size:8.5pt; text-align:left;">{$lblMsgOrderNote}</div>
    EOF;

        // write totals (thin lines)
        $pdf->SetLineWidth(0.1);
        $pdf->writeHTML($bloque5, false, false, false, false, 'R');

        // ---------------------------
        // Signatures: technician (left) and employee (right)
        // - Name centered under its own line
        // - Placed at the bottom of the page
        // ---------------------------
        // position near bottom of page (60 units from bottom)
        $pdf->SetY(-60);

        $technicianNameEsc = htmlspecialchars(trim($user['firstname'] . ' ' . $user['lastname']), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        $employeeNameEsc = ($nameEmploye !== "" && $nameEmploye !== null) ? htmlspecialchars($nameEmploye, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') : '';

        $firmaHtml = <<<EOF
    <table style="width:100%; font-size:9px; ">
        <tr>
            <td style="width:50%; text-align:left; vertical-align:bottom; padding-top:10px;">
                <div style="width:80%; margin:0 auto; text-align:center;">
                    <div style="border-top:1px solid #000; width:100%; height:1px;"></div>
                    <div style="margin-top:6px; text-align:center;">{$technicianNameEsc}</div>
                </div>
            </td>
            <td style="width:50%; text-align:right; vertical-align:bottom; padding-top:10px;">
                <div style="width:80%; margin:0 auto; text-align:center;">
                    <div style="border-top:1px solid #000; width:100%; height:1px;"></div>
                    <div style="margin-top:6px; text-align:center;">{$employeeNameEsc}</div>
                </div>
            </td>
        </tr>
    </table>
    EOF;

        $pdf->writeHTML($firmaHtml, false, false, false, false, '');

        if ($isMail == 0) {
            ob_end_clean();
            $this->response->setHeader("Content-Type", "application/pdf");
            $pdf->Output('ordenMantenimiento.pdf', 'I');
        } else {
            $attachment = $pdf->Output('notaVenta.pdf', 'S');
            return $attachment;
        }

        // END
    }
}
