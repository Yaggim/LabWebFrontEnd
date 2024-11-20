<?php require_once(__DIR__ .'/../config/config.php'); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech - Ofertas de la Semana</title>

    <?php require(RUTA_PROYECTO.'/components/head.php') ?>

    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/css/producto.css">
    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/css/productos.css">
    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO ?>/js/ofertasSemana.js" defer></script>
</head>

<body>
    <?php require(RUTA_PROYECTO.'/components/header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <header class="my-4">
                <h1 class="text-center">Ofertas de la Semana</h1>
            </header>
            <div id="ofertas-container" class="row"></div>
        </div>
    </div>


    <div class="modal" id="modalCarrito" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Oferta agregada!</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Body -->
                <div class="modal-body">
                    Se ha añadido exitosamente {brand} + {model} al carrito
                </div>
                <!-- Footer -->
                <div class="modal-footer">
                    <div class="cantidadCarrito"></div>
                    <button type="button" id="botonModal" class="btn btn-success"
                        data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalError" tabindex="-1" aria-labelledby="modalErrorLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalErrorLabel">Error</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p id="modalErrorMensaje"></p>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="modalErrorCerrar">Cerrar</button>
                </div>
            </div>
        </div>
    </div>       

    <?php require(RUTA_PROYECTO.'/components/footer.php'); ?>
</body>
</html>