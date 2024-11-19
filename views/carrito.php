<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../config/config.php';
require_once(__DIR__ . '/../includes/Admin/carrito.php');

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$carrito = new Carrito($userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['producto_id'])) {
    $productoId = $_POST['producto_id'];
    $cantidad = isset($_POST['cantidad']) ? $_POST['cantidad'] : 1;
    $carrito->agregarAlCarrito($productoId, $cantidad);
}

$productosCarrito = $carrito->verCarrito();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech - Carrito</title>

    <?php require(RUTA_PROYECTO.'/components/head.php') ?>

    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/css/carrito.css">
    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO ?>/js/carrito.js" defer></script>
    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO ?>/js/productos.js" defer></script>
</head>

<body>
    <?php require(RUTA_PROYECTO.'/components/header.php'); ?>

    <div class="container mt-3 mb-3">
        <h1 class="text-light">Tus productos seleccionados:</h1>
        <div class="row">
            <!-- Aquí se agregarán los elementos del carrito dinámicamente -->
             <div id="carrito" class="border mt-3 mb-3 p-3"></div>
                <div class="d-flex flex-column align-items-end mb-3">   
                    <form id="comprar" action="/<?php echo CARPETA_PROYECTO ?>/views/compra.php" method="POST">
                        <button id="btnCompra" type="submit" class="btn btn-success btn-lg mt-1">Realizar compra <i
                                class="bi bi-credit-card"></i></button>
                    </form>
                        <button id="cancelar-compra" type="button" class="btn btn-secondary mt-1 mb-3 ">Cancelar</button>
                    </div>
                </div>
                
            
        </div>
    </div>

    <!--Modal "Compra exitosa"-->
    <div class="modal fade" id="modalFinCompra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Compra exitosa!</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Body -->
                <div class="modal-body">
                    El número de pedido es #123456
                </div>
                <!-- Footer -->
                <div class="modal-footer">
                    <div class="cantidadCarrito"></div>
                    <button type="button" id="botonModal" class="btn btn-warning"
                        data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Modal "Compra exitosa"-->

    <?php require(RUTA_PROYECTO.'/components/footer.php'); ?>
    <script>
        // Pasar la información de la sesión a JavaScript
        var compraExitosa = <?php echo isset($_SESSION['compra_exitosa']) ? json_encode($_SESSION['compra_exitosa']) : 'null'; ?>;
        <?php unset($_SESSION['compra_exitosa']); ?>
    </script>

</body>

</html>