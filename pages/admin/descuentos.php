<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Descuentos</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"
        defer></script>
</head>

<body>

    <nav id="sidebar" class="bg-dark text-white p-3">
        <h3 class="text-center">Panel de administrador</h3>
        <ul class="nav flex-row mt-4">
            <li class="nav-item">
                <a class="nav-link text-white" href="marca.html"><i class="fas fa-tags me-2"></i>Marcas</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="modelo.html"><i class="fas fa-cubes me-2"></i>Modelos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="categoria.html"><i class="fas fa-list-alt me-2"></i>Categorías</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="productos.html"><i class="fas fa-box-open me-2"></i>Productos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="stock.html"><i class="fas fa-warehouse me-2"></i>Stock</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="combos.html"><i class="fas fa-gift me-2"></i>Combos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="descuentos.html"><i class="fas fa-percent me-2"></i>Descuentos</a>
            </li>
        </ul>
    </nav>


    <!-- Crear Descuentos -->
    <div class="card mb-4" id="discounts">
        <div class="card-header">
            <h4>Crear Descuentos</h4>
        </div>
        <div class="card-body">
            <form id="create-discount-form">
                <div class="form-group">
                    <label for="discountName">Nombre del Descuento</label>
                    <input type="text" class="form-control" id="discountName"
                        placeholder="Ingrese el nombre del descuento">
                </div>
                <div class="form-group">
                    <label for="discountPercentage">Porcentaje de Descuento</label>
                    <input type="number" class="form-control" id="discountPercentage"
                        placeholder="Ingrese el porcentaje de descuento">
                </div>
                <div class="form-group">
                    <label for="discountTarget">Aplicar a</label>
                    <select class="form-control" id="discountTarget">
                        <option value="product">Producto</option>
                        <option value="combo">Combo</option>
                        <option value="weekDeal">Oferta de la Semana</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Crear Descuento</button>
            </form>
        </div>
    </div>



    <!-- Crear Oferta de la Semana -->
    <div class="card mb-4" id="weekly-offer">
        <div class="card-header">
            <h4>Crear Oferta de la Semana</h4>
        </div>
        <div class="card-body">
            <form id="create-week-deal-form">
                <div class="form-group">
                    <label for="weekDealName">Nombre de la Oferta</label>
                    <input type="text" class="form-control" id="weekDealName"
                        placeholder="Ingrese el nombre de la oferta">
                </div>
                <div class="form-group">
                    <label for="weekDealProducts">Seleccionar Productos</label>
                    <select multiple class="form-control" id="weekDealProducts">
                        <!-- Opciones de productos -->
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Crear Oferta de la Semana</button>
            </form>
        </div>
    </div>

    <!-- Asignar Descuentos a Combos u Ofertas de la Semana -->
    <div class="card mb-4">
        <div class="card-header">
            <h4>Asignar Descuentos a Combos u Ofertas de la Semana</h4>
        </div>
        <div class="card-body">
            <form id="assign-discount-form">
                <div class="form-group">
                    <label for="selectDiscount">Seleccionar Descuento</label>
                    <select class="form-control" id="selectDiscount">
                        <!-- Opciones de descuentos -->
                    </select>
                </div>
                <div class="form-group">
                    <label for="assignTarget">Asignar a</label>
                    <select class="form-control" id="assignTarget">
                        <option value="combo">Combo</option>
                        <option value="weekDeal">Oferta de la Semana</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Asignar Descuento</button>
            </form>
        </div>
    </div>
</body>

</html>