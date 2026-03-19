
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
            <li><a href="<?= BASE_URL ?>/usuario"><?= $name ? $name :'Datos' ?></a></li>
            <li><a href="<?= BASE_URL ?>/contacto">Contacto</a></li>

            <?php if($token): ?>
                <li class="auth"><a href="<?= BASE_URL ?>/logout">Cerrar sesión</a></li>
            <?php else: ?>
                <li class="auth"><a href="<?= BASE_URL ?>/login">Iniciar sesión</a></li>
                <li class="auth"><a href="<?= BASE_URL ?>/register">Registrarse</a></li>
            <?php endif; ?>

            <!-- importamos el carrito de compra -->
             <?php include __DIR__ . "/carito.php" ?>
        </ul>
    </nav>   