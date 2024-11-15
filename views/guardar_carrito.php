<?php
session_start();

// Verificar si los datos llegan como JSON
$data = file_get_contents('php://input');
if ($data) {
    $carrito = json_decode($data, true);

    if (is_array($carrito)) {
        // Guardar el carrito en la sesión
        $_SESSION['carrito'] = $carrito;

        // Responder con éxito
        http_response_code(200); //PROCESO CORRECTAEMENTE LA SOLICITUR
        echo json_encode(['mensaje' => 'Carrito guardado en la sesión!!!']);
    } else {
        // Error en el formato de los datos
        http_response_code(400); //ERROR DE PROCESO EN LA SOLICITUD
        echo json_encode(['mensaje' => 'Datos de carrito inválidos.']);
    }
} else {
    // No se recibieron datos
    http_response_code(400);
    echo json_encode(['mensaje' => 'No se recibieron datos del carrito.']);
}
