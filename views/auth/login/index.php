 <div id="form_login">
            <form id="login_form" action="<?= BASE_URL ?>/login" method="post">
                <h2>Iniciar Sesión</h2>

                <div class="form_group_login">
                    <label for="user_login">Usuario o Email</label>
                    <input type="text" id="user_login" name="user_login" placeholder="Usuario o correo" required>
                </div>

                <div class="form_group_login">
                    <label for="pass">Contraseña</label>
                    <input type="password" id="pass" name="pass" placeholder="******" required>
                </div>

                <div class="order_buttons">
                    <button type="submit" name="check_form" value="true">Entrar</button>
                    <a href="<?= BASE_URL ?>/">Cancelar</a>
                </div>
            </form>
        </div>

        <div id="modal_login"></div>