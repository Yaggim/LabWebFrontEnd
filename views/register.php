<?php require_once(__DIR__ .'/../config/config.php'); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech - Registro</title>

    <?php require(RUTA_PROYECTO.'/components/head.php') ?>

    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/css/register.css">
    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO ?>/js/register.js" defer></script>
</head>

<body>
    <?php require(RUTA_PROYECTO.'/components/header.php'); ?>

    <main class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="card col-md-8 text-white tarjeta-registro">
                <section class="card-body">
                    <h2 class="text-center mb-4">Crea tu cuenta</h2>
                    <p class="text-center">Por favor, completa el siguiente formulario para crear una cuenta en nuestra tienda.</p>

                    <form action="" method="" class="formulario-registro" id="form">
                        <!-- Información personal -->
                        <fieldset class="mb-4">
                            <legend>Información Personal</legend>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre:</label>
                                    <input type="text" id="nombre" name="nombre" class="form-control"  placeholder="Martín">
                                    <div id="error_nombre" class="error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="apellido" class="form-label">Apellido:</label>
                                    <input type="text" id="apellido" name="apellido" class="form-control" placeholder="Lutero">
                                    <div id="error_apellido" class="error"></div>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="dni" class="form-label">Número de DNI o CUIT:</label>
                                    <input type="number" id="dni" name="dni" class="form-control" placeholder="99.999.999" min="3000000" max="999000000">
                                    <div id="error_dni" class="error"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="telefono" class="form-label">Número de Teléfono:</label>
                                    <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="12-3456-7890">
                                    <div id="error_tel" class="error"></div>
                                </div>
                            </div>
                        </fieldset>

                        <!-- Información de la cuenta -->
                        <fieldset class="mb-4">
                            <legend>Información de la Cuenta</legend>
                            <div class="mb-3">
                                <label for="username" class="form-label">Nombre de Usuario:</label>
                                <input type="text" id="username" name="username" class="form-control" placeholder="usuario123">
                                <div id="error_user" class="error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico:</label>
                                <input type="email" id="email" name="email" class="form-control"  placeholder="correo@ejemplo.com">
                                <div id="error_email" class="error"></div>
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Contraseña:</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="********" minlength="8">
                                <div id="error_pass" class="error"></div>
                                <!--<p>La contraseña debe tener al menos 8 caracteres.</p>-->
                            </div>
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirmar Contraseña:</label>
                                <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="********" minlength="8">
                                <div id="error_pass_conf" class="error"></div>
                                <!--<p>La contraseña debe tener al menos 8 caracteres.</p>-->
                        </fieldset>

                        <!-- Términos y condiciones -->
                        <div class="mb-2 form-check position-relative">
                            <div class="position-absolute top-50 start-50 translate-middle">
                                <input type="checkbox" id="terminos" name="terminos" class="form-check-input">
                                <label for="terminos">He leído y acepto los <a href="#">Términos y Condiciones</a>.</label>
                                <div id="error_terminos" class="error"></div>
                            </div>
                        </div>

                        <!-- Botón de envío -->
                        <div class="mb-4 pt-4 text-center">
                            <button type="submit" class="btn btn-custom">Crear Cuenta</button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    <!-- Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby=" exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Registro Exitoso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Se ha registrado exitosamente!!!!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!---FIN MODAL--->
    </main>

    <?php require(RUTA_PROYECTO.'/components/footer.php'); ?>
</body>

</html>