<?php 
include "conexion.php";

$order ="";
$limit = 5;
$pag = (isset($_GET['pag'])) ? (int)$_GET['pag'] : 0;

if ($pag < 1) {
    $pag = 1;
}
$offset=($pag-1)*$limit;

if (isset($_GET['columna'])){
    $order="ORDER BY ".$_GET['columna'].", ".$_GET['tipo'];
}

$conexion = Conexion::conectar();

$busqueda=$conexion->prepare("SELECT * FROM productos LIMIT $offset, $limit");
$busqueda->execute();

$busquedaTotal = $conexion->prepare("SELECT * FROM productos $order");
$busquedaTotal->execute();
$total=$busquedaTotal->rowCount();




echo "<table class='table table-striped table-hover'>
        <tr class='table-dark'>
            <th class='detalle'>Detalle</th>
            <th class='codigo'>Codigo</th>
            <th>Nombre
                <div class='float-end'>";
                    if(isset($_GET['columna']) && $_GET['columna'] == 'nombre' && $_GET['tipo'] == 'asc'):
                        echo "<i class='fa fa-arrow-up'></i>";
                    else:
                        echo "<a href='listado.php?columna=nombre&tipo=asc'><i class='fa fa-arrow-up'></i></a>";
                    endif;
                    if(isset($_GET['columna']) && $_GET['columna'] == 'nombre' && $_GET['tipo'] == 'desc'):
                        echo "<i class='fa fa-arrow-down'></i>";
                    else:
                        echo "<a href='listado.php?columna=nombre&tipo=desc'><i class='fa fa-arrow-down'></i></a>";
                    endif;
echo        "</div>    
            </th>
            <th>Acciones</th>
        </tr> ";

while($productoFila = $busqueda->fetch(PDO::FETCH_ASSOC)){
    echo "<tr>";
            echo "<td><a href='detalle.php?id=" . $productoFila['id'] . "' class='btn btn-info btn-block botonInfo'>Detalle</a></td>";
            echo "<td class='codigo'><b>" . $productoFila['id'] . "</b></td>";
            echo "<td><b>" . $productoFila['nombre'] . "<b></td>";
            echo "<td>
                <div class='btn-group d-grid gap-0 d-md-flex justify-content-md-end' role='group'>
                    <a href='editar.php?id=" . $productoFila['id'] . "&familia=" . $productoFila['familia'] . "' class='btn btn-warning' type'button'>Actualizar</a>
                    <a href='borrar.php?id=" . $productoFila['id'] . "' class='btn btn-danger' type'button'>Borrar</a>
                </div>
                <a href='muevestock.php?producto=" . $productoFila['id'] . "' class='btn btn-secondary d-grid gap-2'>Mover Stock</a></td>";
            echo "</tr>";
}

echo "<tr><td class='text-center' colspan='4'>";

    $totalPag=ceil($total/$limit);
    $links = array();
    for($i=1; $i<=$totalPag; $i++){
        $links[]="<a style='border:solid 1px blue; padding-left:.6%; padding-right:.6%; padding-top:.25%; padding-bottom:.25%;' href=\"?pag=$i\">$i</a>";
    }

print_r(''.implode(' ', $links));

echo "</td>";


echo "</tr>
    </table>
";

Conexion::desconectar();

?>