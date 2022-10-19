<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Document</title>
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

    <div class="container">
        <div class="row justify-content-md-center">
            <?php
            try {
                $conProyecto = new PDO($dsn, $user, $pass);
                echo "<div'>";
                echo "<p>Conectado</p>";
                echo "</div>";
            } catch (Exception $e) {
                echo "<div class='headerPagina'>";
                die("Error: " . $e->getMessage());
                echo "</div>";
            }
            ?>
        </div>
    </div>



    <?php
    $result = $conProyecto->query(
        "SELECT id, nombre FROM productos"
    );


    $resultado = $result->fetch(PDO::FETCH_OBJ);

    echo "<h1>Gestión de productos</h1>";
    echo "<p>Boton Crear</p>";

    echo "<table class='default'>";
    echo "<tr>";
    echo "<th>Detalle</th>";
    echo "<th>Codigo</th>";
    echo "<th>Nombre</th>";
    echo "<th>Acciones</th>";
    echo "</tr>";
    while ($resultado != null) {
        echo "<tr>";
        echo "<td><p>Detalles</p></td>";
        echo "<td>$resultado->id</td>";
        echo "<td>$resultado->nombre</td>";
        echo "<td><p>Acciones</p></td>";
        echo "</tr>";
        echo "</table";

        $resultado = $result->fetch(PDO::FETCH_OBJ);
    }
    $conProyecto = null;
    ?>
    <script src="./js/bootstrap.min.js"></script>
</body>

</html>