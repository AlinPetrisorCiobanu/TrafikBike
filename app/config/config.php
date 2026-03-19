<?php
/*
|--------------------------------------------------------------------------
| Configuración base de la app
|--------------------------------------------------------------------------
*/

// URL base de la aplicación
// Cambia esta ruta según el entorno
// Local: "/practicas/trafikbike/public"

// Producción: ""
// define("BASE_URL", getenv('BASE_URL') ?: "");

// Localhost
define("BASE_URL", getenv('BASE_URL') ?: "/practicas/trafikbike/public");

// Ruta raíz del proyecto
define("ROOT_PATH", dirname(__DIR__, 2));

// Configuración de base de datos
define("DB_HOST", getenv('DB_HOST') ?: 'localhost');
define("DB_USER", getenv('DB_USER') ?: 'root');
define("DB_PORT", getenv('DB_PORT') ?: '3306');
define("DB_PASS", getenv('DB_PASS') ?: '');
define("DB_NAME", getenv('DB_NAME') ?: 'trafikbike');
