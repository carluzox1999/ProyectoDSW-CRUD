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
        <div class="span10 offset1">
            <div class="card">
                <div class="card-header" id="contenedorPadre">
                    <h3 class="well">Información del Producto</h3>
                    <div class="idData" id="contenedorHijo">
                        <label class="carousel-inner">
                            <?php echo $data->id; ?>
                    </div>

                    </label>
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
</body>

</html>
<?php
}
?>