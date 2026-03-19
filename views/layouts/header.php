<?php
session_start();

$token = "";
$name = "";
$user = "";
$id = 0;

if(isset($_SESSION['token'])){
    $name = $_SESSION['nombre'];
    $token = $_SESSION['token'];
    $id = $_SESSION['id'];
    $user = $_SESSION['usuario'];
}

$cart = [
    "count" => 0,
    "total" => 0,
    "data" => []
];
/*   EJEMPLO DE PRODUCTOS EN EL CARRITO
$cart = [
    "count" => 10,
    "total" => 3_219.87, // Suma de precios finales * cantidad
    "data" => [
        [
            "id_item" => 1,
            "nombre" => "Casco Shoei GT-Air II",
            "categoria" => "casco",
            "precio_final" => 499.99,
            "cantidad" => 1
        ],
        [
            "id_item" => 2,
            "nombre" => "Guantes Alpinestars GP Pro",
            "categoria" => "guantes",
            "precio_final" => 149.99,
            "cantidad" => 2
        ],
        [
            "id_item" => 3,
            "nombre" => "Chaqueta Dainese Tempest",
            "categoria" => "chaqueta",
            "precio_final" => 299.99,
            "cantidad" => 1
        ],
        [
            "id_item" => 4,
            "nombre" => "Mono Alpinestars GP Pro",
            "categoria" => "mono",
            "precio_final" => 799.99,
            "cantidad" => 1
        ],
        [
            "id_item" => 5,
            "nombre" => "Botas Sidi Adventure 2",
            "categoria" => "botas",
            "precio_final" => 349.99,
            "cantidad" => 1
        ],
        [
            "id_item" => 6,
            "nombre" => "Chaleco Airbag Helite Turtle",
            "categoria" => "chaleco",
            "precio_final" => 499.99,
            "cantidad" => 1
        ],
        [
            "id_item" => 7,
            "nombre" => "Casco AGV K6",
            "categoria" => "casco",
            "precio_final" => 399.99,
            "cantidad" => 1
        ],
        [
            "id_item" => 8,
            "nombre" => "Guantes Dainese Tempest",
            "categoria" => "guantes",
            "precio_final" => 129.99,
            "cantidad" => 1
        ],
        [
            "id_item" => 9,
            "nombre" => "Chaqueta Rev'it Horizon",
            "categoria" => "chaqueta",
            "precio_final" => 299.99,
            "cantidad" => 1
        ],
        [
            "id_item" => 10,
            "nombre" => "Botas Gaerne G-Rocket",
            "categoria" => "botas",
            "precio_final" => 399.99,
            "cantidad" => 1
        ]
    ]
];
*/
?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrafikBike</title>

    <!-- CSS global -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/index.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/nav.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/carito.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/footer.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/motos.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/tienda.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/login.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/register.css">

    <!-- JS -->
     <!-- se añade defer para que primero cargue todo el contenido y despues el archivo js -->
    <script src="<?= BASE_URL ?>/assets/scripts/nav_control.js" defer></script>
    <script src="<?= BASE_URL ?>/assets/scripts/carito_control.js" defer></script>
    <script src="<?= BASE_URL ?>/assets/scripts/reg_handler.js" defer></script>

</head>
<body>
<header>
    <?php include __DIR__ . '/nav.php'; ?>
</header>