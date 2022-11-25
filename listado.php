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
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
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

        h4{
            text-align: center;
        }
    </style>
    
    <div class="container">
        <div class="d-flex justify-content-between">
            <div class='p2'>
                <a href="listado.php"><i class="fa-solid fa-house"
                        style="margin-top: 20px; margin-left: 20px; margin-right: 20px; width: 10px;"></i></a>
            </div>
            <div class='p2'>
                <h1>Gestión de productos</h1>
            </div>

            <?php
                    
                    $urlEditarPerfil =  "<a href='perfil.php' class='btn btn-warning' type='button'>Perfil</a>";
                    $urlCerrarSesion =  "<a href='cerrarUsuario.php' class='btn btn-danger' type='button'>Cerrar Sesión</a>";
                ?>

            <div class="p-2">
                <?php  echo "<h4 class='d-flex align-items-center'>".$_SESSION['nombrecompleto']."</h4>"; ?> 
            </div>

            <div class="p-2">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle dropbtn">Menú</button>
                    <div class="dropdown-content">
                        <?php echo "$urlEditarPerfil"; ?>
                        <?php  echo "$urlCerrarSesion"; ?>
                    </div>
                </div>
                
            </div>
        </div>
        

        <div class="d-grid gap-2">
            <a href="crear.php" class="btn btn-success btn-block boton">Crear producto</a>
        </div>
        <?php
            include "paginacion.php";
        ?>
    </div>
    

    <script src="./js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/ad7c1d9068.js" crossorigin="anonymous"></script>
</body>
</html>
<?php
    }
?>