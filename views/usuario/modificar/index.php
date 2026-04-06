<div class="perfil-wrapper">
<?php if(is_array($usuario)): ?>

<div class="perfil-dashboard">

    <h1>Editar usuario</h1>

    <!-- MENSAJES -->
    <?php if(!empty($_SESSION['mensaje'])): ?>
        <div class="success"><?= $_SESSION['mensaje']; unset($_SESSION['mensaje']); ?></div>
    <?php endif; ?>

    <?php if(!empty($_SESSION['error'])): ?>
        <div class="error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>

    <!-- TABS -->
    <div class="tabs">
        <button class="tab-btn active" data-tab="personal">Datos personales</button>
        <button class="tab-btn" data-tab="sistema">Configuración</button>
    </div>

    <form action="<?= BASE_URL ?>/usuario/update?id_user=<?= $usuario['id_user'] ?>" method="POST" class="perfil-form">

        <!-- ================= DATOS PERSONALES ================= -->
        <div class="tab-content active" id="personal">

            <div class="card">
                <h2>Datos personales</h2>

                <label>Nombre</label>
                <input type="text" name="nombre" value="<?= htmlspecialchars($usuario['nombre']) ?>">

                <label>Apellidos</label>
                <input type="text" name="apellidos" value="<?= htmlspecialchars($usuario['apellidos']) ?>">

                <label>DNI</label>
                <input type="text" name="dni" value="<?= htmlspecialchars($usuario['dni']) ?>">

                <label>Teléfono</label>
                <input type="text" name="telefono" value="<?= htmlspecialchars($usuario['telefono']) ?>">

                <label>Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($usuario['email']) ?>">

                <label>Usuario</label>
                <input type="text" name="usuario" value="<?= htmlspecialchars($usuario['usuario']) ?>">

                <label>Contraseña</label>
                <input type="password" name="password" placeholder="Opcional">
            </div>

        </div>

        <!-- ================= CONFIGURACIÓN ================= -->
        <div class="tab-content" id="sistema">
            <div class="card">
                <h2>Configuración del usuario</h2>

                <label>Motero</label>
                <select name="is_biker">
                    <option value="1" <?= $usuario['is_biker'] ? 'selected' : '' ?>>Sí</option>
                    <option value="0" <?= !$usuario['is_biker'] ? 'selected' : '' ?>>No</option>
                </select>

                <label>Permiso</label>
                <select name="permiso">
                    <option value="" <?= $usuario['permiso'] == '' ? 'selected' : '' ?>>Ninguno</option>
                    <option value="B" <?= $usuario['permiso'] == 'B' ? 'selected' : '' ?>>B</option>
                    <option value="AM" <?= $usuario['permiso'] == 'AM' ? 'selected' : '' ?>>AM</option>
                    <option value="A1" <?= $usuario['permiso'] == 'A1' ? 'selected' : '' ?>>A1</option>
                    <option value="A2" <?= $usuario['permiso'] == 'A2' ? 'selected' : '' ?>>A2</option>
                    <option value="A" <?= $usuario['permiso'] == 'A' ? 'selected' : '' ?>>A</option>
                </select>
            </div>
        </div>

        <!-- BOTONES -->
        <div class="perfil-actions">
            <button type="submit" class="btn">Guardar cambios</button>
            <a href="<?= BASE_URL ?>/usuario" class="btn logout">Cancelar</a>
        </div>

    </form>
</div>

<?php endif; ?>
</div>

<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));

        btn.classList.add('active');
        document.getElementById(btn.dataset.tab).classList.add('active');
    });
});
</script>