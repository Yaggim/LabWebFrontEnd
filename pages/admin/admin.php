<?php
    require_once(__DIR__ . "/../../config/config.php"); 
    //session_start();
    if(!isset($_SESSION['usuario'])){
        header('Location: home');
    }

    if (!Permisos::tienePermiso("Crear producto", $_SESSION['usuario']['id']) 
        ||!Permisos::tienePermiso("Crear producto", $_SESSION['usuario']['id'])
        ||!Permisos::tienePermiso("Modificar stock", $_SESSION['usuario']['id'])
        ||!Permisos::tienePermiso("Crear combo", $_SESSION['usuario']['id'])
        ||!Permisos::tienePermiso("Crear oferta semanal", $_SESSION['usuario']['id'])
        ||!Permisos::tienePermiso("Gestionar ventas", $_SESSION['usuario']['id'])) {
        header("Location: home");
        die;
    }
?>


<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin</title>

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous" defer></script>
    </head>

    <body>
       
    <?php require(RUTA_PROYECTO.'/components/adminHeader.php'); ?>
    
        
    </body>

</html>