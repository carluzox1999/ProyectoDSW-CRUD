<?php
require "conexion.php";


$pdo = Conexion::conectar();
$id = $_POST["id"];

$query = $pdo->prepare("SELECT nombre, nombreCorto, descripcion, pvp, familia FROM productos WHERE id = :id");
$query->execute(["id" => $id]);
$resultado = $query->fetch(PDO::FETCH_ASSOC);


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
        <form class="row g-3" method="post" action="listado.php" id="formularioCrear" autocomplete="off">
            <div class="col-md-6">
                <label class="form-label">Nombre</label>
                <input type="text" class="form-control" id="inputNombreCrear" placeholder="Nombre" 
                value="<?php echo $resultado->nombre ?>">
            
            </div>
            <div class="col-md-6">
                <label class="form-label">Nombre Corto</label>
                <input type="text" class="form-control" id="inputNombreCortoCrear" placeholder="Nombre Corto" value="<?php echo !empty($inputNombreCorto) ? $inputNombreCorto : ''; ?>">
                
            </div>
            <div class="col-md-6">
                <label class="form-label">Precio (€)</label>
                <input type="number" class="form-control" id="inputPrecioCrear" placeholder="Precio" value="<?php echo !empty($inputPrecio) ? $inputPrecio : ''; ?>">
                <?php if (!empty($precioError)) : ?>
                    <span class="text-danger"><?php echo $precioError; ?></span>
                <?php endif; ?>
            </div>
            <div class="col-md-6">
                <label class="form-label">Familia</label>
                <select class="form-control" id="selectCrear">
                    <?php
                    $pdoSelect = Conexion::conectar();
                    $sqlSelect = $pdoSelect->query("SELECT nombre FROM familias");
                    $sqlSelect->execute();
                    $data = $sqlSelect->fetchAll();

                    foreach ($data as $valores) :
                        echo '<option value="' . $valores["nombre"] . '">' . $valores["nombre"] . '</option>';
                    endforeach;
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" id="txtDescripcion" rows="5" placeholder="Ingrese una descripción..." value="<?php echo !empty($txtDescripcion) ? $txtDescripcion : ''; ?>">
                </textarea>
                <?php if (!empty($descripcionError)) : ?>
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