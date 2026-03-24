<?php

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

?>

<!DOCTYPE html>
<html lang="es">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrafikBike</title>

    <!-- CSS global -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/main.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/nav.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/carito.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/footer.css">

    <!-- CSS dinámico -->
    <?php if (isset($styles)): ?>
        <?php foreach ($styles as $style): ?>
            <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/<?= $style ?>">
        <?php endforeach; ?>
    <?php endif; ?>

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