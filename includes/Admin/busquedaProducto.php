<?php
require_once(__DIR__ . "/../../config/config.php"); 
require_once(__DIR__ . "/../conexion.php");
require_once(__DIR__ . "/../Crud.php");

class BusquedaBBDD extends Crud {
    public function __construct() {
        parent::__construct("productos");
    }

    public function buscarProductos($termino) {
        $stmt = $this->conexion->prepare("
            SELECT 
                productos.id_producto,
                marcas.nombre AS marca, 
                modelos.nombre AS modelo,
                MIN(imagenes_productos.url) AS imagen,
                productos.precio_usd AS precio,
                categorias.nombre AS categoria,
                GROUP_CONCAT(DISTINCT combos.nombre SEPARATOR ', ') AS combo_nombre
            FROM 
                productos
            INNER JOIN 
                modelos ON productos.id_modelo = modelos.id_modelo
            INNER JOIN 
                marcas ON modelos.id_marca = marcas.id_marca
            INNER JOIN 
                categorias ON productos.id_categoria = categorias.id_categoria
            INNER JOIN 
                imagenes_productos ON productos.id_producto = imagenes_productos.id_producto_fk
            LEFT JOIN 
                productos_combo ON productos.id_producto = productos_combo.id_producto
            LEFT JOIN 
                combos ON productos_combo.id_combo = combos.id_combo
            WHERE 
                marcas.nombre LIKE :termino OR
                modelos.nombre LIKE :termino OR
                categorias.nombre LIKE :termino OR
                combos.nombre LIKE :termino
            GROUP BY 
                productos.id_producto, marcas.nombre, modelos.nombre, productos.precio_usd, categorias.nombre
        ");
        $termino = "%$termino%";
        $stmt->bindParam(":termino", $termino);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

header('Content-Type: application/json');

try {
    if (!isset($_GET['termino'])) {
        throw new Exception('Término de búsqueda no proporcionado.');
    }

    $termino = $_GET['termino'];
    $busqueda = new BusquedaBBDD();
    $resultados = $busqueda->buscarProductos($termino);

    echo json_encode($resultados);
} catch (Exception $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>