<?php

require_once __DIR__ .'/../config/config.php';

if (!$usuario->isLoggedIn()) {
    header("Location: home");
    die;
}

if (!Permisos::tienePermiso("Editar perfil", $_SESSION['usuario']['id'])) {
    header("Location: home");
    die;
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech - Editar perfil</title>

    <?php require(RUTA_PROYECTO.'/components/head.php') ?>

    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/css/register.css">
    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO ?>/js/actualizarPerfil.js" defer></script>
</head>

<body>
    <?php require(RUTA_PROYECTO.'/components/header.php'); ?>

    <main class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="card col-md-8 text-white tarjeta-registro">
                <section class="card-body">
                    <h2 class="text-center mb-4">Modificación de datos:</h2>

                    <form id="formulario" action="" method="POST" class="formulario-registro">
                        <!-- Información personal -->
                        <fieldset class="mb-4">
                            <legend>Tu información personal:</legend>
                            <div class="row mb-3">
                                <div class="col">
                                    <label for="telefono" class="form-label">Nuevo número de teléfono:</label>
                                    <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="12-3456-7890">
                                    <p id="errorTel" class="invalid-feedback"></p>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Información de la cuenta -->
                        <fieldset class="mb-4">
                            <legend>Tu información de cuenta</legend>
                            <div class="mb-3">
                                <label for="email" class="form-label">Actualizar correo electrónico:</label>
                                <input type="email" id="email" name="email" class="form-control" placeholder="correo@ejemplo.com">
                                <p id="errorEmail" class="invalid-feedback"></p>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña nueva:</label>
                                <input type="password" id="password" name="password" class="form-control"
                                    placeholder="La contraseña debe tener al menos 8 caracteres." minlength="8" maxlength="20">
                                <p id="errorPassword" class="invalid-feedback"></p>
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmar nueva contraseña:</label>
                                <input type="password" id="confirm_password" name="confirm_password"
                                    class="form-control" placeholder="Vuelve a ingresar la contraseña" minlength="8" maxlength="20">
                                <p id="errorConfirmPassword" class="invalid-feedback"></p>
                            </div>
                            <div class="mb-3">
                                <label for="oldPassword" class="form-label">Contraseña anterior:</label>
                                <input type="password" id="oldPassword" name="oldPassword" class="form-control"
                                    placeholder="Ingrese su contraseña actual para actualizar sus datos" minlength="8" maxlength="20">
                                <p id="errorOldPassword" class="invalid-feedback"></p>
                            </div>
                        </fieldset>

                        <!-- Botón de envío -->
                        <div class="mb-4 pt-4 text-center">
                            <button type="submit" class="btn btn-custom">Actualizar datos</button>
                            <button type="button" class="btn btn-secondary btnVolverHome">Cancelar</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </main>

    <?php require(RUTA_PROYECTO.'/components/footer.php'); ?>
</body>

</html>