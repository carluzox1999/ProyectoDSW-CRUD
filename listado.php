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
        
        $pdo = Conexion::conectar();
        $sql = $pdo->query("SELECT * FROM productos ORDER BY id DESC");

        foreach ($sql as $resultado) {
            echo "<tr>";
            echo "<td><a href='detalle.php?id=".$resultado['id']."' class='btn btn-info btn-block botonInfo'>Detalle</a></td>";
            echo "<td class='codigo'><b>".$resultado['id']."</b></td>";
            echo "<td><b>".$resultado['nombre']."<b></td>";
            echo "<td>
                <a href='editar.php?id=".$resultado['id']."&familia=".$resultado['familia']."' class='btn btn-warning btn-block botonExtra'>Actualizar</a>
                <a href='borrar.php?id=".$resultado['id']."' class='btn btn-danger btn-block botonExtra'>Borrar</a></td>";
                echo "</tr>";
            echo "</table";

        }
        Conexion::desconectar();
        ?>


        <script src="./js/bootstrap.min.js"></script>
</body>

</html>