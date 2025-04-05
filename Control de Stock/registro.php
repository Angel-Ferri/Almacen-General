<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="estilo/estilo.css">
    <link rel="shortcut icon" href="img/logo.png" type="image/x-icon">
</head>
<body>
    <?php include("bs/conexion.php"); ?>
<div class="conteiner">
    <?php include("partespagina/barrasinsession.php"); ?>
    <div class="formulario">
        <h2>Registro</h2>
        <p>Crea tu cuenta</p>
        <form method="post">
            <h3>Ingrese su nombre</h3>
            <input type="text" name="nombre">
            <h3>Ingrese su Correo Electrónico</h3>
            <input type="email" name="email">
            <h3>Ingrese su Rango</h3>
            <select name="rol">
                <option value="almacenista">almacenista</option>
                <option value="administrador">administrador</option>
                <option value="vendedor">vendedor</option>
            </select>
            <h3>Ingrese contraseña</h3>
            <input type="password" name="password1">
            <h3>Confirme su contraseña</h3>
            <input type="password" name="password">
            <input type="submit" value="Registro" name="registro">

            <?php
    if (!empty($_POST["registro"])) {
        if (empty($_POST["nombre"]) or empty($_POST["email"]) or empty($_POST["rol"]) or empty($_POST["password1"]) or empty($_POST["password"])) {
            echo '<h4>Por favor, completa todos los campos.</h4>';
        } else {
            $nombre=$_POST["nombre"];
            $email=$_POST["email"];
            $rol=$_POST["rol"];
            $password1=$_POST["password1"];
            $password=$_POST["password"];
            
            if ($password1 !== $password) {
                echo '<h4>Las contraseñas no coinciden.</h4>';
            } else {
                $contraigual = $password;               

                $sql = $conexion->query("INSERT INTO usuarios(nombre, email, rol, contraseña) VALUES ('$nombre','$email','$rol','$contraigual')");
            
               if ($sql) {
                    header("Location: login.php");
                    exit;
                } else {
                    echo '<h4 class="error">Error al registrar los datos. Intenta nuevamente.</h4>';
              }   
            }
           }
        }
    
    ?>
        </form>
    </div>

    <?php
        include("partespagina/barrainferior.php")
        ?>

</div>
</body>
</html>