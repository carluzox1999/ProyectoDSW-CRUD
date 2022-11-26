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


    $id = null;

    if (!empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    } else
        header("location: listado.php");

    if (null == $id) {
        $nuevaURL = "listado.php";
        header('Location: ' . $nuevaURL);
    } else {
        $pdo = Conexion::conectar();
        $sql = "SELECT * FROM productos where id = ?;";
        $conexion = $pdo->prepare($sql);
        $conexion->execute([$id]);
        $data = $conexion->fetch(PDO::FETCH_OBJ);
        Conexion::desconectar();
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
    <title>Info producto</title>
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
                <h1>Información</h1>
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

        <div class="span10 offset1">
            <div class="card">
                <div class="card-header" id="contenedorPadre">
                    <h3 class="well">Producto</h3>
                </div>

                <div class="container">
                    <div class="form-horizontal">
                        <div class="control-group">
                            <label class="control-label">Nombre</label>
                            <div class="controls form-control">
                                <label class="carousel-inner">
                                    <?php echo $data->nombre; ?>
                                </label>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Nombre corto</label>
                            <div class="controls form-control disabled">
                                <label class="carousel-inner">
                                    <?php echo $data->nombre_corto; ?>
                                </label>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">PVP</label>
                            <div class="controls form-control disabled">
                                <label class="carousel-inner">
                                    <?php echo $data->pvp; ?>
                                </label>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Familia</label>
                            <div class="controls form-control disabled">
                                <label class="carousel-inner">
                                    <?php echo $data->familia; ?>
                                </label>
                            </div>
                        </div>

                        <div class="control-group">
                            <label class="control-label">Descripción</label>
                            <div class="controls form-check disabled">
                                <label class="carousel-inner">
                                    <?php echo $data->descripcion; ?>
                                </label>
                            </div>
                        </div>
                        <br />
                        <div class="d-grid gap-2">
                            <a href="listado.php" class="btn btn-secondary">Volver</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="./js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/ad7c1d9068.js" crossorigin="anonymous"></script>

</body>

</html>
<?php
}
?>