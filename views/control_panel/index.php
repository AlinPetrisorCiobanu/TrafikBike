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


<main id="main_control">
    <h1 class="h_controlPanel">Control Panel</h1>
    <div class="submenu">
        <ul>
            <?php if($rol !== "VENDEDOR" && $rol !== "MECANICO") { ?>
                <li><a href="<?= BASE_URL ?>/control/panel/workers">Añadir nuevos trabajadores</a></li>
                <li><a href="<?= BASE_URL ?>/control/panel/workers">Modificar trabajadores</a></li>
                <li><a href="<?= BASE_URL ?>/control/panel/workers">Dar de baja trabajador</a></li>
            <?php } ?>

        </ul>
    </div>
</main>
