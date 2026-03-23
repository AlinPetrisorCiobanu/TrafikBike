<?php
session_start();

require_once "../app/config/config.php";
require_once "../app/core/router.php";

$router = new Router();

/*
|----------------------------
| rutas para el Usuario de la aplicación
|----------------------------
*/

$router->get('/', 'homeController@index');
$router->get('/motos', 'motosController@index');
$router->get('/tienda', 'tiendaController@index');
$router->get('/taller', 'tallerController@index');
$router->get('/contacto', 'contactoController@index');
$router->get('/usuario', 'usuarioController@index');

/*
|----------------------------
| rutas authenticacion de la aplicacion
|----------------------------
*/

$router->get('/login', 'authController@index');
$router->post('/login', 'authController@login');
$router->get('/logout', 'authController@logout');

/*
|----------------------------
| rutas register de la aplicacion
|----------------------------
*/

$router->get('/register', 'registerController@index');
$router->post('/register', 'registerController@register');

/*
|----------------------------
|  llamadas ajax para comunicar front-back
|----------------------------
*/

$router->get('/getcart', 'cartController@getCart');
$router->post('/addcart', 'cartController@ajaxAdd');
$router->post('/updatecart', 'cartController@ajaxUpdate');

/*
|----------------------------
|  rutas a control panel de los admins, vendedores y mecanicos
|----------------------------
*/

$router->get('/contro/panel', 'control_panelController@index');

$router->dispatch();