<?php require_once __DIR__ . '/../config/config.php';
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech</title>
    <?php require(RUTA_PROYECTO . '/components/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/css/producto.css">
</head>

<body>
    <?php require(RUTA_PROYECTO . '/components/header.php'); ?>

    <div class="container p-2 my-2 rounded">
    <div class="row">
    <div class="col-12 p-3 text-center text-light rounded">
        <h1>La petición solicitada no se pudo procesar.</h1>
    </div>
    </div>
        <div class="row">
            <div class="col-12 p-3 text-center rounded">
                <form action="<?php echo '/' . CARPETA_PROYECTO . '/index.php'; ?>" method="GET">
                    <button type="submit" class="btn btn-success btn-lg">VOLVER AL HOME <i class="bi bi-house-up"></i></button>
                </form>
            </div>
        </div>

    <?php require(RUTA_PROYECTO . '/components/footer.php'); ?>
</body>

</html>