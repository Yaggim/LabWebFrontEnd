<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../includes/conexion.php';
header('Content-Type: application/json');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $conn->prepare("SELECT p.id_producto, p.descripcion, p.precio_usd, p.habilitado, p.stock, 
                                 i.url AS imagen_url, m.nombre AS marca, c.nombre AS categoria 
                                 FROM productos p 
                                 JOIN imagenes_productos i ON p.id_producto = i.id_producto_fk 
                                 JOIN modelos m ON p.id_modelo = m.id_modelo 
                                 JOIN categorias c ON p.id_categoria = c.id_categoria 
                                 WHERE p.id_producto = :id");

        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($producto);
    } catch (PDOException $e) {
        echo json_encode(['error' => 'Error al obtener el producto: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['error' => 'ID del producto no especificado.']);
}
/*
if (isset($_GET['idProducto'])) {
    $idProducto = $_GET['idProducto'];

    $stmt = $conn->prepare("SELECT 
    p.id_producto AS id, 
    p.descripcion, 
    p.precio_usd AS priceARS, 
    p.stock, 
    p.habilitado,
    m.nombre AS brand, 
    mo.nombre AS model, 
    c.nombre AS category, 
    i.url AS image 
FROM 
    productos p 
JOIN 
    modelos mo ON p.id_modelo = mo.id_modelo 
JOIN 
    categorias c ON p.id_categoria = c.id_categoria 
JOIN 
    imagenes_productos i ON p.id_producto = i.id_producto 
WHERE 
    p.id_producto = :idProducto");
    $stmt->bindParam(':idProducto', $idProducto);
    $stmt->execute();

    $producto = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($producto);
} else {
    echo json_encode(['error' => 'ID de producto no especificado']);
}
    */

    