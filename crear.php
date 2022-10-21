<?php
require "conexion.php";

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
            $pdo = Conexion::conectar();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "INSERT INTO productos (nombre, nombre_corto, descripcion, pvp, familia) VALUES(?,?,?,?,?)";
            $conexion = $pdo->prepare($sql);
            $conexion->execute(array($inputNombre, $inputNombreCorto, $txtDescripcion, $inputPrecio, $select));
            Conexion::desconectar();
            $nuevaURL = "listado.php";
            header('Location: '.$nuevaURL);
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
    <link rel="stylesheet" href="./css/style2.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <title>Crear producto</title>
</head>

<body>

    <h1>Crear Producto</h1>

    <div class="grid estilodiv">
        <form class="row g-3" method="post" action="crear.php" id="formularioCrear" autocomplete="off">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" placeholder="Nombre"
                    value="<?php echo !empty($inputNombre) ? $inputNombre : ''; ?>">
                <?php if (!empty($nombreError)): ?>
                <span class="text-danger"><?php echo $nombreError; ?></span>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Nombre Corto</label>
                <input type="text" class="form-control" name="nombre_corto" placeholder="Nombre Corto"
                    value="<?php echo !empty($inputNombreCorto) ? $inputNombreCorto : ''; ?>">
                <?php if (!empty($nCortoError)): ?>
                <span class="text-danger"><?php echo $nCortoError; ?></span>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Precio (€)</label>
                <input type="number" class="form-control" name="pvp" placeholder="Precio"
                    value="<?php echo !empty($inputPrecio) ? $inputPrecio : ''; ?>">
                <?php if (!empty($precioError)): ?>
                <span class="text-danger"><?php echo $precioError; ?></span>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Familia</label>
                <select class="form-control" name="familia">
                    <?php
                        $pdoSelect = Conexion::conectar();
                        $sqlSelect = $pdoSelect->query("SELECT nombre FROM familias");
                        $sqlSelect->execute();
                        $data = $sqlSelect->fetchAll();

                        foreach ($data as $valores):
                        echo '<option value="'.$valores["nombre"].'">'.$valores["nombre"].'</option>';
                        endforeach;
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" rows="5" placeholder="Ingrese una descripción..."
                    value="<?php echo !empty($txtDescripcion) ? $txtDescripcion : ''; ?>">
                </textarea>
                <?php if (!empty($descripcionError)): ?>
                <span class="text-danger"><?php echo $descripcionError; ?></span>
                <?php endif; ?>
            </div>
            <!-- <input type="hidden" name="oculto" value="1"> -->
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
</body>

</html>