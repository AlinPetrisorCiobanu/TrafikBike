<main class="usuario-citas">

    <h1>Mis Citas</h1>

    <!-- Formulario para nueva cita -->
    <div class="add-cita-form">
        <h2>Agendar nueva cita</h2>
        <form action="<?= BASE_URL ?>/taller/crearCita" method="POST">
            <div class="form-group">
                <label for="fecha_cita">Fecha y hora:</label>
                <input type="datetime-local" id="fecha_cita" name="fecha_cita" required>
            </div>

            <div class="form-group">
                <label for="id_moto">Selecciona tu moto:</label>
                <select id="id_moto" name="id_moto" required>
                    <option value="">-- Elige moto --</option>
                    <?php foreach ($motos as $moto): ?>
                        <option value="<?= $moto['id_moto'] ?>">
                            <?= htmlspecialchars($moto['marca'] . ' ' . $moto['modelo']) ?> (<?= $moto['matricula'] ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Servicios:</label>
                <div class="servicios-checkboxes">
                    <?php foreach ($servicios as $serv): ?>
                        <label class="checkbox-label">
                            <input type="checkbox" name="servicios[]" value="<?= $serv['id_servicio'] ?>">
                            <?= htmlspecialchars($serv['nombre']) ?> (<?= $serv['precio_base'] ?>€)
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="form-group">
                <label for="observaciones">Observaciones:</label>
                <textarea id="observaciones" name="observaciones" placeholder="Detalles adicionales..."></textarea>
            </div>

            <button type="submit" class="btn-add">Agendar cita</button>
        </form>
    </div>

    <!-- Lista de citas existentes -->
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

