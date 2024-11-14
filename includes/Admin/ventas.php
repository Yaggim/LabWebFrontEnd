<?php
require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../Crud.php");

class VentasBBDD extends Crud {
    public function __construct() {
        parent::__construct("ventas");
    }

    public function getAllVentas() {
        $stmt = $this->conexion->prepare("
            SELECT 
                ventas_cabecera.*, 
                estados_ventas.estado,
                personas.nombre AS persona_nombre
            FROM ventas_cabecera
            LEFT JOIN estados_ventas ON ventas_cabecera.id_estado_venta = estados_ventas.id_estados_ventas
            LEFT JOIN personas ON ventas_cabecera.id_persona = personas.id_persona
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVentaDetalles($id_venta_cabecera) {
        $stmt = $this->conexion->prepare("
            SELECT 
                ventas_detalle.*, 
                CONCAT(marcas.nombre, ' ', modelos.nombre) AS producto_nombre,
                combos.nombre AS combo_nombre
            FROM ventas_detalle
            LEFT JOIN productos ON ventas_detalle.id_producto = productos.id_producto
            LEFT JOIN modelos ON productos.id_modelo = modelos.id_modelo
            LEFT JOIN marcas ON modelos.id_marca = marcas.id_marca
            LEFT JOIN combos ON ventas_detalle.id_combo = combos.id_combo
            WHERE ventas_detalle.id_venta_cabecera = :id_venta_cabecera
        ");
        $stmt->bindParam(":id_venta_cabecera", $id_venta_cabecera);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVentasByFilter($filter) {
        $query = "
            SELECT 
                ventas_cabecera.*, 
                estados_ventas.estado,
                personas.nombre AS persona_nombre
            FROM ventas_cabecera
            LEFT JOIN estados_ventas ON ventas_cabecera.id_estado_venta = estados_ventas.id_estados_ventas
            LEFT JOIN personas ON ventas_cabecera.id_persona = personas.id_persona
            WHERE 1=1
        ";

        if (!empty($filter['producto'])) {
            $query .= " AND ventas_cabecera.id_venta_cabecera IN (
                SELECT id_venta_cabecera FROM ventas_detalle WHERE id_producto = :producto
            )";
        }

        if (!empty($filter['combo'])) {
            $query .= " AND ventas_cabecera.id_venta_cabecera IN (
                SELECT id_venta_cabecera FROM ventas_detalle WHERE id_combo = :combo
            )";
        }

        $stmt = $this->conexion->prepare($query);

        if (!empty($filter['producto'])) {
            $stmt->bindParam(":producto", $filter['producto']);
        }

        if (!empty($filter['combo'])) {
            $stmt->bindParam(":combo", $filter['combo']);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function updateVentaEstado($id_venta_cabecera, $id_estado_venta) {
        $stmt = $this->conexion->prepare("
            UPDATE ventas_cabecera 
            SET id_estado_venta = :id_estado_venta 
            WHERE id_venta_cabecera = :id_venta_cabecera
        ");
        $stmt->bindParam(":id_estado_venta", $id_estado_venta);
        $stmt->bindParam(":id_venta_cabecera", $id_venta_cabecera);
        return $stmt->execute();
    }

}

$ventasBBDD = new VentasBBDD();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id_venta_cabecera'])) {
            $id = $_GET['id_venta_cabecera'];
            if (!is_numeric($id)) {
                echo json_encode(['error' => 'ID inválido']);
                break;
            }
            $result = $ventasBBDD->getVentaDetalles($id);
        } elseif (isset($_GET['producto']) || isset($_GET['combo'])) {
            $filter = [
                'producto' => $_GET['producto'] ?? null,
                'combo' => $_GET['combo'] ?? null
            ];
            $result = $ventasBBDD->getVentasByFilter($filter);
        } else {
            $result = $ventasBBDD->getAllVentas();
        }
        echo json_encode($result);
        break;

        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['id_venta_cabecera']) || empty($data['id_estado_venta'])) {
                echo json_encode(['error' => 'Todos los campos son obligatorios']);
                break;
            }
            try {
                $result = $ventasBBDD->updateVentaEstado($data['id_venta_cabecera'], $data['id_estado_venta']);
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