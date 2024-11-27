<?php

session_start();

$ruta_base = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$ruta_actual = str_replace('\\', '/', __DIR__);
$ruta_relativa = str_replace($ruta_base, '', $ruta_actual);
$carpeta_proyecto = explode('/', trim($ruta_relativa, '/'))[0];

define('CARPETA_PROYECTO', $carpeta_proyecto);

define('RUTA_PROYECTO', $_SERVER['DOCUMENT_ROOT'].'/'.CARPETA_PROYECTO);

function loadEnv($path = __DIR__.'/../.env') {
    if (!file_exists($path)) {
        die("Archivo .env no encontrado.");
    }
    $env = parse_ini_file($path);
    foreach ($env as $key => $value) {
        putenv("$key=$value");
    }
}


loadEnv();


require_once RUTA_PROYECTO.'/includes/conexion.php';

require_once RUTA_PROYECTO.'/includes/usuario.php';
$usuario = new Usuario("usuarios");

require_once RUTA_PROYECTO.'/includes/permisos.php';
Permisos::setConexion();
