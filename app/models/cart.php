<?php
require_once __DIR__ . '/../core/database.php';

class Cart {
    private $pdo;

    public function __construct(){
        $this->pdo = Database::getInstance()->pdo;
    }

    // Obtener carrito activo del usuario (solo existente)
    private function getActiveCartId($userId){
        $stmt = $this->pdo->prepare("SELECT id_carrito FROM carrito WHERE id_user=:uid AND estado='activo' LIMIT 1");
        $stmt->execute(['uid'=>$userId]);
        $cart = $stmt->fetch(PDO::FETCH_ASSOC);
        return $cart['id_carrito'] ?? null;
    }

    // Añadir producto
    public function addItem($userId, $itemId){
        $cartId = $this->getActiveCartId($userId);
        if(!$cartId){
            // Crear carrito si no existe
            $stmt = $this->pdo->prepare("INSERT INTO carrito(id_user) VALUES(:uid)");
            $stmt->execute(['uid'=>$userId]);
            $cartId = $this->pdo->lastInsertId();
        }

        // Verificar si ya existe el item
        $stmt = $this->pdo->prepare("SELECT cantidad, precio_final FROM carrito_items WHERE id_carrito=:cid AND id_equipamiento=:eid");
        $stmt->execute(['cid'=>$cartId, 'eid'=>$itemId]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);

        // Precio unitario
        $stmt2 = $this->pdo->prepare("SELECT precio FROM equipamiento WHERE id_equipamiento=:eid");
        $stmt2->execute(['eid'=>$itemId]);
        $precio_unitario = $stmt2->fetchColumn();
        if(!$precio_unitario) return false;

        if($item){
            $cantidad = $item['cantidad'] + 1;
            $precio_final = $cantidad * $precio_unitario;
            $stmt = $this->pdo->prepare("UPDATE carrito_items SET cantidad=:qty, precio_final=:pf WHERE id_carrito=:cid AND id_equipamiento=:eid");
            $stmt->execute(['qty'=>$cantidad, 'pf'=>$precio_final, 'cid'=>$cartId, 'eid'=>$itemId]);
        } else {
            $stmt = $this->pdo->prepare("INSERT INTO carrito_items(id_carrito, id_equipamiento, cantidad, precio_unitario, precio_final) VALUES(:cid,:eid,1,:pu,:pf)");
            $stmt->execute(['cid'=>$cartId,'eid'=>$itemId,'pu'=>$precio_unitario,'pf'=>$precio_unitario]);
        }

        return true;
    }

    // Actualizar cantidad/eliminar
    public function updateItem($userId, $itemId, $action){
        $cartId = $this->getActiveCartId($userId);
        if(!$cartId) return false; // Carrito no existe

        $stmt = $this->pdo->prepare("SELECT cantidad, precio_unitario FROM carrito_items WHERE id_carrito=:cid AND id_equipamiento=:eid");
        $stmt->execute(['cid'=>$cartId,'eid'=>$itemId]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$item) return false; // Producto no existe en carrito

        $cantidad = $item['cantidad'];
        $precio_unitario = $item['precio_unitario'];

        if($action === 'increase') $cantidad++;
        if($action === 'decrease') $cantidad = max(1, $cantidad-1);

        if($action === 'remove'){
            $stmt = $this->pdo->prepare("DELETE FROM carrito_items WHERE id_carrito=:cid AND id_equipamiento=:eid");
            $stmt->execute(['cid'=>$cartId,'eid'=>$itemId]);
            $cantidad = 0;
        } else {
            $stmt = $this->pdo->prepare("UPDATE carrito_items SET cantidad=:qty, precio_final=:pf WHERE id_carrito=:cid AND id_equipamiento=:eid");
            $stmt->execute(['qty'=>$cantidad,'pf'=>$cantidad*$precio_unitario,'cid'=>$cartId,'eid'=>$itemId]);
        }

        // Devolver estado completo del carrito
        return $this->getCart($userId);
    }

    // Obtener items del carrito
    public function getCartItems($userId){
        $cartId = $this->getActiveCartId($userId);
        if(!$cartId) return [];

        $stmt = $this->pdo->prepare("
            SELECT ci.id_item, ci.id_equipamiento, e.nombre, ci.cantidad, ci.precio_final, te.nombre AS categoria
            FROM carrito_items ci
            INNER JOIN equipamiento e ON ci.id_equipamiento = e.id_equipamiento
            INNER JOIN tipo_equipamiento te ON e.id_tipo = te.id_tipo
            WHERE ci.id_carrito=:cid
        ");
        $stmt->execute(['cid'=>$cartId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener resumen del carrito
    public function getCartSummary($userId){
        $cartId = $this->getActiveCartId($userId);
        if(!$cartId) return ['total'=>0,'count'=>0];

        $stmt = $this->pdo->prepare("SELECT SUM(precio_final) AS total, SUM(cantidad) AS count FROM carrito_items WHERE id_carrito=:cid");
        $stmt->execute(['cid'=>$cartId]);
        $summary = $stmt->fetch(PDO::FETCH_ASSOC);
        return [
            'total' => floatval($summary['total'] ?? 0),
            'count' => intval($summary['count'] ?? 0)
        ];
    }

    // Obtener carrito completo (items + resumen)
    public function getCart($userId){
        $items = $this->getCartItems($userId);
        $summary = $this->getCartSummary($userId);
        return [
            'count' => $summary['count'],
            'total' => $summary['total'],
            'data'  => $items
        ];
    }
}