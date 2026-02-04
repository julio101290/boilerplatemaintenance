<?php

namespace julio101290\boilerplatemaintenance\Controllers;

use App\Controllers\BaseController;
use julio101290\boilerplatecompanies\Models\EmpresasModel;
use julio101290\boilerplatebranchoffice\Models\BranchofficesModel;
use CodeIgniter\API\ResponseTrait;
use julio101290\boilerplatemaintenance\Models\OrderMaintenenceModel;

/**
 * Class DashboardController.
 */
class DashboardController extends BaseController {

    protected $xml;
    protected $empresa;
    protected $ordersMaintenance;

    use ResponseTrait;

    public function __construct() {


        $this->empresa = new EmpresasModel();
        $this->ordersMaintenance = new OrderMaintenenceModel();
        $this->branchoffice = new BranchofficesModel();

        helper('menu');
    }

    public function index() {

        helper('auth');
        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('login');
        }

        helper('auth');

        $idUser = user()->id;

        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);
        $titulos["sucursales"] = $this->branchoffice->mdlSucursalesPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
            $empresasRFC[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
            $empresasRFC = array_column($titulos["empresas"], "rfc");
        }


        if (count($titulos["sucursales"]) == "0") {

            $sucursalesID[0] = "0";
        } else {

            $sucursalesID = array_column($titulos["sucursales"], "id");
        }


        $totalMantenimientos = $this->ordersMaintenance->selectCount("id")
                        ->whereIn("idEmpresa", $empresasID)
                        ->first();

        $totalMantenimientosPendientes = $this->ordersMaintenance
                ->selectCount('*', 'total')
                ->join('empresas c', 'ordermaintenance.idEmpresa = c.id')
                ->join('branchoffices d', 'ordermaintenance.idSucursal = d.id')
                ->join('saldos e', 'ordermaintenance.idProduct = e.id')
                ->where("
                        ordermaintenance.dateVen = (
                            SELECT MAX(a2.dateVen)
                            FROM ordermaintenance a2
                            WHERE a2.idEmpresa  = ordermaintenance.idEmpresa
                              AND a2.idSucursal = ordermaintenance.idSucursal
                              AND a2.idProduct  = ordermaintenance.idProduct
                            )
                        ")
                ->where('ordermaintenance.dateVen <= DATE_ADD(CURDATE(), INTERVAL 10 DAY)', null, false)
                ->whereIn('ordermaintenance.idEmpresa', $empresasID)
                ->first();

        /*
          $productos = $this->sells->mdlVentasPorProductosAgrupado(0
          , 0
          , 0
          , '1990-01-01'
          , '2048-01-01'
          , $empresasID
          , $sucursalesID);

          $productosDatos["nombre"] = "";
          $productosDatos["cantidad"] = "";
          $productosDatos["color"] = "";

          $colors[1] = "#f56954";
          $colors[2] = "#00a65a";
          $colors[3] = "#f39c12";
          $colors[4] = "#00c0ef";
          $colors[5] = "#3c8dbc";
          $colors[6] = "#d2d6de";
          $colors[7] = "#009900";
          $colors[8] = "#86134d";
          $colors[9] = "#0033cc";
          $colors[10] = "#cc0000";

          $contador = 1;
          foreach ($productos as $key => $value) {

          $productosDatos["nombre"] .= "'$value[description]',";
          $productosDatos["cantidad"] .= "$value[cant],";
          $productosDatos["color"] .= "'$colors[$contador]',";

          $contador++;
          }



         */


        $data = [
            'title' => 'Tablero',
            'totalMantenimientos' => number_format($totalMantenimientos["id"], 0, ".", ","),
            'MantenimientosPendientes' => number_format($totalMantenimientosPendientes["total"], 0, ".", ","),
        ];
        /*
        $productos["nombreProducto"] = substr($productosDatos["nombre"], 0, -1);
        $productos["cantidadProducto"] = substr($productosDatos["cantidad"], 0, -1);
        $productos["colorProducto"] = substr($productosDatos["color"], 0, -1);
       

        $data["productos"] = $productos;
       
         * 
         */
        return view('julio101290\boilerplatemaintenance\Views\dashboard', $data);
    }

    public function traerInfo($desdeFecha, $hastaFecha) {

        helper('auth');
        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('login');
        }

        helper('auth');

        $idUser = user()->id;

        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);
        $titulos["sucursales"] = $this->branchoffice->mdlSucursalesPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
            $empresasRFC[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
            $empresasRFC = array_column($titulos["empresas"], "rfc");
        }



        if (count($titulos["sucursales"]) == "0") {

            $sucursalessID[0] = "0";
        } else {

            $sucurssalesID = array_column($titulos["sucursales"], "id");
        }


       // $datos = $this->xml->getIngresosXMLGrafica($empresasID, $empresasRFC, $desdeFecha, $hastaFecha)->getResultArray();

        $totalMantenimientos = $this->ordersMaintenance -> selectCount("id")
                        ->whereIn("idEmpresa", $empresasID)
                        ->first();

        $totalMantenimientosPendientes = $this->ordersMaintenance
                ->selectCount('*', 'total')
                ->join('empresas c', 'ordermaintenance.idEmpresa = c.id')
                ->join('branchoffices d', 'ordermaintenance.idSucursal = d.id')
                ->join('saldos e', 'ordermaintenance.idProduct = e.id')
                ->where("
                        ordermaintenance.dateVen = (
                            SELECT MAX(a2.dateVen)
                            FROM ordermaintenance a2
                            WHERE a2.idEmpresa  = ordermaintenance.idEmpresa
                              AND a2.idSucursal = ordermaintenance.idSucursal
                              AND a2.idProduct  = ordermaintenance.idProduct
                            )
                        ")
                ->where('ordermaintenance.dateVen <= DATE_ADD(CURDATE(), INTERVAL 10 DAY)', null, false)
                ->whereIn('ordermaintenance.idEmpresa', $empresasID)
                ->first();
        /*
          $productos = $this->sells->mdlVentasPorProductosAgrupado(0
          , 0
          , 0
          , $desdeFecha
          , $hastaFecha
          , $empresasID
          , $sucurssalesID);

          $productosDatos["nombre"] = "";
          $productosDatos["cantidad"] = "";
          $productosDatos["color"] = "";

          $colors[0] = "#f56954";
          $colors[1] = "#00a65a";
          $colors[2] = "#f39c12";
          $colors[3] = "#00c0ef";
          $colors[4] = "#3c8dbc";
          $colors[5] = "#d2d6de";
          $colors[6] = "#009900";
          $colors[7] = "#86134d";
          $colors[8] = "#0033cc";
          $colors[9] = "#cc0000";

          $contador = 0;
          foreach ($productos as $key => $value) {


          $productos[$contador]["color"] = $colors[$contador];

          $contador++;
          }

          $datosExportar["nombreProducto"] = array_column($productos, "description");
          $datosExportar["cantidadProducto"] = array_column($productos, "cant");
          $datosExportar["colorProducto"] = array_column($productos, "color");

          $data["productos"] = $productosDatos;
         * 
         */

        $datosExportar["totalMantenimientos"] = number_format($totalMantenimientos["id"], "0", ".");
        $datosExportar["MantenimientosPendientes"] = number_format($totalMantenimientosPendientes["total"], "0", ".");

        /*
          $datosExportar["periodo"] = array_column($datos, "periodo");
          $datosExportar["ingresos"] = array_column($datos, "ingreso");
          $datosExportar["egresos"] = array_column($datos, "egreso");
         * 
         */

        /**
         * Cartera vencida 10 primeros
         */
        $primerosMantenimientosPendientes = $this->ordersMaintenance
                ->select("
                        c.nombre AS nombre,
                        d.name AS name,
                        e.lote AS lote,
                        e.descripcion AS descripcion,
                        ordermaintenance.dateVen AS dateVen
                    ")
                ->join('empresas c', 'ordermaintenance.idEmpresa = c.id')
                ->join('branchoffices d', 'ordermaintenance.idSucursal = d.id')
                ->join('saldos e', 'ordermaintenance.idProduct = e.id')
                ->where("
        ordermaintenance.dateVen = (
            SELECT MAX(a2.dateVen)
            FROM ordermaintenance a2
            WHERE a2.idEmpresa  = ordermaintenance.idEmpresa
              AND a2.idSucursal = ordermaintenance.idSucursal
              AND a2.idProduct  = ordermaintenance.idProduct
        )
    ")
                ->where('ordermaintenance.dateVen <= DATE_ADD(CURDATE(), INTERVAL 10 DAY)', null, false)
                ->whereIn('ordermaintenance.idEmpresa', $empresasID)
                ->orderBy('ordermaintenance.dateVen', 'ASC') // opcional, recomendable
                ->findAll(10);

        $mantenimientosProximosAVencerHTML = "";

        foreach ($primerosMantenimientosPendientes as $key => $value) {



           // $ruta = base_url("admin/listSells/1990-01-01/2100-01-01/false/0/0/$value[id]");

            $mantenimientosProximosAVencerHTML .= <<<EOF
                                    <li class="item">

                                          <div class="product-info">
                                              <a href="#" class="product-title">$value[lote]
                                                  <span class="badge badge-warning float-right">$value[dateVen]</span></a>
                                              <span class="product-description">
                                                 $value[descripcion].
                                              </span>
                                          </div>
                                      </li>
                                EOF;
        }



        $datosExportar["mantenimientosProximosAVencerHTML"] = $mantenimientosProximosAVencerHTML;

        echo json_encode($datosExportar);
    }
}
