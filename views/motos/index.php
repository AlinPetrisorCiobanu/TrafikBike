<?php 
    include __DIR__ . "/../layouts/header.php"
?>

<h1 class="h_motos">Nuestras Motos Disponibles</h1>
<div class="contenedor-motos">

<?php if(!empty($motos)): ?>

    <?php foreach($motos as $moto): ?>

        <div class="card-moto">

            <div class="moto-header">
                <h2><?= $moto["marca"]; ?></h2>
                <span class="modelo"><?= $moto["modelo"]; ?></span>
            </div>

            <div class="moto-body">
                <p><strong>Matrícula:</strong> <?= $moto["matricula"]; ?></p>
                <p><strong>Año:</strong> <?= $moto["anio"]; ?></p>
                <p><strong>Kilómetros:</strong> <?= number_format($moto["km"]); ?> km</p>
                <p><strong>Tipo:</strong> <?= $moto["tipo"]; ?></p>
                <p><strong>Permiso:</strong> <?= $moto["permiso"]; ?></p>
                <p><strong>Cilindrada:</strong> <?= $moto["cilindrada"]; ?> cm³</p>
                <p><strong>Color:</strong> <?= $moto["color"]; ?></p>
                <p><strong>Garantía:</strong> <?= $moto["garantia_meses"]; ?> meses</p>
            </div>

            <div class="moto-footer">
                <span class="precio"><?= number_format($moto["precio"]); ?> €</span>
                <button class="btn-ver">Ver moto</button>
            </div>

        </div>

    <?php endforeach; ?>

<?php else: ?>

    <p>No hay motos disponibles.</p>

<?php endif; ?>

</div>


    <?php 
    include __DIR__ . "/../layouts/footer.php"
    ?>