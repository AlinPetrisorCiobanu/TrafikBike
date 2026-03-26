<?php
require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/motos.php';

class motosController extends Controller {

    private $motoModel;

    public function __construct(){
        $this->motoModel = new Moto();
    }

    public function index()
    {
        $page = max(1, (int)($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page-1) * $limit;

        //-- Recibir filtros
        $filters = [
            'search' => $_GET['search'] ?? '',
            'id_marca' => $_GET['marca'] ?? '',
            'id_modelo' => $_GET['modelo'] ?? '',
            'km_range' => $_GET['km_range'] ?? '',
            'anio' => $_GET['anio'] ?? '',
            'precio_range' => $_GET['precio_range'] ?? '',
            'permiso' => $_GET['permiso'] ?? '',
            'cilindrada_range' => $_GET['cilindrada_range'] ?? '',
            'tipo' => $_GET['tipo'] ?? ''
        ];

        //-- Traer motos
        $motos = $this->motoModel->getMotosPaginated($limit, $offset, ...array_values($filters));
        $totalMotos = $this->motoModel->countMotos(...array_values($filters));
        $totalPages = ceil($totalMotos / $limit);

        //-- Marcas y modelos
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

        $queryParams = $_GET; // Para paginación

        return $this->view("motos/index", [
            "styles" => ["motos.css","buscador_motos.css"],
            "motos" => $motos,
            "marcas_modelo" => $marcas_modelo,
            "marcas_modelo_json" => json_encode($marcas_modelo_json),
            "currentPage" => $page,
            "totalPages" => $totalPages,
            "filters" => $filters,
            "queryParams" => $queryParams
        ]);
    }

    public function ver_moto()
{
    $id_moto = $_GET['id'] ?? null;
    $moto = $id_moto ? $this->motoModel->getMotoById($id_moto) : null;
    return $this->view("motos/moto/index", [
        "styles" => ["moto.css"],
        "motos" => $moto ?? [],
        "error" => $moto ? null : "No se pudo cargar la moto"
    ]);
}
}