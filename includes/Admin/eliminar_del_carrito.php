<?php
session_start();
require_once '../includes/Admin/carrito.php';

$data = json_decode(file_get_contents('php://input'), true);
$productoId = $data['producto_id'];

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$carrito = new Carrito($userId);

$response = ['success' => false];
if ($carrito->eliminarDelCarrito($productoId)) {
    $response['success'] = true;
} else {
    $response['message'] = 'Error al eliminar el producto del carrito.';
}

echo json_encode($response);
?>