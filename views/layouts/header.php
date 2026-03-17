<?php
session_start();

$cart = ["count"=>0, "data"=>[], "total"=>0];

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
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/footer.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/motos.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/tienda.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/login.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/styles/register.css">

    <!-- JS -->
     <!-- se añade defer para que primero cargue todo el contenido y despues el archivo js -->
    <script src="<?= BASE_URL ?>/assets/scripts/nav_control.js" defer></script>

</head>
<body>
<header>
    <?php include __DIR__ . '/nav.php'; ?>
</header>