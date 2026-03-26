<h1 class="h_motos">Motos en Venta</h1>

<form method="GET" action="<?= BASE_URL ?>/motos" class="form-buscador">

    <!-- Marca -->
    <select id="marca-select" name="marca">
        <option value="">Todas las marcas</option>
        <?php foreach($marcas_modelo as $id_marca => $marca): ?>
            <option value="<?= $id_marca ?>" <?= ($filters['id_marca']==$id_marca)?'selected':'' ?>>
                <?= htmlspecialchars($marca['nombre_marca']) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <!-- Modelo -->
    <select id="modelo-select" name="modelo">
        <option value="">Todos los modelos</option>
    </select>

    <!-- Sliders fila desktop -->
    <div class="sliders-row">

        <!-- Kilometraje -->
        <div class="slider-container">
            <label for="km_range">Kilometraje máximo:</label>
            <div class="slider-row">
                <input type="range" id="km_range" name="km_range" min="0" max="200000" step="1000"
                    value="<?= $filters['km_range'] ?: 200000 ?>">
                <span id="km_display" class="slider-value"><?= number_format($filters['km_range'] ?: 200000,0,",",".") ?> km</span>
            </div>
        </div>

        <!-- Año -->
        <div class="slider-container">
            <label for="anio">Año mínimo:</label>
            <div class="slider-row">
                <input type="range" id="anio" name="anio" min="1990" max="<?= date('Y') ?>" step="1"
                    value="<?= $filters['anio'] ?: 1990 ?>">
                <span id="anio_display" class="slider-value"><?= $filters['anio'] ?: 1990 ?></span>
            </div>
        </div>

        <!-- Precio -->
        <div class="slider-container">
            <label for="precio_range">Precio máximo (€):</label>
            <div class="slider-row">
                <input type="range" id="precio_range" name="precio_range" min="0" max="20000" step="500"
                    value="<?= $filters['precio_range'] ?: 20000 ?>">
                <span id="precio_display" class="slider-value"><?= number_format($filters['precio_range'] ?: 20000,0,",",".") ?> €</span>
            </div>
        </div>

        <!-- Cilindrada -->
        <div class="slider-container">
            <label for="cilindrada_range">Cilindrada máxima (cc):</label>
            <div class="slider-row">
                <input type="range" id="cilindrada_range" name="cilindrada_range" min="0" max="3000" step="50"
                    value="<?= $filters['cilindrada_range'] ?: 3000 ?>">
                <span id="cilindrada_display" class="slider-value"><?= number_format($filters['cilindrada_range'] ?: 3000,0,",",".") ?> cc</span>
            </div>
        </div>

    </div> <!-- fin sliders-row -->

    <!-- Permiso -->
    <select name="permiso">
        <option value="">Cualquier permiso</option>
        <?php foreach(['AM','A1','A2','A','B'] as $p): ?>
            <option value="<?= $p ?>" <?= $filters['permiso']==$p?'selected':'' ?>><?= $p ?></option>
        <?php endforeach; ?>
    </select>

    <!-- Tipo de moto -->
    <select name="tipo">
        <option value="">Cualquier tipo</option>
        <?php foreach(['deportiva','custom','naked','trail'] as $t): ?>
            <option value="<?= $t ?>" <?= $filters['tipo']==$t?'selected':'' ?>><?= ucfirst($t) ?></option>
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
                    $nombreBase = $moto["matricula"] . "-" . $moto["anio"];
                    $extensiones = ["png","jpg","jpeg","webp","gif"];
                    $rutaWeb = "assets/img/motos/z1000sx.png"; // fallback
                    foreach($extensiones as $ext){
                        $rutaServidor = "assets/img/motos/$nombreBase.$ext";
                        if(file_exists($rutaServidor)){
                            $rutaWeb = $rutaServidor;
                            break;
                        }
                    }
                ?>
                <img src="<?= $rutaWeb ?>" alt="<?= $moto["marca"] . ' ' . $moto["modelo"] ?>">
            </div>
            <div class="moto-info">
                <h2><?= $moto["marca"] ?></h2>
                <p class="modelo"><?= $moto["modelo"] ?></p>
                <p class="anio"><?= $moto["anio"] ?></p>
            </div>
            <div class="moto-footer">
                <span class="precio"><?= number_format($moto["precio"],0,",","."); ?> €</span>
                <a href="<?= BASE_URL ?>/moto?id=<?= $moto["id_moto"]; ?>" class="btn-ver">Ver detalles</a>
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
    <?php for($i=1;$i<=$totalPages;$i++): ?>
        <?php 
            $queryParams['page'] = $i;
            $url = BASE_URL . '/motos?' . http_build_query($queryParams);
        ?>
        <a href="<?= $url ?>" class="<?= $i==$currentPage?'active':'' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>

<!-- JS para sliders y marca-modelo -->
<script>
const marcasModelos = <?= $marcas_modelo_json ?>;
const marcaSelect = document.getElementById('marca-select');
const modeloSelect = document.getElementById('modelo-select');
const modeloId = "<?= $filters['id_modelo'] ?>";

function llenarModelos(idMarca){
    modeloSelect.innerHTML = '<option value="">Todos los modelos</option>';
    if(idMarca && marcasModelos[idMarca]?.modelos){
        marcasModelos[idMarca].modelos.forEach(m=>{
            const option = document.createElement('option');
            option.value = m.id_modelo;
            option.textContent = m.nombre;
            if(m.id_modelo==modeloId) option.selected = true;
            modeloSelect.appendChild(option);
        });
    }
}

marcaSelect.addEventListener('change', e=>{
    llenarModelos(e.target.value);
});

if(marcaSelect.value) llenarModelos(marcaSelect.value);

// Función común para sliders
function initSlider(sliderId, displayId, sufijo){
    const slider = document.getElementById(sliderId);
    const display = document.getElementById(displayId);
    display.textContent = Number(slider.value).toLocaleString('es-ES') + ' ' + sufijo;
    slider.addEventListener('input', ()=>{
        display.textContent = Number(slider.value).toLocaleString('es-ES') + ' ' + sufijo;
    });
}

// Inicializar sliders
initSlider('km_range','km_display','km');
initSlider('anio','anio_display','');
initSlider('precio_range','precio_display','€');
initSlider('cilindrada_range','cilindrada_display','cc');
</script>