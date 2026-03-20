<!-- BOTÓN CARRITO -->
<div class="cart-trigger" onclick="openCart()">
    🛒
    <span id="cart-count"><?= $cart["count"] ?></span>
</div>

<!-- OVERLAY -->
<div id="cart-overlay" onclick="closeCart()"></div>

<!-- DRAWER -->
<div id="cart-drawer">

    <div class="cart-header">
        <h3>Tu carrito</h3>
        <button onclick="closeCart()">✕</button>
    </div>

    <div id="cart-content">

        <?php if ($token && count($cart["data"]) > 0): ?>
            <?php foreach($cart["data"] as $item): ?>
                <div class="cart-item" data-id="<?= $item["id_item"] ?>">
                    
                    <div class="cart-img">
                        <img src="assets/img/productos/default.jpg" alt="Imagen no disponible">
                    </div>

                    <div class="cart-info">
                        <span class="cart-name"><?= $item["nombre"] ?></span>
                        <span class="cart-price"><?= number_format($item["precio_final"],2) ?> €</span>

                        <div class="cart-qty">
                            <button class="qty-decrease" data-id="<?= $item["id_item"] ?>">−</button>
                            <span class="cart-qty-value"><?= $item["cantidad"] ?></span>
                            <button class="qty-increase" data-id="<?= $item["id_item"] ?>">+</button>
                        </div>
                    </div>

                    <button class="cart-remove" data-id="<?= $item["id_item"] ?>">✕</button>
                </div>
            <?php endforeach; ?>

        <?php else: ?>

            <div class="cart-empty">
                <p>Tu carrito está vacío 🛒</p>
                <a href="./tienda">Ir a comprar</a>
            </div>

        <?php endif; ?>

    </div>

    <div class="cart-footer">
        <div class="cart-total">
            <span>Total</span>
            <strong id="cart-total"><?= number_format($cart["total"],2) ?> €</strong>
        </div>

        <a href="./pasarela" class="checkout-btn">Finalizar compra</a>
    </div>

</div>