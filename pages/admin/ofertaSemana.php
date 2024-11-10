<?php require_once(__DIR__ .'/../../config/config.php'); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Ofertas de la Semana</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"
        defer></script>
    <script src="/<?php echo CARPETA_PROYECTO ?>/pages/admin/js/ofertaSemana.js" type="module" defer></script>
</head>

<body>
    <?php require(RUTA_PROYECTO.'/components/adminHeader.php'); ?>

    <div class="container mt-5">
        <h1>Ofertas de la Semana</h1>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addOfertaSemanaModal">Agregar Oferta</button>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descripción</th>
                    <th>Descuento</th>
                    <th>Producto</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="ofertasTableBody">
                <!-- Ofertas se cargarán aquí -->
            </tbody>
        </table>
    </div>

    <!-- Modal para agregar oferta de la semana -->
    <div class="modal fade" id="addOfertaSemanaModal" tabindex="-1" aria-labelledby="addOfertaSemanaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="createOfertaSemanaForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addOfertaSemanaModalLabel">Agregar Oferta de la Semana</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="ofertaDescripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="ofertaDescripcion" required>
                        </div>
                        <div class="mb-3">
                            <label for="ofertaDescuento" class="form-label">Descuento (%)</label>
                            <input type="number" class="form-control" id="ofertaDescuento" required>
                        </div>
                        <div class="mb-3">
                            <label for="ofertaProducto" class="form-label">Producto</label>
                            <select class="form-control" id="ofertaProducto" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="ofertaFechaInicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="ofertaFechaInicio" required>
                        </div>
                        <div class="mb-3">
                            <label for="ofertaFechaFin" class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control" id="ofertaFechaFin" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Crear Oferta</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para editar oferta de la semana -->
    <div class="modal fade" id="editOfertaSemanaModal" tabindex="-1" aria-labelledby="editOfertaSemanaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editOfertaSemanaForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editOfertaSemanaModalLabel">Editar Oferta de la Semana</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editOfertaDescripcion" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="editOfertaDescripcion" required>
                        </div>
                        <div class="mb-3">
                            <label for="editOfertaDescuento" class="form-label">Descuento (%)</label>
                            <input type="number" class="form-control" id="editOfertaDescuento" required>
                        </div>
                        <div class="mb-3">
                            <label for="editOfertaProducto" class="form-label">Producto</label>
                            <select class="form-control" id="editOfertaProducto" required></select>
                        </div>
                        <div class="mb-3">
                            <label for="editOfertaFechaInicio" class="form-label">Fecha de Inicio</label>
                            <input type="date" class="form-control" id="editOfertaFechaInicio" required>
                        </div>
                        <div class="mb-3">
                            <label for="editOfertaFechaFin" class="form-label">Fecha de Fin</label>
                            <input type="date" class="form-control" id="editOfertaFechaFin" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para confirmar eliminación de oferta de la semana -->
    <div class="modal fade" id="deleteOfertaSemanaModal" tabindex="-1" aria-labelledby="deleteOfertaSemanaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteOfertaSemanaModalLabel">Eliminar Oferta de la Semana</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro de que deseas eliminar la oferta <strong id="deleteOfertaName"></strong>?</p>
                    <p>ID: <span id="deleteOfertaId"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
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