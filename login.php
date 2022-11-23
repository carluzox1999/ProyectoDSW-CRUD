<?php
    session_start();
    if (isset($_SESSION['usuario'])) {
        header("location: listado.php");
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
    <link rel="stylesheet" href="./css/style2.css">
    <title>Principal</title>
</head>
<body>
    <div class="d-flex d-flex justify-content-center">
        <div class="bg-light text-white col-md-6 d-flex justify-content-center align-items-center">
            <div class="imagen">
                <img src="./IMG/Imagen Tema.png" width="100%" height="100%">
            </div>
        </div>

        <div class="bg-dark col-md-6 d-flex justify-content-center align-items-center">
            <div class="col-md-6">
                <form class="row g-3" action="login.php" method="post" autocomplete="off">
                    <h1 style="color: white;">LOGIN</h1>
                    <div class="mb-3">
                        <input type="text" class="form-control" name="usuario" placeholder="Usuario">
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control" name="clave" placeholder="Contraseña">
                    </div>
                        
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success">Login</button>
                    </div> 
                </form>
                <?php
                    if ($_POST) {
                        require "conexion.php";
                        $conexion = Conexion::conectar();
                        
                        $usuario = $_POST['usuario'];
                        $clave = hash('sha256', $_POST['clave']);

                        $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                        $query = $conexion->query("SELECT usuario, sha2(clave, 256) FROM usuarios WHERE usuario = '$usuario' AND clave = '$clave'");
                        $query->execute();
                        $usuarioLogin = $query->fetch(PDO::FETCH_ASSOC);

                        if($query -> rowCount() > 0){
                            $_SESSION['usuario'] = $usuarioLogin['usuario'];
                            
                            header("location: listado.php");
                        }else{
                            // print_r("Contraseña a verificar: ".$clave);
                            echo "<br><h5 style='color: red;'>Usuario o password incorrectos</h5>";
                        }
                    }
                ?>
            </div>
        </div>
    </div>

    <script src="./js/bootstrap.min.js"></script>
    <script src="https://kit.fontawesome.com/ad7c1d9068.js" crossorigin="anonymous"></script>
</body>
</html>