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
    // PDO
    $host = "localhost";
    $db = "proyecto";
    $user = "carlos";
    $pass = "carlos1234";
    // $dsn = "pgsql:host=$host;dbname=$db;";
    $dsn = "mysql:host=$host;dbname=$db;";
    ?>

    <?php
    try {
        $conProyecto = new PDO($dsn, $user, $pass);
        echo "<div class=headerConexionSi>";
        echo "<strong>Conectado a base de datos: $db</strong>";
        echo "</div>";
    } catch (Exception $e) {
        echo "<div class='headerConexionNo'>";
        die("<strong>Error: </strong>" . $e->getMessage());
        echo "</div>";
    }
    ?>

    <?php
    $result = $conProyecto->query(
        "SELECT id, nombre FROM productos"
    );

    $resultado = $result->fetch(PDO::FETCH_OBJ);
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
        while ($resultado != null) {
            echo "<tr>";
            echo "<td class='detalle'><a href='' class='btn btn-info btn-block botonInfo'>Detalle</a></td>";
            echo "<td class='codigo'>$resultado->id</td>";
            echo "<td>$resultado->nombre</td>";
            echo "<td>
                <a href='' class='btn btn-warning btn-block botonExtra'>Actualizar</a>
                <a href='' class='btn btn-danger btn-block botonExtra'>Borrar</a></td>";
            echo "</tr>";
            echo "</table";

            $resultado = $result->fetch(PDO::FETCH_OBJ);
        }
        $conProyecto = null;
        ?>


        <script src="./js/bootstrap.min.js"></script>
</body>

</html>