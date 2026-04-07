<main>
    <h1>Modificar Cita #<?= $cita['id_cita'] ?></h1>

    <form action="<?= BASE_URL ?>/control/panel/taller/updateCita/<?= $cita['id_cita'] ?>" method="POST">
        <label>Mecánico:</label>
        <select name="id_mecanico" required>
            <?php foreach ($mecanicos as $m): ?>
                <option value="<?= $m['id_mecanico'] ?>" <?= $m['id_mecanico']==$cita['mecanico']['id_mecanico']?'selected':'' ?>>
                    <?= $m['nombre'].' '.$m['apellidos'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Moto:</label>
        <select name="id_moto">
            <option value="">-- Sin moto --</option>
            <?php foreach ($motos as $moto): ?>
                <option value="<?= $moto['id_moto'] ?>" <?= $moto['id_moto']==$cita['moto']['id_moto']?'selected':'' ?>>
                    <?= $moto['marca'].' '.$moto['modelo'].' ('.$moto['matricula'].')' ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Estado:</label>
        <select name="id_estado" required>
            <?php foreach ($estados as $e): ?>
                <option value="<?= $e['id_estado'] ?>" <?= $e['id_estado']==$cita['id_estado']?'selected':'' ?>>
                    <?= $e['nombre'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Fecha Cita:</label>
        <input type="datetime-local" name="fecha_cita" value="<?= date('Y-m-d\TH:i', strtotime($cita['fecha_cita'])) ?>" required>

        <label>Observaciones:</label>
        <textarea name="observaciones"><?= htmlspecialchars($cita['observaciones']) ?></textarea>

        <h3>Servicios</h3>
        <div class="servicios-list">
            <?php foreach ($cita['servicios'] as $i => $serv): ?>
                <div class="servicio-item">
                    <input type="hidden" name="servicios[<?= $i ?>][id_servicio]" value="<?= $serv['id_servicio'] ?>">
                    <label><?= $serv['nombre'] ?>:</label>
                    Base: <input type="number" step="0.01" name="servicios[<?= $i ?>][precio_base]" value="<?= $serv['precio_base'] ?>">
                    Desc: <input type="number" step="0.01" name="servicios[<?= $i ?>][descuento]" value="<?= $serv['descuento'] ?>">
                    Final: <input type="number" step="0.01" name="servicios[<?= $i ?>][precio_final]" value="<?= $serv['precio_final'] ?>" readonly>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit">Actualizar Cita</button>
    </form>
</main>