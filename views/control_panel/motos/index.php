<main>
    <h1 class="h_controlPanel">Control Panel - Motos</h1>

    <!-- BUSCADOR -->
    <form method="GET" action="<?= BASE_URL ?>/control/panel/motos" class="form-buscador" id="form-buscador-control-panel">
        <input type="text" name="matricula" placeholder="Buscar por matrícula" 
               value="<?= htmlspecialchars($filters['matricula'] ?? '') ?>">
        <input type="text" name="vin" placeholder="Buscar por VIN" 
               value="<?= htmlspecialchars($filters['vin'] ?? '') ?>">

        <select name="id_marca" id="marca-select">
            <option value="">Todas las marcas</option>
            <?php foreach($marcas_modelo as $marca): ?>
                <option value="<?= $marca['id_marca'] ?>" 
                    <?= (($filters['id_marca'] ?? '') == $marca['id_marca']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($marca['nombre_marca']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="id_modelo" id="modelo-select">
            <option value="">Todos los modelos</option>
            <?php
            $marca_id_selected = $filters['id_marca'] ?? '';
            $modelo_id_selected = $filters['id_modelo'] ?? '';
            if($marca_id_selected && isset($marcas_modelo_json[$marca_id_selected])){
                foreach($marcas_modelo_json[$marca_id_selected]['modelos'] as $modelo){
                    $selected = ($modelo_id_selected == $modelo['id_modelo']) ? 'selected' : '';
                    echo "<option value='{$modelo['id_modelo']}' $selected>{$modelo['nombre']}</option>";
                }
            }
            ?>
        </select>

        <button type="submit">Buscar</button>
    </form>

    <!-- LISTADO DE MOTOS -->
    <table class="motos-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Matrícula</th>
                <th>VIN</th>
                <th>Moto</th>
                <th>Ficha técnica</th>
                <th>Características</th>
                <th>Precio</th>
                <th>Garantía</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($motos)): ?>
                <?php foreach ($motos as $moto): ?>
                    <tr>
                        <td><?= $moto["id_moto"] ?></td>
                        <td><?= htmlspecialchars($moto["matricula"]) ?></td>
                        <td><?= htmlspecialchars($moto["vin"]) ?></td>
                        <td class="moto-col"><strong><?= htmlspecialchars($moto["marca"] . ' ' . $moto["modelo"]) ?></strong></td>
                        <td class="ficha-col">
                            <div>📅 <?= $moto["anio"] ?></div>
                            <div>🛣️ <?= number_format($moto["km"],0,',','.') ?> km</div>
                            <div>⚙️ <?= $moto["cilindrada"] ?> cc</div>
                        </td>
                        <td class="caract-col">
                            <span class="tag tipo"><?= htmlspecialchars($moto["tipo"]) ?></span>
                            <span class="tag permiso"><?= htmlspecialchars($moto["permiso"]) ?></span>
                            <span class="tag color">🎨 <?= htmlspecialchars($moto["color"]) ?></span>
                        </td>
                        <td><?= number_format($moto["precio"],2,',','.') ?> €</td>
                        <td><?= $moto["garantia_meses"] ?> meses</td>
                        <td><span class="estado <?= strtolower($moto["estado"]) ?>"><?= htmlspecialchars($moto["estado"]) ?></span></td>
                        <td class="actions">
                            <a href="<?= BASE_URL ?>/control/panel/motos/ver?id=<?= $moto["id_moto"] ?>">👁️</a>
                            <a href="<?= BASE_URL ?>/control/panel/motos/editar?id=<?= $moto["id_moto"] ?>">✏️</a>
                            <a href="<?= BASE_URL ?>/control/panel/motos/eliminar?id=<?= $moto["id_moto"] ?>" onclick="return confirm('¿Seguro?')">🗑️</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="10">No hay motos registradas</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- PAGINACIÓN -->
    <?php if($totalPages > 1): ?>
        <div class="paginacion">
            <?php for($i = 1; $i <= $totalPages; $i++): ?>
                <?php 
                    $queryParams['page'] = $i;
                    $url = BASE_URL . '/control/panel/motos?' . http_build_query($queryParams);
                ?>
                <a href="<?= $url ?>" class="<?= $i==$currentPage?'active':'' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</main>

<script>
    // Filtrar modelos según la marca seleccionada
    const marcasModelos = <?= $marcas_modelo_json ?>;
    const marcaSelect = document.getElementById('marca-select');
    const modeloSelect = document.getElementById('modelo-select');

    marcaSelect.addEventListener('change', () => {
        const marcaId = marcaSelect.value;
        const selectedModelo = ""; // Reseteamos selección al cambiar marca
        modeloSelect.innerHTML = '<option value="">Todos los modelos</option>';
        if(marcaId && marcasModelos[marcaId]){
            marcasModelos[marcaId].modelos.forEach(m => {
                const option = document.createElement('option');
                option.value = m.id_modelo;
                option.textContent = m.nombre;
                if(m.id_modelo == selectedModelo) option.selected = true;
                modeloSelect.appendChild(option);
            });
        }
    });
</script>