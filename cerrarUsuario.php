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
    
        function cerrarSesion(){
            session_destroy();
            header("location: login.php");

        }

        if (isset($_POST['cerrarSesion'])) {
            cerrarSesion();
        }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/style2.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <title>Cerrar Sesión</title>
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

    <div class="container">
        <div class="d-flex justify-content-between">
            <div class='p2'>
                <a href="listado.php"><i class="fa-solid fa-house"
                        style="margin-top: 20px; margin-left: 20px; margin-right: 20px; width: 10px;"></i></a>
            </div>
            <div class='p2'>
                <h1>Cerrar Sesión</h1>
            </div>

            <?php
                $urlEditarPerfil =  "<a href='perfil.php' class='btn btn-warning' type='button'>Perfil</a>";
                $urlCerrarSesion =  "<a href='cerrarUsuario.php' class='btn btn-danger' type='button'>Cerrar Sesión</a>";
            ?>

            <div class="p-2">
                <?php echo "<h4 class='d-flex align-items-center'>" . $_SESSION['nombrecompleto'] . "</h4>"; ?>
            </div>

            <div class="p-2">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle dropbtn">Menú</button>
                    <div class="dropdown-content">
                        <?php echo "$urlEditarPerfil"; ?>
                        <?php echo "$urlCerrarSesion"; ?>
                    </div>
                </div>
            </div>
        </div>

            <form class="form-horizontal" method="post" action="cerrarUsuario.php" id="formularioBorrar" autocomplete="off">
                <div class="alert alert-danger">¿Cerrar Sesión?</div>
                <div class="form-actions">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary" name="cerrarSesion">Cerrar</button>
                        <?php 

                        ?>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="listado.php" class="btn btn-secondary">Volver</a>
                    </div>
                </div>
            </form>
    </div>
    

    <script src="./js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/ad7c1d9068.js" crossorigin="anonymous"></script>

</body>

</html>
<?php   
    }
?>