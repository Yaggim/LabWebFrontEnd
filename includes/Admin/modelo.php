<?php
require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../Crud.php");

class ModeloBBDD extends Crud {
    public function __construct() {
        parent::__construct("modelos"); 
    }

    // Verifica si una marca existe en la tabla de "marcas"
    public function checkMarcaExists($id_marca) {
        $stmt = $this->conexion->prepare("SELECT COUNT(*) FROM marcas WHERE id_marca = :id_marca");
        $stmt->bindParam(":id_marca", $id_marca);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function hasRelatedRecords($id_modelo) {
        $queryProductos = "SELECT COUNT(*) FROM productos WHERE id_modelo = :id_modelo";

        $stmtProductos = $this->conexion->prepare($queryProductos);
        $stmtProductos->bindParam(":id_modelo", $id_modelo);
        $stmtProductos->execute();
        
        $countProductos = $stmtProductos->fetchColumn();

        return $countProductos > 0;
    }

    // Obtiene todos los modelos junto con el nombre de la marca correspondiente
    public function getAllWithMarca() {
        $stmt = $this->conexion->prepare("
            SELECT modelos.*, marcas.nombre AS nombre_marca 
            FROM modelos 
            LEFT JOIN marcas ON modelos.id_marca = marcas.id_marca
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtiene un modelo específico junto con el nombre de la marca
    public function getByIdWithMarca($id) {
        $stmt = $this->conexion->prepare("
            SELECT modelos.*, marcas.nombre AS nombre_marca 
            FROM modelos 
            LEFT JOIN marcas ON modelos.id_marca = marcas.id_marca 
            WHERE modelos.id_modelo = :id
        ");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

// Instancia de la clase ModeloBBDD
$modeloBBDD = new ModeloBBDD();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        $result = $modeloBBDD->getAllWithMarca();
        echo json_encode($result);
        break;
    
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (empty($data['nombre']) || empty($data['id_marca'])) {
            echo json_encode(['error' => 'El nombre del modelo y la marca son obligatorios']);
            break;
        }
        
        if (!is_string($data['nombre']) || strlen($data['nombre']) > 255) {
            echo json_encode(['error' => 'El nombre del modelo debe ser una cadena de texto y no debe exceder los 255 caracteres']);
            break;
        }
        
        if (!is_numeric($data['id_marca']) || !$modeloBBDD->checkMarcaExists($data['id_marca'])) {
            echo json_encode(['error' => 'ID de marca inválido o no existente']);
            break;
        }
        
        $result = $modeloBBDD->create($data);
        echo json_encode($result ? ['result' => $result] : ['error' => 'Error al crear el modelo']);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_modelo']) || !is_numeric($data['id_modelo'])) {
            echo json_encode(['error' => 'ID de modelo inválido']);
            break;
        }
        
        if (empty($data['nombre']) || empty($data['id_marca'])) {
            echo json_encode(['error' => 'El nombre del modelo y la marca son obligatorios']);
            break;
        }
        
        if (!is_string($data['nombre']) || strlen($data['nombre']) > 255) {
            echo json_encode(['error' => 'El nombre del modelo debe ser una cadena de texto y no debe exceder los 255 caracteres']);
            break;
        }
        
        if (!is_numeric($data['id_marca']) || !$modeloBBDD->checkMarcaExists($data['id_marca'])) {
            echo json_encode(['error' => 'ID de marca inválido o no existente']);
            break;
        }

        $id = $data['id_modelo'];
        unset($data['id_modelo']);
        $result = $modeloBBDD->update($id, $data, 'id_modelo');
        echo json_encode($result ? ['result' => $result] : ['error' => 'Error al actualizar el modelo']);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (isset($data['id_modelo'])) {
            $id = $data['id_modelo'];
            
            if (!is_numeric($id)) {
                echo json_encode(['error' => 'ID inválido']);
                break;
            }
            
            if ($modeloBBDD->hasRelatedRecords($id)) {
                echo json_encode(['error' => 'No se puede eliminar el modelo porque está relacionado con productos']);
                break;
            }

            $result = $modeloBBDD->deleteById($id, 'id_modelo');
            echo json_encode($result ? ['result' => $result] : ['error' => 'Error al eliminar el modelo']);
        } else {
            echo json_encode(['error' => 'id_modelo no proporcionado']);
        }
        break;

    default:
        echo json_encode(['error' => 'Método no soportado']);
        break;
}
?>
