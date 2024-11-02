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
    <script src="js/stock.js" type="module" defer></script>
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>