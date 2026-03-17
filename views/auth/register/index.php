<?php 
    include __DIR__ . "/../../layouts/header.php"
?>

<div id="form_reg">
            <form id="login_form" action="<?= BASE_URL ?>/register" method="post">
                <h2>Registro de Usuario</h2>

                <div class="form_group">
                    <label for="name">Nombre</label>
                    <input type="text" id="name" name="nombre" placeholder="Tu nombre">
                </div>

                <div class="form_group">
                    <label for="last_name">Apellidos</label>
                    <input type="text" id="last_name" name="apellidos" placeholder="Tus apellidos">
                </div>

                <div class="form_group">
                    <label for="dni">DNI</label>
                    <input type="text" id="dni" name="dni" placeholder="12345678A">
                </div>

                <div class="form_group">
                    <label for="phone">Teléfono</label>
                    <input type="text" id="phone" name="telefono" placeholder="Ej: 612345678">
                </div>

                <div class="form_group">
                    <label for="email">Correo electrónico</label>
                    <input type="email" id="email" name="email" placeholder="ejemplo@correo.com">
                </div>

                <div class="form_group">
                    <label for="user">Nombre de usuario</label>
                    <input type="text" id="user" name="usuario" placeholder="Usuario">
                </div>

                <div class="form_group">
                    <label for="pass">Contraseña</label>
                    <input type="password" id="pass" name="password" placeholder="******">
                </div>

                <div class="order_buttons">
                    <button type="submit" name="check_form" value="true">Enviar</button>
                    <a href="<?= BASE_URL ?>/">Cancelar</a>
                    <button type="reset" onclick="clear_error()">Borrar todo</button>
                </div>
            </form>
        </div>
        <div id="modal"></div>


<?php 
    include __DIR__ . "/../../layouts/footer.php"
?>