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
//Para el boton eliminar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar_id'])) {
        $idEliminar = $_POST['eliminar_id'];
        // Ejecutar la eliminación en la base de datos
        $stmt = $conexion->prepare("DELETE FROM `movimientos` WHERE `id` = :id");
        $stmt->bindParam(':id', $idEliminar, PDO::PARAM_INT);
        $stmt->execute();
        echo "<p>Producto eliminado correctamente.</p>";
    }

}
?>

<?php
    include("formulario/perdida.php");
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
     <!-- Modal para cargar gasto -->

     
    <div class="main-content">
        <!-- Cabecera -->
        <header>
            <h2>Ingrese sus Gastos</h2>
        </header>

        <!-- Área de widgets -->
        <div class="dashboard">
        <div class="tabla_pro">
            <div class="conte_perdida">
                <div class="tabla_pro">
                    <div class="head_pro">
                        <h2>Tabla de Gastos</h2>
                    </div>
                    <table class="tabla-productos">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Ingreso / Egreso</th>
                                <th>Cantidad(Unidad)</th>
                                <th>Costo</th>
                                <th>Descripcion</th>
                                <th>Fecha y hora de carga</th>
                                <th>Eliminar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                         $stmt = $conexion->prepare("
                         SELECT m.id, m.producto_id, p.producto, m.tipo, m.cantidad, m.gasto, m.fecha, m.comentario 
                         FROM movimientos m
                         JOIN productos p ON m.producto_id = p.id
                         WHERE m.usuario_id = :id
                         ORDER BY m.fecha DESC
                     ");
                     $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                     $stmt->execute();
                     $gastos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                     
                     // Mostrar los datos de los movimientos
                     if ($stmt->rowCount() > 0) {
                        foreach ($gastos as $datospro) {
                            echo "
                            <tr>
                                <td>{$datospro['producto']}</td>
                                <td>{$datospro['tipo']}</td>
                                <td>{$datospro['cantidad']}</td>
                                <td>{$datospro['gasto']}</td>
                                <td>{$datospro['comentario']}</td>
                                <td>{$datospro['fecha']}</td>
                                <td>
                                    <form method='post' id='formEliminar_{$datospro['id']}'>
                                        <input type='hidden' name='eliminar_id' value='{$datospro['id']}'>
                                        <input type='submit' value='Eliminar' class='btn-eliminar'>
                                    </form>
                                </td>
                            </tr>";
                        }              
                    }
                    else{
                        echo "<tr><td colspan='12'>No hay productos registrados.</td></tr>";
                    }
                        ?>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
