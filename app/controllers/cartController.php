<?php

require_once __DIR__ . '/../core/controller.php';
require_once __DIR__ . '/../models/cart.php';

class CartController extends Controller {

    private $cartModel;

    public function __construct(){
        $this->cartModel = new Cart();
    }

    // =========================
    // Helpers internos
    // =========================

    private function json($data){
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    private function requireAuth(){
        if(!isset($_SESSION['id'])){
            $this->json([
                'success' => false,
                'message' => 'No hay usuario logueado'
            ]);
        }
        return $_SESSION['id'];
    }

    private function getRequestData(){
        return json_decode(file_get_contents("php://input"), true) ?? [];
    }

    private function formatCart($items, $summary){
        $cart = [
            'count' => intval($summary['count'] ?? 0),
            'total' => floatval($summary['total'] ?? 0),
            'data'  => []
        ];

        foreach($items as $item){
            $cart['data'][] = [
                'id_item' => $item['id_item'] ?? $item['id_equipamiento'],
                'nombre' => $item['nombre'],
                'cantidad' => $item['cantidad'],
                'precio' => $item['precio_unitario'],
                'precio_final' => $item['precio_final'],
                'categoria' => $item['categoria'] ?? ''
            ];
        }

        return $cart;
    }

    // =========================
    // Endpoints
    // =========================

    public function getCart(){

        if(!isset($_SESSION['id'])){
            return $this->json([
                'count'=>0,
                'total'=>0,
                'data'=>[]
            ]);
        }

        $userId = $_SESSION['id'];

        $items = $this->cartModel->getCartItems($userId);
        $summary = $this->cartModel->getCartSummary($userId);

        return $this->json($this->formatCart($items, $summary));
    }

    public function ajaxAdd(){

        $userId = $this->requireAuth();
        $data = $this->getRequestData();

        $itemId = intval($data['id'] ?? 0);

        if(!$itemId){
            return $this->json([
                'success' => false,
                'message' => 'ID inválido'
            ]);
        }

        $added = $this->cartModel->addItem($userId, $itemId);

        if(!$added){
            return $this->json([
                'success'=>false,
                'message'=>'No se pudo añadir el producto'
            ]);
        }

        $items = $this->cartModel->getCartItems($userId);
        $summary = $this->cartModel->getCartSummary($userId);

        $item = array_values(array_filter(
            $items,
            fn($i) => $i['id_equipamiento'] == $itemId
        ))[0] ?? null;

        return $this->json([
            'success'=>true,
            'id_item' => $itemId,
            'cantidad'=>$item['cantidad'] ?? 0,
            'precio' => $item['precio_unitario'] ?? 0,
            'precio_final'=>$item['precio_final'] ?? 0,
            'total'=>floatval($summary['total']),
            'count'=>intval($summary['count']),
            'nombre'=>$item['nombre'] ?? '',
            'categoria'=>$item['categoria'] ?? ''
        ]);
    }

    public function ajaxUpdate(){

        $userId = $this->requireAuth();
        $data = $this->getRequestData();

        $itemId = intval($data['id'] ?? 0);
        $action = $data['action'] ?? '';

        if(!$itemId || !$action){
            return $this->json([
                'success'=>false,
                'message'=>'Datos inválidos'
            ]);
        }

        $updated = $this->cartModel->updateItem($userId, $itemId, $action);

        if(!$updated){
            return $this->json([
                'success'=>false,
                'message'=>'No se pudo actualizar el producto',
                'debug'=>[
                    'user'=>$userId,
                    'producto'=>$itemId,
                    'action'=>$action
                ]
            ]);
        }

        $items = $this->cartModel->getCartItems($userId);
        $summary = $this->cartModel->getCartSummary($userId);

        $item = array_values(array_filter(
            $items,
            fn($i) => $i['id_equipamiento'] == $itemId
        ))[0] ?? null;

        return $this->json([
            'success'=>true,
            'deleted'=>($action === 'remove'),
            'cantidad'=>$item['cantidad'] ?? 0,
            'precio_final'=>$item['precio_final'] ?? 0,
            'total'=>floatval($summary['total']),
            'count'=>intval($summary['count'])
        ]);
    }
}