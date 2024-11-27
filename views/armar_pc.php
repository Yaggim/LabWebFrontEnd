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
        <div id="actualizar" class="container-fluid">
            <div class="row" id="cpu">
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 procesador-logo">
                    <button id="cpu1">
                        <img src="https://www.gezatek.com.ar/images/armador/amd.jpg" name="Amd">
                    </button>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 procesador-logo">
                    <button id="cpu2" >
                        <img src="https://www.gezatek.com.ar/images/armador/intel.jpg" name="Intel">
                    </button>
                </div>
            </div>
        </div>
    
    
    <div class="floating-cart" id="cart">
        <h4>Paso <span id="paso-actual">0</span> de 10</h4>
        <h2>Total: $<span id="cart-total">0</span></h2>
    </div>

    
<!-------------------------- FIN AMD INTEL-------------------------------------------->




        <!-- Modal Bootstrap -->
        <div class="modal fade" id="cantidadModal" tabindex="-1" role="dialog" aria-labelledby="cantidadModalLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="cantidadModalLabel">Añadir Cantidad</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <form id="cantidadForm">
                  <div class="form-group">
                    <label for="cantidadInput">Cantidad</label>
                    <input type="number" class="form-control" id="cantidadInput" min="1" value="1">
                  </div>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="guardarCantidad">Añadir</button>
              </div>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalError" tabindex="-1" aria-labelledby="modalErrorLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalErrorLabel">Error</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p id="modalErrorMensaje"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="modalErrorCerrar">Cerrar</button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <?php require(RUTA_PROYECTO.'/components/footer.php'); ?>
</body>

</html>