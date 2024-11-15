<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/conexion.php';

if(!isset($_SESSION['usuario'])){
    header('Location: home');
}

// user: comprador / admin
// pass: 123456*a

// SI LLEGA VIA URL, REDIRIGIRLO

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    require(RUTA_PROYECTO . '/views/error.php');
    exit();
}



// IMPORTANTE: LE SACO LOS REQUIRED DE HTML PARA PROBAR LAS VALIDACIONES DE PHP

$errors = [];
$variables = []; 


// VALIDAR FORMULARIO SI LLEGA POR POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    /*echo "<pre>";
    print_r($_POST);
    echo "</pre>";*/
    
    if (isset($_SESSION['carrito'])) {
        $carrito = $_SESSION['carrito']['carrito']; 
        $productos_string = ""; 
        $precio_total = 0.00;


        foreach ($carrito as $producto) {
        $productos_string .= "(" . $producto['id'] . ", " . $producto['cantidad'] . ", " . number_format((float)$producto['precioEnPesos'], 2, '.', '') . "), ";
        $precio_total += $producto['cantidad'] * floatval($producto['precioEnPesos']);
        }

        $productos_string = rtrim($productos_string, ", ");

        $id_persona = intval($_SESSION['usuario']['id_persona']);

        


    /*  COMIENZO DE TODAS LAS VALIDACIONES DEL FORMULARIO POR PHP */
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
    if(isset($_POST['dni'])){
        // Validación del DNI
        if (empty($_POST['dni']) || !filter_var($_POST['dni'], FILTER_VALIDATE_INT, ["options" => ["min_range" => 999999, "max_range" => 99999999]])) {
            $errors['dni'] = true;
        }else{
            $variables['dni']  = $_POST['dni'];}
    }
    if(isset($_POST['tarjeta'])){
        // Validación de la tarjeta de crédito (si no se elige pago en efectivo)
        if (empty($_POST['pagoEfectivo']) && (empty($_POST['tarjeta']) || !preg_match('/^\d{12}$/', $_POST['tarjeta']))) {
            $errors['tarjeta'] = true;
        }elseif(!empty($_POST['pagoEfectivo'])){
            //$variables['efectivo'] = 'SI';
            $variables['metodoPago'] = 'EFECTIVO';
        }
        else{
            $variables['tarjeta']  = $_POST['tarjeta'];}
            $variables['metodoPago'] = 'TARJETA';
    }
    /*  FIN DE TODAS LAS VALIDACIONES DE FORMULARIO */

    // Si no hay errores, se procesa la compra
    if (empty($errors) && !empty($variables)) {
        
        // Procesar la compra: llamar al stored procedure con bindparam y demás puedo re-dirigir a generar_venta.php
        
        try {
            $tipo_pago = $variables['metodoPago'];

            
            $sql = "CALL procesar_compra(:id_persona, :tipo_pago, :precio_total, :productos)";

            
            $parametros = "CALL procesar_compra(" . $id_persona . ", '" . $tipo_pago . "', " . number_format($precio_total, 2, '.', '') . ", '" . $productos_string . "')";
    

            $conexion = Conexion::getConn();

            $stmt = $conexion->prepare($sql);

            $stmt->bindParam(':id_persona', $id_persona, PDO::PARAM_INT);
            $stmt->bindParam(':tipo_pago', $tipo_pago, PDO::PARAM_STR);
            $stmt->bindParam(':precio_total', $precio_total, PDO::PARAM_STR);
            $stmt->bindParam(':productos', $productos_string, PDO::PARAM_STR);

            $stmt->execute();

        } catch (PDOException $e) {

            echo "Error: " . $e->getMessage();
        }

    } else {
        foreach ($errors as $field => $error) {
            echo "<p>Error en {$field}: {$error}</p>";
        }
    }
        //$id_venta_cabecera = $conexion->lastInsertId();
        /*
        // INSERTAR DATOS DE ENVÍO EN LA TABLA 'ENVIOS'
        if (!isset($_POST['retiroLocal'])) {
            // Dividir la cadena en un array
            $nombreApellido = explode(' ', $variables['envioTitular']);
            $nombre = $nombreApellido[0];   
            $apellido = $nombreApellido[1];

        try {
            
            $sql = "INSERT INTO envios (nombre, apellido, calle, altura, cod_postal, notas, dni, id_venta_cabecera) 
                    VALUES (:nombre, :apellido, :calle, :altura, :cod_postal, :notas, :dni, :id_venta_cabecera)";

            $stmt2 = $conn->prepare($sql);

            $stmt2->bindParam(':nombre', $nombre, PDO::PARAM_STR);
            $stmt2->bindParam(':apellido', $apellido, PDO::PARAM_STR);
            $stmt2->bindParam(':calle', $variables['envioCalle'], PDO::PARAM_STR);
            $stmt2->bindParam(':altura', $variables['envioAltura'], PDO::PARAM_INT);
            $stmt2->bindParam(':cod_postal', $variables['envioPostal'], PDO::PARAM_INT);
            $stmt2->bindParam(':notas', $variables['envioNota'], PDO::PARAM_STR);
            $stmt2->bindParam(':dni', (string)$variables['dni'], PDO::PARAM_STR); // YA QUE ES NUMERICO EN PHP PERO EN LA BASE ES VARCHAR
            $stmt2->bindParam(':id_venta_cabecera', (int)$id_venta_cabecera, PDO::PARAM_INT);

            $stmt2->execute();
        
            echo "Los datos fueron insertados correctamente en la base de datos.";
            


        } catch (PDOException $e) {
            echo "Error al insertar los datos: " . $e->getMessage();
        }


        }

        foreach ($variables as $campo => $valor) {
            echo "<p>Variable {$campo}: {$valor}</p>";
        }
        
    } else {
        // Mostrar errores PROVISORIAMENTE luego sacar
        foreach ($errors as $field => $error) {
            echo "<p>Error en {$field}: {$error}</p>";
        }
             */
    }
       
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech - Finalizar Compra</title>

    <?php require(RUTA_PROYECTO . '/components/head.php') ?>
    
    <script type="text/javascript" src="/<?php echo CARPETA_PROYECTO  ?>/js/compra.js" defer></script>
    
</head>

<body>
    <?php require(RUTA_PROYECTO . '/components/header.php'); ?>

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

    <?php require(RUTA_PROYECTO . '/components/footer.php'); ?>
</body>

</html>