<main>
    <h1 class="h_controlPanel">Control Panel - Motos</h1>

    <!-- BUSCADOR -->
    <form method="GET" action="<?= BASE_URL ?>/control/panel/motos" class="form-buscador">

        <input type="text" name="matricula" placeholder="Matrícula" value="<?= htmlspecialchars($filters['matricula'] ?? '') ?>">
        <input type="text" name="vin" placeholder="Bastidor / VIN" value="<?= htmlspecialchars($filters['vin'] ?? '') ?>">

        <select name="marca">
            <option value="">Todas las marcas</option>
            <?php foreach($marcas_modelo as $id => $marca): ?>
                <option value="<?= $id ?>" <?= ($filters['id_marca'] ?? '') == $id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($marca['nombre_marca']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="modelo">
            <option value="">Todos los modelos</option>
            <?php if(!empty($filters['id_marca']) && isset($marcas_modelo_json[$filters['id_marca']])): ?>
                <?php foreach($marcas_modelo_json[$filters['id_marca']]['modelos'] as $modelo): ?>
                    <option value="<?= $modelo['id_modelo'] ?>" <?= ($filters['id_modelo'] ?? '') == $modelo['id_modelo'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($modelo['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            <?php endif; ?>
        </select>

        <button type="submit">Buscar</button>
    </form>

    <p><?= $totalMotos ?> resultados encontrados</p>

    <!-- TABLA DE MOTOS -->
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
            <?php if(!empty($motos)): ?>
                <?php foreach($motos as $moto): ?>
                    <tr>
                        <td><?= $moto['id_moto'] ?></td>
                        <td><?= htmlspecialchars($moto['matricula']) ?></td>
                        <td><?= htmlspecialchars($moto['vin']) ?></td>
                        <td><?= htmlspecialchars($moto['marca'] . ' ' . $moto['modelo']) ?></td>
                        <td>
                            <div>📅 <?= $moto['anio'] ?></div>
                            <div>🛣️ <?= number_format($moto['km'],0,',','.') ?> km</div>
                            <div>⚙️ <?= $moto['cilindrada'] ?> cc</div>
                        </td>
                        <td>
                            <span class="tag tipo"><?= htmlspecialchars($moto['tipo']) ?></span>
                            <span class="tag permiso"><?= htmlspecialchars($moto['permiso']) ?></span>
                            <span class="tag color">🎨 <?= htmlspecialchars($moto['color']) ?></span>
                        </td>
                        <td><?= number_format($moto['precio'],2,',','.') ?> €</td>
                        <td><?= $moto['garantia_meses'] ?> meses</td>
                        <td><span class="estado <?= strtolower($moto['estado']) ?>"><?= htmlspecialchars($moto['estado']) ?></span></td>
                        <td class="actions">
                            <a href="<?= BASE_URL ?>/control/panel/motos/ver?id=<?= $moto['id_moto'] ?>">👁️</a>
                            <a href="<?= BASE_URL ?>/control/panel/motos/editar?id=<?= $moto['id_moto'] ?>">✏️</a>
                            <a href="<?= BASE_URL ?>/control/panel/motos/eliminar?id=<?= $moto['id_moto'] ?>" onclick="return confirm('¿Seguro?')">🗑️</a>
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
            <?php for($i=1;$i<=$totalPages;$i++): ?>
                <?php 
                    $queryParams['page']=$i;
                    $url = BASE_URL.'/control/panel/motos?'.http_build_query($queryParams);
                ?>
                <a href="<?= $url ?>" class="<?= $i==$currentPage?'active':'' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</main>