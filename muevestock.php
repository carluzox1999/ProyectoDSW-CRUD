<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <title>STOCK</title>
</head>

<body>
    <div class="d-grid gap-2">
        <a href="listado.php" class="btn btn-secondary btn-block boton">Volver</a>
    </div>

    <?php
    include "conexion.php";

    if (!empty($_GET['id'])) {
        $id = $_REQUEST['id'];
    }

    $pdoNombre = Conexion::conectar();
    
    $sql = "SELECT * FROM productos WHERE  id = ?;";
    $conexion = $pdoNombre->prepare($sql);
    $conexion->execute([$id]);
    $data = $conexion->fetch(PDO::FETCH_OBJ);


    echo "<h1>Nombre Producto: $data->nombre</h1>";
    Conexion::desconectar();
    ?>


    <table class="table table-striped table-hover">
        <tr class="table-dark">
            <th>Tienda</th>
            <th>Stock Anual</th>
            <th>Nueva Tienda</th>
            <th>Nº Unidades</th>
        </tr>
        <?php

        try {
            $pdo = Conexion::conectar();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $sql = "SELECT * FROM stocks, tiendas where stocks.producto = ? and stocks.tienda = tiendas.id ORDER BY tiendas.nombre;";
            $pdo->commit();
        } catch (Exception $e) {
            $pdo->rollback();
            echo "Lista no completada: " . $e->getMessage();
        }

        foreach ($sql as $resultado) {
            echo "<tr>";
                        echo "<form class='row g-3' method='post' action='muevestock.php' autocomplete='off'>";
                            echo "<td><b>".$resultado['nombre']."<b></td>";
                            echo "<td><b>".$resultado['unidades']."<b></td>";
                            echo "<td>
                                    <select class='form-control' name='familia'>
                                        <option value=".$resultado['tienda']."'>Seleccione Opción</option>
                                        <?php
                                        ".$pdoTienda." = Conexion::conectar();
                                        ".$sqlTienda." = ".$pdoTienda."->query('SELECT id, nombre FROM tiendas');
                                        ".$sqlTienda."->execute();
                                        while (".$data." = ".$sqlTienda."->fetch(PDO::FETCH_OBJ)) {
                                            <option value='' . ".$data."->cod . ''>' .".$data."->nombre . '</option>;
                                        }
                                        ?>
                                    </select>
                                </td>";
                            echo "<td><input type='number' class='form-control' name='unidades'></td>";
                        echo "</form>";
                    echo "</tr>";
                echo "</table";

        }
        Conexion::desconectar();
        ?>


        <script src="./js/bootstrap.min.js"></script>
</body>

</html>