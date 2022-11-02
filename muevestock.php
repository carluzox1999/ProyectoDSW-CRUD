<?php
include "conexion.php";

if (!empty($_GET['producto'])) {
    $id = $_REQUEST['producto'];
}

$pdoNombre = Conexion::conectar();
$sql = "SELECT * FROM productos WHERE  id = '$id';";
$conexion = $pdoNombre->query($sql);
$data = $conexion->fetch(PDO::FETCH_OBJ);
$nombre = $data->nombre;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style2.css">
    <title>Stock</title>
</head>

<body>
    <div class="d-grid gap-2">
        <a href="listado.php" class="btn btn-secondary btn-block boton">Volver</a>
    </div>
    <hr />

    <h1>Mover Stock</h1>

    <h1>
        <?php
        echo "<h1>$nombre</h1>";
        ?>
    </h1>

    <table class="table table-striped table-hover">
        <tr class="table-dark">
            <th>Tienda</th>
            <th>Stock Actual</th>
            <th>Nueva Tienda</th>
            <th>Nº Unidades</th>
            <th>Mover Stock</th>
        </tr>
        <?php
        if (!empty($_GET) & (count($_GET) < 2)) {

            try {
                $pdoListado = Conexion::conectar();
                $pdoListado->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdoListado->beginTransaction();
                $sqlListado = $pdoListado->query("SELECT * FROM stocks, tiendas where producto = '$id' and stocks.tienda = tiendas.id ORDER BY tiendas.nombre;");
                $pdoListado->commit();
            } catch (Exception $e) {;
                echo "Lista no completada: " . $e->getMessage();
            }

            foreach ($sqlListado as $resultado) {
                echo "<tr>";
                echo "<form class='row g-3' method='get' action='muevestock.php' autocomplete='off'>";
                echo "<td><b>" . $resultado['nombre'] . "<b></td>";
                echo "<td><b>" . $resultado['unidades'] . "<b></td>";
                echo "<td>
                                    <select class='form-control' name='tiendaDestino'>";

                $pdoTienda = Conexion::conectar();
                $sqlTienda = $pdoTienda->query('SELECT id, nombre FROM tiendas');
                $sqlTienda->execute();
                while ($data = $sqlTienda->fetch(PDO::FETCH_OBJ)) {
                    if ($resultado['tienda'] != $data->id) {
                        echo "<option value= '" . $data->id . "'>" . $data->nombre . "</option>";
                    }
                }
                Conexion::desconectar();
                echo "</select>";
                echo "</td>";
                echo "<td>
                                    <select class='form-control' name='unidades'>";

                $pdoStock = Conexion::conectar();
                $pdoStock->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdoStock->beginTransaction();
                $sqlStock = "SELECT * FROM stocks;";
                $pdoStock->commit();
                $conexionStock = $pdoStock->query($sqlStock);
                $dataStock = $conexionStock->fetch(PDO::FETCH_OBJ);
                $unidades = $dataStock->unidades;

                for ($i = 1; $i <= $unidades; $i++) {
                    echo '<option value="' . $i . '">' . $i . ' unidades' . '</option>';
                }
                
                echo "</select>";
                echo "</td>";
                echo "<input hidden name='tienda' value='" . $resultado["id"] . "''>";
                echo "<input hidden name='nombre' value='" . $resultado["nombre"] . "' '>";
                echo "<input hidden name='producto' value='" . $id . "' '>";
                echo "<td><button type='submit' class='btn btn-primary d-grid gap-2'>Mover Stock</button></td>";
                echo "</form>";
                echo "</tr>";
                echo "</table";
            }
        } else {
            $isTransaccion = true;
            $nombre = $_GET['nombre'];
            $unidades = $_GET['unidades'];
            $tienda = $_GET['tienda'];
            $producto = $_GET['producto'];
            $tiendaDestino = $_GET['tiendaDestino'];

            $pdo = Conexion::conectar();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            $sqlTiendaActual = $pdo->query("SELECT * FROM stocks where stocks.tienda = '$tienda' and stocks.producto;");
            $transaccion = $sqlTiendaActual->fetch();

            if ($transaccion['unidades'] == $unidades) {
                $sqlBorrarStock = $pdo->query("DELETE FROM stocks where stocks.tienda = '$tienda' and stocks.producto");
                $transaccionStockB = $sqlBorrarStock->fetch(PDO::FETCH_OBJ);
                if (!$transaccionStockB) {
                    $isTransaccion = false;
                }
            } else{
                $sqlActualizarStock = $pdo->query("UPDATE stocks SET unidades = unidades - '$unidades' where stocks.tienda = '$tienda' and stocks.producto;");
                $transaccionStockA = $sqlActualizarStock->fetch(PDO::FETCH_OBJ);
                if (!$transaccionStockA) {
                    $isTransaccion = false;
                }
            }

            $sqlTiendaDestino = $pdo->query("SELECT * FROM stocks where stocks.tienda = '$tiendaDestino' and stocks.producto = '$producto';");
            $transaccionDestino = $sqlTiendaDestino->fetch(PDO::FETCH_OBJ);

            if ($transaccionDestino) {
                $sqlAgregarUnidades = $pdo->query("UPDATE stocks SET unidades = unidades + '$unidades' where stocks.tienda = '$tiendaDestino' and stocks.producto = '$producto';");
                $transaccionUnidadesA = $sqlAgregarUnidades->fetch(PDO::FETCH_OBJ);
                if (!$transaccionUnidadesA) {
                    $isTransaccion = false;
                }
            } else {
                // Se crea el registro del nuevo stock en la nueva tienda destino
                $sqlRegistroStock = $pdo->query("INSERT INTO stocks VALUES('$producto', '$tiendaDestino', '$unidades');");
                $transaccionUnidadesI = $sqlRegistroStock->fetch(PDO::FETCH_OBJ);
                if (!$transaccionUnidadesI) {
                    $isTransaccion = false;
                }
            }

            if ($isTransaccion = false) {
                $pdo->rollback();
            } else {
                $pdo->commit();
            }

            try {
                $pdo = Conexion::conectar();
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sqlListado = $pdo->query("SELECT * FROM stocks, tiendas where producto = '$id' and stocks.tienda = tiendas.id ORDER BY tiendas.nombre;");
            } catch (Exception $e) {;
                echo "Lista no completada: " . $e->getMessage();
            }

            foreach ($sqlListado as $resultado) {
                echo "<tr>";
                echo "<form class='row g-3' method='get' action='muevestock.php' autocomplete='off'>";
                echo "<td><b>" . $resultado['nombre'] . "<b></td>";
                echo "<td><b>" . $resultado['unidades'] . "<b></td>";
                echo "<td>
                                    <select class='form-control' name='tiendaDestino'>";

                $pdoTienda = Conexion::conectar();
                $sqlTienda = $pdoTienda->query('SELECT id, nombre FROM tiendas');
                $sqlTienda->execute();
                while ($data = $sqlTienda->fetch(PDO::FETCH_OBJ)) {
                    if ($resultado['tienda'] != $data->id) {
                        echo "<option value= '" . $data->id . "'>" . $data->nombre . "</option>";
                    }
                }
                Conexion::desconectar();
                echo "</select>";
                echo "</td>";
                echo "<td>
                                    <select class='form-control' name='unidades'>";

                $pdoStock = Conexion::conectar();
                $pdoStock->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $pdoStock->beginTransaction();
                $sqlStock = "SELECT * FROM stocks;";
                $pdoStock->commit();
                $conexionStock = $pdoStock->query($sqlStock);
                $dataStock = $conexionStock->fetch(PDO::FETCH_OBJ);
                $unidades = $dataStock->unidades;

                for ($i = 1; $i <= $unidades; $i++) {
                    echo '<option value="' . $i . '">' . $i . ' unidades' . '</option>';
                }
                echo "</select>";
                echo "</td>";
                echo "<input hidden name='tienda' value='" . $resultado["id"] . "''>";
                echo "<input hidden name='nombre' value='" . $resultado["nombre"] . "' '>";
                echo "<input hidden name='producto' value='" . $id . "' '>";
                echo "<td><button type='submit' class='btn btn-primary d-grid gap-2'>Mover Stock</button></td>";
                echo "</form>";
                echo "</tr>";
                echo "</table";
            }
            $pdo = Conexion::desconectar();
        }

        ?>
        <script src="./js/bootstrap.min.js"></script>
</body>
</html>