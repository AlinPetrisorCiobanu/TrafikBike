<?php 
    include __DIR__ . "/../layouts/header.php"
?>

    <!-- seccion con clase hero tipo banner -->
    <section class="hero">

        <h1>Tu concesionario de motos</h1>
        <p>Venta · Taller · Accesorios</p>

        <a href="#" class="btn">Ver motos</a>
        <a href="#" class="btn">Ver Equipamiento</a>

    </section>

    <section class="motos">

        <h2>Promoción Especial Primavera</h2>
        <h2>Solo el mes de Abril</h2>

        <div class="moto-grid">

            <div class="moto">
                <img src="assets/img/motos/harley_davidson.png" alt="Harley Davidson Fat Bob">
                <h3>Harley Davidson Fat Bob</h3>
                <p>
                    <span class="precio-original">22.700€</span>
                    <span class="precio-oferta">20.899€</span>
                </p>
            </div>
            
            <div class="moto">
                <img src="assets/img/motos/kawasaki.png" alt="Kawasaki ZX10R">
                <h3>Kawasaki ZX 10R</h3>
                <p>
                    <span class="precio-original">20.200€</span>
                    <span class="precio-oferta">19.499€</span>
                </p>
            </div>

            <div class="moto">
                <img src="assets/img/motos/triumph.png" alt="Triumph Rocket 3">
                <h3>Triumph Rocket 3</h3>
                <p>
                    <span class="precio-original">33.500€</span>
                    <span class="precio-oferta">29.999€</span>
                </p>
            </div>

        </div>

    </section>

   <section class="servicios">

        <h2>Nuestros servicios</h2>

        <div class="servicio-grid">

            <div class="servicio">
            <h3>🏍 Venta de motos</h3>
            <p>Motos nuevas y de ocasión.</p>
            </div>

            <div class="servicio">
            <h3>🔧 Taller</h3>
            <p>Mantenimiento y reparaciones.</p>
            </div>

            <div class="servicio">
            <h3>🛠 Repuestos</h3>
            <p>Accesorios y piezas originales.</p>
            </div>

        </div>

    </section>

    <section class="cta">

        <h2>Reserva tu cita en nuestro taller</h2>
        <a href="#" class="btn">Contactar</a>

    </section>
    
<?php 
    include __DIR__ . "/../layouts/footer.php"
?>