<h1 class="h_motos">Motos en Venta</h1>

<form method="GET" action="<?= BASE_URL ?>/motos" class="form-buscador">
    
    <!-- Marca -->
    <select id="marca-select" name="marca">
        <option value="">Todas las marcas</option>
        <?php foreach($marcas_modelo as $id_marca => $marca): ?>
            <option value="<?= $id_marca ?>" <?= ($filters['id_marca'] ?? '') == $id_marca ? 'selected' : '' ?>>
                <?= htmlspecialchars($marca['nombre_marca']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Modelo -->
    <select id="modelo-select" name="modelo">
        <option value="">Todos los modelos</option>
    </select>

    <div class="sliders-row">
        <!-- Resultados por página -->
        <div class="slider-container">
            <label for="limit">Resultados por página:</label>
            <div class="slider-row">
                <?php $limit = isset($filters['limit']) && $filters['limit']!=='' ? $filters['limit'] : 8; ?>
                <select id="limit" name="limit">
                    <?php foreach([5,8,10,15,20,50] as $l): ?>
                        <option value="<?= $l ?>" <?= $l == $limit ? 'selected' : '' ?>><?= $l ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>

    <div class="sliders-row">

        <!-- Kilometraje -->
        <div class="slider-container">
            <label for="km_range">Kilometraje máximo:</label>
            <div class="slider-row">
                <?php $km = isset($filters['km_range']) && $filters['km_range']!=='' ? $filters['km_range'] : 200000; ?>
                <input type="range" id="km_range" name="km_range" min="0" max="60000" step="1000" value="<?= $km ?>">
                <span id="km_display" class="slider-value"><?= number_format($km,0,",",".") ?> km</span>
            </div>
        </div>

        <!-- Año -->
        <div class="slider-container">
            <label for="anio">Año mínimo:</label>
            <div class="slider-row">
                <?php $anio = isset($filters['anio']) && $filters['anio']!=='' ? $filters['anio'] : 1990; ?>
                <input type="range" id="anio" name="anio" min="2015" max="<?= date('Y') ?>" step="1" value="<?= $anio ?>">
                <span id="anio_display" class="slider-value"><?= $anio ?></span>
            </div>
        </div>

        <!-- Precio -->
        <div class="slider-container">
            <label for="precio_range">Precio máximo (€):</label>
            <div class="slider-row">
                <?php $precio = isset($filters['precio_range']) && $filters['precio_range']!=='' ? $filters['precio_range'] : 20000; ?>
                <input type="range" id="precio_range" name="precio_range" min="0" max="20000" step="500" value="<?= $precio ?>">
                <span id="precio_display" class="slider-value"><?= number_format($precio,0,",",".") ?> €</span>
            </div>
        </div>

        <!-- Cilindrada -->
        <div class="slider-container">
            <label for="cilindrada_range">Cilindrada máxima (cc):</label>
            <div class="slider-row">
                <?php $cilindrada = isset($filters['cilindrada_range']) && $filters['cilindrada_range']!=='' ? $filters['cilindrada_range'] : 3000; ?>
                <input type="range" id="cilindrada_range" name="cilindrada_range" min="0" max="3000" step="50" value="<?= $cilindrada ?>">
                <span id="cilindrada_display" class="slider-value"><?= number_format($cilindrada,0,",",".") ?> cc</span>
            </div>
        </div>

    </div>

    <!-- Permiso -->
    <select name="permiso">
        <option value="">Cualquier permiso</option>
        <?php foreach(['AM','A1','A2','A','B'] as $p): ?>
            <option value="<?= $p ?>" <?= isset($filters['permiso']) && $filters['permiso']==$p ? 'selected' : '' ?>><?= $p ?></option>
        <?php endforeach; ?>
    </select>

    <!-- Tipo de moto -->
    <select name="tipo">
        <option value="">Cualquier tipo</option>
        <?php foreach(['deportiva','custom','naked','trail'] as $t): ?>
            <option value="<?= $t ?>" <?= isset($filters['tipo']) && $filters['tipo']==$t ? 'selected' : '' ?>><?= ucfirst($t) ?></option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Buscar</button>
</form>

<!-- Contenedor de motos -->
<div class="contenedor-motos">
<?php if(!empty($motos)): ?>
    <?php foreach($motos as $moto): ?>
        <div class="card-moto">
            <div class="moto-img">
                <?php 
                    $imgBase = $moto["matricula"]."-".$moto["anio"];
                    $exts = ["png","jpg","jpeg","webp","gif"];
                    $imgPath = "assets/img/motos/default.png";
                    foreach($exts as $ext){
                        $file = "assets/img/motos/$imgBase.$ext";
                        if(file_exists($file)){
                            $imgPath = $file;
                            break;
                        }
                    }
                ?>
                <img src="<?= $imgPath ?>" alt="<?= htmlspecialchars($moto["marca"].' '.$moto["modelo"]) ?>">
            </div>
            <div class="moto-info">
                <h2><?= htmlspecialchars($moto["marca"]) ?></h2>
                <p class="modelo"><?= htmlspecialchars($moto["modelo"]) ?></p>
                <p class="anio"><?= htmlspecialchars($moto["anio"]) ?></p>
            </div>
            <div class="moto-footer">
                <span class="precio"><?= number_format($moto["precio"],0,",",".") ?> €</span>
                <a href="<?= BASE_URL ?>/moto?id_moto=<?= $moto["id_moto"] ?>" class="btn-ver">Ver detalles</a>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No hay motos disponibles.</p>
<?php endif; ?>
</div>

<!-- Paginación -->
<?php if($totalPages>1): ?>
<div class="paginacion">
    <?php if($currentPage>1): ?>
        <?php $queryParams['page']=$currentPage-1; ?>
        <a href="<?= BASE_URL.'/motos?'.http_build_query($queryParams) ?>" class="prev">&laquo; Anterior</a>
    <?php endif; ?>
    <?php for($i=1;$i<=$totalPages;$i++): ?>
        <?php $queryParams['page']=$i; ?>
        <a href="<?= BASE_URL.'/motos?'.http_build_query($queryParams) ?>" class="<?= $i==$currentPage?'active':'' ?>"><?= $i ?></a>
    <?php endfor; ?>
    <?php if($currentPage<$totalPages): ?>
        <?php $queryParams['page']=$currentPage+1; ?>
        <a href="<?= BASE_URL.'/motos?'.http_build_query($queryParams) ?>" class="next">Siguiente &raquo;</a>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- JS para sliders y marca-modelo -->
<script>
const marcasModelos = <?= $marcas_modelo_json ?>;
const marcaSelect = document.getElementById('marca-select');
const modeloSelect = document.getElementById('modelo-select');
const selectedModelo = "<?= $filters['id_modelo'] ?? '' ?>";

function llenarModelos(idMarca){
    modeloSelect.innerHTML = '<option value="">Todos los modelos</option>';
    if(idMarca && marcasModelos[idMarca]?.modelos){
        marcasModelos[idMarca].modelos.forEach(m=>{
            const opt = document.createElement('option');
            opt.value = m.id_modelo;
            opt.textContent = m.nombre;
            if(m.id_modelo==selectedModelo) opt.selected=true;
            modeloSelect.appendChild(opt);
        });
    }
}

marcaSelect.addEventListener('change', e => llenarModelos(e.target.value));
if(marcaSelect.value) llenarModelos(marcaSelect.value);

function initSlider(sliderId, displayId, sufijo){
    const slider = document.getElementById(sliderId);
    const display = document.getElementById(displayId);
    display.textContent = Number(slider.value).toLocaleString('es-ES') + ' ' + sufijo;
    slider.addEventListener('input', ()=> display.textContent = Number(slider.value).toLocaleString('es-ES') + ' ' + sufijo);
}

initSlider('km_range','km_display','km');
initSlider('anio','anio_display','');
initSlider('precio_range','precio_display','€');
initSlider('cilindrada_range','cilindrada_display','cc');
</script>