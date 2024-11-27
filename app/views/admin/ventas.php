<?php
    require_once(__DIR__ . "/../../../config/config.php"); 

    if(!isset($_SESSION['usuario'])){
        header('Location: /'.CARPETA_PROYECTO.'/home');
    }

    if (!Permisos::tienePermiso("Gestionar ventas", $_SESSION['usuario']['id'])) {
        header('Location: /'.CARPETA_PROYECTO.'/home');
        die;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ventas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"
        defer></script>
    <script src="/<?php echo CARPETA_PROYECTO ?>/public/js/admin/ventas.js" type="module" defer></script>
</head>

<body>
    <?php require(RUTA_PROYECTO.'/app/views/components/adminHeader.php'); ?>

    <div class="container mt-5">
        <h1>Reporte de Ventas</h1>
        <div class="mb-3">
            <label for="filterProducto" class="form-label">Filtrar por Producto</label>
            <select class="form-control" id="filterProducto"></select>
        </div>
        <div class="mb-3">
            <label for="filterCombo" class="form-label">Filtrar por Combo</label>
            <select class="form-control" id="filterCombo"></select>
        </div>
        <button class="btn btn-primary mb-3" id="filterButton">Filtrar</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Fecha</th>
                    <th>Precio Total</th>
                    <th>Persona</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="ventasTableBody">
                <!-- Ventas se cargarán aquí -->
            </tbody>
        </table>
    </div>

    <!-- Modal para ver detalles de la venta -->
    <div class="modal fade" id="ventaDetallesModal" tabindex="-1" aria-labelledby="ventaDetallesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ventaDetallesModalLabel">Detalles de la Venta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Producto/Combo</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                            </tr>
                        </thead>
                        <tbody id="ventaDetallesTableBody">
                            
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editEstadoVentaModal" tabindex="-1" aria-labelledby="editEstadoVentaModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editEstadoVentaModalLabel">Editar Estado de la Venta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="editEstadoVenta" class="form-label">Estado</label>
                    <select class="form-control" id="editEstadoVenta" required></select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="updateEstadoVentaButton">Guardar Cambios</button>
            </div>
        </div>
    </div>
</div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="errorToastMessage">
                    <!-- Mensaje de error -->
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

</body>

</html>