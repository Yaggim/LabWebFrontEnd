<?php
require_once(__DIR__ . "/../../config/config.php"); 
require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../Crud.php");

class EstadoVentaBBDD extends Crud {
    public function __construct() {
        parent::__construct("estadosVentas");
    }

    public function getAllEstados() {
        $stmt = $this->conexion->prepare("SELECT * FROM estados_ventas");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

$estadoVentaBBDD = new EstadoVentaBBDD();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $result = $estadoVentaBBDD->getAllEstados();
        echo json_encode($result);
        break;

    default:
        echo json_encode(['error' => 'Método no soportado']);
        break;
}
?>