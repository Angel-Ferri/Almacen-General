<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilo/estilo.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="estilo/img/logo.png" type="image/x-icon">
    <title>Inicio</title>
</head>
<body>
    <div class="conteiner">
        <?php include("partespagina/barrasinsession.php"); ?>

        <div class="contenido-inicio">
            <h1>Bienvenido a Tu Gestor de Almacén</h1>
            <p>Administra tus gastos y ganancias de manera sencilla.</p>

            <h2>INICIA SESIÓN para acceder al panel de control</h2>
            <h2>REGÍSTRATE para crear tu cuenta</h2>

            <div class="listabotoninicio">
                <a href="login.php" class="boton">INICIAR SESIÓN</a>
                <a href="registro.php" class="boton">REGISTRO</a>
            </div>
        </div>
    </div>

    <?php include("partespagina/barrainferior.php"); ?>
</body>
</html>
