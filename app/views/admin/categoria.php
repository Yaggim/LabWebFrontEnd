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
    <title>Gestión de Categorías</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"
        defer></script>
    <script src="/<?php echo CARPETA_PROYECTO ?>/public/js/admin/categoria.js" type="module" defer></script>
</head>

<body>

    <?php require(RUTA_PROYECTO.'/app/views/components/adminHeader.php'); ?>

    <div id="content">
        <div id="category" class="container mt-5">
            <h2>Gestión de Categorías</h2>
            <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-plus"></i> Añadir Categoría
            </button>

            <!-- Tabla de marcas existentes -->
            <table class="table table-striped" id="categoryTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Categoría</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Modales -->
        <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="addCategoryForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCategoryModalLabel">Añadir Nueva Categoría</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="categoryName" class="form-label">Nombre de la Categoría</label>
                                <input type="text" class="form-control" id="categoryName" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Categoría</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para Editar Marca -->
        <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editCategoryForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editCategoryModalLabel">Editar Categoría</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="editCategoryName" class="form-label">Nombre de la Categoría</label>
                                <input type="text" class="form-control" id="editCategoryName" required>
                                <input type="hidden" id="editCategoryId">
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

        <!-- Modal para Eliminar Marca -->
        <div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteCategoryModalLabel">Eliminar Categoría</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro que deseas eliminar la categoría <strong id="deleteCategoryName"></strong> con ID <strong id="deleteCategoryId"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="confirmDelete" class="btn btn-danger">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>



</body>

</html>