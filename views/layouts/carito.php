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
                        <?php 
                            $cat = strtolower($item["categoria"]);
                            switch($cat){
                                case "casco": ?>
                                    <svg viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M12 2C7 2 3 6 3 11v4c0 1.1.9 2 2 2h2v-5H5c0-3.9 3.1-7 7-7s7 3.1 7 7h-2v5h2c1.1 0 2-.9 2-2v-4c0-5-4-9-9-9z"/>
                                    </svg>
                                <?php break;
                                case "guantes": ?>
                                    <svg viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M7 2v10H5V6H3v12h4v4h2v-8h2v8h2v-4h4V6h-2v6h-2V2h-2v10h-2V2H7z"/>
                                    </svg>
                                <?php break;
                                case "chaqueta": ?>
                                    <svg viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M12 2l4 2v4h2v14h-4v-6h-4v6H6V8h2V4l4-2z"/>
                                    </svg>
                                <?php break;
                                case "mono": ?>
                                    <svg viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M12 2l3 7h-6l3-7zm-4 9v11h2v-7h4v7h2V11H8z"/>
                                    </svg>
                                <?php break;
                                case "botas": ?>
                                    <svg viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M6 2v16h2v4h8v-4h2V2H6z"/>
                                    </svg>
                                <?php break;
                                case "chaleco": ?>
                                    <svg viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M12 2l6 6v12H6V8l6-6z"/>
                                    </svg>
                                <?php break;
                                default: ?>
                                    <svg viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M7 4h-2l-1 2v2h2l3.6 7.59-1.35 2.44C7.16 18.37 7 18.68 7 19a2 2 0 002 2h10v-2H9.42a.25.25 0 01-.23-.37L10.1 17h5.45a2 2 0 001.79-1.11l3.58-6.49A1 1 0 0020 8H6.21l-.94-2z"/>
                                    </svg>
                        <?php } ?>
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