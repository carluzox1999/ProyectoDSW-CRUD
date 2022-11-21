<?php 
    session_start();
    require "conexion.php";
    $conexion = Conexion::conectar();
    if(!isset($_SESSION['usuario'])){
        header("location: login.php");
    } elseif (isset($_SESSION['usuario'])){
        
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Proyecto DSW</title>
</head>

<body>
    <div class='float-lg-start'>
        <a href="listado.php"><i class="fa-solid fa-house"
                style="margin-top: 20px; margin-left: 20px; margin-right: 20px; width: 10px;"></i></a>
    </div>

    <h1>Gestión de productos</h1>

    <div class="d-grid gap-2">
        <a href="cerrarSesion.php" class="btn btn-success btn-block boton">Cerrar Sesión</a>
        <a href="crear.php" class="btn btn-success btn-block boton">Crear producto</a>
    </div>
    <?php
        include "paginacion.php";
    ?>

    <script src="./js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/ad7c1d9068.js" crossorigin="anonymous"></script>
</body>
</html>
<?php
    }
?>