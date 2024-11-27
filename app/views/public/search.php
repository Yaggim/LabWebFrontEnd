<?php require_once(__DIR__ .'/../../../config/config.php'); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title id="title-tag" >HardTech - Búsqueda</title>

    <?php require(RUTA_PROYECTO.'/app/views/components/head.php') ?>

    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/public/css/public/producto.css">
    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/public/css/public/search.css">
</head>

<body>
    <?php require(RUTA_PROYECTO.'/app/views/components/header.php'); ?>

    <div class="container-fluid content-wrap align-content-center">
        <div class="row justify-content-center">
            <main class="col-lg-10 col-md-12 col-sm-8 text-center">
                <div class="container-titulo">
                    <h1 id="titulo-categoria">Productos</h1>
                </div>
                <div id="product-container-search" class="row"></div>
            </main>
        </div>
    </div>

    <?php require(RUTA_PROYECTO.'/app/views/components/footer.php'); ?>
</body>
</html>