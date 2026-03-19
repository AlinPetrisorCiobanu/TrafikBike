document.addEventListener("DOMContentLoaded", () => {

    // --- ELEMENTOS DEL DOM ---
    const cartTrigger = document.querySelector(".cart-trigger");
    const cartOverlay = document.getElementById("cart-overlay");
    const cartDrawer = document.getElementById("cart-drawer");
    const cartContent = document.getElementById("cart-content");
    const cartCount = document.getElementById("cart-count");
    const cartTotal = document.getElementById("cart-total");
    const BASE_URL = "/practicas/trafikbike/public";

    // --- VARIABLE GLOBAL DEL CARRITO ---
    let $cart = {
        count: 0,
        total: 0,
        data: []
    };

    if(!cartTrigger || !cartOverlay || !cartDrawer || !cartContent) return;

    // --- ABRIR / CERRAR CARRITO ---
    cartTrigger.addEventListener("click", () => {
        cartDrawer.classList.add("active");
        cartOverlay.classList.add("active");
    });
    const closeCart = () => {
        cartDrawer.classList.remove("active");
        cartOverlay.classList.remove("active");
    };
    cartOverlay.addEventListener("click", closeCart);
    cartDrawer.querySelector("button").addEventListener("click", closeCart);

    // --- ACTUALIZAR DOM ---
    const renderCart = () => {
        cartContent.innerHTML = "";
        if($cart.count === 0){
            cartContent.innerHTML = `<div class="cart-empty">
                <p>Tu carrito está vacío 🛒</p>
                <a href="./tienda">Ir a comprar</a>
            </div>`;
        } else {
            $cart.data.forEach(item => {
                const div = document.createElement("div");
                div.classList.add("cart-item");
                div.dataset.id = item.id_item;
                div.innerHTML = `
                    <div class="cart-img">
                        <svg viewBox="0 0 24 24">
                            <path fill="currentColor" d="M7 4h-2l-1 2v2h2l3.6 7.59-1.35 2.44C7.16 18.37 7 18.68 7 19a2 2 0 002 2h10v-2H9.42a.25.25 0 01-.23-.37L10.1 17h5.45a2 2 0 001.79-1.11l3.58-6.49A1 1 0 0020 8H6.21l-.94-2z"/>
                        </svg>
                    </div>
                    <div class="cart-info">
                        <span class="cart-name">${item.nombre}</span>
                        <span class="cart-price">${parseFloat(item.precio_final).toFixed(2)} €</span>
                        <div class="cart-qty">
                            <button class="decrease">−</button>
                            <span>${item.cantidad}</span>
                            <button class="increase">+</button>
                        </div>
                    </div>
                    <button class="remove">✕</button>
                `;
                cartContent.appendChild(div);
            });
        }

        if(cartCount) cartCount.textContent = $cart.count;
        if(cartTotal) cartTotal.textContent = parseFloat($cart.total).toFixed(2) + " €";
    };

    // --- CARGAR CARRITO INICIAL ---
    const loadCart = () => {
        fetch(`${BASE_URL}/getcart`)
        .then(res => res.json())
        .then(data => {
            $cart = data;
            renderCart();
        })
        .catch(err => console.error("Error cargar carrito inicial:", err));
    };
    loadCart();

    // --- ACTUALIZAR CANTIDAD / ELIMINAR ---
    cartContent.addEventListener("click", e => {
        const btn = e.target;
        const cartItem = btn.closest(".cart-item");
        if(!cartItem) return;

        const id = cartItem.dataset.id;
        let action = null;
        if(btn.classList.contains("increase")) action = "increase";
        if(btn.classList.contains("decrease")) action = "decrease";
        if(btn.classList.contains("remove")) action = "remove";
        if(!action) return;

        fetch(`${BASE_URL}/updatecart`, {
            method: "POST",
            headers: {"Content-Type": "application/json"},
            body: JSON.stringify({id, action})
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                if(action === "remove" || data.cantidad === 0){
                    $cart.data = $cart.data.filter(item => item.id_item != id);
                } else {
                    const item = $cart.data.find(item => item.id_item == id);
                    if(item){
                        item.cantidad = data.cantidad;
                        item.precio_final = data.precio_final;
                    }
                }
                $cart.count = data.count;
                $cart.total = data.total;
                renderCart();
            }
        })
        .catch(err => console.error("Error actualizar carrito:", err));
    });

    // --- AÑADIR PRODUCTO ---
    document.querySelectorAll(".add-cart-form").forEach(form => {
        form.addEventListener("submit", e => {
            e.preventDefault();
            const id = form.dataset.id;

            fetch(`${BASE_URL}/addcart`, {
                method: "POST",
                headers: {"Content-Type": "application/json"},
                body: JSON.stringify({id})
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    // Actualizar variable $cart
                    const existingItem = $cart.data.find(item => item.id_item == id);
                    if(existingItem){
                        existingItem.cantidad = data.cantidad;
                        existingItem.precio_final = data.precio_final;
                    } else {
                        $cart.data.unshift({
                            id_item: id,
                            nombre: data.nombre,
                            cantidad: data.cantidad,
                            precio_final: data.precio_final,
                            categoria: data.categoria
                        });
                    }
                    $cart.count = data.count;
                    $cart.total = data.total;
                    renderCart();
                } else {
                    alert(data.message || "Error al añadir el producto");
                }
            })
            .catch(err => console.error("Error añadir al carrito:", err));
        });
    });

});