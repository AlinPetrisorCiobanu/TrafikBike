<h1 class="h_motos">Motos en Venta</h1>

<div class="contenedor-motos">

<?php if(!empty($motos)): ?>

    <?php foreach($motos as $moto): ?>

        <div class="card-moto">

            <!-- Imagen -->
            <div class="moto-img">
                <?php 
                    // Nombre base sin extensión
                    $nombreBase = $moto["matricula"] . "-" . $moto["anio"];

                    // Extensiones posibles
                    $extensiones = ["png", "jpg", "jpeg", "webp", "gif"];

                    $rutaWeb = "assets/img/motos/z1000sx.png";

                    // Buscar archivo existente
                    foreach($extensiones as $ext) {
                        $rutaServidor = "assets/img/motos/$nombreBase.$ext";
                        if(file_exists($rutaServidor)) {
                            $rutaWeb = "assets/img/motos/$nombreBase.$ext";
                            break;
                        }
                    }
                ?>
                <img src="<?= $rutaWeb ?>" alt="<?= $moto["marca"] . ' ' . $moto["modelo"]; ?>">
            </div>

            <!-- Info básica -->
            <div class="moto-info">
                <h2><?= $moto["marca"]; ?></h2>
                <p class="modelo"><?= $moto["modelo"]; ?></p>
                <p class="anio"><?= $moto["anio"]; ?></p>
            </div>

            <!-- Precio + acción -->
            <div class="moto-footer">
                <span class="precio"><?= number_format($moto["precio"]); ?> €</span>
                <a href="<?= BASE_URL ?>/moto?id=<?= $moto["id_moto"]; ?>" class="btn-ver">Ver detalles</a>
            </div>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <p>No hay motos disponibles.</p>

<?php endif; ?>

</div>