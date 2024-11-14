<?php

require_once __DIR__ .'/../config/config.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data['username'] ?? '');
$password = trim($data['password'] ?? '');

function generarRespuesta($result, $archivoForm) {
    if ($result['success']) {
        ob_start();
        include RUTA_PROYECTO . "/components/$archivoForm";
        $result['html'] = ob_get_clean();
    }
    echo json_encode($result);
}

if ($data['action'] === 'login') {
    // Aquí llama al método de login
    $result = $usuario->login($username, $password);
    generarRespuesta($result, 'form_logout.php');
    
} elseif ($data['action'] === 'logout') {
    // Aquí llama al método de logout
    $result = $usuario->logout();
    generarRespuesta($result, 'form_login.php');
}