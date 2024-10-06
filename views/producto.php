<?php require_once __DIR__ .'/../config/config.php' ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech - Producto</title>

    <?php require(RUTA_PROYECTO.'/components/head.php') ?>

    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/css/producto.css">
    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO ?>/js/producto.js" defer></script>
</head>

<body>
    <?php require(RUTA_PROYECTO.'/components/header.php'); ?>

    <div class="container p-2 my-2 rounded">
        <div class="row">
            <!--DIV mitad izquierda-->
            <div class="col-6 h-100 p-2">
                <!--Carrusel-->
                <div id="demo" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#demo" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#demo" data-bs-slide-to="1"></button>
                    </div>

                    <div class="carousel-inner rounded">
                        <div class="carousel-item active">
                            <img id="producto-image1" src="/<?php echo CARPETA_PROYECTO ?>/" alt="Imagen 1 producto "
                                class="d-block w-100 h-100 object-fit-cover">
                        </div>
                        <div class="carousel-item">
                            <img id="producto-image2" src="/<?php echo CARPETA_PROYECTO ?>/" alt="Imagen 2 producto"
                                class="d-block w-100 h-100 object-fit-cover">
                        </div>
                    </div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#demo" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#demo" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>
                <!--Fin carrusel-->
                <div id="divDescripcion" class="p-2 my-2 border rounded">
                    <h3>Descripción del producto</h3>
                    <p id="producto-description">{description}</p>
                </div>
            </div>
            <!--Fin DIV mitad izquierda-->

            <!--DIV mitad derecha-->
            <div class="col-6 h-100 p-2">
                <div id="divData" class="p-2 rounded">
                    <p id="producto-id" class="idProducto">Código de identificación de producto: {id}
                    <p>
                    <h1 id="producto-brand-model">Marca producto {brand} + modelo {model}</h1>
                    <h6 id="producto-category">Categoría {category}</h6>
                    <h2 id="producto-priceARS">$Precio {price ars}</h2>
                </div>
                <div class="row d-flex justify-content-center">
                    <div id="divStock" class="col-4 p-2 my-3 mx-1 border text-center rounded">
                        <h5>Stock actual en la web:</h5>
                        <h2>ALTO O BAJO {stock}</h2>
                    </div>
                    <div class="col-4 p-2 my-3 mx-1 border text-center rounded">
                        <p class="cantidad">Seleccione la cantidad</p>
                        <div class="d-flex align-items-center justify-content-center rounded">
                            <!-- Botón para disminuir la cantidad -->
                            <button id="btnDecrement" class="btn btn-secondary">-</button>
                            <!-- Campo de texto para la cantidad elegida -->
                            <input id="quantity" type="text" value="1" class="form-control text-center mx-2" readonly>
                            <!-- Botón para aumentar la cantidad -->
                            <button id="btnIncrement" class="btn btn-secondary">+</button>
                        </div>
                    </div>
                </div>
                <!-- Botón de compra -->
                <div class="col-12 p-2 text-center rounded">
                    <button id="btnCompra" type="button" class="btn btn-success btn-lg">REALIZAR COMPRA <i
                            class="bi bi-credit-card"></i></button>
                </div>
                <!-- Carrito -->
                <div class="col-12 p-2 text-center rounded">
                    <button id="btnCarrito" type="button" class="btn btn-primary btn-lg">Agregar al carrito <i
                            class="bi bi-cart-plus"></i></button>
                </div>

                <div class="p-2 my-2 border rounded text-center">
                    <h3>Gestionamos todos los medios de pago gracias a:</h3>
                    <img src="/<?php echo CARPETA_PROYECTO ?>/images/mediopago1.png">
                </div>
            </div>
            <!--Fin DIV mitad derecha-->
        </div>
        <!--Fin DIV row-->
    </div>
    <!--Fin container-->

    <!--Modal "Agregado al carrito"-->
    <div class="modal" id="modalCarrito" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Producto agregado!</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Body -->
                <div class="modal-body">
                    Se ha añadido exitosamente {brand} + {model} al carrito
                </div>
                <!-- Footer -->
                <div class="modal-footer">
                    <div class="cantidadCarrito"></div>
                    <button type="button" id="botonModal" class="btn btn-success"
                        data-bs-dismiss="modal">Aceptar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Modal "Agregado al carrito"-->

    <!--Modal "Comprar"-->
    <div class="modal fade" id="modalCompra" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Finalizar la compra:</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formCompra" method="POST">
                        <!-- Opción para retiro en el local -->
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="retiroLocal" id="retiroLocal">
                            <label class="form-check-label" for="retiroLocal">
                                <h6 class="modal-title fs-5"><i class="bi bi-geo-alt"></i> ¡Quiero retirarlo por su
                                    local!</h6>
                            </label>
                        </div>
                        <!-- Dirección y horario del local (oculto por defecto) -->
                        <div class="mb-3 d-none" id="infoLocal">
                            <p><strong>Dirección:</strong><br>Malabia 355 - Capital Federal</p>
                            <p><strong>Horarios:</strong><br> Lunes a viernes de 9:00 a 19:00</p>
                            <p>Importante: Deberá decirnos el número de pedido, que le figurará luego de que finalice la
                                compra.</p>
                        </div>

                        <!-- Campos de envío a domicilio -->
                        <div id="camposEnvio">
                            <h6 class="modal-title fs-5"><i class="bi bi-truck"></i> Prefiero que lo envíen a domicilio:
                            </h6>
                            <div class="mb-3">
                                <label for="envioTitular" class="col-form-label">Ingrese su nombre y apellido:</label>
                                <input type="text" class="form-control" name="envioTitular" id="envioTitular"
                                    placeholder="Máximo Cozzetti" pattern="^[A-Za-záéíóúÁÉÍÓÚ ]+$" minlength="3"
                                    maxlength="50" required>
                                <div id="errorNombre" class="invalid-feedback">El nombre debe: Tener 2 palabras,
                                    contener entre 3 y 50 caracteres y ser solamente letras.</div>
                            </div>
                            <div class="mb-3">
                                <label for="envioCalle" class="col-form-label">Ingrese nombre de calle:</label>
                                <input type="text" class="form-control" name="envioCalle" id="envioCalle"
                                    placeholder="Manuel Belgrano" pattern="^[A-Za-z0-9.áéíóúÁÉÍÓÚ ]+$" minlength="3"
                                    maxlength="30" required>
                                <div id="errorCalle" class="invalid-feedback">La calle debe: Contener entre 3 y 30
                                    caracteres. Pueden ser: Letras mayus/minus, números, puntos, tildes y espacios</div>
                            </div>
                            <div class="mb-3">
                                <label for="envioAltura" class="col-form-label">Ingrese altura:</label>
                                <input type="number" class="form-control" name="envioAltura" id="envioAltura"
                                    placeholder="1027" min="1" max="99999" required>
                                <div id="errorAltura" class="invalid-feedback">La altura debe: Ser numérica y estar
                                    comprendida entre N° 1 - 99999</div>
                            </div>
                            <div class="mb-3">
                                <label for="envioPostal" class="col-form-label">Código Postal:</label>
                                <input type="number" class="form-control" name="envioPostal" id="envioPostal"
                                    placeholder="1838" min="1000" max="9999" required>
                                <div id="errorPostal" class="invalid-feedback">El CP debe: Tener 4 dígitos</div>
                            </div>
                            <div class="mb-3">
                                <label for="envioNota" class="col-form-label">Notas adicionales:</label>
                                <textarea class="form-control" name="envioNota" id="envioNota"
                                    placeholder="Agregue, de ser necesario, número de departamento o instrucciones para el repartidor."
                                    maxlength="200"></textarea>
                                <div id="errorNota" class="invalid-feedback">La nota debe: Tener como máximo 200
                                    caracteres</div>
                            </div>
                        </div>
                        <h6 class="modal-title fs-5"><i class="bi bi-credit-card-2-back"></i> Concretar el pago</h6>
                        <div class="mb-3">
                            <label for="dni" class="col-form-label">Ingrese su número de DNI:</label>
                            <input type="number" class="form-control" name="dni" id="dni"
                                placeholder="Solo números, sin puntos." min="999999" max="99999999" required>
                            <div id="errorDni" class="invalid-feedback">El DNI debe: Ser numérico sin puntos y estar
                                comprendido entre 999999 y 99999999</div>
                            <label for="tarjeta" class="col-form-label">Ingrese el número de su tarjeta registrada en
                                MercadoPago:</label>
                            <input type="number" class="form-control" name="tarjeta" id="tarjeta"
                                placeholder="1234 5678 1234 (12 digitos)" min="100010001000" max="999999999999"
                                required>
                            <div id="errorTarjeta" class="invalid-feedback">El número de tarjeta debe: Contener 12
                                dígitos</div>
                            <p id="pagoTotal"></p>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" form="formCompra" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Modal "Comprar"-->

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