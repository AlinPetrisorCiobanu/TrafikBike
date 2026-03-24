<div class="worker-form-container">
    <h2>Registrar nuevo trabajador</h2>

    <?php if(!empty($_SESSION['message'])): ?>
        <div class="alert success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
    <?php endif; ?>

    <?php if(!empty($_SESSION['error'])): ?>
        <div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <form action="<?= BASE_URL ?>/control/panel/addWorker" method="POST">
        <!-- Tipo: mecánico o vendedor -->
        <label for="tipo">Tipo de trabajador</label>
        <select name="tipo" id="tipo" required>
            <option value="">Seleccione...</option>
            <option value="mecanico">Mecánico</option>
            <option value="vendedor">Vendedor</option>
        </select>

        <!-- Campos comunes -->
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="apellidos">Apellidos</label>
        <input type="text" name="apellidos" id="apellidos" required>

        <label for="dni">DNI</label>
        <input type="text" name="dni" id="dni" maxlength="11" required>

        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono">

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="usuario">Usuario</label>
        <input type="text" name="usuario" id="usuario" required>

        <label for="password">Contraseña</label>
        <input type="password" name="password" id="password" required>

        <label for="sueldo">Sueldo (€)</label>
        <input type="number" name="sueldo" id="sueldo" step="0.01" required>

        <!-- Campo extra dinámico -->
        <div id="extra-field-container">
            <label for="extra" id="extra-label">Cargo / Comisión</label>
            <input type="text" name="extra" id="extra" required>
        </div>

        <button type="submit">Registrar</button>
    </form>
</div>
<script>
    const tipoSelect = document.getElementById('tipo');
    const extraLabel = document.getElementById('extra-label');
    const extraInput = document.getElementById('extra');

    tipoSelect.addEventListener('change', function() {
        if (this.value === 'mecanico') {
            extraLabel.textContent = 'Cargo';
            extraInput.placeholder = 'Cargo del mecánico';
            extraInput.type = 'text';
        } else if (this.value === 'vendedor') {
            extraLabel.textContent = 'Comisión (%)';
            extraInput.placeholder = 'Comisión del vendedor';
            extraInput.type = 'number';
            extraInput.step = '0.01';
        } else {
            extraLabel.textContent = 'Cargo / Comisión';
            extraInput.placeholder = '';
            extraInput.type = 'text';
        }
    });
</script>