<?php

require_once __DIR__ . '/../core/database.php';

class Tienda {

    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->pdo;
    }

    /** Obtener todos los Productos */
    public function getAllProductos() {
        try {
            $stmt = $this->pdo->prepare("SELECT 
                equipamiento.id_equipamiento, equipamiento.nombre, 
                equipamiento.descripcion, equipamiento.talla,
                equipamiento.material, equipamiento.genero,
                equipamiento.temporada, equipamiento.precio,
                equipamiento.stock, equipamiento.color,
                tipo_equipamiento.nombre AS tipo,
                marca_equipamiento.nombre AS marca
                FROM equipamiento
                INNER JOIN marca_equipamiento USING(id_marca_equipamiento)
                INNER JOIN tipo_equipamiento USING(id_tipo)
                WHERE equipamiento.activo = 1
                ORDER BY marca DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch(PDOException $e) {

            return [];
        }
    }

    /** Obtener carrito de un usuario */
    public function getCart($id_user){
        $stmt = $this->pdo->prepare("
            SELECT ci.id_item, ci.cantidad, ci.precio_final, e.nombre
            FROM carrito_items ci
            INNER JOIN carrito c ON ci.id_carrito = c.id_carrito
            INNER JOIN equipamiento e ON ci.id_equipamiento = e.id_equipamiento
            WHERE c.id_user = :id_user AND c.estado = 'activo'
        ");
        $stmt->execute(['id_user'=>$id_user]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $total = 0;
        foreach($items as $item){
            $total += $item['precio_final'];
        }

        return [
            "data"=>$items,
            "count"=>count($items),
            "total"=>$total
        ];
    }

    /** Añadir producto al carrito */
    public function addToCart($id_user, $id_equipamiento){
        try {
            // Buscar carrito activo o crear uno
            $stmt = $this->pdo->prepare("SELECT id_carrito FROM carrito WHERE id_user=:id_user AND estado='activo'");
            $stmt->execute(['id_user'=>$id_user]);
            $carrito = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$carrito){
                $stmt = $this->pdo->prepare("INSERT INTO carrito (id_user) VALUES (:id_user)");
                $stmt->execute(['id_user'=>$id_user]);
                $id_carrito = $this->pdo->lastInsertId();
            } else {
                $id_carrito = $carrito['id_carrito'];
            }

            // Obtener precio y stock
            $stmt = $this->pdo->prepare("SELECT precio, stock FROM equipamiento WHERE id_equipamiento=:id");
            $stmt->execute(['id'=>$id_equipamiento]);
            $prod = $stmt->fetch(PDO::FETCH_ASSOC);

            if(!$prod || $prod['stock'] < 1) return false;

            // Insertar o actualizar item
            $stmt = $this->pdo->prepare("
                INSERT INTO carrito_items (id_carrito,id_equipamiento,cantidad,precio_unitario,precio_final)
                VALUES (:id_carrito,:id_equipamiento,1,:precio,:precio)
                ON DUPLICATE KEY UPDATE cantidad=cantidad+1, precio_final=(cantidad+1)*:precio
            ");
            $stmt->execute([
                'id_carrito'=>$id_carrito,
                'id_equipamiento'=>$id_equipamiento,
                'precio'=>$prod['precio']
            ]);

            // Reducir stock
            $stmt = $this->pdo->prepare("UPDATE equipamiento SET stock=stock-1 WHERE id_equipamiento=:id");
            $stmt->execute(['id'=>$id_equipamiento]);

            return true;
        } catch(PDOException $e){
            return false;
        }
    }

    /** Actualizar cantidad de item en carrito */
    public function updateCartQuantity($id_item, $qty){
        if($qty<1) return false;

        // Obtener item
        $stmt = $this->pdo->prepare("
            SELECT id_equipamiento, cantidad 
            FROM carrito_items 
            WHERE id_item=:id_item
        ");
        $stmt->execute(['id_item'=>$id_item]);
        $item = $stmt->fetch(PDO::FETCH_ASSOC);
        if(!$item) return false;

        $delta = $qty - $item['cantidad'];

        // Verificar stock
        $stmt = $this->pdo->prepare("SELECT stock FROM equipamiento WHERE id_equipamiento=:id");
        $stmt->execute(['id'=>$item['id_equipamiento']]);
        $stock = $stmt->fetchColumn();
        if($delta > $stock) return false;

        // Actualizar cantidad y stock
        $stmt = $this->pdo->prepare("
            UPDATE carrito_items 
            SET cantidad=:qty, precio_final=:qty*precio_unitario 
            WHERE id_item=:id_item
        ");
        $stmt->execute(['qty'=>$qty,'id_item'=>$id_item]);

        $stmt = $this->pdo->prepare("UPDATE equipamiento SET stock=stock-:delta WHERE id_equipamiento=:id");
        $stmt->execute(['delta'=>$delta,'id'=>$item['id_equipamiento']]);

        return true;
    }

    /** Eliminar item del carrito */
    public function removeFromCart($id_item){
        // Restaurar stock
        $stmt = $this->pdo->prepare("
            UPDATE equipamiento e
            INNER JOIN carrito_items ci ON e.id_equipamiento = ci.id_equipamiento
            SET e.stock = e.stock + ci.cantidad
            WHERE ci.id_item=:id_item
        ");
        $stmt->execute(['id_item'=>$id_item]);

        // Eliminar item
        $stmt = $this->pdo->prepare("DELETE FROM carrito_items WHERE id_item=:id_item");
        $stmt->execute(['id_item'=>$id_item]);

        return true;
    }

    public function checkoutCart($id_user, $payment_method){
    try {
        $cart = $this->getCart($id_user);
        if(empty($cart['data'])){
            return ['success'=>false, 'message'=>'Carrito vacío'];
        }

        // Crear pedido
        $stmt = $this->pdo->prepare("INSERT INTO pedidos (id_user, estado, total) VALUES (?, 'pendiente', ?)");
        $stmt->execute([$id_user, $cart['total']]);
        $id_pedido = $this->pdo->lastInsertId();

        // Insertar items
        $stmtItem = $this->pdo->prepare("INSERT INTO pedido_items (id_pedido, id_equipamiento, cantidad, precio_unitario, precio_final) VALUES (?,?,?,?,?)");

        foreach($cart['data'] as $item){
            $stmtItem->execute([$id_pedido, $item['id_equipamiento'], $item['cantidad'], $item['precio_unitario'], $item['precio_final']]);
            // Reducir stock
            $this->pdo->prepare("UPDATE equipamiento SET stock = stock - ? WHERE id_equipamiento = ?")->execute([$item['cantidad'], $item['id_equipamiento']]);
        }

        // Vaciar carrito
        $this->pdo->prepare("DELETE FROM carrito_items WHERE id_carrito = (SELECT id_carrito FROM carrito WHERE id_user=? AND estado='activo')")->execute([$id_user]);

        return ['success'=>true, 'message'=>'Pedido realizado'];
    } catch(PDOException $e){
        return ['success'=>false, 'message'=>$e->getMessage()];
    }
}
}
?>