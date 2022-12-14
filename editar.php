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



    if (!empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    } else
        header("Location: listado.php");

    if (!empty($_POST)) {

        $id = $_POST['id'];
        $nombre = $_POST['nombre'];
        $nombre_corto = $_POST['nombre_corto'];
        $pvp = $_POST['pvp'];
        $select = $_POST['familia'];
        $descripcion = $_POST['descripcion'];

        try {
            $pdo = Conexion::conectar();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $sql = "UPDATE productos  SET nombre = ?, nombre_corto = ?, descripcion = ?, pvp = ?, familia = ? WHERE id = ?;";
            $pdo->commit();
            $conexion = $pdo->prepare($sql);
            $conexion->execute([$nombre, $nombre_corto, $descripcion, $pvp, $select, $id]);
            Conexion::desconectar();
            $nuevaURL = "listado.php";
            header('Location: ' . $nuevaURL);
        } catch (Exception $e) {
            $pdo->rollback();
            echo "Lista no completada: " . $e->getMessage();
        }
    }

    try {
        $pdo = Conexion::conectar();
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();
        $sql = "SELECT * FROM productos where id = ?;";
        $pdo->commit();
        $conexion = $pdo->prepare($sql);
        $conexion->execute([$id]);
        $data = $conexion->fetch(PDO::FETCH_OBJ);
        
        $nombre = $data->nombre;
        $nombre_corto = $data->nombre_corto;
        $pvp = $data->pvp;
        $select = $data->familia;
        $descripcion = $data->descripcion;
    } catch (Exception $e) {
        $pdo->rollback();
        echo "Lista no completada: " . $e->getMessage();
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
    <title>Editar producto</title>
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
                <h1>Actualizar Producto</h1>
            </div>

            <?php
                $urlEditarPerfil =  "<a href='perfil.php' class='btn btn-warning' type='button'>Perfil</a>";
                $urlCerrarSesion =  "<a href='cerrarUsuario.php' class='btn btn-danger' type='button'>Cerrar Sesi??n</a>";
            ?>

            <div class="p-2">
                <?php echo "<h4 class='d-flex align-items-center'>" . $_SESSION['nombrecompleto'] . "</h4>"; ?>
            </div>

            <div class="p-2">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle dropbtn">Men??</button>
                    <div class="dropdown-content">
                        <?php echo "$urlEditarPerfil"; ?>
                        <?php echo "$urlCerrarSesion"; ?>
                    </div>
                </div>
            </div>
        </div>

            <form class="row g-3" method="post" action="editar.php" id="formularioActualizar" autocomplete="off">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" name="nombre" placeholder="Nombre" value="<?php echo !empty($nombre) ? $nombre : ''; ?>">

                </div>
                <div class="col-md-6">
                    <label class="form-label">Nombre Corto</label>
                    <input type="text" class="form-control" name="nombre_corto" placeholder="Nombre Corto" value="<?php echo !empty($nombre_corto) ? $nombre_corto : ''; ?>">

                </div>
                <div class="col-md-6">
                    <label class="form-label">Precio (???)</label>
                    <input type="number" class="form-control" name="pvp" placeholder="Precio" value="<?php echo !empty($pvp) ? $pvp : ''; ?>">

                </div>
                <div class="col-md-6">
                    <label class="form-label">Familia</label>
                    <select class="form-control" name="familia">
                        <?php

                        $pdoSelect = Conexion::conectar();
                        $sqlSelect = $pdoSelect->query("SELECT cod, nombre FROM familias");
                        $sqlSelect->execute();

                        $sqlFamilia = $pdoSelect->prepare("SELECT * FROM familias WHERE cod=?;");
                        $sqlFamilia->execute([$_GET['familia']]);
                        $dataF = $sqlFamilia->fetch(PDO::FETCH_OBJ);
                        echo '<option selected value="' . $dataF->cod . '">' . $dataF->nombre . '</option>';

                        while ($data = $sqlSelect->fetch(PDO::FETCH_OBJ)) {
                            if ($data->cod != $dataF->cod)
                                echo '<option value="' . $data->cod . '">' . $data->nombre . '</option>';
                        }
                        Conexion::desconectar();
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descripci??n</label>
                    <textarea class="form-control" name="descripcion" cols="10" rows="5" placeholder="Ingrese una descripci??n..."><?php echo !empty($descripcion) ? $descripcion : ''; ?></textarea>
                </div>

                <input type='hidden' name='id' value='<?= $id ?>'>
                
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </div>
                <div class="d-grid gap-2">
                    <a href="listado.php" class="btn btn-secondary">Volver</a>
                </div>
            </form>
    </div>
    
    <script src="./js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/ad7c1d9068.js" crossorigin="anonymous"></script>

    <?php
    Conexion::desconectar();
    ?>
</body>

</html>
<?php
}
?>