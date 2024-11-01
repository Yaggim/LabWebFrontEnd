<?php require_once __DIR__ .'/../config/config.php' ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech - Producto</title>

    <?php require(RUTA_PROYECTO.'/components/head.php') ?>

    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO ?>/js/compra.js" defer></script>
</head>

<body>
    <?php require(RUTA_PROYECTO.'/components/header.php'); ?>

    <!-- Comienzo del CONTAINER -->
    <div class="container mt-5 mb-5 border">
        <h1 class="text-center">Finalizar la compra</h1>
        <form id="formCompra" action="generar_venta.php" method="POST">
            <!-- Opción para retiro en el local -->
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" name="retiroLocal" id="retiroLocal">
                <label class="form-check-label" for="retiroLocal">
                    <h6 class="modal-title fs-5"><i class="bi bi-geo-alt"></i> ¡Quiero retirarlo por su local!</h6>
                </label>
            </div>
            <!-- Dirección y horario del local (oculto por defecto) -->
            <div class="mb-3 d-none" id="infoLocal">
                <p><strong>Dirección:</strong><br>Malabia 355 - Capital Federal</p>
                <p><strong>Horarios:</strong><br> Lunes a viernes de 9:00 a 19:00</p>
                <p>Importante: Deberá decirnos el número de pedido, que le figurará luego de que finalice la compra.</p>
            </div>

            <!-- Campos de envío a domicilio -->
            <div id="camposEnvio">
                <h6 class="modal-title fs-5"><i class="bi bi-truck"></i> Prefiero que lo envíen a domicilio:</h6>
                <div class="mb-3">
                    <label for="envioTitular" class="col-form-label">Ingrese su nombre y apellido:</label>
                    <input type="text" class="form-control" name="envioTitular" id="envioTitular"
                        placeholder="Máximo Cozzetti" pattern="^[A-Za-záéíóúÁÉÍÓÚ ]+$" minlength="3" maxlength="50" required>
                        <div id="errorNombre" class="invalid-feedback">El nombre debe: Tener 2 palabras, contener entre 3 y 50 caracteres y ser solamente letras.</div>
                </div>
                <div class="mb-3">
                    <label for="envioCalle" class="col-form-label">Ingrese nombre de calle:</label>
                    <input type="text" class="form-control" name="envioCalle" id="envioCalle"
                        placeholder="Manuel Belgrano" pattern="^[A-Za-z0-9.áéíóúÁÉÍÓÚ ]+$" minlength="3" maxlength="30"
                        required>
                        <div id="errorCalle" class="invalid-feedback">La calle debe: Contener entre 3 y 30 caracteres. Pueden ser: Letras mayus/minus, números, puntos, tildes y espacios</div>
                </div>
                <div class="mb-3">
                    <label for="envioAltura" class="col-form-label">Ingrese altura:</label>
                    <input type="number" class="form-control" name="envioAltura" id="envioAltura" placeholder="1027"
                        min="1" max="99999" required>
                        <div id="errorAltura" class="invalid-feedback">La altura debe: Ser numérica y estar comprendida entre N° 1 - 99999</div>
                </div>
                <div class="mb-3">
                    <label for="envioPostal" class="col-form-label">Código Postal:</label>
                    <input type="number" class="form-control" name="envioPostal" id="envioPostal" placeholder="1838"
                        min="1000" max="9999" required>
                        <div id="errorPostal" class="invalid-feedback">El CP debe: Tener 4 dígitos</div>
                </div>
                <div class="mb-3">
                    <label for="envioNota" class="col-form-label">Notas adicionales:</label>
                    <textarea class="form-control" name="envioNota" id="envioNota"
                        placeholder="Agregue, de ser necesario, número de departamento o instrucciones para el repartidor."
                        maxlength="200"></textarea>
                        <div id="errorNota" class="invalid-feedback">La nota debe: Tener como máximo 200 caracteres</div>
                </div>
            </div>
            <h6 class="modal-title fs-5"><i class="bi bi-credit-card-2-back"></i> Concretar el pago</h6>
            <div class="mb-3">
                <label for="dni" class="col-form-label">Ingrese su número de DNI:</label>
                <input type="number" class="form-control" name="dni" id="dni"
                    placeholder="Solo números, sin puntos." min="999999" max="99999999" required>
                    <div id="errorDni" class="invalid-feedback">El DNI debe: Ser numérico sin puntos y estar comprendido entre 999999 y 99999999</div>
                <label for="tarjeta" class="col-form-label">Ingrese el número de su tarjeta registrada en
                    MercadoPago:</label>
                <input type="number" class="form-control" name="tarjeta" id="tarjeta"
                    placeholder="1234 5678 1234 (12 digitos)" min="100010001000" max="999999999999"
                    required>
                    <div id="errorTarjeta" class="invalid-feedback">El número de tarjeta debe: Contener 12 dígitos</div>
                <p id="pagoTotal"></p>
            </div>
            <div class="d-flex justify-content-between mb-3">
                <button type="button" class="btn btn-secondary" id="btnCancelar">Cancelar</button>
                <button type="submit" class="btn btn-primary">Confirmar</button>
            </div>
        </form>
    </div>
    <!-- Fin del CONTAINER -->

    <!--Modal "Compra exitosa"-->
    <div class="modal fade" id="modalFinCompra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Compra exitosa!</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Body -->
                <div class="modal-body">
                    El número de pedido es #123456
                </div>
                <!-- Footer -->
                <div class="modal-footer">
                    <div class="cantidadCarrito"></div>
                    <button type="button" id="botonModal" class="btn btn-warning"
                        data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Modal "Compra exitosa"-->

    <?php require(RUTA_PROYECTO.'/components/footer.php'); ?>
</body>

</html>