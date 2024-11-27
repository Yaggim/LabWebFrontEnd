<?php

require_once __DIR__ .'/../../../config/config.php';

if (!$usuario->isLoggedIn()) {
    header("Location: home");
    die;
}

if (!Permisos::tienePermiso("Visualizar perfil", $_SESSION['usuario']['id'])) {
    header("Location: home");
    die;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech - Perfil</title>

    <?php require RUTA_PROYECTO.'/app/views/components/head.php'; ?>

    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/public/css/public/register.css">
    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO ?>/public/js/public/register.js" defer></script>
</head>

<body>
    <?php require RUTA_PROYECTO.'/app/views/components/header.php'; ?>

    <main class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="card col-md-8 text-white tarjeta-registro">
                <section class="card-body d-flex flex-column align-items-center justify-content-center">
                    <div class="row justify-content-center align-items-center mb-4" >
                        <img src="/<?php echo CARPETA_PROYECTO ?>/public/images/user-profile.png" class="col-4 col-sm-4 col-md-4 col-lg-4 img-fluid" alt="imagen de perfil genérica">
                        <h2 class="text-center col-12 col-sm-5 col-md-6 col-lg-4">Perfil</h2>
                    </div>

                    <!-- Información personal -->
                    <div class="container-fluid" >
                        <div class="row justify-content-center" >
                            <div class="col-12 col-md-12 col-lg-auto d-flex flex-column align-items-center align-items-md-center mb-4">
                                <h4 class="mb-3">Información Personal</h4>
                                <div class="align-self-center mb-1 mt-1">Nombre: <?php echo $_SESSION['usuario']['nombre'] ?></div>
                                <div class="align-self-center mb-1 mt-1">Apellido: <?php echo $_SESSION['usuario']['apellido'] ?></div>
                                <div class="align-self-center mb-1 mt-1">Número de DNI: <?php echo $_SESSION['usuario']['dni'] ?></div>
                                <div class="align-self-center mb-1 mt-1">Número de Teléfono: <?php echo $_SESSION['usuario']['telefono'] ?></div>
                            </div>

                            <!-- Información de la cuenta -->
                            <div class="col-12 col-md-12 col-lg-auto d-flex flex-column align-items-center align-items-md-center mb-3">
                                <h4 class="mb-3">Información de la Cuenta</h4>
                                <div class="align-self-center mb-1 mt-1">Nombre de Usuario: <?php echo $_SESSION['usuario']['username'] ?></div>
                                <div class="align-self-center mb-1 mt-1">Correo Electrónico: <?php echo $_SESSION['usuario']['email'] ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Botón de envío -->
                    <div class="container-fluid mt-5 mb-2" >
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-6 col-lg-auto d-flex flex-column align-items-center align-items-md-center mb-3">
                                <button class="btn btn-custom w-100 btnEditarPerfil">Editar perfil</button>
                            </div>
                            <div class="col-12 col-md-6 col-lg-auto d-flex flex-column align-items-center align-items-md-center mb-3">
                                <button class="btn btn-secondary w-100 btnVolverHome">Volver a Home</button>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>

    <?php require RUTA_PROYECTO.'/app/views/components/footer.php'; ?>
</body>

</html>