<?php require_once(__DIR__ .'/../config/config.php'); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech - Productos</title>

    <?php require(RUTA_PROYECTO.'/components/head.php') ?>

    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/css/producto.css">
    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/css/productos.css">
    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO ?>/js/productos.js" defer></script>
</head>

<body>
    <?php require(RUTA_PROYECTO.'/components/header.php'); ?>

    <div class="container-fluid">
        <div class="row">
            <button class="btn btn-primary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar" aria-controls="sidebar" aria-expanded="false" aria-label="Toggle navigation">
                <i class="bi bi-list"></i> Categorías
            </button>
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse mt-3">
                <div class="position-sticky">
                    <input type="text" id="search" class="form-control" placeholder="Buscar por producto...">
                    
                    <ul class="nav flex-column" id="category-list">
                        <!-- Categorías dinámicas se cargarán aquí -->
                    </ul>
                    <button id="clear-filters" class="btn btn-secondary btn-clear">
                        <i class="bi bi-x"></i>
                        Limpiar filtros
                    </button>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <header class="my-4">
                    <h1 class="text-center">Productos</h1>
                </header>
                <div id="product-container" class="row"></div>
            </main>
        </div>
    </div>

    <?php require(RUTA_PROYECTO.'/components/footer.php'); ?>
</body>
</html>