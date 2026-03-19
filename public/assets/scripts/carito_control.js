function openCart() {
    document.getElementById('cart-drawer').classList.add('active');
    document.getElementById('cart-overlay').classList.add('active');
}

function closeCart() {
    document.getElementById('cart-drawer').classList.remove('active');
    document.getElementById('cart-overlay').classList.remove('active');
}

/* CERRAR CON ESC */
document.addEventListener('keydown', (e) => {
    if (e.key === "Escape") closeCart();
});