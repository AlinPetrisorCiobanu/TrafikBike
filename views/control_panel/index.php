<?php 

        $rolesPermitidos = ["SUPER_ADMIN", "ADMIN", "VENDEDOR", "MECANICO"];

        if (!isset($_SESSION['token']) || !isset($_SESSION['rol'])) {
           header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if (!in_array($_SESSION['rol'], $rolesPermitidos)) {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

?>

<nav class="sidebar">
    <ul>
        <li>
            <a href="<?= BASE_URL ?>/control/panel" class="active">
                <span class="icon">🏠</span>
                <span class="text">Home</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/control/panel/usuarios">
                <span class="icon">👤</span>
                <span class="text">Usuarios</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/control/panel/motos">
                <span class="icon">🏍️</span>
                <span class="text">Motos</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/control/panel/tienda">
                <span class="icon">🛒</span>
                <span class="text">Tienda</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/control/panel/taller">
                <span class="icon">🔧</span>
                <span class="text">Taller</span>
            </a>
        </li>
    </ul>
</nav>

<main>
    <h1 class="h_controlPanel">Control Panel</h1>
</main>
