<?php

namespace julio101290\boilerplatemaintenance\Controllers;

use App\Controllers\BaseController;
use julio101290\boilerplateproducts\Models\ProductsModel;
use julio101290\boilerplatelog\Models\LogModel;
use julio101290\boilerplatecompanies\Models\UsuariosempresaModel;
use julio101290\boilerplatemaintenance\Models\FilesOrderMaintenanceModel;
use julio101290\boilerplatecompanies\Models\EmpresasModel;
use julio101290\boilerplatemaintenance\Models\OrderMaintenenceModel;
use CodeIgniter\API\ResponseTrait;
use Config\Database;

class FilesOrderMaintenanceController extends BaseController {

    use ResponseTrait;

    protected $log;
    protected $products;
    protected $empresa;
    protected $filesMaintenance;
    protected $orderMaintenance;

    public function __construct() {
        $this->log = new LogModel();
        $this->empresa = new EmpresasModel();
        $this->filesMaintenance = new FilesOrderMaintenanceModel();
        $this->orderMaintenance = new OrderMaintenenceModel();

        helper('menu');
    }

    public function index() {
        
    }

    public function getFilesPerOrder($registro) {

        helper('auth');

        // =============================
        // VALIDAR PERMISO MYTH/AUTH
        // =============================
        if (!user()->can('orderMaintenance-permission')) {

            return $this->response->setStatusCode(403)->setJSON([
                        "error" => "No tienes permisos para acceder a esta información."
            ]);
        }

        $request = service('request');

        $draw = (int) ($request->getGet('draw') ?? 1);
        $start = (int) ($request->getGet('start') ?? 0);
        $length = (int) ($request->getGet('length') ?? 10);

        $searchValue = $request->getGet('search')['value'] ?? null;
        $order = $request->getGet('order');
        $columnsReq = $request->getGet('columns');

        // =============================
        // EMPRESAS DEL USUARIO (TU LÓGICA)
        // =============================
        $idUser = user()->id;

        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        $empresasID = count($titulos["empresas"]) === 0 ? [0] : array_column($titulos["empresas"], "id");

        /**
         * ⚠️ WHITELIST SEGURA
         */
        $columnsMap = [
            'id' => 'a.id',
            'descripcion' => 'a.description',
            'created_at' => 'a.created_at',
            'updated_at' => 'a.updated_at',
            'deleted_at' => 'a.deleted_at'
        ];

        // =============================
        // BASE QUERY
        // =============================
        $builder = $this->filesMaintenance
                ->mdlGetFilesOrdersPerRow($empresasID, $registro);

        // =============================
        // TOTAL SIN FILTROS
        // =============================
        $totalBuilder = clone $builder;
        $recordsTotal = $totalBuilder->countAllResults(false);

        // =============================
        // BUSQUEDA GLOBAL SEGURA
        // =============================
        if (!empty($searchValue) && !empty($columnsReq)) {

            $builder->groupStart();

            foreach ($columnsReq as $col) {

                if ($col['searchable'] === "true") {

                    $columnName = $col['data'];

                    if (isset($columnsMap[$columnName])) {

                        $builder->orLike($columnsMap[$columnName], $searchValue);
                    }
                }
            }

            $builder->groupEnd();
        }

        // =============================
        // TOTAL FILTRADO
        // =============================
        $filteredBuilder = clone $builder;
        $recordsFiltered = $filteredBuilder->countAllResults(false);

        // =============================
        // ORDENAMIENTO SEGURO
        // =============================
        if (!empty($order) && !empty($columnsReq)) {

            $orderIndex = $order[0]['column'];
            $orderDir = $order[0]['dir'] === 'desc' ? 'desc' : 'asc';

            $requestColumnName = $columnsReq[$orderIndex]['data'] ?? null;

            if (isset($columnsMap[$requestColumnName])) {

                if ($columnsReq[$orderIndex]['orderable'] === "true") {

                    $builder->orderBy($columnsMap[$requestColumnName], $orderDir);
                }
            }
        } else {

            $builder->orderBy('a.id', 'asc');
        }

        // =============================
        // PAGINACION
        // =============================
        if ($length != -1) {
            $builder->limit($length, $start);
        }

        $data = $builder->get()->getResultArray();

        // =============================
        // RESPUESTA DATATABLES
        // =============================
        return $this->response->setJSON([
                    "draw" => $draw,
                    "recordsTotal" => $recordsTotal,
                    "recordsFiltered" => $recordsFiltered,
                    "data" => $data
        ]);
    }

    /**
     * Save or update Products
     */

