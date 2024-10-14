<?php require_once(__DIR__ .'/../config/config.php'); ?>

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

    <div class="container-carrito">
        <div class="row">
             <!-- Aquí se agregarán los elementos del carrito dinámicamente -->
             <div id="carrito" class="bg-light p-3"></div>
                
                    <!-- Aquí se agregarán los elementos del carrito dinámicamente -->
                    <div class="d-flex flex-column align-items-end mb-3">
                       
                        <button id="finalizar-compra" class="btn btn-success mt-3">Finalizar compra</button>
                        <button id="cancelar-compra" type="button" class="btn btn-secondary mt-3">Cancelar</button>
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
</body>

</html>