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

    <h2>Motos destacadas</h2>

    <div class="moto-grid">

        <div class="moto">
            <img src="assets/img/harley_davidson.png" alt="Harley Davidson Fat Bob">
            <h3>Harley Davidson Fat Bob</h3>
            <p>18.700€</p>
        </div>
        
        <div class="moto">
            <img src="assets/img/kawasaki.png" alt="Kawasaki ZX10R">
            <h3>Kawasaki ZX 10R</h3>
            <p>20.200€</p>
        </div>

        <div class="moto">
    <img src="assets/img/triumph.png" alt="Triumph Rocket 3">
    <h3>Triumph Rocket 3</h3>
    <p>18.500€</p>
</div>

    </div>

</section>
    
<?php 
    include __DIR__ . "/../layouts/footer.php"
?>