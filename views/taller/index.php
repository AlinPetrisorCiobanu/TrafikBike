<main class="usuario-citas">

    <h1>Mis Citas</h1>

    <!-- Botón para añadir nueva cita -->
    <div class="add-cita">
        <a href="<?= BASE_URL ?>/taller/cita" class="btn-add">
            + Nueva Cita
        </a>
    </div>

    <!-- Lista de citas -->
    <div class="citas-container">
        <?php if (!empty($citas)): ?>
            <?php foreach ($citas as $cita): ?>
                <div class="cita-card">
                    <div class="cita-header">
                        <h3>Cita #<?= $cita['id_cita'] ?></h3>
                        <span class="estado estado-<?= strtolower(str_replace(' ', '-', $cita['estado'])) ?>">
                            <?= $cita['estado'] ?>
                        </span>
                    </div>

                    <div class="cita-body">
                        <p><strong>Fecha de cita:</strong> <?= $cita['fecha_cita'] ?></p>
                        <p><strong>Mecánico:</strong> <?= htmlspecialchars($cita['mecanico']['nombre']) ?></p>
                        <p><strong>Moto:</strong> <?= htmlspecialchars($cita['moto']['marca'] ?? '') ?> <?= htmlspecialchars($cita['moto']['modelo'] ?? 'No especificada') ?></p>

                        <div class="servicios">
                            <strong>Servicios:</strong>
                            <ul>
                                <?php foreach ($cita['servicios'] as $servicio): ?>
                                    <li><?= htmlspecialchars($servicio['nombre']) ?> - <?= $servicio['precio_final'] ?>€</li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <?php if (!empty($cita['observaciones'])): ?>
                            <p class="observaciones"><strong>Observaciones:</strong> <?= nl2br(htmlspecialchars($cita['observaciones'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="no-citas">No tienes citas registradas.</p>
        <?php endif; ?>
    </div>
</main>