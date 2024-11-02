<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"
        defer></script>
    <script src="js/producto.js" type="module" defer></script>
</head>

<body>

    <nav id="sidebar" class="bg-dark text-white p-3">
        <h3 class="text-center">Panel de administrador</h3>
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

    <!--Productos-->

    <div id="products" class="container mt-5">
        <h2>Gestión de Productos</h2>
        <button class="btn btn-primary my-3" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="fas fa-plus"></i> Añadir Producto
        </button>
        
        <!-- Tabla de productos existentes -->
        <table class="table table-striped" id="productsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Precio (USD)</th>
                    <th>Habilitado</th>
                    <th>Imagen</th>
                    <th>Imagen 2</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    
    <!-- Modal para añadir producto -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="addProductForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProductModalLabel">Añadir Nuevo Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="productModel" class="form-label">Modelo</label>
                                <div class="input-group">
                                    <select class="form-select" id="productModel" required>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="productCategory" class="form-label">Categoría</label>
                                <select class="form-select" id="productCategory" required>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="productStock" class="form-label">stock</label>
                                <input type="number" class="form-control" id="productStock" required>
                            </div>
                            <div class="col-md-6">
                                <label for="productPriceUSD" class="form-label">Precio en Dólares (USD)</label>
                                <input type="number" class="form-control" id="productPriceUSD">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label d-block">Habilitado</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="productEnabled" checked>
                                    <label class="form-check-label" for="productEnabled">Sí</label>
                                </div>
                            </div>
                            <div class="col-12" id="imageContainer">
    <label for="productImages" class="form-label">Imágenes (URLs)</label>
    <input type="text" class="form-control mb-2" id="productImage" placeholder="URL de imagen">
    <button type="button" class="btn btn-secondary" id="addImageField">Agregar otra imagen</button>
</div>
                            <div class="col-12">
                                <label for="productDescription" class="form-label">Descripción</label>
                                <textarea class="form-control" id="productDescription" rows="3" required></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar Producto</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="editProductForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <input type="hidden" id="editProductId"/>
                            <div class="col-md-6">
                                <label for="editProductModel" class="form-label">Modelo</label>
                                <div class="input-group">
                                    <select class="form-select" id="editProductModel" required>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="editProductCategory" class="form-label">Categoría</label>
                                <select class="form-select" id="editProductCategory" required>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="editProductStock" class="form-label">Stock</label>
                                <input type="number" class="form-control" id="editProductStock" required>
                            </div>
                            <div class="col-md-6">
                                <label for="editProductPriceUSD" class="form-label">Precio en Dólares (USD)</label>
                                <input type="number" class="form-control" id="editProductPriceUSD">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label d-block">Habilitado</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="editProductEnabled" checked>
                                    <label class="form-check-label" for="editProductEnabled">Sí</label>
                                </div>
                            </div>
                            <div class="mb-3">
        <label for="editProductImages" class="form-label">Imágenes</label>
        <div id="editImageContainer">
            
        </div>
        <button type="button" id="editImageField" class="btn btn-secondary">Agregar imagen</button>
    </div>
                            <div class="col-12">
                                <label for="editProductDescription" class="form-label">Descripción</label>
                                <textarea class="form-control" id="editProductDescription" rows="3" required></textarea>
                            </div>
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


    <!-- Modal para Eliminar Producto -->
    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProductModalLabel">Eliminar Producto</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Estás seguro que deseas eliminar el producto <strong id="deleteProductName"></strong> con ID <strong id="deleteProductId"></strong>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="confirmDelete" class="btn btn-danger">Eliminar</button>
                </div>
            </div>
        </div>
    </div>


    
</body>

</html>