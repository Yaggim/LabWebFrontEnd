<?php
require_once __DIR__ . '/../config/config.php';

header('Content-Type: application/json');

$response = [];

try {
    // Verifica si el usuario está logueado
    if (!$usuario->isLoggedIn()) {
        $response['redirect'] = 'home';
        echo json_encode($response);
        die;
    }

    // Verifica si el usuario tiene permisos para editar el perfil
    if (!Permisos::tienePermiso("Editar perfil", $_SESSION['usuario']['id'])) {
        $response['redirect'] = 'home';
        echo json_encode($response);
        die;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $telefono = $_POST['telefono'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $oldPassword = $_POST['oldPassword'];

        $usuario->actualizarPerfilUsuario($telefono, $email, $password, $oldPassword);

        $response['redirect'] = 'home';
    }
} catch (Exception $e) {
    $response['error'] = 'Error al editar datos: ' . $e->getMessage();
}

echo json_encode($response);
die;

