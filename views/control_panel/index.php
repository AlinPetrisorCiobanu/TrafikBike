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


<main>
    <h1 class="h_controlPanel">Control Panel</h1>
    <div class="submenu">
        <ul>
            <li><a href="<?= BASE_URL ?>/control/panel/workers">Añadir nuevos trabajadores</a></li>
        </ul>
    </div>
</main>
