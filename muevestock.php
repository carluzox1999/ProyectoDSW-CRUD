<?php
session_start();
require "conexion.php";
$conexion = Conexion::conectar();
if (!isset($_SESSION['usuario'])) {
    header("location: login.php");
} elseif (isset($_SESSION['usuario'])) {

    $usuarioActual = $_SESSION['usuario'];

    $conexion = Conexion::conectar();

    $especificacionesUsuarioSQL = $conexion->query("SELECT usuario, colorfondo, tipoletra 
    FROM usuarios WHERE usuario = '$usuarioActual';");

    $especificacionesUsuarioSQL->execute();
    $especificaciones = $especificacionesUsuarioSQL->fetch(PDO::FETCH_ASSOC);

    $_SESSION['colorfondo'] = $especificaciones["colorfondo"];
    $_SESSION["tipoletra"] = $especificaciones["tipoletra"];


    if (!empty($_GET['producto'])) {
        $id = $_REQUEST['producto'];
    } else
        header("location: listado.php");

    $pdoNombre = Conexion::conectar();
    $sql = "SELECT nombre FROM productos WHERE  id = '$id';";
    $conexion = $pdoNombre->query($sql);
    $data = $conexion->fetch(PDO::FETCH_OBJ);
    $nombre = $data->nombre;
    $pdoNombre = Conexion::desconectar();
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/bootstrap.min.css">
        <link rel="stylesheet" href="./css/style.css">
        <link rel="stylesheet" href="./css/style2.css">
        <title>Stock</title>
    </head>

    <body>
        <style>
            html,
            body {
                background-color: <?php echo "#" . $_SESSION['colorfondo'] ?>;
                font-family: <?php echo $_SESSION['tipoletra'] ?>;
                padding: 0;
                margin: 0;
                height: 100%;
            }

            body::-webkit-scrollbar {
                display: none;
            }

            @media (max-width: 800px) {
                .codigo {
                    display: none;
                }
            }

            @media (max-width: 300px) {
                .detalle {
                    display: none;
                }

                .codigo {
                    display: none;
                }

            }

            tr th {
                vertical-align: middle;
                border-style: inset;
                border-width: 5px;
            }

            tr td {
                text-align: center;
                vertical-align: middle;
            }
        </style>


        <div class="container">
            <div class="d-flex justify-content-between">
                <div class='p2'>
                    <a href="listado.php"><i class="fa-solid fa-house" style="margin-top: 20px; margin-left: 20px; margin-right: 20px; width: 10px;"></i></a>
                </div>
                <div class='p2'>
                    <h1>Mover Stock</h1>
                </div>

                <?php
                $urlEditarPerfil =  "<a href='perfil.php' class='btn btn-warning' type='button'>Perfil</a>";
                $urlCerrarSesion =  "<a href='cerrarUsuario.php' class='btn btn-danger' type='button'>Cerrar Sesión</a>";
                ?>

                <div class="p-2">
                    <?php echo "<h4 class='d-flex align-items-center'>" . $_SESSION['nombrecompleto'] . "</h4>"; ?>
                </div>

                <div class="p-2">
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle dropbtn">Menú</button>
                        <div class="dropdown-content">
                            <?php echo "$urlEditarPerfil"; ?>
                            <?php echo "$urlCerrarSesion"; ?>
                        </div>
                    </div>
                </div>
            </div>

            <h1>
                <?php
                echo "<h3>$nombre</h3>";
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
                        $pdoListado = Conexion::desconectar();
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

                        $pdoUnidades = Conexion::conectar();
                        $sqlUnidades = "SELECT unidades FROM stocks WHERE producto = '$id' and tienda = '$resultado[tienda]';";
                        $conexionUnidades = $pdoUnidades->query($sqlUnidades);
                        $dataUnidades = $conexionUnidades->fetch(PDO::FETCH_OBJ);
                        $unidades = $dataUnidades->unidades;

                        for ($i = 1; $i <= $unidades; $i++) {
                            echo '<option value="' . $i . '">' . $i . ' unidades' . '</option>';
                        }
                        Conexion::desconectar();
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
                    $sqlTiendaActual = $pdo->query("SELECT * FROM stocks where stocks.tienda = '$tienda' and stocks.producto = '$producto';");
                    $transaccion = $sqlTiendaActual->fetch();

                    if ($transaccion['unidades'] == $unidades) {
                        $sqlBorrarStock = $pdo->query("DELETE FROM stocks where stocks.tienda = '$tienda' and stocks.producto = '$producto'");
                        $transaccionStockB = $sqlBorrarStock->fetch(PDO::FETCH_OBJ);
                        if (!$transaccionStockB) {
                            $isTransaccion = false;
                        }
                    } else {
                        $sqlActualizarStock = $pdo->query("UPDATE stocks SET unidades = unidades - '$unidades' where stocks.tienda = '$tienda' and stocks.producto = '$producto';");
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
                        Conexion::desconectar();
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

                        $pdoUnidades = Conexion::conectar();
                        $sqlUnidades = "SELECT unidades FROM stocks WHERE producto = '$id' and tienda = '$resultado[tienda]';";
                        $conexionUnidades = $pdoUnidades->query($sqlUnidades);
                        $dataUnidades = $conexionUnidades->fetch(PDO::FETCH_OBJ);
                        $unidades = $dataUnidades->unidades;

                        for ($i = 1; $i <= $unidades; $i++) {
                            echo '<option value="' . $i . '">' . $i . ' unidades' . '</option>';
                        }
                        Conexion::desconectar();
                        echo "</select>";
                        echo "</td>";
                        echo "<input hidden name='tienda' value='" . $resultado["id"] . "''>";
                        echo "<input hidden name='nombre' value='" . $resultado["nombre"] . "' '>";
                        echo "<input hidden name='producto' value='" . $id . "' '>";
                        echo "<td><button type='submit' class='btn btn-primary d-grid gap-2'>Mover Stock</button></td>";
                        echo "</form>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>



        <script src="./js/bootstrap.min.js"></script>
        <script src="https://kit.fontawesome.com/ad7c1d9068.js" crossorigin="anonymous"></script>

    </body>

    </html>
<?php
}
?>