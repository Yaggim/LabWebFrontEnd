<?php
//CONEXIÓN CON REQUIRE_ONCE (SI YA ESTÁ CONECTADO NO LO VUELVE A INCLUIR)
require_once 'conexion.php';

function generarVenta($carrito, $id_persona, $datosEnvio = null)
{
    global $conn;

    //INICIAR TRANSACCION (SI SALE BIEN CONFIRMA CON COMMIT, NO ANTES)
    $conn->beginTransaction();

    try {
        //INSERTAR EN TABLA ventas_cabecera
        $fecha_venta = date("Y-m-d H:i:s");
        $stmt_cabecera = $conn->prepare("INSERT INTO ventas_cabecera (fecha_venta, id_persona) VALUES (?, ?)");
        $stmt_cabecera->execute([$fecha_venta, $id_persona]);
        $id_venta_cabecera = $conn->lastInsertId(); // Obtener el ID de la venta recién creada

        //INSERTAR EN TABLA ventas_detalle Y ACTUALIZAR SU STOCK TABLA productos
        $stmt_detalle = $conn->prepare("INSERT INTO ventas_detalle (id_venta_cabecera, id_producto, cantidad, precio_unitario) VALUES (?, ?, ?, ?)");
        $stmt_stock = $conn->prepare("UPDATE productos SET stock = stock - ? WHERE id_producto = ?");

        foreach ($carrito as $producto) {
            $id_producto = $producto['id'];
            $cantidad = $producto['cantidad'];
            $precio_unitario = $producto['precioARS'];

            //INSRTAR ventas_detalle
            $stmt_detalle->execute([$id_venta_cabecera, $id_producto, $cantidad, $precio_unitario]);

            //ACTUALIZAR STOCK
            $stmt_stock->execute([$cantidad, $id_producto]);
        }

        //INSERTAR EN TABLA DE ENVÍOS (SI SE SELECCIONÓ ENVIO)
        if ($datosEnvio) {
            $stmt_envio = $conn->prepare("INSERT INTO envios (nombre, apellido, dni, calle, altura, cod_postal, notas, id_venta_cabecera) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt_envio->execute([
                explode(' ', $datosEnvio['envioTitular'])[0],  // nombre de envioTitular
                explode(' ', $datosEnvio['envioTitular'])[1],  // apellido de envioTitular
                $datosEnvio['dni'],
                $datosEnvio['envioCalle'],
                $datosEnvio['envioAltura'],
                $datosEnvio['envioPostal'],
                $datosEnvio['envioNota'], 
                $id_venta_cabecera// ACÁ HAY QUE USAR PARA RELACIONAR CON EL ENVÍO 
            ]);
        }

        //CONFIRMACIÓN
        $conn->commit();
        echo "Compra realizada con éxito.";

    } catch (Exception $e) {
        //ROLLBACK SI HAY ERRORES
        $conn->rollBack();
        echo "Error en la compra: " . $e->getMessage();
    }
}

// 
$carrito = json_decode($_POST['carrito'], true); //CARRITO
$id_persona = $_POST['id_persona'];//LO TIENE QUE RECIBIR POR LA SESIÓN
$datosEnvio = isset($_POST['envio']) ? json_decode($_POST['envio'], true) : null;

//GENERAR LA VENTA
generarVenta($carrito, $id_persona, $datosEnvio);

?>