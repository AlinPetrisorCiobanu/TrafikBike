<div class="cita-card">
    <div class="cita-header">
        <h3>Cita #<?= $cita['id_cita'] ?></h3>
        <span class="estado estado-<?= strtolower(str_replace(' ', '-', $cita['estado'])) ?>">
            <?= $cita['estado'] ?>
        </span>
    </div>

    <div class="cita-body">
        <p><strong>Fecha de cita:</strong> <?= date('d/m/Y H:i', strtotime($cita['fecha_cita'])) ?></p>
        <p><strong>Mecánico:</strong> <?= htmlspecialchars($cita['mecanico']['nombre']) ?></p>
        <p><strong>Moto:</strong> <?= htmlspecialchars($cita['moto']['marca'] ?? '') ?> <?= htmlspecialchars($cita['moto']['modelo'] ?? 'No especificada') ?></p>

        <div class="servicios">
            <strong>Servicios:</strong>
            <ul>
                <?php foreach ($cita['servicios'] as $servicio): ?>
                    <li><?= htmlspecialchars($servicio['nombre']) ?> - <?= $servicio['precio_final'] ?? $servicio['precio_base'] ?>€</li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php if (!empty($cita['observaciones'])): ?>
            <p class="observaciones"><strong>Observaciones:</strong> <?= nl2br(htmlspecialchars($cita['observaciones'])) ?></p>
        <?php endif; ?>
    </div>
</div>