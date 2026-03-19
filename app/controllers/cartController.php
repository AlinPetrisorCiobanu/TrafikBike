<?php
session_start();
require_once __DIR__ . '/../models/cart.php';

class CartController {
    private $cartModel;

    public function __construct(){
        $this->cartModel = new Cart();
    }

    // --- Devuelve el carrito completo ---
    public function getCart(){
        header('Content-Type: application/json');

        if(!isset($_SESSION['id'])){
            echo json_encode([
                'count'=>0,
                'total'=>0,
                'data'=>[]
            ]);
            exit;
        }

        $userId = $_SESSION['id'];
        $items = $this->cartModel->getCartItems($userId);
        $summary = $this->cartModel->getCartSummary($userId);

        // Formato consistente
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
                'precio_final' => $item['precio_final'],
                'categoria' => $item['categoria'] ?? ''
            ];
        }

        echo json_encode($cart);
    }

    // --- Añadir producto ---
    public function ajaxAdd(){
        header('Content-Type: application/json');

        if(!isset($_SESSION['id'])){
            echo json_encode(['success'=>false,'message'=>'No hay usuario logueado']);
            exit;
        }

        $userId = $_SESSION['id'];
        $data = json_decode(file_get_contents("php://input"), true);
        $itemId = intval($data['id'] ?? 0);

        $added = $this->cartModel->addItem($userId, $itemId);

        if($added){
            $items = $this->cartModel->getCartItems($userId);
            $summary = $this->cartModel->getCartSummary($userId);
            $item = array_filter($items, fn($i)=>$i['id_equipamiento']==$itemId);
            $item = array_values($item)[0] ?? null;

            echo json_encode([
                'success'=>true,
                'cantidad'=>$item['cantidad'] ?? 0,
                'precio_final'=>$item['precio_final'] ?? 0,
                'total'=>floatval($summary['total']),
                'count'=>intval($summary['count']),
                'nombre'=>$item['nombre'] ?? '',
                'categoria'=>$item['categoria'] ?? ''
            ]);
        } else {
            echo json_encode(['success'=>false,'message'=>'No se pudo añadir el producto']);
        }
    }

    // --- Actualizar producto ---
    public function ajaxUpdate(){
        header('Content-Type: application/json');

        if(!isset($_SESSION['id'])){
            echo json_encode(['success'=>false,'message'=>'No hay usuario logueado']);
            exit;
        }

        $userId = $_SESSION['id'];
        $data = json_decode(file_get_contents("php://input"), true);
        $itemId = intval($data['id'] ?? 0);
        $action = $data['action'] ?? '';

        $updated = $this->cartModel->updateItem($userId, $itemId, $action);

        if($updated){
            $items = $this->cartModel->getCartItems($userId);
            $summary = $this->cartModel->getCartSummary($userId);
            $item = array_filter($items, fn($i)=>$i['id_equipamiento']==$itemId);
            $item = array_values($item)[0] ?? null;

            echo json_encode([
                'success'=>true,
                'deleted'=>($action=='remove'),
                'cantidad'=>$item['cantidad'] ?? 0,
                'precio_final'=>$item['precio_final'] ?? 0,
                'total'=>floatval($summary['total']),
                'count'=>intval($summary['count'])
            ]);
        } else {
            echo json_encode([
                'success'=>false,
            'message'=>'No se pudo actualizar el producto',
            'user'=>$userId,
            'producto'=>$itemId,
            'action'=>$action
            ]);
        }
    }
}
?>