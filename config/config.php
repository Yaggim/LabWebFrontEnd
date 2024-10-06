<?php

$ruta_base = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
$ruta_actual = str_replace('\\', '/', __DIR__);
$ruta_relativa = str_replace($ruta_base, '', $ruta_actual);
$carpeta_proyecto = explode('/', trim($ruta_relativa, '/'))[0];

define('CARPETA_PROYECTO', $carpeta_proyecto);

define('RUTA_PROYECTO', $_SERVER['DOCUMENT_ROOT'].'/'.CARPETA_PROYECTO);