<main class="usuario-citas">
    <h1>Agendar nueva cita</h1>

    <div class="add-cita-form">
        <form action="<?= BASE_URL ?>/taller/crearCita" method="POST">

            <!-- Fecha -->
            <div class="form-group">
                <label for="fecha_cita">Fecha y hora:</label>
                <input type="datetime-local" id="fecha_cita" name="fecha_cita" required>
            </div>

            <!-- Moto -->
            <div class="form-group">
                <label for="id_moto">Moto:</label>

                <?php if(in_array($user_role, ['SUPER_ADMIN','ADMIN','VENDEDOR'])): ?>
                    <select id="id_moto" name="id_moto" required>
                        <option value="">-- Selecciona una moto --</option>
                        <?php foreach($motos as $moto): ?>
                            <option value="<?= $moto['id_moto'] ?>">
                                <?= htmlspecialchars($moto['marca']) ?> <?= htmlspecialchars($moto['modelo']) ?> - <?= htmlspecialchars($moto['matricula']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <?php if(!empty($motos)): ?>
                        <select id="id_moto" name="id_moto" required>
                            <option value="">-- Selecciona tu moto --</option>
                            <?php foreach($motos as $moto): ?>
                                <option value="<?= $moto['id_moto'] ?>">
                                    <?= htmlspecialchars($moto['marca']) ?> <?= htmlspecialchars($moto['modelo']) ?> - <?= htmlspecialchars($moto['matricula']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php else: ?>
                        <input type="text" id="id_moto" name="id_moto" placeholder="Escribe la moto (marca y modelo)" required>
                    <?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Servicios -->
            <div class="form-group">
                <label>Servicios:</label>
                <div class="dual-select compact">
                    <div class="available-services">
                        <h4>Disponibles</h4>
                        <ul id="available-services">
                            <?php foreach ($servicios as $serv): ?>
                                <li data-id="<?= $serv['id_servicio'] ?>">
                                    <?= htmlspecialchars($serv['nombre']) ?> (<?= $serv['precio_base'] ?>€)
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="selected-services">
                        <h4>Seleccionados</h4>
                        <ul id="selected-services"></ul>
                    </div>
                </div>
            </div>
            <div id="selected-inputs"></div>

            <!-- Observaciones -->
            <div class="form-group">
                <label for="observaciones">Observaciones:</label>
                <textarea id="observaciones" name="observaciones" placeholder="Detalles adicionales..."></textarea>
            </div>

            <button type="submit" class="btn-add">Agendar cita</button>
        </form>
    </div>
</main>

<script>
const available = document.getElementById('available-services');
const selected = document.getElementById('selected-services');
const selectedInputs = document.getElementById('selected-inputs');

function updateHiddenInputs() {
    selectedInputs.innerHTML = '';
    Array.from(selected.children).forEach(li => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'servicios[]';
        input.value = li.dataset.id;
        selectedInputs.appendChild(input);
    });
}

available.addEventListener('click', (e) => {
    if(e.target.tagName === 'LI') {
        selected.appendChild(e.target);
        updateHiddenInputs();
    }
});

selected.addEventListener('click', (e) => {
    if(e.target.tagName === 'LI') {
        available.appendChild(e.target);
        updateHiddenInputs();
    }
});

// Limitar fecha a entre semana y horario de 9 a 18
const fechaInput = document.getElementById('fecha_cita');
fechaInput.addEventListener('input', () => {
    const dt = new Date(fechaInput.value);
    const hour = dt.getHours();
    const day = dt.getDay(); // 0=domingo,6=sábado

    if(day === 0 || day === 6 || hour < 9 || hour > 18) {
        alert("Selecciona un día entre semana y horario de 9:00 a 18:00");
        fechaInput.value = '';
    }
});
</script>