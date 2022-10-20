<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style2.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <title>Crear producto</title>
</head>

<body>

    <h1>Crear Producto</h1>

    <div class="grid estilodiv">
        <form class="row g-3" method="post" id="formularioCrear">
            <div class="col-md-6">
                <label for="inputEmail4" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="inputNombreCrear">
            </div>
            <div class="col-md-6">
                <label for="inputPassword4" class="form-label">Nombre Corto</label>
                <input type="text" class="form-control" id="inputNombreCortoCrear">
            </div>
            <div class="col-md-6">
                <label for="inputEmail4" class="form-label">Precio (€)</label>
                <input type="number" class="form-control" id="inputPrecioCrear">
            </div>
            <div class="col-md-6">
                <label for="inputPassword4" class="form-label">Familia</label>
                <select class="form-control" id="selectCrear">
                    <option selected="selected" class="custon.select">Seleccione una opción</option>
                    <?php
                        foreach ($familia as $fami) {
                            # code...
                        }
                    ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="exampleFormControlTextarea1" class="form-label">Descripción</label>
                <textarea class="form-control" id="txtDescripcion" rows="5"></textarea>
            </div>
            <input type="hidden" name="oculyo" value="1">
            <div class="d-grid gap-2">
                <a href="listado.php" type="submit" class="btn btn-primary">Crear producto</a>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-success">Limpiar</button>
            </div>

            <div class="d-grid gap-2">
                <a href="listado.php" class="btn btn-secondary">Volver</a>
            </div>
        </form>
    </div>

    <script src="./js/bootstrap.min.js"></script>
</body>

</html>