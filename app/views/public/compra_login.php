<?php require_once __DIR__ . '/../../../config/config.php';
?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech</title>
    <?php require(RUTA_PROYECTO . '/app/views/components/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/public/css/public/producto.css">
    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO ?>/public/js/public/login.js" defer></script>
</head>

<body class="d-flex flex-column" style="height: 100vh;">
    <?php require(RUTA_PROYECTO . '/app/views/components/header.php'); ?>

    <main class="d-flex flex-column justify-content-center align-items-center flex-fill">
        <div class="container p-2 my-2 rounded">
            <div class="row">
                <div class="col-12 p-3 text-center text-light rounded">
                    <h1>¡Lo sentimos!</h1>
                    <h3>Debes tener una cuenta para comprar en HardTech</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-12 p-3 text-center rounded">
                    <form action="<?php echo '/' . CARPETA_PROYECTO . '/registro'; ?>" method="GET">
                        <button type="submit" class="btn btn-warning btn-lg">Registrarme <i
                                class="bi bi-person-lines-fill"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </main>
    <?php require(RUTA_PROYECTO . '/app/views/components/footer.php'); ?>
</body>

</html>