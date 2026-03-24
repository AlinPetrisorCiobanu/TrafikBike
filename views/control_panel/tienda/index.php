<?php 

$rolesPermitidos = ["SUPER_ADMIN", "ADMIN", "VENDEDOR"];

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
    <h1 class="h_controlPanel">Control Panel - Tienda</h1>

    <table class="tienda-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Producto</th>
                <th>Ficha técnica</th>
                <th>Características</th>
                <th>Precio</th>
                <th>Stock</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($productos)): ?>
                <?php foreach ($productos as $prod): ?>
                    <tr>
                        <td><?= $prod["id_equipamiento"] ?></td>

                        <!-- Nombre + Marca + Tipo -->
                        <td class="producto-col">
                            <strong><?= htmlspecialchars($prod["nombre"]) ?></strong>
                            <div class="marca-tipo">
                                <?= htmlspecialchars($prod["marca"]) ?> - <?= htmlspecialchars($prod["tipo"]) ?>
                            </div>
                        </td>

                        <!-- Ficha técnica: talla, material, temporada -->
                        <td class="ficha-col">
                            <div>Talla: <?= htmlspecialchars($prod["talla"]) ?></div>
                            <div>Material: <?= htmlspecialchars($prod["material"]) ?></div>
                            <div>Temporada: <?= htmlspecialchars($prod["temporada"]) ?></div>
                        </td>

                        <!-- Características: género, color -->
                        <td class="caract-col">
                            <span class="tag genero"><?= htmlspecialchars($prod["genero"]) ?></span>
                            <span class="tag color">🎨 <?= htmlspecialchars($prod["color"]) ?></span>
                        </td>

                        <td><?= number_format($prod["precio"], 2, ',', '.') ?> €</td>
                        <td><?= $prod["stock"] ?></td>

                        <td class="actions">
                            <a href="<?= BASE_URL ?>/control/panel/tienda/ver?id=<?= $prod["id_equipamiento"] ?>">👁️</a>
                            <a href="<?= BASE_URL ?>/control/panel/tienda/editar?id=<?= $prod["id_equipamiento"] ?>">✏️</a>
                            <a href="<?= BASE_URL ?>/control/panel/tienda/eliminar?id=<?= $prod["id_equipamiento"] ?>" onclick="return confirm('¿Seguro?')">🗑️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No hay productos activos</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>