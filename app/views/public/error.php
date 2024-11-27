<?php require_once __DIR__ . '/../../../config/config.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech - Error</title>
    <?php require(RUTA_PROYECTO . '/app/views/components/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/public/css/public/producto.css">
</head>

<body class="d-flex flex-column" style="height: 100vh;">
    <?php require(RUTA_PROYECTO . '/app/views/components/header.php'); ?>

    <main class="d-flex flex-column justify-content-center align-items-center flex-fill">
        <div class="container p-2 my-2 rounded">
            <div class="row">
                <div class="col-12 p-3 text-center text-light rounded">
                    <h1>La petición solicitada no se pudo procesar.</h1>
                </div>
            </div>
            <div class="row">
                <div class="col-12 p-3 text-center rounded">
                    <form action="/<?php echo CARPETA_PROYECTO ?>/home" method="GET">
                        <button type="submit" class="btn btn-success btn-lg">VOLVER AL HOME <i class="bi bi-house-up"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <?php require(RUTA_PROYECTO . '/app/views/components/footer.php'); ?>
</body>

</html>