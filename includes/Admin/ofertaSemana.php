<?php
require_once(__DIR__ . "/../../config/config.php"); 
require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../Crud.php");

class OfertaSemanaBBDD extends Crud {
    public function __construct() {
        parent::__construct("ofertas_semana");
    }

    public function getAllWithDetails() {
        $stmt = $this->conexion->prepare("
            SELECT 
                ofertas_semana.*, 
                productos.descripcion AS producto_descripcion,
                productos.precio_usd AS producto_precio_usd,
                productos.stock AS producto_stock,
                COALESCE(
                    (SELECT GROUP_CONCAT(url SEPARATOR ',') FROM imagenes_productos WHERE id_producto_fk = productos.id_producto),
                    ''
                ) AS imagenes,
                marcas.nombre AS producto_marca,
                modelos.nombre AS producto_modelo
            FROM ofertas_semana
            LEFT JOIN productos ON ofertas_semana.id_producto = productos.id_producto
            LEFT JOIN modelos ON productos.id_modelo = modelos.id_modelo
            LEFT JOIN marcas ON modelos.id_marca = marcas.id_marca
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByIdWithDetails($id) {
        $stmt = $this->conexion->prepare("
            SELECT 
                ofertas_semana.*, 
                productos.descripcion AS producto_descripcion,
                productos.precio_usd AS producto_precio_usd,
                productos.stock AS producto_stock,
                COALESCE(
                    (SELECT GROUP_CONCAT(url SEPARATOR ',') FROM imagenes_productos WHERE id_producto_fk = productos.id_producto),
                    ''
                ) AS imagenes,
                marcas.nombre AS producto_marca,
                modelos.nombre AS producto_modelo
            FROM ofertas_semana
            LEFT JOIN productos ON ofertas_semana.id_producto = productos.id_producto
            LEFT JOIN modelos ON productos.id_modelo = modelos.id_modelo
            LEFT JOIN marcas ON modelos.id_marca = marcas.id_marca
            WHERE ofertas_semana.id_oferta_semana = :id
        ");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);

        foreach ($result as &$row) {
            $row['imagenes'] = stripslashes($row['imagenes']);
        }
    }

    public function createOfertaSemana($data) {
        $this->conexion->beginTransaction();
    
        try {
            $stmt = $this->conexion->prepare("INSERT INTO ofertas_semana (descripcion, descuento, id_producto, fecha_inicio, fecha_fin) VALUES (:descripcion, :descuento, :id_producto, :fecha_inicio, :fecha_fin)");
            $stmt->bindParam(":descripcion", $data['descripcion']);
            $stmt->bindParam(":descuento", $data['descuento']);
            $stmt->bindParam(":id_producto", $data['id_producto']);
            $stmt->bindParam(":fecha_inicio", $data['fecha_inicio']);
            $stmt->bindParam(":fecha_fin", $data['fecha_fin']);
            $stmt->execute();
    
            $this->conexion->commit();
            return $this->conexion->lastInsertId();
        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }

    public function updateOfertaSemana($id, $data) {
        $this->conexion->beginTransaction();
    
        try {
            $stmt = $this->conexion->prepare("UPDATE ofertas_semana SET descripcion = :descripcion, descuento = :descuento, id_producto = :id_producto, fecha_inicio = :fecha_inicio, fecha_fin = :fecha_fin WHERE id_oferta_semana = :id");
            $stmt->bindParam(":descripcion", $data['descripcion']);
            $stmt->bindParam(":descuento", $data['descuento']);
            $stmt->bindParam(":id_producto", $data['id_producto']);
            $stmt->bindParam(":fecha_inicio", $data['fecha_inicio']);
            $stmt->bindParam(":fecha_fin", $data['fecha_fin']);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
    
            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }

    public function deleteOfertaSemana($id) {
        $this->conexion->beginTransaction();
    
        try {
            $stmt = $this->conexion->prepare("DELETE FROM ofertas_semana WHERE id_oferta_semana = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
    
            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }
}

$ofertaSemanaBBDD = new OfertaSemanaBBDD();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id_oferta_semana'])) {
            $id = $_GET['id_oferta_semana'];
            if (!is_numeric($id)) {
                echo json_encode(['error' => 'ID inválido']);
                break;
            }
            $result = $ofertaSemanaBBDD->getByIdWithDetails($id);
        } else {
            $result = $ofertaSemanaBBDD->getAllWithDetails();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['descripcion']) || !isset($data['descuento']) || empty($data['id_producto']) || empty($data['fecha_inicio']) || empty($data['fecha_fin'])) {
            echo json_encode(['error' => 'Todos los campos son obligatorios']);
            break;
        }
        try {
            $result = $ofertaSemanaBBDD->createOfertaSemana($data);
            echo json_encode(['result' => $result]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['id_oferta_semana']) || empty($data['descripcion']) || !isset($data['descuento']) || empty($data['id_producto']) || empty($data['fecha_inicio']) || empty($data['fecha_fin'])) {
            echo json_encode(['error' => 'Todos los campos son obligatorios']);
            break;
        }
        try {
            $result = $ofertaSemanaBBDD->updateOfertaSemana($data['id_oferta_semana'], $data);
            echo json_encode(['result' => $result]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['id_oferta_semana']) || !is_numeric($data['id_oferta_semana'])) {
            echo json_encode(['error' => 'ID inválido']);
            break;
        }
        try {
            $result = $ofertaSemanaBBDD->deleteOfertaSemana($data['id_oferta_semana']);
            echo json_encode(['result' => $result]);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['error' => 'Método no soportado']);
        break;
}
?>