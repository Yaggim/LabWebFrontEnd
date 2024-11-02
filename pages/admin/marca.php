<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Marcas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"
        defer></script>
    <script src="js/marca.js" type="module" defer></script>
</head>

<body>

    <nav id="sidebar" class="bg-dark text-white p-3">
        <h3 class="text-center">Admin Panel</h3>
        <ul class="nav flex-row mt-4">
            <li class="nav-item">
                <a class="nav-link text-white" href="marca.php"><i class="fas fa-tags me-2"></i>Marcas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="modelo.php"><i class="fas fa-cubes me-2"></i>Modelos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="categoria.php"><i class="fas fa-list-alt me-2"></i>Categorías</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="productos.php"><i class="fas fa-box-open me-2"></i>Productos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="stock.php"><i class="fas fa-warehouse me-2"></i>Stock</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="combos.php"><i class="fas fa-gift me-2"></i>Combos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="descuentos.php"><i class="fas fa-percent me-2"></i>Descuentos</a>
            </li>
        </ul>
    </nav>

    <!--Contenido Principal-->
    <div id="content">
        <div id="brands" class="container mt-5">
            <h2>Gestión de Marcas</h2>
            <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addBrandModal">
                <i class="fas fa-plus"></i> Añadir Marca
            </button>

            <!-- Tabla de marcas existentes -->
            <table class="table table-striped" id="brandsTable">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nombre de Marca</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>

        <!-- Modales -->
        <div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="addBrandForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addBrandModalLabel">Añadir Nueva Marca</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div id="addBrandError" class="alert alert-danger" style="display: none;"></div>
                            <div class="mb-3">
                                <label for="brandName" class="form-label">Nombre de la Marca</label>
                                <input type="text" class="form-control" id="brandName" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Guardar Marca</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal para Editar Marca -->
        <div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editBrandForm">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editBrandModalLabel">Editar Marca</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div id="editBrandError" class="alert alert-danger" style="display: none;"></div>
                            <div class="mb-3">
                                <label for="editBrandName" class="form-label">Nombre de la Marca</label>
                                <input type="text" class="form-control" id="editBrandName" required>
                                <input type="hidden" id="editBrandId">
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
        <div class="modal fade" id="deleteBrandModal" tabindex="-1" aria-labelledby="deleteBrandModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteBrandModalLabel">Eliminar Marca</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Estás seguro que deseas eliminar la marca <strong id="deleteBrandName"></strong> con ID <strong id="deleteBrandId"></strong>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" id="confirmDelete" class="btn btn-danger">Eliminar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Toast de error -->
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

    </div>

</body>

</html>