    /**
     * Subir archivo y guardar registro en files_maintenance
     */
    public function save() {
        helper('auth');

        $userName = user() ? user()->username : null;
        $idUser = user() ? user()->id : null;

        // POST
        $idOrderMaintenance = $this->request->getPost('idOrderMaintenance');
        $description = $this->request->getPost('description');

        if (empty($idOrderMaintenance)) {
            return $this->response->setStatusCode(400)->setBody('Falta idOrderMaintenance');
        }

        $dataOrder = $this->orderMaintenance->where("id", $idOrderMaintenance)->first();
        if (!$dataOrder) {
            return $this->response->setStatusCode(400)->setBody('Orden no encontrada');
        }

        $idEmpresa = $dataOrder["idEmpresa"] ?? 0;

        // Archivo
        $file = $this->request->getFile('file');

        if (!$file) {
            return $this->response->setStatusCode(400)->setBody('No se recibió file');
        }

        if (!$file->isValid()) {
            return $this->response->setStatusCode(400)
                            ->setBody('Archivo inválido: ' . $file->getErrorString());
        }

        // Validaciones
        $allowed = ['png', 'jpg', 'jpeg', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'txt', 'zip'];
        $ext = strtolower($file->getClientExtension() ?: '');
        $maxSize = 10 * 1024 * 1024; // 10MB

        if (!in_array($ext, $allowed)) {
            return $this->response->setStatusCode(415)
                            ->setBody('Extensión no permitida');
        }

        if ($file->getSize() > $maxSize) {
            return $this->response->setStatusCode(413)
                            ->setBody('Archivo demasiado grande (máx 10MB)');
        }

        // Nombre único
        try {
            $uuid = bin2hex(random_bytes(16));
        } catch (\Exception $e) {
            $uuid = uniqid();
        }

        $originalName = $file->getClientName();
        $safeOriginal = preg_replace('/[^a-zA-Z0-9_\.\-]/', '_', $originalName);
        $newName = $uuid . '_' . $safeOriginal;

        // ✅ DESTINO EN WRITABLE
        $targetDir = WRITEPATH . 'ordersFiles';

        // Crear carpeta si no existe
        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
                return $this->response->setStatusCode(500)
                                ->setBody('No se pudo crear carpeta: ' . $targetDir);
            }
            @chmod($targetDir, 0755);
        }

        if (!is_writable($targetDir)) {
            return $this->response->setStatusCode(500)
                            ->setBody('Carpeta no escribible: ' . $targetDir);
        }

        if ($file->hasMoved()) {
            return $this->response->setStatusCode(400)
                            ->setBody('El archivo ya fue movido');
        }

        // Mover archivo
        try {
            $file->move($targetDir, $newName);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)
                            ->setBody('Error al mover archivo: ' . $e->getMessage());
        }

        $fullPath = $targetDir . DIRECTORY_SEPARATOR . $newName;

        if (!$file->hasMoved() || !file_exists($fullPath)) {
            return $this->response->setStatusCode(500)
                            ->setBody('No se pudo almacenar el archivo');
        }

        // 🔥 Guardar SOLO ruta relativa (sin writable)
        $fileRoute = 'ordersFiles/' . $newName;

        $filesModel = new \julio101290\boilerplatemaintenance\Models\FilesOrderMaintenanceModel();

        $dataToSave = [
            'idEmpresa' => $idEmpresa,
            'idOrdenMaintenance' => $idOrderMaintenance,
            'fileRoute' => $fileRoute,
            'description' => $description,
        ];

        try {
            $ok = $filesModel->save($dataToSave);

            if ($ok === false) {
                @unlink($fullPath);
                return $this->response->setStatusCode(400)
                                ->setBody(json_encode($filesModel->errors()));
            }

            return $this->response->setBody('Guardado Correctamente');
        } catch (\Exception $e) {
            @unlink($fullPath);
            return $this->response->setStatusCode(500)
                            ->setBody('Error al guardar en BD: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar archivo y registro
     */
    public function delete($id) {
        $filesModel = new \julio101290\boilerplatemaintenance\Models\FilesOrderMaintenanceModel();

        helper('auth');
        $userName = user() ? user()->username : 'system';

        // Buscar registro
        $fileRecord = $filesModel->find($id);

        if (!$fileRecord) {
            return $this->failNotFound('Registro no encontrado');
        }

        // Ruta física del archivo
        $filePath = WRITEPATH . $fileRecord['fileRoute'];

        // Iniciar transacción
        $filesModel->db->transStart();

        // Eliminar registro
        if (!$filesModel->delete($id)) {
            $filesModel->db->transRollback();
            return $this->failServerError('No se pudo eliminar el registro');
        }

        // Eliminar archivo físico si existe
        if (!empty($fileRecord['fileRoute']) && file_exists($filePath)) {
            if (!unlink($filePath)) {
                // Si falla el borrado físico, hacemos rollback
                $filesModel->db->transRollback();
                return $this->failServerError('No se pudo eliminar el archivo físico');
            }
        }

        $filesModel->purgeDeleted();

        $filesModel->db->transCommit();

        return $this->respondDeleted([
                    'id' => $id
                        ], 'Archivo eliminado correctamente');
    }

    /**
     * Descargar archivo
     */
    public function download($id) {
        $filesModel = new \julio101290\boilerplatemaintenance\Models\FilesOrderMaintenanceModel();

        helper('auth');

        // 🔐 Validar permiso con Myth/Auth
        if (!logged_in() || !has_permission('downloadFilesOrderMaintenance-permission')) {
            return $this->failForbidden('No tienes permiso para descargar archivos');
        }
        // Buscar registro
        $fileRecord = $filesModel->find($id);

        if (!$fileRecord) {
            return $this->failNotFound('Archivo no encontrado');
        }

        if (empty($fileRecord['fileRoute'])) {
            return $this->failNotFound('Ruta de archivo inválida');
        }

        // Construir ruta física segura
        $filePath = WRITEPATH . $fileRecord['fileRoute'];

        if (!file_exists($filePath)) {
            return $this->failNotFound('El archivo físico no existe');
        }

        // Obtener nombre limpio (remover UUID)
        $storedName = basename($filePath);

        // Si usaste uuid_nombre.ext → quitamos el uuid
        $originalName = preg_replace('/^[a-f0-9]{32}_/', '', $storedName);

        // Forzar descarga
        return $this->response->download($filePath, null)
                        ->setFileName($originalName);
    }
}
