<?php
require_once(__DIR__ . "/../../config/config.php"); 
require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../Crud.php");



class ComboBBDD extends Crud {
    public function __construct() {
        parent::__construct("combos");
    }

    public function getAllWithDetails() {
        $stmt = $this->conexion->prepare("
            SELECT 
                combos.*, 
                GROUP_CONCAT(DISTINCT imagenes_combos.url SEPARATOR ',') AS imagenes,
                GROUP_CONCAT(DISTINCT productos_combo.id_producto, ':', productos_combo.cantidad SEPARATOR ',') AS productos
            FROM combos
            LEFT JOIN imagenes_combos ON combos.id_combo = imagenes_combos.id_combo
            LEFT JOIN productos_combo ON combos.id_combo = productos_combo.id_combo
            GROUP BY combos.id_combo
        ");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
        foreach ($result as &$row) {
            $row['imagenes'] = $row['imagenes'] ? explode(',', $row['imagenes']) : [];
    
            // Obtener detalles de cada producto
            $productosDetalles = [];
            foreach (explode(',', $row['productos']) as $productoInfo) {
                list($productoId, $cantidad) = explode(':', $productoInfo);
                $productoStmt = $this->conexion->prepare("
                    SELECT 
                        productos.*, 
                        modelos.nombre AS model_name,
                        categorias.nombre AS category_name,
                        marcas.nombre AS brand_name
                    FROM productos
                    LEFT JOIN modelos ON productos.id_modelo = modelos.id_modelo
                    LEFT JOIN categorias ON productos.id_categoria = categorias.id_categoria
                    LEFT JOIN marcas ON modelos.id_marca = marcas.id_marca
                    WHERE productos.id_producto = :id_producto
                ");
                $productoStmt->bindParam(":id_producto", $productoId);
                $productoStmt->execute();
                $productoDetalle = $productoStmt->fetch(PDO::FETCH_ASSOC);
                if ($productoDetalle) {
                    $productoDetalle['cantidad'] = (int)$cantidad;
                    $productosDetalles[] = $productoDetalle;
                }
            }
            // Asignar el arreglo con detalles de productos directamente en 'productos'
            $row['productos'] = $productosDetalles;
        }
    
        return $result;
    }

    public function getByIdWithDetails($id) {
        $stmt = $this->conexion->prepare("
            SELECT 
                combos.*, 
                GROUP_CONCAT(DISTINCT imagenes_combos.url SEPARATOR ',') AS imagenes,
                GROUP_CONCAT(DISTINCT productos_combo.id_producto, ':', productos_combo.cantidad SEPARATOR ',') AS productos
            FROM combos
            LEFT JOIN imagenes_combos ON combos.id_combo = imagenes_combos.id_combo
            LEFT JOIN productos_combo ON combos.id_combo = productos_combo.id_combo
            WHERE combos.id_combo = :id
            GROUP BY combos.id_combo
        ");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($result) {
            $result['imagenes'] = $result['imagenes'] ? explode(',', $result['imagenes']) : [];

            $productosDetalles = [];
            foreach (explode(',', $result['productos']) as $productoInfo) {
                list($productoId, $cantidad) = explode(':', $productoInfo);
                $productoStmt = $this->conexion->prepare("
                    SELECT 
                        productos.*, 
                        modelos.nombre AS model_name,
                        categorias.nombre AS category_name,
                        marcas.nombre AS brand_name
                    FROM productos
                    LEFT JOIN modelos ON productos.id_modelo = modelos.id_modelo
                    LEFT JOIN categorias ON productos.id_categoria = categorias.id_categoria
                    LEFT JOIN marcas ON modelos.id_marca = marcas.id_marca
                    WHERE productos.id_producto = :id_producto
                ");
                $productoStmt->bindParam(":id_producto", $productoId);
                $productoStmt->execute();
                $productoDetalle = $productoStmt->fetch(PDO::FETCH_ASSOC);
                if ($productoDetalle) {
                    $productoDetalle['cantidad'] = (int)$cantidad;
                    $productosDetalles[] = $productoDetalle;
                }
            }
            $result['productos'] = $productosDetalles;
        }
    
        return $result;
    }


    public function createCombo($data, $userId) {
        $this->conexion->beginTransaction();
    
        try {
            $stmt = $this->conexion->prepare("INSERT INTO combos (nombre, habilitado, descuento) VALUES (:nombre, :habilitado, :descuento)");
            $stmt->bindParam(":nombre", $data['nombre']);
            $stmt->bindParam(":habilitado", $data['habilitado']);
            $stmt->bindParam(":descuento", $data['descuento']);
            $stmt->execute();
    
            $comboId = $this->conexion->lastInsertId();
    
            if (!empty($data['imagenes'])) {
                foreach ($data['imagenes'] as $url) {
                    $stmt = $this->conexion->prepare("INSERT INTO imagenes_combos (url, id_combo) VALUES (:url, :id_combo)");
                    $stmt->bindParam(":url", $url);
                    $stmt->bindParam(":id_combo", $comboId);
                    $stmt->execute();
                }
            }
    
            if (!empty($data['productos'])) {
                foreach ($data['productos'] as $producto) {
                    $stmt = $this->conexion->prepare("INSERT INTO productos_combo (id_producto, id_combo, cantidad) VALUES (:id_producto, :id_combo, :cantidad)");
                    $stmt->bindParam(":id_producto", $producto['id_producto']);
                    $stmt->bindParam(":id_combo", $comboId);
                    $stmt->bindParam(":cantidad", $producto['cantidad']);
                    $stmt->execute();
    
                    // Descontar stock y registrar movimiento
                    $this->updateStockAndRegisterMovement($producto['id_producto'], $producto['cantidad'], $comboId, 4, $userId);
                }
            }
    
            $this->conexion->commit();
            return $comboId;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }

    public function updateCombo($id, $data, $userId) {
        $this->conexion->beginTransaction();
    
        try {
            $stmt = $this->conexion->prepare("UPDATE combos SET nombre = :nombre, habilitado = :habilitado, descuento = :descuento WHERE id_combo = :id");
            $stmt->bindParam(":nombre", $data['nombre']);
            $stmt->bindParam(":habilitado", $data['habilitado']);
            $stmt->bindParam(":descuento", $data['descuento']);
            $stmt->bindParam(":id", $id);
            $stmt->execute();
    
            // Obtener los productos actuales del combo
            $stmt = $this->conexion->prepare("SELECT id_producto, cantidad FROM productos_combo WHERE id_combo = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $productosActuales = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Crear un array para acceder rápidamente a las cantidades actuales por id_producto
            $productosActualesMap = [];
            foreach ($productosActuales as $producto) {
                $productosActualesMap[$producto['id_producto']] = $producto['cantidad'];
            }
    
            $stmt = $this->conexion->prepare("DELETE FROM imagenes_combos WHERE id_combo = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
    
            if (!empty($data['imagenes'])) {
                foreach ($data['imagenes'] as $url) {
                    $stmt = $this->conexion->prepare("INSERT INTO imagenes_combos (url, id_combo) VALUES (:url, :id_combo)");
                    $stmt->bindParam(":url", $url);
                    $stmt->bindParam(":id_combo", $id);
                    $stmt->execute();
                }
            }
    
            // Obtener los ids de los productos nuevos
            $nuevosProductosIds = array_column($data['productos'], 'id_producto');

            // Devolver el stock de los productos que ya no están en el combo
            foreach ($productosActuales as $producto) {
                if (!in_array($producto['id_producto'], $nuevosProductosIds)) {
                    $this->updateStockAndRegisterMovement($producto['id_producto'], -$producto['cantidad'], $id, 6, $userId);
                }
            }

            $stmt = $this->conexion->prepare("DELETE FROM productos_combo WHERE id_combo = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
    
            if (!empty($data['productos'])) {
                foreach ($data['productos'] as $producto) {
                    $stmt = $this->conexion->prepare("INSERT INTO productos_combo (id_producto, id_combo, cantidad) VALUES (:id_producto, :id_combo, :cantidad)");
                    $stmt->bindParam(":id_producto", $producto['id_producto']);
                    $stmt->bindParam(":id_combo", $id);
                    $stmt->bindParam(":cantidad", $producto['cantidad']);
                    $stmt->execute();
    
                    // Evaluar si la cantidad es mayor o menor que la cantidad actual
                    if (isset($productosActualesMap[$producto['id_producto']])) {
                        $cantidadActual = $productosActualesMap[$producto['id_producto']];
                        if ($producto['cantidad'] > $cantidadActual) {
                            // Si la nueva cantidad es mayor, restar la diferencia del stock
                            $this->updateStockAndRegisterMovement($producto['id_producto'], $cantidadActual + $producto['cantidad'], $id, 7, $userId);
                        } elseif ($producto['cantidad'] < $cantidadActual) {
                            // Si la nueva cantidad es menor, sumar la diferencia al stock
                            $this->updateStockAndRegisterMovement($producto['id_producto'], $producto['cantidad'] - $cantidadActual, $id, 6, $userId);
                        }
                    } else {
                        // Si el producto no estaba en el combo antes, se considera como nuevo
                        $this->updateStockAndRegisterMovement($producto['id_producto'], $producto['cantidad'], $id, 7, $userId);
                    }
                }
            }
    
            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }

    public function deleteCombo($id) {
        $this->conexion->beginTransaction();
    
        try {
            $userId = $_SESSION['usuario']['id'];
            // Obtener los productos del combo antes de eliminar
            $stmt = $this->conexion->prepare("SELECT id_producto, cantidad FROM productos_combo WHERE id_combo = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            // Devolver el stock de los productos
            foreach ($productos as $producto) {
                $this->updateStockAndRegisterMovement($producto['id_producto'], -$producto['cantidad'], $id, 5, $userId);
            }
    
            // Eliminar movimientos de stock relacionados con el combo (Esto se tuvo que hacer para no tener problemas con la FK)
            $stmt = $this->conexion->prepare("DELETE FROM movimientos_stock WHERE id_combo = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
    
            $stmt = $this->conexion->prepare("DELETE FROM imagenes_combos WHERE id_combo = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
    
            $stmt = $this->conexion->prepare("DELETE FROM productos_combo WHERE id_combo = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
    
            $stmt = $this->conexion->prepare("DELETE FROM combos WHERE id_combo = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();
    
            $this->conexion->commit();
            return true;
        } catch (Exception $e) {
            $this->conexion->rollBack();
            throw $e;
        }
    }

    private function updateStockAndRegisterMovement($productId, $quantity, $comboId, $movementType, $userId) {

        // Descontar stock
        $stmt = $this->conexion->prepare("UPDATE productos SET stock = stock - :cantidad WHERE id_producto = :id_producto");
        $stmt->bindParam(":cantidad", $quantity);
        $stmt->bindParam(":id_producto", $productId);
        $stmt->execute();
    
        // Verificar si el stock es 0 y deshabilitar el producto
        $stmt = $this->conexion->prepare("SELECT stock FROM productos WHERE id_producto = :id_producto");
        $stmt->bindParam(":id_producto", $productId);
        $stmt->execute();
        $stock = $stmt->fetchColumn();
    
        if ($stock <= 0) {
            $stmt = $this->conexion->prepare("UPDATE productos SET habilitado = 0 WHERE id_producto = :id_producto");
            $stmt->bindParam(":id_producto", $productId);
            $stmt->execute();
        }
    
        

        // Registrar movimiento de stock
        $stmt = $this->conexion->prepare("INSERT INTO movimientos_stock (cantidad, id_movimiento_tipo, fecha, id_usuario, id_producto, id_combo) VALUES (:cantidad, :id_movimiento_tipo, NOW(), :id_usuario, :id_producto, :id_combo)");
        $stmt->bindParam(":cantidad", $quantity);
        $stmt->bindParam(":id_movimiento_tipo", $movementType);
        $stmt->bindParam(":id_usuario", $userId); 
        $stmt->bindParam(":id_producto", $productId);
        $stmt->bindParam(":id_combo", $comboId);
        $stmt->execute();
    }
}



$comboBBDD = new ComboBBDD();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];


switch ($method) {
    case 'GET':
        if (isset($_GET['id_combo'])) {
            $id = $_GET['id_combo'];
            if (!is_numeric($id)) {
                echo json_encode(['error' => 'ID inválido']);
                break;
            }
            $result = $comboBBDD->getByIdWithDetails($id);
        } else {
            $result = $comboBBDD->getAllWithDetails();
        }
        echo json_encode($result);
        break;

        case 'POST':
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['nombre']) || !isset($data['habilitado']) || !isset($data['descuento'])) {
                echo json_encode(['error' => 'Todos los campos son obligatorios']);
                break;
            }
            try {
                $userId = $_SESSION['usuario']['id'];
                $result = $comboBBDD->createCombo($data, $userId);
                echo json_encode(['result' => $result]);
            } catch (Exception $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;
        
        case 'PUT':
            $data = json_decode(file_get_contents('php://input'), true);
            if (empty($data['id_combo']) || empty($data['nombre']) || !isset($data['habilitado']) || !isset($data['descuento'])) {
                echo json_encode(['error' => 'Todos los campos son obligatorios']);
                break;
            }
            try {
                $userId = $_SESSION['usuario']['id'];
                $result = $comboBBDD->updateCombo($data['id_combo'], $data, $userId);
                echo json_encode(['result' => $result]);
            } catch (Exception $e) {
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['id_combo']) || !is_numeric($data['id_combo'])) {
            echo json_encode(['error' => 'ID inválido']);
            break;
        }
        try {
            
            $result = $comboBBDD->deleteCombo($data['id_combo']);
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