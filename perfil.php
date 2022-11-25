<?php
session_start();
require "conexion.php";
$conexion = Conexion::conectar();
    if(!isset($_SESSION['usuario'])){
        header("location: login.php");
    } elseif (isset($_SESSION['usuario'])){

        if (!empty($_POST)) {

            $usuario = $_POST['usuario'];
            $nombre_completo = $_POST['nombre_completo'];
            $correo = $_POST['correo'];
            $colorfondo = $_POST['colorfondo'];
            $tipoletra = $_POST['tipoletra'];

            try {
                $pdoPerfil = Conexion::conectar();
                $pdoPerfil->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdoPerfil->beginTransaction();
                $sqlActualizarPerfil = "UPDATE usuarios  SET nombrecompleto = ?, correo = ?, colorfondo = ?, tipoletra = ? WHERE usuario = '".$_SESSION['usuario']."';";
                $conexion = $pdoPerfil->prepare($sqlActualizarPerfil);
                $conexion->execute([$nombre_completo, $correo, $colorfondo, $tipoletra]);
                $pdoPerfil->commit();
                Conexion::desconectar();
                $_SESSION['nombrecompleto'] = $nombre_completo;
                $_SESSION['correo'] = $correo;
                $_SESSION['colorfondo'] = $colorfondo;
                $_SESSION['tipoletra'] = $tipoletra;

                header("Location: listado.php");

            } catch (Exception $e) {
                $pdoPerfil->rollback();
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
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/style2.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <title>Perfil</title>
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

    <h1>Actualizar Producto</h1>

    <div class="grid estilodiv">
        <form class="row g-3" method="post" action="perfil.php" id="formularioActualizar" autocomplete="off">
            <div class="col-md-6">
                <label class="form-label">Usuario</label>
                <input type="text" class="form-control" name="usuario" placeholder="Usuario" value="<?php echo $_SESSION['usuario']; ?>" readonly disabled>

            </div>
            <div class="col-md-6">
                <label class="form-label">Nombre Completo</label>
                <input type="text" class="form-control" name="nombre_completo" placeholder="Nombre Completo" value="<?php echo $_SESSION['nombrecompleto']; ?>">

            </div>
            <div class="col-md-6">
                <label class="form-label">Correo</label>
                <input type="email" class="form-control" name="correo" placeholder="Correo" value="<?php echo $_SESSION['correo']; ?>">

            </div>
            <div class="col-md-6">
                <label class="form-label">Color Fondo</label>
                <input type="text" class="form-control" name="colorfondo" placeholder="Color" value="<?php echo $_SESSION['colorfondo']; ?>">
            </div>
            <div class="col-md-6">
            <label class="form-label">Tipo Letra</label>
                <input type="text" class="form-control" name="tipoletra" placeholder="Fuente" value="<?php echo $_SESSION['tipoletra']; ?>">
            </div>
            
            <input type='hidden' name='usuario' value='<?= $usuario ?>'>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
            <div class="d-grid gap-2">
                <a href="listado.php" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>

    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/script.js"></script>
    <?php
    Conexion::desconectar();
    ?>
</body>

</html>
<?php
    }
?>