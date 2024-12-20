<?php
require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../includes/conexion.php';


if(!isset($_SESSION['usuario'])){
    header('Location: /' . CARPETA_PROYECTO . '/compra-login');
    die();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    require(RUTA_PROYECTO . '/app/views/public/error.php');
    exit();
}

// IMPORTANTE: LE SACO LOS REQUIRED DE HTML PARA PROBAR LAS VALIDACIONES DE PHP

$errors = [];
$variables = []; 

// VALIDAR FORMULARIO SI LLEGA POR POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {  
    $carrito = $_SESSION['carrito']['carrito']; 
    $productos_string = ""; 
    $combos_string = ""; 
    $precio_total_productos = 0.00;
    $precio_total_combos = 0.00;

    foreach ($carrito as $producto) {
        if (isset($producto['productos'])) {
            // Es un combo
            foreach ($producto['productos'] as $prod) {
                $combos_string .= "(" . $producto['id'] . ", " . $prod['id_producto'] . ", " . $prod['cantidad'] . ", " . number_format((float)$prod['precioEnPesos'], 2, '.', '') . "), ";
            }
            $precio_total_combos += $producto['precioEnPesos'];
        } else {
            // Es un producto normal
            $productos_string .= "(" . $producto['id'] . ", " . $producto['cantidad'] . ", " . number_format((float)$producto['precioEnPesos'], 2, '.', '') . "), ";
            $precio_total_productos += $producto['cantidad'] * floatval($producto['precioEnPesos']);
        }
    }
    $productos_string = rtrim($productos_string, ", ");
    $combos_string = rtrim($combos_string, ", ");
    $id_persona = intval($_SESSION['usuario']['id_persona']);
    $id_usuario = intval($_SESSION['usuario']['id']);

    $esRetiroLocal = isset($_POST['retiroLocal']) && $_POST['retiroLocal'] === 'on';
    if(!$esRetiroLocal){
        if(isset($_POST['envioTitular'])){
            // Validación del nombre y apellido: 2 Palabras, min 3 max 50 caracteres validos
            if (empty($_POST['envioTitular']) || !preg_match('/^\S+\s+\S+$/', $_POST['envioTitular']) || !preg_match('/^[A-Za-záéíóúÁÉÍÓÚ ]{3,50}$/', $_POST['envioTitular'])) {
                $errors['envioTitular'] = true;
            }else{
                $variables['envioTitular'] = $_POST['envioTitular'];}
        }

        if(isset($_POST['envioCalle'])){
            // Validación de la calle: Caracteres validos, min 3 mas 30
            if (empty($_POST['envioCalle']) || !preg_match('/^[A-Za-z0-9.áéíóúÁÉÍÓÚ ]{3,30}$/', $_POST['envioCalle'])) {
                $errors['envioCalle'] = true;
            }else{
                $variables['envioCalle']  = $_POST['envioCalle'];}
        }

        if(isset($_POST['envioAltura'])){
            // Validación de la altura: 1-99999
            if (empty($_POST['envioAltura']) || !filter_var($_POST['envioAltura'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 99999]])) {
                $errors['envioAltura'] = true;
            }else{
                $variables['envioAltura']  = $_POST['envioAltura'];}
        }

        if(isset($_POST['envioPostal'])){
            // Validación del código postal: 4 digitos
            if (empty($_POST['envioPostal']) || !preg_match('/^\d{4}$/', $_POST['envioPostal'])) {
                $errors['envioPostal'] = true;
            }else{
                $variables['envioPostal']  = $_POST['envioPostal'];} 
        }

        if(isset($_POST['envioNota'])){
            // Validación de notas adicionales (CAMPO NO OBLIGATORIO)
            if (!empty($_POST['envioNota']) && strlen($_POST['envioNota']) > 200) {
                $errors['envioNota'] = true;
            }else{
                $variables['envioNota']  = $_POST['envioNota'];}
        }
    }

    if(isset($_POST['dni'])){
        // Validación del DNI
        if (empty($_POST['dni']) || !filter_var($_POST['dni'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 999999, "max_range" => 99999999]])) {
            $errors['dni'] = true;
        }else{
            $variables['dni']  = $_POST['dni'];}
    }

    $esPagoEfectivo = isset($_POST['pagoEfectivo']) && $_POST['pagoEfectivo'] === 'on';
    if ($esPagoEfectivo) {
        // El usuario seleccionó pagar en efectivo
        $variables['metodoPago'] = 'EFECTIVO';
    } else {
        // El usuario no seleccionó pago en efectivo, debe pagar con tarjeta
        if (isset($_POST['tarjeta'])){
             if(!empty($_POST['tarjeta']) && preg_match('/^\d{12}$/', $_POST['tarjeta'])) {
                // La tarjeta es válida
                $variables['tarjeta'] = $_POST['tarjeta'];
                $variables['metodoPago'] = 'TARJETA';
            } else {
                // Error: tarjeta no válida
                $errors['tarjeta'] = true;
            }
        }
    }
    
    if (empty($errors) && !empty($variables)) {      
        try {
            $tipo_pago = $variables['metodoPago'];
            $conexion = Conexion::getConn();
    
            if (!empty($productos_string)) {
                // Llamar al SP
                $sql_productos = "CALL procesar_compra(:id_persona, :tipo_pago, :precio_total, :productos, :id_usuario, @id_venta_cabecera)";
                $stmt_productos = $conexion->prepare($sql_productos);
                $stmt_productos->bindParam(':id_persona', $id_persona, PDO::PARAM_INT);
                $stmt_productos->bindParam(':tipo_pago', $tipo_pago, PDO::PARAM_STR);
                $stmt_productos->bindParam(':precio_total', $precio_total_productos, PDO::PARAM_STR);
                $stmt_productos->bindParam(':productos', $productos_string, PDO::PARAM_STR);
                $stmt_productos->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt_productos->execute();
    
                // Obtener el último ID de la venta_cabecera insertada (es el parámetro de salida)
                $sql_last_id = "SELECT @id_venta_cabecera AS id_venta_cabecera";
                $result = $conexion->query($sql_last_id)->fetch(PDO::FETCH_ASSOC);
                $id_venta_cabecera = $result['id_venta_cabecera'];
            }
    
            if (!empty($combos_string)) {
                // Llamar al procedimiento para combos
                $sql_combos = "CALL procesar_compra_combo(:id_persona, :tipo_pago, :precio_total, :combos, :id_usuario, @id_venta_cabecera)";
                $stmt_combos = $conexion->prepare($sql_combos);
                $stmt_combos->bindParam(':id_persona', $id_persona, PDO::PARAM_INT);
                $stmt_combos->bindParam(':tipo_pago', $tipo_pago, PDO::PARAM_STR);
                $stmt_combos->bindParam(':precio_total', $precio_total_combos, PDO::PARAM_STR);
                $stmt_combos->bindParam(':combos', $combos_string, PDO::PARAM_STR);
                $stmt_combos->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
                $stmt_combos->execute();
    
                // Obtener el último ID de la venta_cabecera insertada (es el parámetro de salida)
                $sql_last_id = "SELECT @id_venta_cabecera AS id_venta_cabecera";
                $result = $conexion->query($sql_last_id)->fetch(PDO::FETCH_ASSOC);
                $id_venta_cabecera = $result['id_venta_cabecera'];
            }
    
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    
        // INSERTAR DATOS DE ENVÍO EN LA TABLA 'ENVIOS'
        if (!empty($variables['envioTitular']) && !empty($variables['envioCalle']) && !empty($variables['envioAltura']) && !empty($variables['envioPostal']) && !empty($variables['dni']) && !$esRetiroLocal) {
            // Dividir la cadena en un array
            $nombreApellido = explode(' ', $variables['envioTitular']);
            $nombre = $nombreApellido[0];   
            $apellido = $nombreApellido[1];
            $variables['envioNota'] = !empty($variables['envioNota']) ? $variables['envioNota'] : 'Sin nota';
            $dni_str = (string)$variables['dni'];
            $id_venta_cabecera = (int)$id_venta_cabecera;
    
            try {
                $sql = "INSERT INTO envios (nombre, apellido, calle, altura, cod_postal, notas, dni, id_venta_cabecera) 
                        VALUES (:nombre, :apellido, :calle, :altura, :cod_postal, :notas, :dni, :id_venta_cabecera)";
    
                $stmt = $conexion->prepare($sql);
    
                $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
                $stmt->bindParam(':apellido', $apellido, PDO::PARAM_STR);
                $stmt->bindParam(':calle', $variables['envioCalle'], PDO::PARAM_STR);
                $stmt->bindParam(':altura', $variables['envioAltura'], PDO::PARAM_INT);
                $stmt->bindParam(':cod_postal', $variables['envioPostal'], PDO::PARAM_INT);
                $stmt->bindParam(':notas', $variables['envioNota'], PDO::PARAM_STR);
                $stmt->bindParam(':dni', $dni_str, PDO::PARAM_STR); // YA QUE ES NUMERICO EN PHP PERO EN LA BASE ES VARCHAR
                $stmt->bindParam(':id_venta_cabecera', $id_venta_cabecera, PDO::PARAM_INT);
    
                $stmt->execute();
            
            } catch (PDOException $e) {
                echo "Error al insertar los datos: " . $e->getMessage();
            }  
        }
        if($esRetiroLocal){
            $tipoEntrega = 'Retira por el local';
        } else {
            $tipoEntrega = 'Envío a domicilio';
        }
        date_default_timezone_set('America/Argentina/Buenos_Aires');
        // ALMACENO EN UNA VARIABLE DE SESION TODOS LOS DATOS DE LA COMPRA PARA MOSTRAR EN LA SIGUIENTE PANTALLA
        $_SESSION['compraRegistrada'] = [
            'id' => $id_venta_cabecera,
            'productos' => $carrito,
            //'productos' => $productos_string,
            //'combos' => $combos_string,
            'metodoPago' => $variables['metodoPago'],
            'tipoEntrega' => $tipoEntrega,
            'total' => $precio_total_productos + $precio_total_combos,
            'fecha' => date('d-m-Y H:i:s')
            
        ];

        //VACIAR EL CARRITO CUANDO SE HAYA EFECTUADO LA COMPRA. TAMBIEN HAY QUE HACERLO VIA JAVASCRIPT 
        $_SESSION['carrito'] = [];
    }
     else {
        foreach ($errors as $field => $error) {
            echo "<p>Error en {$field}: {$error}</p>";
        }
    }
        
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech - Finalizar Compra</title>

    <?php require(RUTA_PROYECTO . '/app/views/components/head.php') ?>
    
    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO  ?>/public/js/public/compra.js" defer></script>
    
</head>

<body>
    <?php require(RUTA_PROYECTO . '/app/views/components/header.php'); ?>

    <!-- Comienzo del CONTAINER -->
    <div class="container mt-5 mb-5 border">
        <h1 class="text-center">Finalizar la compra</h1>
        <form id="formCompra" action="compra.php" method="POST">
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
                    <input type="text" value="<?php if (isset($variables['envioTitular'])){echo $_POST['envioTitular'];}?>"
                        class="form-control <?php if (isset($errors['envioTitular'])) {echo 'is-invalid';} else{echo '';}; ?>"
                        name="envioTitular" id="envioTitular" placeholder="Máximo Cozzetti"
                        pattern="^[A-Za-záéíóúÁÉÍÓÚ ]+$" minlength="3" maxlength="50">
                    <div id="errorNombre" class="invalid-feedback">El nombre debe: Tener 2 palabras, contener entre 3 y 50 caracteres y ser solamente letras.</div>
                </div>
                <div class="mb-3">
                    <label for="envioCalle" class="col-form-label">Ingrese nombre de calle:</label>
                    <input type="text" value="<?php if (isset($variables['envioCalle'])){echo $_POST['envioCalle'];}?>"
                    class="form-control <?php if (isset($errors['envioCalle'])) {echo 'is-invalid';} else{echo '';}; ?>" 
                    name="envioCalle" id="envioCalle" placeholder="Manuel Belgrano" pattern="^[A-Za-z0-9.áéíóúÁÉÍÓÚ ]+$" minlength="3" maxlength="30">
                    <div id="errorCalle" class="invalid-feedback">La calle debe: Contener entre 3 y 30 caracteres.
                        Pueden ser: Letras mayus/minus, números, puntos, tildes y espacios</div>
                </div>
                <div class="mb-3">
                    <label for="envioAltura" class="col-form-label">Ingrese altura:</label>
                    <input type="number" value="<?php if (isset($variables['envioAltura'])){echo $_POST['envioAltura'];}?>"
                    class="form-control <?php if (isset($errors['envioAltura'])) {echo 'is-invalid';} else{echo '';}; ?>" 
                    name="envioAltura" id="envioAltura" placeholder="1027" min="1" max="99999" >
                    <div id="errorAltura" class="invalid-feedback">La altura debe: Ser numérica y estar comprendida entre N° 1 - 99999</div>
                </div>
                <div class="mb-3">
                    <label for="envioPostal" class="col-form-label">Código Postal:</label>
                    <input type="number" value="<?php if (isset($variables['envioPostal'])){echo $_POST['envioPostal'];}?>"
                    class="form-control <?php if (isset($errors['envioPostal'])) {echo 'is-invalid';} else{echo '';}; ?>" 
                    name="envioPostal" id="envioPostal" placeholder="1838" min="1000" max="9999" >
                    <div id="errorPostal" class="invalid-feedback">El CP debe: Tener 4 dígitos</div>
                </div>
                <div class="mb-3">
                    <label for="envioNota" class="col-form-label">Notas adicionales:</label>
                    <textarea class="form-control <?php if (isset($errors['envioNota'])) {echo 'is-invalid';} else{echo '';}; ?>" 
                    name="envioNota" id="envioNota" placeholder="Agregue, de ser necesario, número de departamento o instrucciones para el repartidor." maxlength="200"
                    ></textarea>
                    <div id="errorNota" class="invalid-feedback">La nota debe: Tener como máximo 200 caracteres</div>
                </div>
            </div>
            <h6 class="modal-title fs-5"><i class="bi bi-credit-card-2-back"></i> Concretar el pago</h6>
            <div class="mb-3">
                <label for="dni" class="col-form-label">Ingrese DNI:</label>
                <input type="number" value="<?php if (isset($variables['dni'])){echo $_POST['dni'];}?>"
                class="form-control <?php if (isset($errors['dni'])){echo 'is-invalid';} else{echo '';}; ?>" 
                name="dni" id="dni" placeholder="Solo números, sin puntos." min="999999" max="99999999" >
                <div id="errorDni" class="invalid-feedback">El DNI debe: Ser numérico sin puntos y estar comprendido
                    entre 999999 y 99999999</div>
                <!-- Nueva opción para pago en efectivo -->
                <div class="form-check mt-3">
                    <input type="checkbox" class="form-check-input" id="pagoEfectivo" name="pagoEfectivo" 
                    <?php if (!empty($_POST['pagoEfectivo'])) echo 'checked'; ?>>
                    <label class="form-check-label" for="pagoEfectivo">QUIERO PAGAR EN EFECTIVO</label>
                </div>
                <div id="tarjetaContainer">
                    <label for="tarjeta" class="col-form-label">PAGAR CON TARJETA:<br>Ingrese el número de su tarjeta registrada en MercadoPago:</label>
                    <input type="number" value="<?php if (isset($variables['tarjeta'])){echo $_POST['tarjeta'];}?>" 
                    class="form-control <?php if (isset($errors['tarjeta'])){echo 'is-invalid';} else{echo '';}; ?>" name="tarjeta" id="tarjeta"
                    placeholder="1234 5678 1234 (12 digitos)" min="100010001000" max="999999999999" >
                    <div id="errorTarjeta" class="invalid-feedback">El número de tarjeta debe: Contener 12 dígitos</div>
                </div>
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
                    <h4 class="modal-title">Compra registrada!</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <!-- Body -->
                <div class="modal-body" id="modalBody">
                    Gracias por comprar en HardTech.
                </div>

                <!-- Footer -->
                <div class="modal-footer">
                    <div>Redirigiendo a su detalle de compra...</div>
                    <button type="button" id="botonModal" class="btn btn-warning"
                        data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
    <!--Fin Modal "Compra exitosa"-->

    <?php require(RUTA_PROYECTO . '/app/views/components/footer.php'); ?>
</body>

</html>