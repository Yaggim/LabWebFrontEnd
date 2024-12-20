<?php
    require_once(__DIR__ . "/../../../config/config.php"); 

    if(!isset($_SESSION['usuario'])){
        header('Location: /'.CARPETA_PROYECTO.'/home');
    }

    if (!Permisos::tienePermiso("Crear producto", $_SESSION['usuario']['id'])) {
        header('Location: /'.CARPETA_PROYECTO.'/home');
        die;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Modelos</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"
        defer></script>
    <script src="/<?php echo CARPETA_PROYECTO ?>/public/js/admin/modelo.js" type="module" defer></script>
</head>

<body>

    <?php require(RUTA_PROYECTO.'/app/views/components/adminHeader.php'); ?>


    <div id="models" class="container mt-5">
        <h2>Gestión de Modelos</h2>
        <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addModelModal">
            <i class="fas fa-plus"></i> Añadir Modelo
        </button>

        <table class="table table-striped" id="modelsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Modelo</th>
                    <th>Marca Asociada</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <div class="modal fade" id="addModelModal" tabindex="-1" aria-labelledby="addModelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addModelForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModelModalLabel">Añadir Nuevo Modelo</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="modelName" class="form-label">Nombre del Modelo</label>
                            <input type="text" class="form-control" id="modelName" required>
                        </div>
                        <div class="mb-3">
                            <label for="brandSelect" class="form-label">Selecciona una marca</label>
                            <select class="form-select" id="brandSelect" required>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Modelo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <div class="modal fade" id="editModelModal" tabindex="-1" aria-labelledby="editModelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editModelForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModelModalLabel">Editar Marca</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editModelName" class="form-label">Nombre del Modelo</label>
                            <input type="text" class="form-control" id="editModelName" required>
                            <input type="hidden" id="editModelId">
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editModelSelect" class="form-label">Selecciona una marca</label>
                            <select class="form-select" id="editBrandSelect" required>
                            </select>
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

    <div class="modal fade" id="deleteModelModal" tabindex="-1" aria-labelledby="deleteModelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModelModalLabel">Eliminar Modelo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas eliminar el modelo <strong id="deleteModelName"></strong> con ID <strong id="deleteModelId"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="confirmDelete" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="errorToastMessage">
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

</body>

</html>