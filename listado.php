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

    <?php
    // $result = $conProyecto->query(
    //     "SELECT id, nombre FROM productos"
    // );

    // $resultado = $result->fetch(PDO::FETCH_OBJ);
    ?>

    <hr>

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
        
        $pdo = Conexion::conectar();
        $sql = $pdo->query("SELECT id, nombre FROM productos ORDER BY id");
        // $conexion = $sql->fetchAll(PDO::FETCH_OBJ);

        foreach ($sql as $resultado) {
            echo "<tr>";
            echo "<td class='detalle'><a href='' class='btn btn-info btn-block botonInfo'>Detalle</a></td>";
            echo "<td class='codigo'>".$resultado['id']."</td>";
            echo "<td>".$resultado['nombre']."</td>";
            echo "<td>
                <a href='' class='btn btn-warning btn-block botonExtra'>Actualizar</a>
                <a href='' class='btn btn-danger btn-block botonExtra'>Borrar</a></td>";
            echo "</tr>";
            echo "</table";

        }
        Conexion::desconectar();
        ?>


        <script src="./js/bootstrap.min.js"></script>
</body>

</html>