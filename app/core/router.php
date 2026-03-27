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
        $httpMethod = $_SERVER['REQUEST_METHOD'];

        $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $url = str_replace(BASE_URL, "", $url);
        $url = rtrim($url, "/");

        if ($url === "") {
            $url = "/";
        }

        if (!isset($this->routes[$httpMethod][$url])) {
            http_response_code(404);
            echo "404 - Página no encontrada";
            return;
        }

        $action = $this->routes[$httpMethod][$url];
        list($controllerPath, $method) = explode('@', $action);

        $controllerPath = str_replace('\\', '/', $controllerPath);

        $parts = explode('/', $controllerPath);
        $controllerName = array_pop($parts);
        $directory = implode('/', $parts);

        $filePath = ROOT_PATH . "/app/controllers/";
        if (!empty($directory)) {
            $filePath .= strtolower($directory) . "/";
        }
        $filePath .= $controllerName . ".php";

        if (!file_exists($filePath)) {
            http_response_code(500);
            echo "Controlador no encontrado";
            return;
        }

        require_once $filePath;

        $controller = new $controllerName();

        if (!method_exists($controller, $method)) {
            http_response_code(500);
            echo "Método no encontrado";
            return;
        }

        $controller->$method();
    }
}