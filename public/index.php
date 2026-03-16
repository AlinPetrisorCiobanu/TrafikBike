<?php

require_once "../app/config/config.php";
require_once "../app/core/router.php";

$router = new Router();

/*
|----------------------------
| rutas de la aplicación
|----------------------------
*/

$router->get('/', 'homecontroller@index');

$router->dispatch();