<?php

class Router {

    private $routes = [];

    public function get($url, $action)
    {
        $this->routes['GET'][$url] = $action;
    }

    public function post($url, $action)
    {
        $this->routes['POST'][$url] = $action;
    }

    public function dispatch()
    {

        $method = $_SERVER['REQUEST_METHOD'];

        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $base = BASE_URL;

        $url = str_replace($base, "", $url);

        $url = rtrim($url, "/");

        if($url === ""){
            $url = "/";
        }

        if(isset($this->routes[$method][$url]))
        {

            $action = $this->routes[$method][$url];

            list($controllerPath, $method) = explode('@', $action);

            // Convertir namespace tipo ControlPanel/DashboardController
            $controllerPath = str_replace('\\', '/', $controllerPath);

            // Separar carpeta(s) y clase
            $parts = explode('/', $controllerPath);
            $controllerName = array_pop($parts);
            $directory = implode('/', $parts);  

            // Construir ruta del archivo
            $filePath = ROOT_PATH . "/app/controllers/";

            if (!empty($directory)) {
                $filePath .= strtolower($directory) . "/";
            }

            $filePath .= $controllerName . ".php";

            if (!file_exists($filePath)) {
                echo "Controlador no encontrado";
                return;
            }

            require_once $filePath;

            // Instanciar controlador
            $controller = new $controllerName();

            if (!method_exists($controller, $method)) {
                echo "Método no encontrado";
                return;
            }

            $controller->$method();

            $controller->$method();

        }
        else
        {

            echo "404 - pagina no encontrada";

        }

    }

}