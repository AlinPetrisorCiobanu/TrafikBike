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

    include __DIR__ . "/../../layouts/header.php"
?>



<nav class="sidebar">
    <ul>
        <li>
            <a href="<?= BASE_URL ?>/control/panel">
                <span class="icon">🏠</span>
                <span class="text">Home</span>
            </a>
        </li>
        <li>
            <a href="<?= BASE_URL ?>/control/panel/usuarios" class="active">
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
    <h1 class="h_controlPanel">Control Panel - Usuarios</h1>

<table class="users-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Usuario</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Teléfono</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?= $user["id_user"] ?></td>
                <td><?= $user["nombre"] . " " . $user["apellidos"] ?></td>
                <td><?= $user["usuario"] ?></td>
                <td><?= $user["email"] ?></td>
                <td><?= $user["rol"] ?></td>
                <td><?= $user["telefono"] ?></td>

                <td class="actions">
                    <a href="<?= BASE_URL ?>/control/panel/usuarios/ver?id=<?= $user["id_user"] ?>">👁️</a>
                    <a href="<?= BASE_URL ?>/control/panel/usuarios/editar?id=<?= $user["id_user"] ?>">✏️</a>
                    <a href="<?= BASE_URL ?>/control/panel/usuarios/eliminar?id=<?= $user["id_user"] ?>" onclick="return confirm('¿Seguro?')">🗑️</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</main>

<?php 
    include __DIR__ . "/../../layouts/footer.php"
?>