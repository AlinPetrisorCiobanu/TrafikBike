<?php

require_once "../app/config/config.php";
require_once "../app/core/router.php";

$router = new Router();

/*
|----------------------------
| rutas de la aplicación
|----------------------------
*/

$router->get('/', 'homeController@index');
$router->get('/motos', 'motosController@index');
$router->get('/tienda', 'tiendaController@index');

/*
|----------------------------
| rutas authenticacion de la aplicacion
|----------------------------
*/

$router->get('/login', 'authController@index');
$router->post('/login', 'authController@login');
$router->get('/logout', 'authController@logout');

$router->dispatch();