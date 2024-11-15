<?php
// Iniciar sesión si es necesario para obtener $id_persona desde la sesión


// Conexión a la base de datos
require_once(__DIR__ . "/../conexion.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    //EJECUTAR EL STORED PROCEDURE RECIBIENDO LOS PARÁMETROS DEL ID DE USUARIO, FECHA, PRODUCTOS, CANTIDAD ...
}

/*

function generarVenta($carrito, $id_persona, $datosEnvio = null)
{
    global $conn;
    $conn->beginTransaction();

    try {
        // Insertar en ventas_cabecera
        $fecha_venta = date("Y-m-d H:i:s");
        $stmt_cabecera = $conn->prepare("INSERT INTO ventas_cabecera (fecha_venta, id_persona) VALUES (?, ?)");
        $stmt_cabecera->execute([$fecha_venta, $id_persona]);
        $id_venta_cabecera = $conn->lastInsertId();

        // Preparar declaraciones para insertar en ventas_detalle y actualizar stock en productos
        $stmt_detalle = $conn->prepare("INSERT INTO ventas_detalle (id_venta_cabecera, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
        $stmt_stock = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE id_producto = ?");

        // Recorrer los productos del carrito
        foreach ($carrito as $producto) {
            $id_producto = $producto['id'];
            $cantidad = $producto['cantidad'];
            $precio_unitario = $producto['priceARS'];

            // Insertar en ventas_detalle
            $stmt_detalle->execute([$id_venta_cabecera, $id_producto, $cantidad, $precio_unitario]);

            // Actualizar el stock en productos
            $stmt_stock->execute([$cantidad, $id_producto]);
        }

        // Insertar en la tabla envios si datosEnvio está presente
        if ($datosEnvio) {
            list($nombre, $apellido) = array_pad(explode(' ', $datosEnvio['envioTitular']), 2, null);
            $stmt_envio = $conn->prepare("INSERT INTO envios (nombre, apellido, dni, calle, altura, cod_postal, notas, id_venta_cabecera) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_envio->execute([
                $nombre,
                $apellido,
                $datosEnvio['dni'],
                $datosEnvio['envioCalle'],
                $datosEnvio['envioAltura'],
                $datosEnvio['envioPostal'],
                $datosEnvio['envioNota'],
                $id_venta_cabecera
            ]);
        }

        // Confirmar la transacción
        $conn->commit();
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Compra realizada con éxito.']);

    } catch (Exception $e) {
        // Rollback en caso de error
        $conn->rollBack();
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error en la compra: ' . $e->getMessage()]);
    }
}

// Procesar datos recibidos por POST
$carrito = json_decode($_POST['carrito'], true);
$id_persona = $_SESSION['id_persona'] ?? $_POST['id_persona'];  // Obtenido de la sesión o de POST
$datosEnvio = isset($_POST['envio']) ? json_decode($_POST['envio'], true) : null;

// Generar la venta
generarVenta($carrito, $id_persona, $datosEnvio);

// Cerrar la conexión
$conn = null;
*/
?>
