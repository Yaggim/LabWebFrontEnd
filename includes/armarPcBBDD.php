<?php

require_once(__DIR__ . "../clasesBBDD.php");

$categoria  = null;
$marca      = null;
$resultados = null;

header('Content-Type: application/json'); 

if (isset($_GET['categoria']) && !empty($_GET['categoria'])) {
    $categoria = $_GET['categoria'];

    $obj = new ProductoBBDD();

    if (isset($_GET['marca']) && !empty($_GET['marca'])) {
        $marca = $_GET['marca'];
        $resultados = $obj->getProdByCategoriaAndMarca($marca, $categoria);
    } else {
        $resultados = $obj->getProdByCategoria($categoria);
    }
} else {
    // Si no se recibe una categoría, devuelve un error
    $resultados = ["error" => "Categoría no especificada o vacía"];
}


generarResponse($resultados);

function generarResponse($resultados) {
    echo json_encode($resultados);
}

?>
