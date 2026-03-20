<?php
session_start();

$token = "";
$name = "";
$user = "";
$rol = "";
$id = 0;

if(isset($_SESSION['token'])){
    $name = $_SESSION['nombre'];
    $token = $_SESSION['token'];
    $id = $_SESSION['id'];
    $user = $_SESSION['usuario'];
    $rol = $_SESSION['rol'];
}

$cart = [
    "count" => 0,
    "total" => 0,
    "data" => []
];

//   EJEMPLO DE PRODUCTOS EN EL CARRITO
// $cart = [
//     "count" => 10,
//     "total" => 1849.95,
//     "data" => [
//         [
//             "id_item" => 1,
//             "nombre" => "Casco Integral Shark",
//             "tipo" => "Casco",
//             "precio_final" => 249.95,
//             "cantidad" => 1
//         ],
//         [
//             "id_item" => 2,
//             "nombre" => "Guantes Racing Pro",
//             "tipo" => "Guantes",
//             "precio_final" => 59.95,
//             "cantidad" => 2
//         ],
//         [
//             "id_item" => 3,
//             "nombre" => "Chaqueta Moto Touring",
//             "tipo" => "Chaqueta",
//             "precio_final" => 199.95,
//             "cantidad" => 1
//         ],
//         [
//             "id_item" => 4,
//             "nombre" => "Mono Deportivo",
//             "tipo" => "Mono",
//             "precio_final" => 349.95,
//             "cantidad" => 1
//         ],
//         [
//             "id_item" => 5,
//             "nombre" => "Botas Urban Moto",
//             "tipo" => "Botas",
//             "precio_final" => 129.95,
//             "cantidad" => 1
//         ],
//         [
//             "id_item" => 6,
//             "nombre" => "Chaleco Reflectante",
//             "tipo" => "Chaleco",
//             "precio_final" => 49.95,
//             "cantidad" => 1
//         ],
//         [
//             "id_item" => 7,
//             "nombre" => "Casco Abierto City",
//             "tipo" => "Casco",
//             "precio_final" => 179.95,
//             "cantidad" => 1
//         ],
//         [
//             "id_item" => 8,
//             "nombre" => "Guantes Invierno",
//             "tipo" => "Guantes",
//             "precio_final" => 69.95,
//             "cantidad" => 1
//         ],
//         [
//             "id_item" => 9,
//             "nombre" => "Chaqueta Verano Ventilada",
//             "tipo" => "Chaqueta",
//             "precio_final" => 189.95,
//             "cantidad" => 1
//         ],
//         [
//             "id_item" => 10,
//             "nombre" => "Botas Off-Road Adventure",
//             "tipo" => "Botas",
//             "precio_final" => 189.95,
//             "cantidad" => 1
//         ]
//     ]
// ];

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
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/contacto.css">

    <!-- JS -->
     <!-- se añade defer para que primero cargue todo el contenido y despues el archivo js -->
    <script src="<?= BASE_URL ?>/assets/scripts/nav_control.js" defer></script>
    <script src="<?= BASE_URL ?>/assets/scripts/carito_control.js" defer></script>
    <script src="<?= BASE_URL ?>/assets/scripts/cart.js"></script>
    <script src="<?= BASE_URL ?>/assets/scripts/reg_handler.js" defer></script>

</head>
<body>
<header>
    <?php include __DIR__ . '/nav.php'; ?>
</header>