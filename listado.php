<?php 
    session_start();
    require "conexion.php";
    $conexion = Conexion::conectar();

    if(!isset($_SESSION['usuario'])){
        header("location: login.php");
    } elseif (isset($_SESSION['usuario'])){
        
        $usuarioActual = $_SESSION['usuario'];

        $conexion = Conexion::conectar();
        
        $especificacionesUsuarioSQL = $conexion->query("SELECT usuario, colorfondo, tipoletra 
        FROM usuarios WHERE usuario = '$usuarioActual';");

        $especificacionesUsuarioSQL->execute();
        $especificaciones = $especificacionesUsuarioSQL->fetch(PDO::FETCH_ASSOC);

        $_SESSION['colorfondo'] = $especificaciones["colorfondo"];
        $_SESSION["tipoletra"] = $especificaciones["tipoletra"];
    
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
    <style>
    html, body {
        background-color: <?php echo "#".$_SESSION['colorfondo'] ?>;
        font-family: <?php echo $_SESSION['tipoletra'] ?>;
        padding:0;
        margin:0;
        height:100%;
    }

    body::-webkit-scrollbar {
        display: none;
    }

    @media (max-width: 800px) {
        .codigo {
        display: none;
        }
    }

    @media (max-width: 300px) {
        .detalle {
            display: none;
        }
        
        .codigo {
        display: none;
        }

    }

    tr th{
        vertical-align: middle;
        border-style: inset;
        border-width: 5px;
    }

    tr td{
        text-align: center;
        vertical-align: middle;
    }
    </style>
    <div class='float-lg-start'>
        <a href="listado.php"><i class="fa-solid fa-house"
                style="margin-top: 20px; margin-left: 20px; margin-right: 20px; width: 10px;"></i></a>
    </div>

    <h1>Gestión de productos</h1>

    <div class="d-grid gap-2">
        <a href="cerrarSesion.php" class="btn btn-secondary btn-block boton">Cerrar Sesión</a>
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