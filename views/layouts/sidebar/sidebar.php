<nav class="sidebar">
    <ul>
        <li>
            <a href="<?= BASE_URL ?>/control/panel" 
               class="<?= ($active ?? '') === 'home' ? 'active' : '' ?>">
                <span class="icon">🏠</span>
                <span class="text">Home</span>
            </a>
        </li>
<?php if($rol !== "VENDEDOR" && $rol !== "MECANICO") { ?>
    <li>
        <a href="<?= BASE_URL ?>/control/panel/usuarios" 
           class="<?= ($active ?? '') === 'usuarios' ? 'active' : '' ?>">
            <span class="icon">👤</span>
            <span class="text">Usuarios</span>
        </a>
    </li>

<?php } 
if($rol !== "MECANICO" ) { ?>
        <li>
            <a href="<?= BASE_URL ?>/control/panel/motos" 
               class="<?= ($active ?? '') === 'motos' ? 'active' : '' ?>">
                <span class="icon">🏍️</span>
                <span class="text">Motos</span>
            </a>
        </li>

        <li>
            <a href="<?= BASE_URL ?>/control/panel/tienda" 
               class="<?= ($active ?? '') === 'tienda' ? 'active' : '' ?>">
                <span class="icon">🛒</span>
                <span class="text">Tienda</span>
            </a>
        </li>
<?php } 
if($rol !== "VENDEDOR") { ?>
        <li>
            <a href="<?= BASE_URL ?>/control/panel/taller" 
               class="<?= ($active ?? '') === 'taller' ? 'active' : '' ?>">
                <span class="icon">🔧</span>
                <span class="text">Taller</span>
            </a>
        </li>
    </ul>
<?php } ?>    
</nav>