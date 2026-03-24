<?php

    class Controller
    {
        public function view($view, $data = [], $options = []){

                extract($data);

                $layout = $options['layout'] ?? 'main';

                require_once __DIR__ . "/../../views/layouts/$layout.php";
            }
        
        // Redirección centralizada
        protected function redirect($path) {
            // Asegúrate de que BASE_URL está definido en config.php
            header("Location: " . BASE_URL . $path);
            exit;
        }
    }