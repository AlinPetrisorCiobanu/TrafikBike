<?php

    class Controller
    {
        public function view($view, $data = [])
        {
            extract($data);

            require_once __DIR__ . "/../../views/layouts/header.php";
            require_once __DIR__ . "/../../views/$view.php";
            require_once __DIR__ . "/../../views/layouts/footer.php";
        }
        
        // Redirección centralizada
        protected function redirect($path) {
            // Asegúrate de que BASE_URL está definido en config.php
            header("Location: " . BASE_URL . $path);
            exit;
        }
    }