<?php 
// Verificación de sesión y roles
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
    <h1 class="h_controlPanel">Control Panel - Citas Taller</h1>

    <!-- Filtros -->
    <form method="GET" class="citas-filtros" style="margin-left:260px; margin-top:15px;">
        <label>
            Estado:
            <select name="estado">
                <option value="">Todos</option>
                <?php
                $estadosPosibles = ["Pendiente","Confirmada","En Proceso","Completada","Cancelada","Retrasada","Esperando Piezas"];
                foreach($estadosPosibles as $estadoOption): ?>
                    <option value="<?= $estadoOption ?>" <?= isset($filters['estado']) && $filters['estado']==$estadoOption ? 'selected' : '' ?>>
                        <?= $estadoOption ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <button type="submit" class="btn-filtro">Filtrar</button>
    </form>

    <!-- Tabla de citas -->
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
                        <td class="user-col">
                            <strong><?= htmlspecialchars($cita['usuario']['nombre'] ?? '-') ?> <?= htmlspecialchars($cita['usuario']['apellidos'] ?? '-') ?></strong>
                        </td>
                        <td class="mec-col">
                            <strong><?= htmlspecialchars($cita['mecanico']['nombre']) ?> <?= htmlspecialchars($cita['mecanico']['apellidos']) ?></strong>
                        </td>
                        <td class="moto-col">
                            <div><strong><?= htmlspecialchars($cita['moto']['marca'] ?? '-') ?> <?= htmlspecialchars($cita['moto']['modelo'] ?? '-') ?></strong></div>
                            <div>Matrícula: <?= htmlspecialchars($cita['moto']['matricula'] ?? '-') ?></div>
                        </td>
                        <td><?= $cita['fecha_cita'] ?></td>

                        <!-- Estado editable con AJAX usando POST + hidden id -->
                        <td class="estado-col">
                            <form class="estado-form">
                                <input type="hidden" name="id_cita" value="<?= $cita['id_cita'] ?>">
                                <select name="estado" class="estado-select estado-<?= strtolower(str_replace(' ', '-', $cita['estado'])) ?>">
                                    <?php foreach($estadosPosibles as $estadoOption): ?>
                                        <option value="<?= $estadoOption ?>" <?= $cita['estado'] == $estadoOption ? 'selected' : '' ?>>
                                            <?= $estadoOption ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>

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

    <!-- Paginación -->
    <div class="pagination" style="margin-left:260px; margin-top:15px;">
        <?php if($current_page > 1): ?>
            <a href="?page=<?= $current_page-1 ?>&<?= http_build_query($filters) ?>">« Anterior</a>
        <?php endif; ?>
        <span>Página <?= $current_page ?></span>
        <?php if(count($citas) == $perPage): ?>
            <a href="?page=<?= $current_page+1 ?>&<?= http_build_query($filters) ?>">Siguiente »</a>
        <?php endif; ?>
    </div>
</main>

<style>
/* Botón filtro */
.btn-filtro { padding:6px 12px; background:#111827; color:#fff; border:none; border-radius:4px; cursor:pointer; }
.btn-filtro:hover { background:#374151; }

/* Tabla y estados */
.estado-select { padding:4px; border-radius:4px; border:1px solid #d1d5db; font-size:12px; cursor:pointer; }
.estado-select:focus { outline:none; border-color:#3b82f6; }
.estado-pendiente { background-color:#fbbf24; color:#111827; }
.estado-confirmada { background-color:#3b82f6; color:#fff; }
.estado-en-proceso { background-color:#6366f1; color:#fff; }
.estado-completada { background-color:#10b981; color:#fff; }
.estado-cancelada { background-color:#ef4444; color:#fff; }
.estado-retrasada { background-color:#f97316; color:#fff; }
.estado-esperando-piezas { background-color:#f59e0b; color:#111827; }
</style>

<script>
// Cambiar estado vía AJAX sin recargar
document.querySelectorAll('.estado-select').forEach(select => {
    select.addEventListener('change', () => {
        const form = select.closest('form');
        const id = form.querySelector('input[name="id_cita"]').value;
        const estado = select.value;

        fetch("<?= BASE_URL ?>/control/panel/taller/modificar", {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: "id=" + encodeURIComponent(id) + "&estado=" + encodeURIComponent(estado)
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                select.className = 'estado-select estado-' + estado.toLowerCase().replace(/ /g, '-');
            } else {
                alert('Error al actualizar: ' + data.error);
            }
        })
        .catch(err => alert('Error de red: ' + err));
    });
});
</script>