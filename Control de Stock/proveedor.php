<?php
session_start();

// Verificar si la sesión tiene el email del usuario
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email']; // Recuperar el email de la sesión
} else {
    // Si no hay sesión, redirigir al login
    header("Location: login.php");
    exit;
}

//es la conexion
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

//trae la fuente de configuraciones
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
</head>
<body>
    <!-- Barra lateral de navegación -->
    <div class="container">
    <?php
    include("partespagina/barralateral.php");
?>

        <!-- Contenedor principal -->
        <div class="main-content">
            <!-- Cabecera -->
            <header>
                <h2>Proveedores</h2>
            </header>

            <!-- Área de widgets -->
            <div class="dashboard">
            <div class="tabla_pro">
                <table class="tabla-productos">
                    <thead>
                        <tr>
                            <th>Proveedor</th>
                            <th>Contacto</th>
                            <th>Telefono</th>
                            <th>Correo</th>
                            <th>Direccion</th>
                        </tr>
                    </thead>
                    <?php
                    $mues = $conexion->query("SELECT * FROM `proveedores` WHERE usuario_id = '$id' ");

                    foreach ($mues as $pro) {
                        echo'
                        <tr>
                            <td>'.$pro['nombre'].'</td>
                            <td>'.$pro['contacto'].'</td>
                            <td>'.$pro['telefono'].'</td>
                            <td>'.$pro['email'].'</td>
                            <td>'.$pro['direccion'].'</td>
                        </tr>      
                        ';
                    }
                ?>
        </div>
        <button id="openModal">Cargar Proveedor</button>

        <div id="modal" class="modal-proveedor" style="display: none;">
            <div class="modal-contenido">
            <span class="close-btn">&times;</span>
                <h2>Cargar un Proveedor</h2>
                <form method="post" class="formulario-modal">
                    <h3>Nombre del Proveedor</h3>
                    <input type="text" name="nombre_proveedor" required>
                    
                    <h3>Nombre del Contacto</h3>
                    <input type="text" name="nombre_contacto" required>
                    
                    <h3>Teléfono</h3>
                    <input type="text" name="telefono" required>
                    
                    <h3>Correo</h3>
                    <input type="email" name="correo" required>
                    
                    <h3>Dirección</h3>
                    <input type="text" name="direccion" required>
                    
                    <br><br>
                    <input type="submit" class="enviarprove" value="Guardar">
                </form>
            </div>
        </div>
        <script>
            // Variables
            const modal = document.getElementById("modal");
            const openModalBtn = document.getElementById("openModal");
            const closeModalBtn = document.querySelector(".close-btn");

            // Mostrar modal
            openModalBtn.addEventListener("click", () => {
                modal.style.display = "block";
            });

            // Cerrar modal
            closeModalBtn.addEventListener("click", () => {
                modal.style.display = "none";
            });

            // Cerrar modal al hacer clic fuera del contenido
            window.addEventListener("click", (e) => {
                if (e.target === modal) {
                    modal.style.display = "none";
                }
            });
        </script>

    </div>
        </div>
</body>
</html>
