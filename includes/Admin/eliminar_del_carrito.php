<?php
session_start();
require_once '../includes/Admin/carrito.php';
require_once '../config.php';
require_once RUTA_PROYECTO.'/includes/usuario.php';

$usuario = new Usuario("usuarios");

header('Content-Type: application/json');

try {
    $data = json_decode(file_get_contents('php://input'), true);
    if (!isset($data['username']) || !isset($data['password'])) {
        throw new Exception('Nombre de usuario o contraseña no proporcionados.');
    }

    $username = $data['username'];
    $password = $data['password'];

    if (!$usuario->login($username, $password)) {
        throw new Exception('Debe iniciar sesión para eliminar productos del carrito.');
    }

    if (!isset($data['producto_id'])) {
        throw new Exception('ID de producto no proporcionado.');
    }

    $productoId = $data['producto_id'];
    $userId = $_SESSION['user_id'];
    $carrito = new Carrito($userId);

    $response = ['success' => false];
    if ($carrito->eliminarDelCarrito($productoId)) {
        $response['success'] = true;
    } else {
        $response['message'] = 'Error al eliminar el producto del carrito.';
    }

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>