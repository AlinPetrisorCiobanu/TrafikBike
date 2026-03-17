<?php
session_start();
$token = "";
$name = "";
$user = "";
$id = 0;

if(isset($_SESSION['token'])){
    $name = $_SESSION['nombre'];
    $token = $_SESSION['token'];
    $id = $_SESSION['id'];
    $user = $_SESSION['usuario'];
}
?>
<nav>
       <div class="logo-container">
            <a href="<?= BASE_URL ?>/" class="logo-link">
                <img src="<?= BASE_URL ?>/assets/img/logo.png" alt="Logo TrafikBike" class="logo-img">
                <span class="concesionario-name">TrafikBike</span>
            </a>
        </div>
        <div class="hamburger" onclick="open_menu()">☰</div>
        <ul class="menu" id="menu">
            <li><a href="<?= BASE_URL ?>/">Inicio</a></li>
            <li><a href="<?= BASE_URL ?>/motos">Motos</a></li>
            <li><a href="<?= BASE_URL ?>/tienda">Tienda</a></li>
            <li><a href="<?= BASE_URL ?>/taller">Taller</a></li>
            <li><a href="<?= BASE_URL ?>/datos"><?= $name ? $name :'Datos' ?></a></li>
            <li><a href="<?= BASE_URL ?>/contacto">Contacto</a></li>

            <?php if($token): ?>
                <li class="auth"><a href="<?= BASE_URL ?>/logout">Cerrar sesión</a></li>
            <?php else: ?>
                <li class="auth"><a href="<?= BASE_URL ?>/login">Iniciar sesión</a></li>
                <li class="auth"><a href="<?= BASE_URL ?>/register">Registrarse</a></li>
            <?php endif; ?>

            <!-- MINI CARRITO -->
             
            
             
            <li class="cart">
                <div onclick="open_cart()" class="cart-icon">🛒 
                    <span class="cart-count"><?php echo $cart["count"]; ?></span>
                </div>

                <?php if ($token){ ?>
                    <div class="cart-dropdown">
                            <div id="cart-items">
                                <?php foreach($cart["data"] as $item): ?>
                                    <div class="cart-item" data-id="<?= $item["id_item"] ?>">
                                        <span class="cart-name"><?= $item["nombre"] ?></span>
                                            <div class="cart-qty-controls">
                                                <button class="qty-decrease">-</button>
                                                    <span class="cart-qty"><?= $item["cantidad"] ?></span>
                                                <button class="qty-increase">+</button>
                                            </div>
                                        <span class="cart-price"><?= number_format($item["precio_final"],2) ?> €</span>
                                        <button class="cart-remove">❌</button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <div class="cart-total">Total: <strong id="cart-total"><?php echo number_format($cart["total"],2); ?> €</strong></div>
                        <a href="./pasarela" id="go-cart">Ver carrito</a>
                    </div>
                <?php
                    }else{
                        ?>
                        <div class="cart-dropdown">
                            
                        <span class="cart-name">Inicia sesion para ver productos</span>
                        <a href="./login" id="go-cart">Login</a>
                    </div>
                        <?php
                    }
                ?>
            </li>
        </ul>
    </nav>   