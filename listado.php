<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Proyecto DSW</title>
</head>

<body>

    <h1>Gestión de productos</h1>

    <div class="d-grid gap-2">
        <a href="crear.php" class="btn btn-success btn-block boton">Crear producto</a>
    </div>


    <table class="table table-striped table-hover">
        <tr class="table-dark">
            <th class="detalle">Detalle</th>
            <th class="codigo">Codigo</th>
            <th>Nombre</th>
            <th>Acciones</th>
        </tr>
        <?php
        include "conexion.php";

        try {
            $pdo = Conexion::conectar();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $sql = $pdo->query("SELECT * FROM productos ORDER BY id DESC");
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollback();
            echo "Lista no completada: " . $e->getMessage();
        }

        foreach ($sql as $resultado) {
            echo "<tr>";
            echo "<td><a href='detalle.php?id=" . $resultado['id'] . "' class='btn btn-info btn-block botonInfo'>Detalle</a></td>";
            echo "<td class='codigo'><b>" . $resultado['id'] . "</b></td>";
            echo "<td><b>" . $resultado['nombre'] . "<b></td>";
            echo "<td>
                <div class='d-grid gap-2 d-md-block'>
                    <a href='editar.php?id=" . $resultado['id'] . "&familia=" . $resultado['familia'] . "' class='btn btn-warning botonExtra' type'button'>Actualizar</a>
                    <a href='borrar.php?id=" . $resultado['id'] . "' class='btn btn-danger botonExtra' type'button'>Borrar</a>
                </div>
                <a href='muevestock.php?producto=" . $resultado['id'] . "' class='btn btn-secondary d-grid gap-2'>Mover Stock</a></td>";
            echo "</tr>";
            echo "</table";
        }
        Conexion::desconectar();
        ?>


        <script src="./js/bootstrap.min.js"></script>
</body>

</html>