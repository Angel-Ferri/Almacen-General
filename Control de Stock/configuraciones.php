<?php

session_start();
$email = $_SESSION['email'];
include("bs/conexion.php");


// Consulta para obtener el ID del usuario usando el email
$stmt = $conexion->query("SELECT id FROM usuarios WHERE email = '$email'");

if ($stmt) {
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($usuario) {
        $id = $usuario['id']; // Guardar el ID en la variable $id
        $_SESSION['usuario_id'] = $id; // Guardar el ID en la sesión
    }
}

$stmt = $conexion->query("SELECT contraseña FROM usuarios WHERE email = '$email'");

if ($stmt) {
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($usuario) {
        $contrabs = $usuario['contraseña']; // Guardar el ID en la variable $id
        $_SESSION['contraseña'] = $contrabs; // Guardar el ID en la sesión
    }
}

include("partespagina/fuente.php");

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="estilo/dashboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

</head>
<body>
    <h2 class="saludo">
        <?php
        // Verificar si la sesión tiene el email del usuario
        if (isset($_SESSION['email'])) {
            $email = $_SESSION['email']; // Recuperar el email de la sesión
        } else {
            // Si no hay sesión, redirigir al login
            header("Location: login.php");
            exit;
        }
        ?>

        <script>
        // Función para ocultar el mensaje después de 2 minutos
        setTimeout(function() {
            // Obtener el elemento del mensaje por su ID
            const mensaje = document.getElementById('mensaje');
            if (mensaje) {
                mensaje.style.display = 'none'; // Ocultar el mensaje
            }
        }, 2000); // 2 segundos
        </script>
    </h2>

    <!-- Barra lateral de navegación -->
    <div class="container">
    <?php
    include("partespagina/barralateral.php");
?>

        <!-- Contenedor principal -->
        <div class="main-content">
            <!-- Cabecera -->
            <header>
                <h2>Configuración</h2>
            </header>

            <!-- Área de widgets -->
            <div class="dashboard">
                <div class="cart-configuraciones">
                    <h3>Cambia tu Contraseña</h3>
                    <form method="post">
                    <label>Introduce tu Contraseña actual<br>
                        <input name='pasac' minlength="8" placeholder="Contraseña actual" type='password' size='20'>
                        </label>
                        <br><br>
                        <label>Introduce tu nueva Contraseña<br>
                        <input id="elpassword" name='clave' minlength="8" type='password' placeholder="Nueva contraseña" size='20'>
                        <svg id=clickme width=28 height=25 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M569.354 231.631C512.97 135.949 407.81 72 288 72 168.14 72 63.004 135.994 6.646 231.631a47.999 47.999 0 0 0 0 48.739C63.031 376.051 168.19 440 288 440c119.86 0 224.996-63.994 281.354-159.631a47.997 47.997 0 0 0 0-48.738zM288 392c-102.556 0-192.091-54.701-240-136 44.157-74.933 123.677-127.27 216.162-135.007C273.958 131.078 280 144.83 280 160c0 30.928-25.072 56-56 56s-56-25.072-56-56l.001-.042C157.794 179.043 152 200.844 152 224c0 75.111 60.889 136 136 136s136-60.889 136-136c0-31.031-10.4-59.629-27.895-82.515C451.704 164.638 498.009 205.106 528 256c-47.908 81.299-137.444 136-240 136z"/></svg>
                        </label>
                        <br><br>
                        <label>Confirmar tu nueva Contraseña<br>
                        <input name='clave2' id=elpassword type=password placeholder="Verificacion de la nueva"  minlength="8" size='20'>
                        <svg id=clickme width=28 height=25 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path d="M569.354 231.631C512.97 135.949 407.81 72 288 72 168.14 72 63.004 135.994 6.646 231.631a47.999 47.999 0 0 0 0 48.739C63.031 376.051 168.19 440 288 440c119.86 0 224.996-63.994 281.354-159.631a47.997 47.997 0 0 0 0-48.738zM288 392c-102.556 0-192.091-54.701-240-136 44.157-74.933 123.677-127.27 216.162-135.007C273.958 131.078 280 144.83 280 160c0 30.928-25.072 56-56 56s-56-25.072-56-56l.001-.042C157.794 179.043 152 200.844 152 224c0 75.111 60.889 136 136 136s136-60.889 136-136c0-31.031-10.4-59.629-27.895-82.515C451.704 164.638 498.009 205.106 528 256c-47.908 81.299-137.444 136-240 136z"/></svg>
                        </label>
                        <script>
                            jQuery('#clickme').on('click', function() {
                                jQuery('#elpassword').attr('type', function(index, attr) {
                                    return attr == 'text' ? 'password' : 'text';
                                })
                                })
                        </script>
                        <input type="submit" value="Cambiar contraseña" name="cambiarcontra">
                    </form>
                    <?php
                       if (isset($_POST['cambiarcontra'])) {
                        $contraactual = $_POST['pasac'];
                        $clavenueva = $_POST['clave'];
                        $clavenueva2 = $_POST['clave2'];
                    
                       
                        if ($contraactual === $contrabs) {
                            if ($clavenueva === $clavenueva2) {
                                $contrfinal = $clavenueva;
                                $conexion->query("UPDATE `usuarios` SET `contraseña` = '$contrfinal' WHERE `id` = $id");
                                echo '<div id="exito" class="exito">Se actualizó la contraseña</div>';
                            } else {
                                echo '<div id="error" class="error">No son iguales</div>';
                            }
                        } else {
                            echo '<div id="error" class="error">La contraseña no es igual a la actual</div>';
                        }
                    }
                    
                    ?>
            </div>
            <div class="cart-configuraciones">
                <h3>Cambio la fuente</h3>
                <form method="post">
                    <select name="fuente">
                        <option value="1">Arial</option>
                        <option value="2">Times New Roman</option>
                        <option value="3">Georgia</option>
                        <option value="4">Verdana</option>
                        <option value="5">Trebuchet MS</option>
                    </select>
                    <input type="submit" value="Cargar cambios" name="cam_form">
                    <?php
                    ?>
                </form>
            </div>
        </div>
        <br><br>
        <div class="cart-configuraciones">
                <h3>Cambiar de tema</h3>
                <form method="post">
                    <select name="tema">
                        <option value="1">Normal</option>
                        <option value="2">Oscuro</option>
                    </select>
                    <input type="submit" value="Cargar cambios" name="cam_temas">
                    <?php
                      include("partespagina/fuente.php");
                    ?>
                </form>
            </div>
            <script>
                setTimeout(function() {
                    const mensaje = document.getElementById('error');
                    if (mensaje) {
                        mensaje.style.display = 'none';
                    }
                    }, 2000);

                setTimeout(function(){
                    const mensaje = document.getElementById('exito');
                    if (mensaje) {
                        mensaje.style.display = 'none';
                    }
                }, 2000);
            </script>
    </div>
</body>
</html>
