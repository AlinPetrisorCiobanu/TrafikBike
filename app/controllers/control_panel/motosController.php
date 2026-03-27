<?php
require_once __DIR__ . '/../../core/controller.php';
require_once __DIR__ . '/../../models/motos.php';

class motosController extends Controller {

    private $rolesPermitidos = ["SUPER_ADMIN", "ADMIN", "VENDEDOR", "MECANICO"];
    private $motoModel;

    public function __construct(){
        $this->motoModel = new Moto();
    }

    public function index()
    {
        if (!isset($_SESSION['token']) || !isset($_SESSION['rol'])) {
            header('Location: ' . BASE_URL . '/login'); exit;
        }
        if (!in_array($_SESSION['rol'], $this->rolesPermitidos)) {
            header('Location: ' . BASE_URL . '/'); exit;
        }

        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 8;
        $offset = ($page-1)*$limit;

        // Filtros de búsqueda
        $filters = [
            'matricula' => $_GET['matricula'] ?? '',
            'vin' => $_GET['vin'] ?? '',
            'id_marca' => $_GET['marca'] ?? '',
            'id_modelo' => $_GET['modelo'] ?? ''
        ];

        // Buscar motos
        $motos = $this->motoModel->searchMotos($filters, $limit, $offset);
        $totalMotos = $this->motoModel->countSearchMotos($filters);
        $totalPages = ceil($totalMotos / $limit);

        // Marcas y modelos
        $marcas_modelo_raw = $this->motoModel->get_marcas_modelos();
        $marcas_modelo = [];
        $marcas_modelo_json = [];

        foreach($marcas_modelo_raw as $item){
            $id = $item['id_marca'];
            if(!isset($marcas_modelo[$id])){
                $marcas_modelo[$id] = ['id_marca'=>$id,'nombre_marca'=>$item['nombre_marca']];
            }
            if(!isset($marcas_modelo_json[$id])){
                $marcas_modelo_json[$id] = ['nombre'=>$item['nombre_marca'], 'modelos'=>[]];
            }
            if(!empty($item['id_modelo'])){
                $marcas_modelo_json[$id]['modelos'][] = ['id_modelo'=>$item['id_modelo'],'nombre'=>$item['nombre_modelo']];
            }
        }

        foreach($marcas_modelo_json as $id => &$marca){
            usort($marca['modelos'], fn($a,$b)=> strcmp($a['nombre'],$b['nombre']));
        }

        $queryParams = $_GET;

        return $this->view(
            "control_panel/motos/index",
            [
                "styles" => ["control_panel/control_panel.css","control_panel/motos.css"],
                "active" => "motos",
                "motos" => $motos,
                "marcas_modelo" => $marcas_modelo,
                "marcas_modelo_json" => json_encode($marcas_modelo_json),
                "currentPage" => $page,
                "totalPages" => $totalPages,
                "filters" => $filters,
                "queryParams" => $queryParams,
                "totalMotos" => $totalMotos,
                "estados" => ['Disponible','Vendido','Reservado']
            ],
            ["layout" => "control_panel"]
        );
    }
}