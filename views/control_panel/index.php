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
    <div class="submenu-table">
    <?php
    // Definimos los menús según el rol
    $menuItems = [];

    if ($rol !== "VENDEDOR" && $rol !== "MECANICO") {
        $menuItems = [
            "Trabajadores" => ["Añadir", "Modificar", "Dar de baja"],
            "Productos" => ["Añadir", "Modificar", "Eliminar"],
            "Motos" => ["Añadir", "Modificar", "Eliminar"],
            "Citas" => ["Añadir", "Modificar", "Eliminar"],
        ];
    } elseif ($rol === "VENDEDOR") {
        $menuItems = [
            "Productos" => ["Añadir", "Modificar", "Eliminar"],
            "Motos" => ["Añadir", "Modificar", "Eliminar"],
            "Citas" => ["Añadir", "Modificar", "Eliminar"],
        ];
    } else {
        $menuItems = [
            "Citas" => ["Añadir", "Modificar", "Eliminar"],
        ];
    }
    ?>

    <table>
        <thead>
            <tr>
                <th>Sección</th>
                <th>Añadir</th>
                <th>Modificar</th>
                <th>Eliminar / Baja</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($menuItems as $section => $actions): ?>
                <tr>
                    <td><?= $section ?></td>
                    <?php
                        // Rellenamos cada columna según las acciones
                        for ($i = 0; $i < 3; $i++) {
                            $actionName = isset($actions[$i]) ? $actions[$i] : '';
                            echo '<td>';
                            if ($actionName) {
                                echo '<a href="'.BASE_URL.'/control/panel/workers">'.$actionName.' '.$section.'</a>';
                            }
                            echo '</td>';
                        }
                    ?>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</main>
