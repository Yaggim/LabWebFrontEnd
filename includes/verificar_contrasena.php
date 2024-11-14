<?php

require_once __DIR__ .'/../config/config.php';

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);
$old_password = isset($data['oldPassword']) ? trim($data['oldPassword']) : null;

// Comparar la contraseña ingresada con la almacenada en la sesión
if (isset($_SESSION['usuario']['password']) && password_verify($old_password, $_SESSION['usuario']['password'])) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'La contraseña es incorrecta.']);
}

