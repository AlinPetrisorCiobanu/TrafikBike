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
    <h1 class="h_controlPanel">Control Panel - Citas Taller</h1>

    <table class="citas-table">
        <thead>
            <tr>
                <th>ID Cita</th>
                <th>Usuario</th>
                <th>Mecánico</th>
                <th>Moto</th>
                <th>Fecha Cita</th>
                <th>Estado</th>
                <th>Servicios</th>
                <th>Observaciones</th>
            </tr>
        </thead>

        <tbody>
            <?php if (!empty($citas)): ?>
                <?php foreach ($citas as $cita): ?>
                    <tr>
                        <td><?= $cita['id_cita'] ?></td>

                        <!-- Usuario -->
                        <td class="user-col">
                            <strong><?= htmlspecialchars($cita['usuario']['nombre'] . ' ' . $cita['usuario']['apellidos']) ?></strong>
                        </td>

                        <!-- Mecánico -->
                        <td class="mec-col">
                            <strong><?= htmlspecialchars($cita['mecanico']['nombre'] . ' ' . $cita['mecanico']['apellidos']) ?></strong>
                        </td>

                        <!-- Moto -->
                        <td class="moto-col">
                            <div><strong><?= htmlspecialchars($cita['moto']['marca'] . ' ' . $cita['moto']['modelo']) ?></strong></div>
                            <div><?= htmlspecialchars($cita['moto']['color']) ?>, <?= $cita['moto']['tipo'] ?>, <?= $cita['moto']['cilindrada'] ?>cc, <?= $cita['moto']['anio'] ?></div>
                            <div>Matrícula: <?= htmlspecialchars($cita['moto']['matricula']) ?></div>
                        </td>

                        <!-- Fecha cita -->
                        <td><?= $cita['fecha_cita'] ?></td>

                        <!-- Estado -->
                        <td class="estado-col">
                            <span class="estado-tag estado-<?= strtolower(str_replace(' ', '-', $cita['estado'])) ?>">
                                <?= $cita['estado'] ?>
                            </span>
                        </td>

                        <!-- Servicios -->
                        <td class="serv-col">
                            <?php if (!empty($cita['servicios'])): ?>
                                <ul>
                                    <?php foreach ($cita['servicios'] as $serv): ?>
                                        <li>
                                            <strong><?= htmlspecialchars($serv['nombre']) ?></strong>:
                                            Base <?= $serv['precio_base'] ?>€, 
                                            Desc <?= $serv['descuento'] ?>€, 
                                            Final <?= $serv['precio_final'] ?>€
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                Sin servicios
                            <?php endif; ?>
                        </td>

                        <!-- Observaciones -->
                        <td class="obs-col"><?= nl2br(htmlspecialchars($cita['observaciones'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8">No hay citas registradas</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>