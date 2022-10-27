<?php
require "conexion.php";

if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}

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
    <link rel="stylesheet" href="./css/style2.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <title>Editar producto</title>
</head>

<body>

    <h1>Actualizar Producto</h1>

    <div class="grid estilodiv">
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
                <label class="form-label">Precio (€)</label>
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
                        if($data -> cod != $dataF -> cod)
                            echo '<option value="' . $data->cod . '">' . $data->nombre . '</option>';
                        
                        
                    }
                    Conexion::desconectar();
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" cols="20" rows="10" placeholder="Ingrese una descripción..."><?php echo !empty($descripcion) ? $descripcion : ''; ?></textarea>

            </div>
            <input type='hidden' name='id' value='<?=$id?>'>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Actualizar</button>
            </div>
            <div class="d-grid gap-2">
                <a href="listado.php" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>

    <script src="./js/bootstrap.min.js"></script>
    <?php
    Conexion::desconectar();
    ?>
</body>

</html>