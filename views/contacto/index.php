<h1>Contacto</h1>

<section class="contact-section">
    <div class="contact-card">
        <h1>Contacto</h1>
        <p>¿Tienes alguna pregunta o proyecto en mente? ¡Escríbenos y nos pondremos en contacto contigo!</p>

        <form action="enviar_contacto.php" method="POST" class="contact-form">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Tu nombre" required>
            </div>

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" placeholder="tu@email.com" required>
            </div>

            <div class="form-group">
                <label for="mensaje">Mensaje</label>
                <textarea id="mensaje" name="mensaje" rows="6" placeholder="Escribe tu mensaje aquí..." required></textarea>
            </div>

            <button type="submit" class="btn-submit">Enviar Mensaje</button>
        </form>
    </div>
</section>
