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
    <h1 class="h_controlPanel">Control Panel - Motos</h1>

    <table class="motos-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Matrícula</th>
            <th>Moto</th>
            <th>Ficha técnica</th>
            <th>Características</th>
            <th>Precio</th>
            <th>Garantía</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>

    <tbody>
        <?php if (!empty($motos)): ?>
            <?php foreach ($motos as $moto): ?>
                <tr>
                    <td><?= $moto["id_moto"] ?></td>
                    <td><?= htmlspecialchars($moto["matricula"]) ?></td>

                    <!-- MOTO -->
                    <td class="moto-col">
                        <strong>
                            <?= htmlspecialchars($moto["marca"]) ?>
                            <?= htmlspecialchars($moto["modelo"]) ?>
                        </strong>
                    </td>

                    <!-- FICHA TECNICA -->
                    <td class="ficha-col">
                        <div>📅 <?= $moto["anio"] ?></div>
                        <div>🛣️ <?= number_format($moto["km"], 0, ',', '.') ?> km</div>
                        <div>⚙️ <?= $moto["cilindrada"] ?> cc</div>
                    </td>

                    <!-- CARACTERISTICAS -->
                    <td class="caract-col">
                        <span class="tag tipo"><?= htmlspecialchars($moto["tipo"]) ?></span>
                        <span class="tag permiso"><?= htmlspecialchars($moto["permiso"]) ?></span>
                        <span class="tag color">🎨 <?= htmlspecialchars($moto["color"]) ?></span>
                    </td>

                    <td><?= number_format($moto["precio"], 2, ',', '.') ?> €</td>
                    <td><?= $moto["garantia_meses"] ?> meses</td>
                    <td>
                        <span class="estado <?= strtolower($moto["estado"]) ?>">
                            <?= htmlspecialchars($moto["estado"]) ?>
                        </span>
                    </td>

                    <td class="actions">
                        <a href="<?= BASE_URL ?>/control/panel/motos/ver?id=<?= $moto["id_moto"] ?>">👁️</a>
                        <a href="<?= BASE_URL ?>/control/panel/motos/editar?id=<?= $moto["id_moto"] ?>">✏️</a>
                        <a href="<?= BASE_URL ?>/control/panel/motos/eliminar?id=<?= $moto["id_moto"] ?>" onclick="return confirm('¿Seguro?')">🗑️</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">No hay motos registradas</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>
</main>