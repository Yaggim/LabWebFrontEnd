<?php
    require_once(__DIR__ . "/../../../config/config.php"); 

    if(!isset($_SESSION['usuario'])){
        header('Location: /'.CARPETA_PROYECTO.'/home');
    }

    if (!Permisos::tienePermiso("Crear combo", $_SESSION['usuario']['id'])) {
        header('Location: /'.CARPETA_PROYECTO.'/home');
        die;
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Combos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"
        defer></script>
        <script src="/<?php echo CARPETA_PROYECTO ?>/public/js/admin/combos.js" type="module" defer></script>
</head>

<body>

    <?php require(RUTA_PROYECTO.'/app/views/components/adminHeader.php'); ?>

    <div class="container mt-5">
        <h2>Gestión de Combos</h2>
        <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addComboModal">
            <i class="fas fa-plus"></i> Añadir Combo
        </button>

        <!-- Tabla para listar combos existentes -->
        <table class="table table-striped" id="combosTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Combo</th>
                    <th>Productos</th>
                    <th>Habilitado</th>
                    <th>Descuento</th>
                    <th>Imagenes</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Aquí se rellenará dinámicamente la tabla -->
            </tbody>
        </table>
    </div>

    <!-- Modal para crear combo -->
    <div class="modal fade" id="addComboModal" tabindex="-1" aria-labelledby="addComboModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="createComboForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="addComboModalLabel">Crear Combo de Productos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="comboName" class="form-label">Nombre del Combo</label>
                        <input type="text" class="form-control" id="comboName" required>
                    </div>

                    <div class="mb-3">
                        <label for="productCategory" class="form-label">Categoría</label>
                        <select class="form-select" id="productCategory" required></select>
                    </div>

                    <div class="mb-3">
                        <label for="comboDiscount" class="form-label">Descuento</label>
                        <input type="number" class="form-control" id="comboDiscount" required>
                    </div>

                    <div class="mb-3">
                        <label for="comboImages" class="form-label">Imágenes (URLs)</label>
                        <div id="imageContainer">
                            <input type="text" class="form-control mb-2" placeholder="URL de imagen">
                        </div>
                        <button type="button" class="btn btn-secondary" id="addImageField">Agregar otra imagen</button>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label d-block">Habilitado</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="comboProductEnabled" checked>
                            <label class="form-check-label" for="comboProductEnabled">Sí</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="comboProducts" class="form-label">Seleccionar Productos</label>
                        <select multiple class="form-select" id="comboProducts"></select>
                    </div>

                    <div id="warningMessage" class="alert alert-warning mt-3 d-none" role="alert">
                        Debe seleccionar al menos un producto.
                    </div>
                    
                    <button type="button" class="btn btn-success mb-3" id="addProductBtn">Agregar Producto</button>

                    <!-- Lista de productos seleccionados -->
                    <h5>Productos Seleccionados</h5>
                    <table class="table table-striped" id="selectedProductsTable">
                        <thead>
                            <tr>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Categoría</th>
                                <th>Cantidad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Crear Combo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editCombosModal" tabindex="-1" aria-labelledby="editCombosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editCombosForm">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Combo:</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="editComboName" class="form-label">Nombre del Combo</label>
                        <input type="text" class="form-control" id="editComboName" required>
                        <input type="hidden" id="editComboId">
                    </div>
                    <div class="mb-3">
                        <label for="editProductCategory" class="form-label">Categoría</label>
                        <select class="form-select" id="editProductCategory" required></select>
                    </div>

                    <div class="mb-3">
                        <label for="editComboDiscount" class="form-label">Descuento</label>
                        <input type="number" class="form-control" id="editComboDiscount" required>
                    </div>

                    <div class="mb-3">
                        <label for="editComboImages" class="form-label">Imágenes</label>
                        <div id="editImageContainer"></div>
                        <button type="button" class="btn btn-secondary" id="editImageField">Agregar imagen</button>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label d-block">Habilitado</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="editComboProductEnabled" checked>
                            <label class="form-check-label" for="editComboProductEnabled">Sí</label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="editComboProducts" class="form-label">Seleccionar Productos</label>
                        <select multiple class="form-select" id="editComboProducts"></select>
                    </div>

                    <div id="editWarningMessage" class="alert alert-warning mt-3 d-none" role="alert">
                        Debe seleccionar al menos un producto.
                    </div>
                    
                    <button type="button" class="btn btn-success mb-3" id="editAddProductBtn">Agregar Producto</button>

                    <h5>Productos Seleccionados</h5>
                    <table class="table table-striped" id="editSelectedProductsTable">
                        <thead>
                            <tr>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Categoría</th>
                                <th>Cantidad</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <div class="modal fade" id="deleteComboModal" tabindex="-1" aria-labelledby="deleteCombosModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteCombosModalLabel">Eliminar Combo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" ></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas eliminar el combo <strong id="deleteComboName"></strong> con ID <strong id="deleteComboId"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Eliminar</button>
                </div>
            </div>
        </div>
    </div>
   

</body>

</html>