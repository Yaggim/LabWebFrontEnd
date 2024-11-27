<?php 
require_once __DIR__ . '/../../../config/config.php'; 
require_once __DIR__ . '/../../../includes/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    require(RUTA_PROYECTO . '/app/views/public/error.php');
    exit();
}

$productos = $_SESSION['compraRegistrada']['productos'];
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>HardTech - Tu Compra</title>
    <?php require(RUTA_PROYECTO . '/app/views/components/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/<?php echo CARPETA_PROYECTO ?>/public/css/public/producto.css">
</head>

<body>
    <?php require(RUTA_PROYECTO . '/app/views/components/header.php'); ?>
    <div class="row text-light p-2 mx-2">
        <h1>Compra Registrada: </h1>
    </div>
    <div class="container p-2 my-2 rounded">
        <div class="row">
            <div class="col-lg-12 p-3 text-light rounded">
                <h3>Código de identificación de compra: #<?php echo $_SESSION['compraRegistrada']['id']; ?></h3>
                <h3>Tus productos:</h3>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Cantidad</th>
                                <th>Producto</th>
                                <th>Precio Unitario</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($productos as $producto) {
                                ?>
                                <tr>
                                    <td><img src="public/<?php echo $producto['imagen']; ?>" width="35"></td>
                                    <td><?php echo $producto['cantidad']; ?></td>
                                    <td><?php echo $producto['marca'] . ' - ' . $producto['modelo']; ?></td>
                                    <td>$<?php echo number_format($producto['precioEnPesos'], 2, ',', '.'); ?></td>
                                </tr>
                            <?php 
                            }?>
                        </tbody>
                    </table>
                    </div>
                
            </div>

        </div>
        <div class="row">
            <div class="col-lg-12 p-3 rounded">
                <h4>Método de pago:</h4> <h5 class="text-light"><?php echo $_SESSION['compraRegistrada']['metodoPago']; ?></h5>
                <h4>Tipo de entrega:</h4> <h5 class="text-light"><?php echo $_SESSION['compraRegistrada']['tipoEntrega']; ?></h5>
                <h4>Fecha:</h4> <h5 class="text-light"><?php echo $_SESSION['compraRegistrada']['fecha']; ?></h5>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 p-3  text-end text-light rounded">
                <h2>Monto total: $ <?php echo number_format($_SESSION['compraRegistrada']['total'], 2,',', '.'); ?></h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 p-3 text-end rounded">
                <form action="<?php echo '/' . CARPETA_PROYECTO . '/home'; ?>" method="GET">
                    <button type="submit" class="btn btn-success btn-lg">VOLVER AL HOME <i
                            class="bi bi-house-up"></i></button>
                </form>
            </div>
        </div>
    </div>

    <?php require(RUTA_PROYECTO . '/app/views/components/footer.php'); ?>
</body>

</html>