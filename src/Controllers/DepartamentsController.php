<?php

namespace julio101290\boilerplatemaintenance\Controllers;

use App\Controllers\BaseController;
use julio101290\boilerplatemaintenance\Models\{
    DepartamentsModel
};
use CodeIgniter\API\ResponseTrait;
use julio101290\boilerplatelog\Models\LogModel;
use julio101290\boilerplatecompanies\Models\EmpresasModel;
use julio101290\boilerplatebranchoffice\Models\BranchofficesModel;
use julio101290\boilerplate\Models\UserModel;

class DepartamentsController extends BaseController {

    use ResponseTrait;

    protected $log;
    protected $departaments;
    protected $empresa;
    protected $brachoffice;
    protected $users;

    public function __construct() {
        $this->departaments = new DepartamentsModel();
        $this->log = new LogModel();
        $this->empresa = new EmpresasModel();
        $this->brachoffice = new BranchofficesModel();
        $this->users = new UserModel();
        helper(['menu', 'utilerias']);
    }

    public function index() {
        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);
        $empresasID = count($titulos["empresas"]) === 0 ? [0] : array_column($titulos["empresas"], "id");

        if ($this->request->isAJAX()) {
            $request = service('request');

            $draw = (int) $request->getGet('draw');
            $start = (int) $request->getGet('start');
            $length = (int) $request->getGet('length');
            $searchValue = $request->getGet('search')['value'] ?? '';
            $orderColumnIndex = (int) $request->getGet('order')[0]['column'] ?? 0;
            $orderDir = $request->getGet('order')[0]['dir'] ?? 'asc';

            $fields = $this->departaments->allowedFields;
            $orderField = $fields[$orderColumnIndex] ?? 'id';

            $builder = $this->departaments->mdlGetDepartaments($empresasID);

            $total = clone $builder;
            $recordsTotal = $total->countAllResults(false);

            if (!empty($searchValue)) {
                $builder->groupStart();
                foreach ($fields as $field) {
                    $builder->orLike("a." . $field, $searchValue);
                }
                $builder->groupEnd();
            }

            $filteredBuilder = clone $builder;
            $recordsFiltered = $filteredBuilder->countAllResults(false);

            $data = $builder->orderBy("a." . $orderField, $orderDir)
                    ->get($length, $start)
                    ->getResultArray();

            return $this->response->setJSON([
                        'draw' => $draw,
                        'recordsTotal' => $recordsTotal,
                        'recordsFiltered' => $recordsFiltered,
                        'data' => $data,
            ]);
        }

        $titulos["title"] = lang('departaments.title');
        $titulos["subtitle"] = lang('departaments.subtitle');
        return view('julio101290\boilerplatemaintenance\Views\departaments', $titulos);
    }

    public function getDepartaments() {
        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);
        $empresasID = count($titulos["empresas"]) === 0 ? [0] : array_column($titulos["empresas"], "id");

        $idDepartaments = $this->request->getPost("idDepartaments");
        $dato = $this->departaments->whereIn('idEmpresa', $empresasID)
                ->where('id', $idDepartaments)
                ->first();

        //Get Company name
        $empresa = $this->empresa->find($dato["idsucursal"]);

        //Get brachoffice
        $branchoffice = $this->brachoffice->find($dato["idsucursal"]);

        //Get Users
        $user = $this->users->asArray()->find($dato["areamanager"]);

        $dato["nombreEmpresa"] = $empresa["nombre"];
        $dato["nombreSucursal"] = $branchoffice["name"];
        $dato["nombreUsuario"] = $user["username"];

        return $this->response->setJSON($dato);
    }

    public function save() {
        helper('auth');

        $userName = user()->username;
        $datos = $this->request->getPost();
        $idKey = $datos["idDepartaments"] ?? 0;

        if ($idKey == 0) {
            try {
                if (!$this->departaments->save($datos)) {
                    $errores = implode(" ", $this->departaments->errors());
                    return $this->respond(['status' => 400, 'message' => $errores], 400);
                }
                $this->log->save([
                    "description" => lang("departaments.logDescription") . json_encode($datos),
                    "user" => $userName
                ]);
                return $this->respond(['status' => 201, 'message' => 'Guardado correctamente'], 201);
            } catch (\Throwable $ex) {
                return $this->respond(['status' => 500, 'message' => 'Error al guardar: ' . $ex->getMessage()], 500);
            }
        } else {
            if (!$this->departaments->update($idKey, $datos)) {
                $errores = implode(" ", $this->departaments->errors());
                return $this->respond(['status' => 400, 'message' => $errores], 400);
            }
            $this->log->save([
                "description" => lang("departaments.logUpdated") . json_encode($datos),
                "user" => $userName
            ]);
            return $this->respond(['status' => 200, 'message' => 'Actualizado correctamente'], 200);
        }
    }

    public function delete($id) {
        helper('auth');

        $userName = user()->username;
        $registro = $this->departaments->find($id);

        if (!$this->departaments->delete($id)) {
            return $this->respond(['status' => 404, 'message' => lang("departaments.msg.msg_get_fail")], 404);
        }

        $this->departaments->purgeDeleted();
        $this->log->save([
            "description" => lang("departaments.logDeleted") . json_encode($registro),
            "user" => $userName
        ]);

        return $this->respondDeleted($registro, lang("departaments.msg_delete"));
    }
}
