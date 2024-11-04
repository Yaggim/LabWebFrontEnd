<?php

require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../Crud.php");

class MarcaBBDD extends Crud {
    public function __construct() {
        parent::__construct("marcas");
    }

    public function hasRelatedRecords($id_marca) {

        $queryModelos = "SELECT COUNT(*) FROM modelos WHERE id_marca = :id_marca";

        $stmtModelos = $this->conexion->prepare($queryModelos);
        $stmtModelos->bindParam(":id_marca", $id_marca);
        $stmtModelos->execute();

        $countModelos = $stmtModelos->fetchColumn();

        return  $countModelos > 0;
    }
}

$marcaBBDD = new MarcaBBDD();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id_marca'])) {
            $id = $_GET['id_marca'];
            if (!is_numeric($id)) {
                echo json_encode(['error' => 'ID inválido']);
                break;
            }
            $result = $marcaBBDD->getById($id, 'id_marca');
        } else {
            $result = $marcaBBDD->getAll();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['nombre'])) {
            echo json_encode(['error' => 'El nombre de la marca es obligatorio']);
            break;
        }
        if (!is_string($data['nombre']) || strlen($data['nombre']) > 255) {
            echo json_encode(['error' => 'El nombre de la marca debe ser una cadena de texto y no debe exceder los 255 caracteres']);
            break;
        }
        $result = $marcaBBDD->create($data);
        if ($result) {
            echo json_encode(['result' => $result]);
        } else {
            echo json_encode(['error' => 'Error al crear la marca']);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['id_marca']) || !is_numeric($data['id_marca'])) {
            echo json_encode(['error' => 'ID inválido']);
            break;
        }
        if (empty($data['nombre'])) {
            echo json_encode(['error' => 'El nombre de la marca es obligatorio']);
            break;
        }
        if (!is_string($data['nombre']) || strlen($data['nombre']) > 255) {
            echo json_encode(['error' => 'El nombre de la marca debe ser una cadena de texto y no debe exceder los 255 caracteres']);
            break;
        }
        $id = $data['id_marca'];
        unset($data['id_marca']);
        $result = $marcaBBDD->update($id, $data, 'id_marca');
        if ($result) {
            echo json_encode(['result' => $result]);
        } else {
            echo json_encode(['error' => 'Error al actualizar la marca']);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id_marca'])) {
            $id = $data['id_marca'];
            if (!is_numeric($id)) {
                echo json_encode(['error' => 'ID inválido']);
                break;
            }

            if ($marcaBBDD->hasRelatedRecords($id)) {
                echo json_encode(['error' => 'No se puede eliminar la marca porque está relacionada con productos o modelos']);
                break;
            }

            $result = $marcaBBDD->deleteById($id, 'id_marca');
            if ($result) {
                echo json_encode(['result' => $result]);
            } else {
                echo json_encode(['error' => 'Error al eliminar la marca']);
            }
        } else {
            echo json_encode(['result' => 0, 'error' => 'ID de marca no proporcionado']);
        }
        break;

    default:
        echo json_encode(['error' => 'Método no soportado']);
        break;
}
?>