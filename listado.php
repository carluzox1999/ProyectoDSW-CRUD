<style>
    .headerPagina{
        display: flex;
        background-color: gray;
        color: black;
        height: 5em;
        justify-content: center;
        align-items: center;
    }

    table{
        width: 100%;
    }

    tr th{
        background-color: black;
        color: white;
        height: 2em;
    }

    td{
        text-align: center;
    }
</style>

<?php

// PDO
$host = "localhost";
$db = "proyecto";
$user = "carlos";
$pass = "carlos1234";
// $dsn = "pgsql:host=$host;dbname=$db;";
$dsn = "mysql:host=$host;dbname=$db;";

try {
    $conProyecto = new PDO($dsn, $user, $pass);
    echo "<div class='headerPagina'>";
        echo "<h3>Conectado a la base de datos</h3>";
    echo "</div>";
} catch (Exception $e) {
    echo "<div class='headerPagina'>";
        die("Error: " . $e->getMessage());
    echo "</div>";
}

$result = $conProyecto->query(
"SELECT id, nombre FROM productos");


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