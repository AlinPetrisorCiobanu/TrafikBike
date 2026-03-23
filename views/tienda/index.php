<h1 class="h_boutique">Boutique Motera</h1>

<div class="contenedor-boutique">
    <?php if (!empty($productos)): ?>
        <?php foreach($productos as $accesorio): ?>
            <div class="card-accesorio">

                <!-- Imagen del accesorio -->
                <div class="accesorio-img">
                    <img src="assets/img/productos/default.jpg" alt="Imagen no disponible">
                </div>

                <!-- Info del accesorio -->
                <div class="accesorio-info">
                    <h2><?= $accesorio["marca"]; ?></h2>
                    <p class="modelo"><?= $accesorio["nombre"]; ?></p>
                    <p class="tipo"><?= $accesorio["tipo"]; ?></p>
                    <p class="tipo">Talla: <?= $accesorio["talla"]; ?></p>
                    <p class="tipo">Material: <?= $accesorio["material"]; ?></p>
                    <p class="tipo">Género: <?= $accesorio["genero"]; ?></p>
                    <p class="tipo">Temporada: <?= $accesorio["temporada"]; ?></p>
                    <p class="tipo">Color: <?= $accesorio["color"]; ?></p>
                    <p class="tipo"><?= $accesorio["descripcion"]; ?></p>
                </div>

                <!-- Footer con precio y botón -->
                <div class="accesorio-footer">
                    <span class="precio"><?= number_format($accesorio["precio"], 2); ?> €</span>
                    <form class="add-cart-form" data-id="<?= $accesorio['id_equipamiento']; ?>">
                        <button class="btn-ver" type="submit">Añadir al carrito</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay accesorios disponibles.</p>
    <?php endif; ?>
</div>