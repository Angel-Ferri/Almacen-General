<?php
// Iniciar la sesión al principio
session_start();
// Conexion usando PDO
include('bs/conexion.php');
?>
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
<div class="conteiner">
    <?php include("partespagina/barrasinsession.php"); ?>
    <div class="formulario">
        <h2>LOGIN</h2>
        <form method="post">
            <h3>Ingrese su correo electrónico</h3>
            <input type="email" name="email" required>
            <h3>Ingrese su contraseña</h3>
            <input type="password" name="password" required><br>
            <input type="submit" value="INICIAR SESIÓN" name="login">
        </form>

        <?php
        // Verificamos si se envió el formulario
        if (isset($_POST['login'])) {
            // Obtener los datos del formulario
            $email = trim($_POST['email']);
            $password = trim($_POST['password']);

            try {
                // Manteniendo tu consulta original, pero adaptada para PDO
                $query = "SELECT * FROM `usuarios` WHERE email = '$email' AND contraseña = '$password'";
                $stmt = $conexion->query($query);

                // Verificar si el usuario existe
                if ($stmt->rowCount() > 0) {
                    $_SESSION['email'] = $email; // Guardar el email en la sesión
                    echo "<p>Inicio de sesión exitoso. Redirigiendo...</p>";
                    header("Location: dashboard.php");
                    exit;
                } else {
                    echo "<p>Correo o contraseña incorrectos</p>";
                }
            } catch (PDOException $e) {
                echo "Error en la consulta: " . $e->getMessage();
            }
        }
        ?>
    </div>
    <?php
        include("partespagina/barrainferior.php")
        ?>
</div>
</body>
</html>
