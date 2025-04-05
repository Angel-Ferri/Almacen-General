<?php
include("bs/conexion.php");

session_start();

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
    <h2 class="saludo">
        <?php
        // Verificar si la sesión tiene el email del usuario
            $email = $_SESSION['email']; // Recuperar el email de la sesión
            echo "<div id='mensaje'>Bienvenido, $email</div>";
        
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
                <h2>Bienvenido al Dashboard</h2>
            </header>

            <!-- Área de widgets -->
            <div class="dashboard">
                <div class="card">
                    <h3>N° Proveedores</h3>
                    <?php
                    // Ejemplo de consulta usando PDO
                    try {
                        $stmt = $conexion->query("SELECT COUNT(*) FROM proveedores WHERE usuario_id = '$id'");
                        $count = $stmt->fetchColumn();
                        echo "<p>$count</p>";
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                    ?>
                </div>

                <div class="card">
                    <h3>Ventas hoy</h3>
                    <p>
                    <?php
                        // Trae el día actual en formato 'YYYY-MM-DD'
                        $hoy = date("Y-m-d"); 

                        // Consulta para contar los registros que cumplen las condiciones
                        $trigo = $conexion->query("SELECT COUNT(id) FROM `ventas` WHERE usuario_id = '$id'AND fecha = CURDATE();");
                        $contador = $trigo->fetchColumn(); // Obtiene el resultado de la consulta

                        // Mostrar el contador de ventas del día
                        echo $contador;
                    ?>

                    </p>
                </div>

                <div class="card">
                    <h3>Ventas mensuales</h3>
                    <p>
                    <?php
                    //trae los datos con la caracteristica de que sea del presente mes
                        $mesActual = date("m"); // Obtener el mes actual
                        $trigomes = $conexion->query("SELECT SUM(precio) AS total_precio FROM `ventas` WHERE usuario_id = '$id' AND MONTH(fecha) = '$mesActual';");
                        $contadormes = $trigomes->fetchColumn();
                        echo $contadormes;
                    ?>
                        </p>
                </div>
                <div class="card">
                    <h3>Ingreso anuales</h3>
                    <p>
                    <?php
                    //trae datos del presente año
                        $anioActual = date("Y"); // Obtener el año actual
                        $trigoanio = $conexion->query("SELECT SUM(precio) AS total_precio FROM `ventas` WHERE usuario_id = '$id' AND YEAR(fecha) = '$anioActual';");
                        $contadoranio = $trigoanio->fetchColumn();
                        echo $contadoranio;
                    ?>

                    </p>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="graficos">
                <div class="card-graf">
                    <h2>Cantidad por Producto</h2>
                    <canvas id="graficoProducto" width="400" height="200"></canvas>
                    </div>
                <div class="card-graf">
                    <h2>Cantidad por Categoría</h2>
                    <canvas id="graficotortacategoria" width="300px" height="150px"></canvas>
                </div>
            </div>
            <div class="graficos">
                <div class="card-graf">
                    <h2>Ingresos Anuales (por mes)</h2>
                    <!-- Graficos de barras -->
                    <canvas id="barChart" width="500px" height="450px"></canvas>
                </div>
                <div class="card-graf">
                    <h2>Cantidad por Ventas</h2>
                    <!-- Graficos de ventas -->
                    <canvas id="graficoVentas" width="500px" height="450px"></canvas>
                </div>

            </div>
            <?php include('graficos/graficostotal.php'); ?>

        </div>
    </div>
</body>
</html>
