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


// Procesamiento de validaciones
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombreError = null;
    $nCortoError = null;
    $precioError = null;
    $selectError = null;
    $descripcionError = null;

    if (!empty($_POST)) {
        $validacion = true;
        $nuevoProducto = false;
        if (!empty($_POST["nombre"])) {
            $inputNombre = $_POST['nombre'];
        } else {
            $nombreError = 'Nombre erroneo!';
            $validacion = false;
        }

        if (!empty($_POST["nombre_corto"])) {
            $inputNombreCorto = $_POST['nombre_corto'];
        } else {
            $nCortoError = 'Nombre corto erroneo!';
            $validacion = false;
        }

        if (!empty($_POST["pvp"])) {
            $inputPrecio = $_POST['pvp'];
        } else {
            $precioError = 'Precio erroneo!';
            $validacion = false;
        }

        if (!empty($_POST["familia"])) {
            $select = $_POST['familia'];
        } else {
            $selectError = 'Elija opción!';
            $validacion = false;
        }

        if (!empty($_POST["descripcion"])) {
            $txtDescripcion = $_POST['descripcion'];
        } else {
            $descripcionError = 'Ponga un texto!';
            $validacion = false;
        }

        if ($validacion) {
            try {
                $pdo = Conexion::conectar();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "INSERT INTO productos (nombre, nombre_corto, descripcion, pvp, familia) VALUES(?,?,?,?,?);";
                $pdo->commit();
                $conexion = $pdo->prepare($sql);
                $conexion->execute([$inputNombre, $inputNombreCorto, $txtDescripcion, $inputPrecio, $select]);
                Conexion::desconectar();
                $nuevaURL = "listado.php";
                header('Location: ' . $nuevaURL);
            } catch (Exception $e) {
                $pdo->rollback();
                echo "Lista no completada: " . $e->getMessage();
            }
        }
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
    <title>Crear producto</title>
</head>

<body>
    <style>
        html,
        body {
            background-color: <?php echo "#".$_SESSION['colorfondo'] ?>;
            font-family: <?php echo $_SESSION['tipoletra'] ?>;
            padding: 0;
            margin: 0;
            height: 100%;
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

        tr th {
            vertical-align: middle;
            border-style: inset;
            border-width: 5px;
        }

        tr td {
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
                <h1>Crear Producto</h1>
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

        <form class="row g-3" method="post" action="crear.php" id="formularioCrear" autocomplete="off">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" placeholder="Nombre"
                    value="<?php echo !empty($inputNombre) ? $inputNombre : ''; ?>">
                <?php if (!empty($nombreError)) : ?>
                <span class="text-danger"><?php echo $nombreError; ?></span>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nombre Corto</label>
                <input type="text" class="form-control" name="nombre_corto" placeholder="Nombre Corto"
                    value="<?php echo !empty($inputNombreCorto) ? $inputNombreCorto : ''; ?>">
                <?php if (!empty($nCortoError)) : ?>
                <span class="text-danger"><?php echo $nCortoError; ?></span>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Precio (€)</label>
                <input type="number" class="form-control" name="pvp" placeholder="Precio"
                    value="<?php echo !empty($inputPrecio) ? $inputPrecio : ''; ?>">
                <?php if (!empty($precioError)) : ?>
                <span class="text-danger"><?php echo $precioError; ?></span>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Familia</label>
                <select class="form-control" name="familia">
                    <option value="<?php echo !empty($select) ? $select : ''; ?>">Seleccione Opcion</option>
                    <?php
                            $pdoSelect = Conexion::conectar();
                            $sqlSelect = $pdoSelect->query("SELECT cod, nombre FROM familias");
                            $sqlSelect->execute();
                            while ($data = $sqlSelect->fetch(PDO::FETCH_OBJ)) {
                                echo '<option value="' . $data->cod . '">' . $data->nombre . '</option>';
                            }
                            ?>
                    <span class="text-danger"><?php echo $selectError; ?></span>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" cols="10" rows="5"
                    placeholder="Ingrese una descripción..."
                    value="<?php echo !empty($txtDescripcion) ? $txtDescripcion : ''; ?>"></textarea>
                <?php if (!empty($descripcionError)) : ?>
                <span class="text-danger"><?php echo $descripcionError; ?></span>
                <?php endif; ?>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>

            <div class="d-grid gap-2">
                <button type="reset" class="btn btn-success">Limpiar</button>
            </div>

            <div class="d-grid gap-2">
                <a href="listado.php" class="btn btn-secondary">Volver</a>
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