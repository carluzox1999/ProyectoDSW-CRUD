<?php
include "conexion.php";

if (!empty($_GET['producto'])) {
    $id = $_REQUEST['producto'];
}

$pdoNombre = Conexion::conectar();
$sql = "SELECT * FROM productos WHERE  id = '$id';";
$conexion = $pdoNombre->prepare($sql);
$conexion->execute([$id]);
$data = $conexion->fetch(PDO::FETCH_OBJ);

// $pdoIdTienda = Conexion::conectar();
// $sqlTienda = "SELECT * FROM stocks WHERE producto = ?;";
// $conexion = $pdoNombre->prepare($sqlTienda);
// $conexion->execute([$id]);
// $dataTienda = $conexion->fetch(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style2.css">
    <title>Stock</title>
</head>

<body>
    <div class="d-grid gap-2">
        <a href="listado.php" class="btn btn-secondary btn-block boton">Volver</a>
    </div>
    <hr/>

    <h1>Mover Stock</h1>

    <h1>
        <?php
        echo "<h1>Nombre Producto: $data->nombre</h1>";
        ?>
    </h1>

    <table class="table table-striped table-hover">
        <tr class="table-dark">
            <th>Tienda</th>
            <th>Stock Anual</th>
            <th>Nueva Tienda</th>
            <th>Nº Unidades</th>
            <th>Mover Stock</th>
        </tr>
        <?php
        if (!empty($_GET)&(count($_GET)<2)) {
            
            try {
                $pdo = Conexion::conectar();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdo->beginTransaction();
                $sql = $pdo->query("SELECT * FROM stocks, tiendas where producto = '$id' and stocks.tienda = tiendas.id ORDER BY tiendas.nombre;");
                $pdo->commit();
            } catch (Exception $e) {;
                echo "Lista no completada: " . $e->getMessage();
                
            }

            foreach ($sql as $resultado) {
                    echo "<tr>";
                        echo "<form class='row g-3' method='get' action='muevestock.php' autocomplete='off'>";
                            echo "<td><b>".$resultado['nombre']."<b></td>";
                            echo "<td><b>".$resultado['unidades']."<b></td>";
                            echo "<td>
                                    <select class='form-control' name='destino'>
                                        <option>Seleccione Opción</option>";

                                        $pdoTienda = Conexion::conectar();
                                        $sqlTienda = $pdoTienda->query('SELECT id, nombre FROM tiendas');
                                        $sqlTienda->execute();
                                        while ($data = $sqlTienda->fetch(PDO::FETCH_OBJ)) {
                                            echo "<option value= '".$data->id . "'>".$data->nombre . "</option>";
                                        }
                                        Conexion::desconectar();
                                    echo "</select>";
                                echo "</td>";
                            echo "<td>
                                    <select class='form-control' name='unidades'>
                                        <option>Seleccione Opción</option>";

                                    $pdo = Conexion::conectar();
                                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $pdo->beginTransaction();
                                    $sql = "SELECT * FROM stocks;";
                                    $pdo->commit();
                                    $conexion = $pdo->prepare($sql);
                                    $conexion->execute();
                                    $data = $conexion->fetch(PDO::FETCH_OBJ);
                                    $unidades = $data->unidades;

                                    for ($i = 1; $i<=$unidades; $i++){
                                        echo '<option value="' . $i . '">' . $i.' unidades' . '</option>';
                                    }
                                    Conexion::desconectar();
                            echo "</select>";
                            echo "</td>";
                            echo "<input hidden name='tienda' value='".$resultado["id"]."''>";    
                            echo "<input hidden name='nombre' value='".$resultado["nombre"]."' '>";  
                            echo "<input hidden name='producto' value='".$id."' '>";       
                            echo "<td><button type='submit' class='btn btn-primary'>Mover Stock</button></td>";                
                        echo "</form>";
                    echo "</tr>";
                echo "</table";
            }

        } else{
            $nombre = $_GET['nombre'];
            $unidades = $_GET['unidades'];
            $tienda = $_GET['tienda'];
            $producto = $_GET['producto'];
            $destino = $_GET['destino'];

            $pdo = Conexion::conectar();
            // $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $sql = $pdo->query("SELECT * FROM stocks where tienda = ;");
        }

        ?>

    <script src="./js/bootstrap.min.js"></script>
</body>

</html>