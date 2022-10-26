<?php
include "conexion.php";

if (!empty($_GET['id'])) {
    $id = $_REQUEST['id'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>Stock</title>
</head>
<body>
    <h1>Gestión Stock</h1>

    <table class="table table-striped table-hover">
        <tr class="table-dark">
            <th>Tienda</th>
            <th>Stock Anual</th>
            <th>Nueva Tienda</th>
            <th>Nº Unidades</th>
        </tr>
        <?php

        if (!empty($_POST)) {

            $id = $_POST['id'];
            $tienda = $_POST['tienda'];
            $unidades = $_POST['unidades'];
            $nombre = $_POST['nombre'];

            try {
                $pdo = Conexion::conectar();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = "SELECT * FROM stocks, tiendas WHERE stocks.tienda = id and stocks.producto = ? ORDER BY tiendas.nombre ASC;";
                $pdo->commit();
                $conexion = $pdo->prepare($sql);
                $conexion->execute([$tienda, $unidades, $nombre, $id]);
                Conexion::desconectar();
            
            } catch (Exception $e) {
                $pdo->rollback();
                echo "Lista no completada: " . $e->getMessage();
            }
        }

        foreach ($sql as $resultado) {
            echo "<tr>";
                echo  "<td><b>".$resultado['tienda']."</b></td>";
                echo "<td><b>".$resultado['unidades']."</b></td>";
                echo "<td><b><b></td>";
                echo "<td><b><b></td>";
            echo "</tr>";
            echo "</table";

        }
        Conexion::desconectar();
        ?>


    <script src="./js/bootstrap.min.js"></script>
</body>
</html>