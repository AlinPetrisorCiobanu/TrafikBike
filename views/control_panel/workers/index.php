<?php if(!empty($_SESSION['message'])): ?>
    <div class="alert success"><?= $_SESSION['message']; unset($_SESSION['message']); ?></div>
<?php endif; ?>

<?php if(!empty($_SESSION['error'])): ?>
    <div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="worker-form-container">
    <h2>Registrar trabajador</h2>

    <form action="<?= BASE_URL ?>/control/panel/addWorker" method="POST">

        <div class="full-width">
            <label for="tipo">Tipo de trabajador</label>
            <select name="tipo" id="tipo" required>
                <option value="">Seleccione...</option>
                <option value="mecanico">Mecánico</option>
                <option value="vendedor">Vendedor</option>
            </select>
        </div>

        <div>
            <label>Nombre</label>
            <input type="text" name="nombre" required>
        </div>

        <div>
            <label>Apellidos</label>
            <input type="text" name="apellidos" required>
        </div>

        <div>
            <label>DNI</label>
            <input type="text" name="dni" required>
        </div>

        <div>
            <label>Teléfono</label>
            <input type="text" name="telefono">
        </div>

        <div>
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div>
            <label>Usuario</label>
            <input type="text" name="usuario" required>
        </div>

        <div>
            <label>Contraseña</label>
            <input type="password" name="password" required>
        </div>

        <div>
            <label>Sueldo (€)</label>
            <input type="number" name="sueldo" step="0.01" required>
        </div>

        <div id="extra-field-container">
            <label>Cargo / Comisión</label>
            <input type="text" name="extra" required>
        </div>

        <button type="submit">Registrar trabajador</button>

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