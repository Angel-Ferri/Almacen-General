<?php
session_start();

include("bs/conexion.php");

// Verificar si la sesión tiene el email del usuario
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email']; // Recuperar el email de la sesión
} else {
    // Si no hay sesión, redirigir al login
    header("Location: login.php");
    exit;
}

// Consulta para obtener el ID del usuario usando el email
$stmt = $conexion->query("SELECT id FROM usuarios WHERE email = '$email'");
if ($stmt) {
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($usuario) {
        $id = $usuario['id']; // Guardar el ID en la variable $id
        $_SESSION['usuario_id'] = $id; // Toma el id del usuario
    }
}
// Trae la fuente de configuraciones
include("partespagina/fuente.php");
// <!-- Formulario para cargar la venta -->

include("formulario/ventas.php");
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
    <div class="container">
        <!-- Barra lateral de navegación -->
        <?php
    include("partespagina/barralateral.php");
?>

        <!-- Contenedor principal -->
        <div class="main-content">
            <header>
                <h2>Ventas</h2>
            </header>
            <div class="dashboard">
                <!-- Tabla de ventas -->
                <div class="tabla-ventas">
                    <h2>Tabla de Ventas</h2>
                <table class="tabla-productos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha</th>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Consulta para obtener las ventas del usuario
                        $sql = "SELECT 
                                    v.id, 
                                    v.fecha, 
                                    p.producto, 
                                    v.cantidad, 
                                    v.precio, 
                                    (v.cantidad * v.precio) AS total
                                FROM ventas v
                                INNER JOIN productos p 
                                    ON v.producto_id = p.id 
                                    AND v.usuario_id = p.usuario_id
                                ORDER BY v.precio DESC";

                        $stmt = $conexion->prepare($sql);
                        $stmt->execute();
                        $ventas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach ($ventas as $venta) {
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($venta['id']) . "</td>";
                            echo "<td>" . htmlspecialchars($venta['fecha']) . "</td>";
                            echo "<td>" . htmlspecialchars($venta['producto']) . "</td>";
                            echo "<td>" . htmlspecialchars($venta['cantidad']) . "</td>";
                            echo "<td>" . htmlspecialchars($venta['precio']) . "</td>";
                            
                            //Boton de eliminar registro
                        echo '<td>
                                    <form method="post" id="formEliminar_' . htmlspecialchars($venta['id']) . '">
                                        <input type="hidden" name="eliminar_id" value="' . htmlspecialchars($venta['id']) . '">
                                        <input type="submit" value="Eliminar" class="btn-eliminar">
                                    </form>
                                </td>';
                        echo "</tr>";
                        }
                        // Verifica si el formulario ha sido enviado para eliminar un registro
                        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_id'])) {
                            $id = intval($_POST['eliminar_id']); // Asegúrate de convertir a entero para seguridad

                            // Preparar y ejecutar la consulta DELETE
                            $stmt = $conexion->prepare("DELETE FROM ventas WHERE id = :id");
                            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

                            if ($stmt->execute()) {
                                echo "<div class='exito' id='exito'>Venta con ID $id eliminada exitosamente.</div>";
                                exit;
                            } else {
                                echo "<div class='error' id='error'>Error al intentar eliminar la venta.</div>";
                            }
                        }

                        ?>
                    </tbody>
                </div>
            </div>   
         </div>
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
</body>
</html>
