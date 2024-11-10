<?php

require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../Crud.php");

class CategoriaBBDD extends Crud {
    public function __construct() {
        parent::__construct("categorias");
    }
}

$categoriaBBDD = new CategoriaBBDD();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id_categoria'])) {
            $id = $_GET['id_categoria'];
            if (!is_numeric($id)) {
                echo json_encode(['error' => 'ID inválido']);
                break;
            }
            $result = $categoriaBBDD->getById($id, 'id_categoria');
        } else {
            $result = $categoriaBBDD->getAll();
        }
        echo json_encode($result);
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['nombre'])) {
            echo json_encode(['error' => 'El nombre de la categoría es obligatorio']);
            break;
        }
        if (!is_string($data['nombre']) || strlen($data['nombre']) > 255) {
            echo json_encode(['error' => 'El nombre de la categoría debe ser una cadena de texto y no debe exceder los 255 caracteres']);
            break;
        }
        $result = $categoriaBBDD->create($data);
        if ($result) {
            echo json_encode(['result' => $result]);
        } else {
            echo json_encode(['error' => 'Error al crear la categoría']);
        }
        break;
    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['id_categoria']) || !is_numeric($data['id_categoria'])) {
            echo json_encode(['error' => 'ID inválido']);
            break;
        }
        if (empty($data['nombre'])) {
            echo json_encode(['error' => 'El nombre de la categoría es obligatorio']);
            break;
        }
        if (!is_string($data['nombre']) || strlen($data['nombre']) > 255) {
            echo json_encode(['error' => 'El nombre de la categoría debe ser una cadena de texto y no debe exceder los 255 caracteres']);
            break;
        }
        $id = $data['id_categoria'];
        unset($data['id_categoria']);
        $result = $categoriaBBDD->update($id, $data, 'id_categoria');
        if ($result) {
            echo json_encode(['result' => $result]);
        } else {
            echo json_encode(['error' => 'Error al actualizar la categoría']);
        }
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id_categoria'])) {
            $id = $data['id_categoria'];
            if (!is_numeric($id)) {
                echo json_encode(['error' => 'ID inválido']);
                break;
            }
            $result = $categoriaBBDD->deleteById($id, 'id_categoria');
            if ($result) {
                echo json_encode(['result' => $result]);
            } else {
                echo json_encode(['error' => 'Error al eliminar la categoría']);
            }
        }else{
            echo json_encode(['result' => 0, 'error' => 'ID de categoría no especificado']);
        }
        break;
    default:
            echo json_encode(['error' => 'Método no soportado']);
        break;
}
?>