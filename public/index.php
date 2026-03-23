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
|  rutas a control panel
|----------------------------
*/

$router->get('/contro/panel', 'control_panelController@index');

/*
|----------------------------
|  rutas de control de los admins, vendedores y mecanicos
|----------------------------
*/

/*    Usuarios    */
$router->get('/control/panel/usuarios', 'control_panel_usuariosController@index');
$router->post('/control/panel/usuarios/modificar', 'control_panel_usuariosController@modificar');
$router->post('/control/panel/usuarios/eliminar', 'control_panel_usuariosController@eliminar');

/*    Motos    */
$router->get('/control/panel/motos', 'control_panel_motosController@index');
$router->post('/control/panel/motos/modificar', 'control_panel_motosController@modificar');
$router->post('/control/panel/motos/eliminar', 'control_panel_motosController@eliminar');

/*    Tienda    */
$router->get('/control/panel/tienda', 'control_panel_tiendaController@index');
$router->post('/control/panel/tienda/modificar', 'control_panel_tiendaController@modificar');
$router->post('/control/panel/tienda/eliminar', 'control_panel_tiendaController@eliminar');

/*    Taller    */
$router->get('/control/panel/taller', 'control_panel_tallerController@index');
$router->post('/control/panel/taller/modificar', 'control_panel_tallerController@modificar');
$router->post('/control/panel/taller/eliminar', 'control_panel_tallerController@eliminar');


$router->dispatch();