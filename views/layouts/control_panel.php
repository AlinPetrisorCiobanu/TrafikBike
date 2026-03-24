<?php 

    require_once __DIR__ . "/header/header.php";
    require_once __DIR__ . '/sidebar/sidebar.php'; 

?>

<main class="admin-layout">
    <?php require_once __DIR__ . "/../$view.php"; ?>
</main>

<?php require_once __DIR__ . "/footer/footer.php"; ?>