<?php
require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../Crud.php");

class ProductoBBDD extends Crud
{
    public function __construct()
    {
        parent::__construct("productos");
    }

    public function getAllWithDetails()
    {
        $stmt = $this->conexion->prepare("
        SELECT 
            productos.*, 
            modelos.id_modelo AS model_id, modelos.nombre AS model_name,
            categorias.id_categoria AS category_id, categorias.nombre AS category_name,
            marcas.id_marca AS brand_id, marcas.nombre AS brand_name,
            COALESCE(
                (SELECT GROUP_CONCAT(url SEPARATOR ',') FROM imagenes_productos WHERE id_producto_fk = productos.id_producto),
                ''
            ) AS imagenes
        FROM productos
        LEFT JOIN modelos ON productos.id_modelo = modelos.id_modelo
        LEFT JOIN categorias ON productos.id_categoria = categorias.id_categoria
        LEFT JOIN marcas ON modelos.id_marca = marcas.id_marca
    ");
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as &$row) {
            // Convierte la cadena separada por comas en un array.
            $row['imagenes'] = $row['imagenes'] ? explode(',', $row['imagenes']) : [];
        }

        return $result;
    }

    public function getByIdWithDetails($id)
    {
        $stmt = $this->conexion->prepare("
            SELECT 
                productos.*, 
                modelos.id_modelo AS model_id, modelos.nombre AS model_name,
                categorias.id_categoria AS category_id, categorias.nombre AS category_name,
                marcas.id_marca AS brand_id, marcas.nombre AS brand_name,
                COALESCE(
                    (SELECT GROUP_CONCAT(url SEPARATOR ',') FROM imagenes_productos WHERE id_producto_fk = productos.id_producto),
                    ''
                ) AS imagenes
            FROM productos
            LEFT JOIN modelos ON productos.id_modelo = modelos.id_modelo
            LEFT JOIN categorias ON productos.id_categoria = categorias.id_categoria
            LEFT JOIN marcas ON modelos.id_marca = marcas.id_marca
            WHERE productos.id_producto = :id
        ");
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Convierte la cadena separada por comas en un array.
        if ($result) {
            $result['imagenes'] = $result['imagenes'] ? explode(',', $result['imagenes']) : [];
        }

        return $result;
    }
}

$productoBBDD = new ProductoBBDD();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id_producto'])) {
            $id = $_GET['id_producto'];
            if (!is_numeric($id)) {
                echo json_encode(['error' => 'ID inválido']);
                break;
            }
            $result = $productoBBDD->getByIdWithDetails($id);
        } else {
            $result = $productoBBDD->getAllWithDetails();
        }
        echo json_encode($result);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);


        if (empty($data['id_modelo']) || empty($data['id_categoria']) || empty($data['stock']) || empty($data['precio_usd']) || empty($data['habilitado']) || empty($data['descripcion'])) {
            echo json_encode(['error' => 'Todos los campos son obligatorios']);
            break;
        }

        if (!is_numeric($data['id_modelo']) || !is_numeric($data['id_categoria']) || !is_numeric($data['stock']) || !is_numeric($data['precio_usd'])) {
            echo json_encode(['error' => 'ID de modelo, categoría, stock y precio deben ser numéricos']);
            break;
        }

        $imagenes = $data['imagenes'] ?? [];
        unset($data['imagenes']);


        $result = $productoBBDD->create($data);

        if (!$result) {
            echo json_encode(['error' => 'Error al crear el producto']);
            break;
        }


        $productId = $productoBBDD->getConexion()->lastInsertId();


        if (!empty($imagenes) && is_array($imagenes)) {
            foreach ($imagenes as $url) {
                $stmt = $productoBBDD->getConexion()->prepare("INSERT INTO imagenes_productos (id_producto_fk, url) VALUES (:productId, :url)");
                $stmt->bindParam(":productId", $productId);
                $stmt->bindParam(":url", $url);
                $stmt->execute();
            }
        }

        echo json_encode(['result' => $result]);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['id_producto']) || !is_numeric($data['id_producto'])) {
            echo json_encode(['error' => 'ID de producto inválido']);
            break;
        }

        if (empty($data['id_modelo']) || empty($data['id_categoria']) || empty($data['stock']) || empty($data['precio_usd']) || empty($data['habilitado']) || empty($data['descripcion'])) {
            echo json_encode(['error' => 'Todos los campos son obligatorios']);
            break;
        }

        if (!is_numeric($data['id_modelo']) || !is_numeric($data['id_categoria']) || !is_numeric($data['stock']) || !is_numeric($data['precio_usd'])) {
            echo json_encode(['error' => 'ID de modelo, categoría, stock y precio deben ser numéricos']);
            break;
        }

        $id = $data['id_producto'];
        $imagenes = $data['imagenes'] ?? [];
        unset($data['id_producto']);
        unset($data['imagenes']);

        $result = $productoBBDD->update($id, $data, 'id_producto');

        if ($result) {
            $stmt = $productoBBDD->getConexion()->prepare("DELETE FROM imagenes_productos WHERE id_producto_fk = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            if (!empty($imagenes) && is_array($imagenes)) {
                foreach ($imagenes as $url) {
                    $stmt = $productoBBDD->getConexion()->prepare("INSERT INTO imagenes_productos (id_producto_fk, url) VALUES (:id, :url)");
                    $stmt->bindParam(":id", $id);
                    $stmt->bindParam(":url", $url);
                    $stmt->execute();
                }
            }
        }

        echo json_encode($result ? ['result' => $result] : ['error' => 'Error al actualizar el producto']);
        break;

    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['id_producto'])) {
            $id = $data['id_producto'];

            if (!is_numeric($id)) {
                echo json_encode(['error' => 'ID inválido']);
                break;
            }

            $stmt = $productoBBDD->getConexion()->prepare("DELETE FROM imagenes_productos WHERE id_producto_fk = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            $result = $productoBBDD->deleteById($id, 'id_producto');
            echo json_encode($result ? ['result' => $result] : ['error' => 'Error al eliminar el producto']);
        } else {
            echo json_encode(['error' => 'id_producto no proporcionado']);
        }
        break;

    default:
        echo json_encode(['error' => 'Método no soportado']);
        break;
}
?>