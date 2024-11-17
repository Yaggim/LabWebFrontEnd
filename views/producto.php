<?php require_once __DIR__ . '/../config/config.php';

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech - Producto</title>

    <?php require(RUTA_PROYECTO . '/components/head.php') ?>

    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/css/producto.css">
    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO ?>/js/producto.js" defer></script>
</head>

<body>
    <?php require(RUTA_PROYECTO . '/components/header.php'); ?>

    <div class="container p-2 my-2 rounded">
        <div class="row">
            <!--DIV mitad izquierda-->
            <div class="col-6 h-100 p-2">
                <!-- Carrusel para productos -->
            <div id="producto-carousel" class="carousel slide" data-bs-ride="carousel" style="display: none;">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#producto-carousel" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#producto-carousel" data-bs-slide-to="1"></button>
                </div>

                <div class="carousel-inner rounded">
                    <div class="carousel-item active" id="producto-image1-container">
                        <img id="producto-image1" src="/<?php echo CARPETA_PROYECTO ?>/" alt="Imagen 1 producto"
                            class="d-block w-100 h-100 object-fit-cover">
                    </div>
                    <div class="carousel-item" id="producto-image2-container">
                        <img id="producto-image2" src="/<?php echo CARPETA_PROYECTO ?>/" alt="Imagen 2 producto"
                            class="d-block w-100 h-100 object-fit-cover">
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#producto-carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#producto-carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
            <!-- Fin carrusel para productos -->

            <!-- Carrusel para combos -->
            <div id="combo-carousel" class="carousel slide" data-bs-ride="carousel" style="display: none;">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#combo-carousel" data-bs-slide-to="0" class="active"></button>
                </div>

                <div class="carousel-inner rounded">
                    <div class="carousel-item active" id="combo-image1-container">
                        <img id="combo-image1" src="/<?php echo CARPETA_PROYECTO ?>/" alt="Imagen 1 combo"
                            class="d-block w-100 h-100 object-fit-cover">
                    </div>
                </div>

                <button class="carousel-control-prev" type="button" data-bs-target="#combo-carousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#combo-carousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
            <!-- Fin carrusel para combos -->
                <div id="divDescripcion" class="p-2 my-2 border rounded">
                    <h3 id="descripcion-titulo">Descripción del producto</h3>
                    <p id="producto-description">{description}</p>
                    <div id="combo-description"></div>
                </div>
            </div>
            <!--Fin DIV mitad izquierda-->

            <!--DIV mitad derecha-->
            <div class="col-6 h-100 p-2">
                <div id="divData" class="p-2 rounded">
                <div id="producto-container" style="display: none;">
                    <p id="producto-id" class="idProducto">Código de identificación de producto: {id}</p>
                    <h1 id="producto-brand-model">Marca producto {brand} + modelo {model}</h1>
                    <h6 id="producto-category">Categoría {category}</h6>
                    <h2 id="producto-priceARS">$Precio {price ars}</h2>
                </div>
                    <!-- Contenedor para los detalles del combo -->
                    <div id="combo-container" style="display: none;">
                        <p id="combo-id" class="idCombo">Código de identificación de combo: {id}</p>
                        <h1 id="combo-nombre">Nombre del combo {nombre}</h1>
                        <h6 id="combo-descuento">Descuento {descuento}%</h6>
                        <div id="combo-description"></div>
                        <h2 id="combo-priceARS">Precio AR$ {price}</h2> 
                    </div>
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
                    <form id="comprar" action="/<?php echo CARPETA_PROYECTO ?>/views/compra.php" method="POST">
                        <button id="btnCompra" type="submit" class="btn btn-success btn-lg">Ver carrito 
                            <i class="bi bi-eye"></i></i></button>
                    </form>
                </div>
                <!-- Carrito -->
                <div class="col-12 p-2 text-center rounded">
                    <input type="hidden" id="carritoInput" name="carrito">
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
                    <h4 class="modal-title">Producto/Combo agregado!</h4>
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

    <?php require(RUTA_PROYECTO . '/components/footer.php'); ?>
</body>

</html>