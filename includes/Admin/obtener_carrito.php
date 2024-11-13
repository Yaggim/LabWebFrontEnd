<?php
session_start();
require_once '../includes/Admin/carrito.php';

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$carrito = new Carrito($userId);
$productosCarrito = $carrito->verCarrito();

echo json_encode($productosCarrito);
?>