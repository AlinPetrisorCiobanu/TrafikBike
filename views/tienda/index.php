<?php 
    include __DIR__ . "/../layouts/header.php"
?>

<h1 class="h_boutique">Boutique</h1>
    <div class="contenedor-boutique">
        <?php if($resultado["success"]): ?>
            <?php foreach($resultado["data"] as $accesorio): ?>
                <div class="card-accesorio">
                    <div class="accesorio-header">
                        <h2><?php echo $accesorio["marca"]; ?></h2>
                        <span class="modelo"><?php echo $accesorio["nombre"]; ?></span>
                    </div>
                    <div class="accesorio-body">
                        <p><strong>Tipo:</strong> <?php echo $accesorio["tipo"]; ?></p>
                        <p><strong>Talla:</strong> <?php echo $accesorio["talla"]; ?></p>
                        <p><strong>Material:</strong> <?php echo $accesorio["material"]; ?></p>
                        <p><strong>Genero:</strong> <?php echo $accesorio["genero"]; ?></p>
                        <p><strong>Temporada:</strong> <?php echo $accesorio["temporada"]; ?></p>
                        <p><strong>Descripcion:</strong> <?php echo $accesorio["descripcion"]; ?></p>
                        <p><strong>Stock:</strong> <?php echo $accesorio["stock"]; ?></p>
                        <p><strong>Color:</strong> <?php echo $accesorio["color"]; ?></p>
                    </div>
                    <div class="accesorio-footer">
                        <span class="precio"><?php echo number_format($accesorio["precio"],2); ?> €</span>
                        <form class="add-cart-form" data-id="<?php echo $accesorio['id_equipamiento']; ?>">
                            <button type="submit" class="add_to_cart">Añadir al carrito</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Error: <?php echo $resultado["message"]; ?></p>
        <?php endif; ?>
    </div>



<?php 
    include __DIR__ . "/../layouts/footer.php"
?>