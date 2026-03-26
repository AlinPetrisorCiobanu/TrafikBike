<?php if(!empty($motos)): ?>
    <div class="detalle-moto">

        <!-- Imagen principal -->
        <div class="moto-img">
            <?php 
                $nombreBase = $motos["matricula"] . "-" . $motos["anio"];
                $extensiones = ["png", "jpg", "jpeg", "webp", "gif"];
                $rutaWeb = "assets/img/motos/z1000sx.png";

                foreach($extensiones as $ext) {
                    $rutaServidor = "assets/img/motos/$nombreBase.$ext";
                    if(file_exists($rutaServidor)) {
                        $rutaWeb = "assets/img/motos/$nombreBase.$ext";
                        break;
                    }
                }
            ?>
            <img src="<?= $rutaWeb ?>" alt="<?= $motos["marca"] . ' ' . $motos["modelo"]; ?>">
            <!-- Badge opcional -->
            <!-- <div class="badge">Nuevo</div> -->
        </div>

        <!-- Información de la moto -->
        <div class="moto-info">
            <h2><?= $motos["marca"] . ' ' . $motos["modelo"]; ?></h2>
            <p><strong>Año:</strong> <?= $motos["anio"]; ?></p>
            <p><strong>Color:</strong> <?= $motos["color"]; ?></p>
            <p><strong>Km:</strong> <?= $motos["km"]; ?></p>
            <p><strong>Garantía:</strong> <?= $motos["garantia_meses"]; ?> meses</p>
            <p><strong>Tipo:</strong> <?= $motos["tipo"]; ?></p>
            <p><strong>Permiso:</strong> <?= $motos["permiso"]; ?></p>
            <p><strong>Cilindrada:</strong> <?= $motos["cilindrada"]; ?> cc</p>
            <p><strong>Estado:</strong> <?= $motos["estado"]; ?></p>
            <p><strong>Precio:</strong> <?= number_format($motos["precio"]); ?> €</p>

            <div class="btn-container">
                <a href="<?= BASE_URL ?>/motos" class="btn-volver">Volver al listado</a>
                <a href="<?= BASE_URL ?>/tienda/comprar?id=<?= $motos["id_moto"] ?>" class="btn-comprar">Comprar ahora</a>
            </div>
        </div>

    </div>
<?php else: ?>
    <p>No se pudo cargar la moto.</p>
<?php endif; ?>