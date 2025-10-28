<?php

namespace julio101290\boilerplatemaintenance\Controllers;

use App\Controllers\BaseController;
use julio101290\boilerplatemaintenance\Models\{
    EmployesModel
};
use CodeIgniter\API\ResponseTrait;
use julio101290\boilerplatelog\Models\LogModel;
use julio101290\boilerplatecompanies\Models\EmpresasModel;
use julio101290\boilerplatebranchoffice\Models\BranchofficesModel;
use julio101290\boilerplatemaintenance\Models\DepartamentsModel;

class EmployesController extends BaseController {

    use ResponseTrait;

    protected $log;
    protected $employes;
    protected $empresa;
    protected $branchOffice;
    protected $departament;

    public function __construct() {
        $this->employes = new EmployesModel();
        $this->log = new LogModel();
        $this->empresa = new EmpresasModel();
        $this->branchOffice = new BranchofficesModel();
        $this->departament = new DepartamentsModel();
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

            $fields = $this->employes->allowedFields;
            $orderField = $fields[$orderColumnIndex] ?? 'id';

            $builder = $this->employes->mdlGetEmployes($empresasID);

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

        $titulos["title"] = lang('employes.title');
        $titulos["subtitle"] = lang('employes.subtitle');
        return view('julio101290\boilerplatemaintenance\Views\employes', $titulos);
    }

    public function getEmployes() {
        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);
        $empresasID = count($titulos["empresas"]) === 0 ? [0] : array_column($titulos["empresas"], "id");

        $idEmployes = $this->request->getPost("idEmployes");

        $dato = $this->employes->whereIn('idEmpresa', $empresasID)
                ->where('id', $idEmployes)
                ->first();

        //GET BRANCHOFFICE

        $branchOffice = $this->branchOffice
                        ->select("name")
                        ->where("id", $dato["idBranchOffice"])->first();

        // GET DEPARTAMENT
        $departament = $this->departament
                        ->select("description")
                        ->where("id", $dato["idDepartament"])->first();

        $dato["branchOfficeName"] = $branchOffice["name"];
        $dato["departamentName"] = $departament["description"];

        return $this->response->setJSON($dato);
    }

    public function save() {
        helper('auth');

        $userName = user()->username;
        $datos = $this->request->getPost();
        $idKey = $datos["idEmployes"] ?? 0;

        if ($idKey == 0) {
            try {
                if (!$this->employes->save($datos)) {
                    $errores = implode(" ", $this->employes->errors());
                    return $this->respond(['status' => 400, 'message' => $errores], 400);
                }
                $this->log->save([
                    "description" => lang("employes.logDescription") . json_encode($datos),
                    "user" => $userName
                ]);
                return $this->respond(['status' => 201, 'message' => 'Guardado correctamente'], 201);
            } catch (\Throwable $ex) {
                return $this->respond(['status' => 500, 'message' => 'Error al guardar: ' . $ex->getMessage()], 500);
            }
        } else {
            if (!$this->employes->update($idKey, $datos)) {
                $errores = implode(" ", $this->employes->errors());
                return $this->respond(['status' => 400, 'message' => $errores], 400);
            }
            $this->log->save([
                "description" => lang("employes.logUpdated") . json_encode($datos),
                "user" => $userName
            ]);
            return $this->respond(['status' => 200, 'message' => 'Actualizado correctamente'], 200);
        }
    }

    public function delete($id) {
        helper('auth');

        $userName = user()->username;
        $registro = $this->employes->find($id);

        if (!$this->employes->delete($id)) {
            return $this->respond(['status' => 404, 'message' => lang("employes.msg.msg_get_fail")], 404);
        }

        $this->employes->purgeDeleted();
        $this->log->save([
            "description" => lang("employes.logDeleted") . json_encode($registro),
            "user" => $userName
        ]);

        return $this->respondDeleted($registro, lang("employes.msg_delete"));
    }
}
