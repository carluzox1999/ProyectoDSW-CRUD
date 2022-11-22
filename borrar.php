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


    $id = 0;

    if (!empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    } else
        header("Location: listado.php");

    if (!empty($_POST)) {
        $id = $_POST['id'];

        try {
            $pdo = Conexion::conectar();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $sql = "DELETE FROM productos where id = ?;";
            $pdo->commit();
            $conexion = $pdo->prepare($sql);
            $conexion->execute([$id]);
            Conexion::desconectar();
            $nuevaURL = "listado.php";
            header('Location: ' . $nuevaURL);
        } catch (Exception $e) {
            $pdo->rollback();
            echo "Lista no completada: " . $e->getMessage();
        }
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
    <title>Borrar producto</title>
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

    <h1>Borrar Producto</h1>

    <div class="grid estilodiv">
        <form class="form-horizontal" method="post" action="borrar.php" id="formularioBorrar" autocomplete="off">
            <input type="hidden" name="id" value="<?php echo $id; ?>" />
            <div class="alert alert-danger">¿Eliminar Producto?</div>
            <div class="form-actions">
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Borrar</button>
                </div>
                <div class="d-grid gap-2">
                    <a href="listado.php" class="btn btn-secondary">No</a>
                </div>
            </div>
        </form>
    </div>

    <script src="./js/bootstrap.min.js"></script>
</body>

</html>
<?php
}
?>