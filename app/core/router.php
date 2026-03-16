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

            list($controller, $method) = explode('@', $action);

            require_once ROOT_PATH."/app/controllers/".$controller.".php";

            $controller = new $controller();

            $controller->$method();

        }
        else
        {

            echo "404 - pagina no encontrada";

        }

    }

}