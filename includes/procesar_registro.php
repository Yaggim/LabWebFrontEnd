<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

$response = [];

try {
    if ($usuario->isLoggedIn()) {
        if (!Permisos::tienePermiso("Registrar usuario", $_SESSION['usuario']['id'])) {
            header("Location: home");
            die;
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'register') {

        $nombre   = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $dni      = $_POST['dni'];
        $telefono = $_POST['telefono'];
        $email    = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $usuario->register($nombre, $apellido, $dni, $telefono, $username, $email, $password);

        $response['redirect'] = 'home';
    }
} catch (Exception $e) {
    $response['error'] = 'Error al realizar el registro: ' . $e->getMessage();
}

echo json_encode($response);
die;