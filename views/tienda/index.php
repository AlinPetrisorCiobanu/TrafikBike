<?php 
include __DIR__ . "/../layouts/header.php";
?>

<h1 class="h_boutique">Boutique Motera</h1>

<div class="contenedor-boutique">
    <?php if($resultado["success"]): ?>
        <?php foreach($resultado["data"] as $accesorio): ?>
            <div class="card-accesorio">

                <!-- Imagen del accesorio -->
                <div class="accesorio-img">
                    <img src="assets/img/productos/default.jpg" alt="Imagen no disponible">
                </div>

                <!-- Info del accesorio -->
                <div class="accesorio-info">
                    <h2><?php echo $accesorio["marca"]; ?></h2>

                    <p class="modelo"><?php echo $accesorio["nombre"]; ?></p>
                    <p class="tipo"><?php echo $accesorio["tipo"]; ?></p>
                    <p class="tipo">Talla: <?php echo $accesorio["talla"]; ?></p>
                    <p class="tipo">Material: <?php echo $accesorio["material"]; ?></p>
                    <p class="tipo">Género: <?php echo $accesorio["genero"]; ?></p>
                    <p class="tipo">Temporada: <?php echo $accesorio["temporada"]; ?></p>
                    <p class="tipo">Color: <?php echo $accesorio["color"]; ?></p>
                    <p class="tipo"><?php echo $accesorio["descripcion"]; ?></p>
                </div>

                <!-- Footer con precio y botón -->
                <div class="accesorio-footer">
                    <span class="precio"><?php echo number_format($accesorio["precio"], 2); ?> €</span>
                    <form class="add-cart-form" data-id="<?php echo $accesorio['id_equipamiento']; ?>">
                        <button class="btn-ver" type="submit">Añadir al carrito</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay accesorios disponibles.</p>
    <?php endif; ?>
</div>

<?php 
include __DIR__ . "/../layouts/footer.php";
?>