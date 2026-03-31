<div class="perfil-wrapper">
    <?php if(is_array($usuario)): ?>

    <div class="perfil-dashboard">

        <!-- HEADER PERFIL -->
        <div class="perfil-header">
            <div class="perfil-avatar">
                <?= strtoupper(substr($usuario['nombre'], 0, 1)) ?>
            </div>
            <div class="perfil-info">
                <h1><?= $usuario['nombre'] . ' ' . ($usuario['apellidos'] ?? '') ?></h1>
                <p class="perfil-rol"><?= $usuario['rol'] ?> - 245 000 000<?= $usuario['id_user'] ?> </p>
                <span class="estado <?= $usuario['is_active'] ? 'activo' : 'inactivo' ?>">
                    <?= $usuario['is_active'] ? 'Activo' : 'Inactivo' ?>
                </span>
            </div>
        </div>

        <!-- GRID INFO -->
        <div class="perfil-grid">

            <!-- DATOS PERSONALES -->
            <div class="card">
                <h2>Datos personales</h2>
                
                <p><strong>Usuario:</strong> <?= $usuario['usuario'] ?></p>
                <p><strong>Email:</strong> <?= $usuario['email'] ?></p>
                <p><strong>DNI:</strong> <?= $usuario['dni'] ?></p>
                <p><strong>Teléfono:</strong> <?= $usuario['telefono'] ?></p>
            </div>

            <!-- INFO GENERAL -->
            <div class="card">
                <h2>Información General</h2>
                <p><strong>Estado:</strong> <?= $usuario['is_active'] ? 'Activo' : 'Inactivo' ?></p>
                <p><strong>Motero:</strong> <?= $usuario['is_biker'] ? 'SI' : 'NO' ?></p>
                <p><strong>Permiso:</strong> <?php echo $usuario['permiso'] ?></p>
                <p><strong>Confirmación:</strong> <?= $usuario['confirmed'] ? 'Cuenta Confirmada' : 'Cuenta No Confirmada' ?></p>
            </div>
            
            <!-- INFO ADICIONAL -->
             <?php if($usuario['is_biker'] && $usuario['permiso'] && $usuario['confirmed']) {?>
                <div class="card">
                    <h2>Información Adicional</h2>
                    <p><strong>Estado:</strong> <?= $usuario['is_active'] ? 'Activo' : 'Inactivo' ?></p>
                    <p><strong>Motero:</strong> <?= $usuario['is_biker'] ? 'SI' : 'NO' ?></p>
                    <p><strong>Permiso:</strong> <?php echo $usuario['permiso'] ?></p>
                    <p><strong>Confirmación:</strong> <?= $usuario['confirmed'] ? 'Cuenta Confirmada' : 'Cuenta No Confirmada' ?></p>
                </div>
            <?php } ?>
            <!-- VENDEDOR -->
            <?php if (!empty($usuario['sueldo_vendedor'])): ?>
            <div class="card">
                <h2>Datos de vendedor</h2>
                <p><strong>Sueldo:</strong> <?= $usuario['sueldo_vendedor'] ?> €</p>
                <p><strong>Comisión:</strong> <?= $usuario['comision'] ?>%</p>
                <p><strong>Contrato:</strong> <?= $usuario['fecha_contrato_vendedor'] ?></p>
            </div>
            <?php endif; ?>

            <!-- MECÁNICO -->
            <?php if (!empty($usuario['sueldo_mecanico'])): ?>
            <div class="card">
                <h2>Datos de mecánico</h2>
                <p><strong>Sueldo:</strong> <?= $usuario['sueldo_mecanico'] ?> €</p>
                <p><strong>Cargo:</strong> <?= $usuario['cargo'] ?></p>
                <p><strong>Contrato:</strong> <?= $usuario['fecha_contrato_mecanico'] ?></p>
            </div>
            <?php endif; ?>

        </div>

        <!-- ACCIONES -->
        <div class="perfil-actions">
            <a href="#" class="btn">Editar perfil</a>
            <a href="<?= BASE_URL ?>/logout" class="btn logout">Cerrar sesión</a>
        </div>

    </div>

    <?php else: ?>
    
        <p>Error cargando usuario</p>
    
    <?php endif; ?>
</div>