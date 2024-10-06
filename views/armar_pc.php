<?php require_once(__DIR__ .'/../config/config.php'); ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech - Armá tu PC</title>
    
    <?php require(RUTA_PROYECTO.'/components/head.php') ?>

    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/css/armar-pc.css">
    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO ?>/js/armarPc.js" defer></script>
    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO ?>/js/productos.js" defer></script>
</head>

<body>
    <?php require(RUTA_PROYECTO.'/components/header.php'); ?>

    <main id="main">
        <!----------------------------Mensaje arma tu pc------------------------------------->

        <div class="row" id="mensaje">
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 div-mensaje">
                <div class="alert alert-info mt-5 mb-5" role="alert" id="mensaje-armapc">
                    Elegí la marca del procesador y el sistema te irá dando las opciones compatibles para configurar tu
                    PC. Una vez que esté lista podrás agregarla al carrito, seleccionar los métodos de pago y envío, y
                    terminar tu compra!
                </div>

            </div>
        </div>
        <!----------------------------------AMD Intel-------------------------------------->
        <div id="actualizar">
            <div class="row" id="cpu">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 procesador-logo">
                    <button id="cpu1" value="amd">
                        <img src="https://www.gezatek.com.ar/images/armador/amd.jpg">
                    </button>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 procesador-logo">
                    <button id="cpu2" value="intel">
                        <img src="https://www.gezatek.com.ar/images/armador/intel.jpg">
                    </button>
                </div>
            </div>
        </div>


    <div class="floating-cart" id="cart">
        <h4>Paso x de x</h4>
        <h2>Total: $<span id="cart-total">0</span></h2>
    </div>
    
<!-------------------------- FIN AMD INTEL-------------------------------------------->

        <!------------------------------Recibí un email---------------------------------------->

        <div class="container-fluid email">
            <div class="row">
                <div class="col-md-12 col-lg-12">
                    <div class="nv-ht">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor"
                            class="bi bi-envelope-check-fill" viewBox="0 0 16 16">
                            <path
                                d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.026A2 2 0 0 0 2 14h6.256A4.5 4.5 0 0 1 8 12.5a4.49 4.49 0 0 1 1.606-3.446l-.367-.225L8 9.586zM16 4.697v4.974A4.5 4.5 0 0 0 12.5 8a4.5 4.5 0 0 0-1.965.45l-.338-.207z" />
                            <path
                                d="M16 12.5a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0m-1.993-1.679a.5.5 0 0 0-.686.172l-1.17 1.95-.547-.547a.5.5 0 0 0-.708.708l.774.773a.75.75 0 0 0 1.174-.144l1.335-2.226a.5.5 0 0 0-.172-.686" />
                        </svg> Recibí las mejores fertas por correo!!!
                        <input type="email" name="" placeholder="Tu Email">
                        <button class="btn btn-outline-success">Sucribirme</button>
                    </div>
                </div>

            </div>
        </div>
        <!---------------------------------Fin recibi email----------------------------------->
    </main>

    <?php require(RUTA_PROYECTO.'/components/footer.php'); ?>
</body>

</html>