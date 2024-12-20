<?php
    require_once(__DIR__ . "/../../../config/config.php"); 

    if(!isset($_SESSION['usuario'])){
        header('Location: /'.CARPETA_PROYECTO.'/home');
    }

    if (!Permisos::tienePermiso("Modificar stock", $_SESSION['usuario']['id'])) {
        header('Location: /'.CARPETA_PROYECTO.'/home');
        die;
    }
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Stock</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"
        defer></script>
    <script src="/<?php echo CARPETA_PROYECTO ?>/public/js/admin/stock.js" type="module" defer></script>
</head>

<body>

    <?php require(RUTA_PROYECTO.'/app/views/components/adminHeader.php'); ?>

    <!-- Stock -->
    <div id="stock" class="container mt-5">
        <h2>Gestión de Stock</h2>
        
        <!-- Tabla de stock de productos -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Categoría</th>
                    <th>Cantidad</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>

    <div class="modal fade" id="manageStockModal" tabindex="-1" aria-labelledby="manageStockModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="manageStockForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="manageStockModalLabel">Gestionar Stock</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div id="error-message" class="alert alert-danger d-none" role="alert">
                            La cantidad ingresada es menor al stock actual.
                        </div>
                        <div class="mb-3">
                            <label for="stockQuantity" class="form-label">Cantidad</label>
                            <input type="number" class="form-control" id="stockQuantity" required>
                        </div>
                        <div class="mb-3">
                            <label for="stockAction" class="form-label">Acción</label>
                            <select class="form-select" id="stockAction" required>
                                <option value="add">Añadir Stock</option>
                                <option value="subtract">Descontar Stock</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="stockReason" class="form-label">Motivo</label>
                            <select class="form-select" id="stockReason" required>
                                <!-- Opciones serán llenadas dinámicamente -->
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
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