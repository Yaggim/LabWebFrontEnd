<?php
session_start();
require_once '../includes/Admin/carrito.php';

header('Content-Type: application/json');

try {
    $userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    $carrito = new Carrito($userId);
    $productosCarrito = $carrito->verCarrito();

    echo json_encode($productosCarrito);
} catch (Exception $e) {
    // Manejo de errores
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>