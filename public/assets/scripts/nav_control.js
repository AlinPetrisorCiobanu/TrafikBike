
const open_menu = () => {
    const menu = document.getElementById("menu");
    menu.classList.toggle("active");
};
const open_cart = () => {
    const cart = document.querySelector('.cart-dropdown');
    cart.classList.toggle("cart_active");
}