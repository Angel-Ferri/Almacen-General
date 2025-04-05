<?php
include("bs/conexion.php"); // Asegúrate de incluir tu archivo de conexión a la base de datos

session_start();

// Verificar si la sesión tiene el email del usuario
if (isset($_SESSION['email'])) {
    $email = $_SESSION['email']; // Recuperar el email de la sesión
    $id = $_SESSION['usuario_id']; // Recuperar el ID del usuario de la sesión
} else {
    // Si no hay sesión, redirigir al login
    header("Location: login.php");
    exit;
}

// Manejar la solicitud AJAX para obtener los datos
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['mes'])) {
    $mes = $_POST['mes'];

    // Consulta para productos
    $stmtProductos = $conexion->prepare(
        "SELECT id, categoria, producto, cantidad, precio, DATE_FORMAT(fecha, '%m-%d') as fecha, 
                caracteristicas, caracteristicas1, caracteristicas2, dni 
         FROM productos 
         WHERE usuario_id = ? AND DATE_FORMAT(fecha, '%Y-%m') = ?"
    );
    $stmtProductos->execute([$id, $mes]);
    $productos = $stmtProductos->fetchAll(PDO::FETCH_NUM);

    // Consulta para movimientos
    $stmtMovimientos = $conexion->prepare(
        "SELECT id, tipo, cantidad, gasto, DATE_FORMAT(fecha, '%m-%d') as fecha, comentario 
         FROM movimientos 
         WHERE usuario_id = ? AND DATE_FORMAT(fecha, '%Y-%m') = ?"
    );
    $stmtMovimientos->execute([$id, $mes]);
    $movimientos = $stmtMovimientos->fetchAll(PDO::FETCH_NUM);

    // Consulta para proveedores
    $stmtProveedores = $conexion->prepare(
        "SELECT id, nombre, contacto, telefono, email, direccion 
         FROM proveedores 
         WHERE usuario_id = ?"
    );
    $stmtProveedores->execute([$id]);
    $proveedores = $stmtProveedores->fetchAll(PDO::FETCH_NUM);

    echo json_encode(['productos' => $productos, 'movimientos' => $movimientos, 'proveedores' => $proveedores]);
    exit;
}

include("partespagina/fuente.php");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="estilo/dashboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.13/jspdf.plugin.autotable.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Barra lateral -->
        <?php include("partespagina/Barralateral.php"); ?>

        <div class="main-content">
            <header>
                <h2>Imprime</h2>
                <h4>Selecciona el registro que deseas descargar, es por mes</h4>
            </header>
            <div class="dashboard">
                <div class="imprimir">
                    <?php
                    $stmtMeses = $conexion->prepare(
                        "SELECT DISTINCT DATE_FORMAT(fecha, '%Y-%m') as mes 
                         FROM productos 
                         WHERE usuario_id = ?"
                    );
                    $stmtMeses->execute([$id]);
                    $meses = $stmtMeses->fetchAll(PDO::FETCH_ASSOC);

                    if ($meses) {
                        foreach ($meses as $value) {
                            echo '<h3>' . htmlspecialchars($value['mes']) . '</h3>';
                            echo '<div class="info">
                                    <a href="javascript:descargarpdf(\'' . htmlspecialchars($value['mes']) . '\')" class="flex-container">
                                        <img src="estilo/img/registro.png" alt="Registro">
                                        <h5>Registro General</h5>
                                    </a>
                                  </div>';
                        }
                    } else {
                        echo '<p>No hay registros disponibles.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
        function descargarpdf(mes) {
            $.ajax({
                url: '', // Enviar la solicitud al mismo archivo
                type: 'POST',
                data: { mes: mes },
                success: function(response) {
                    const { jsPDF } = window.jspdf;
                    var doc = new jsPDF();

                    try {
                        var data = JSON.parse(response);

                        if (data.productos && data.productos.length > 0) {
                            var columnsProductos = ["Id", "Categoria", "Producto", "Cantidad", "Precio", "Fecha", "Caracteristicas", "Caracteristicas1", "Caracteristicas2", "DNI"];
                            tituloproducto = ["Registro de Productos"];
                            doc.autoTable({
                                head: [tituloproducto],
                                styles: { fillColor: [255, 150, 150] } 
                            });
                            doc.autoTable({
                                head: [columnsProductos],
                                body: data.productos,
                                startY: doc.lastAutoTable.finalY + 5,
                                headStyles: { fillColor: [255, 150, 150] },
                                bodyStyles: { fillColor: [255, 230, 230] } 
                            });
                        }

                        if (data.movimientos && data.movimientos.length > 0) {
                            var columnsMovimientos = ["Id", "Tipo", "Cantidad", "Gasto", "Fecha", "Comentario"];
                            titulogasto = ["Registro de Gastos"];
                            doc.autoTable({
                                head: [titulogasto],
                                styles: { fillColor: [150, 255, 150] }
                            });
                            doc.autoTable({
                                head: [columnsMovimientos],
                                body: data.movimientos,
                                startY: doc.lastAutoTable.finalY + 10,
                                headStyles: { fillColor: [150, 255, 150] }, 
                                bodyStyles: { fillColor: [230, 255, 230] } 
                            });
                        }

                        if (data.proveedores && data.proveedores.length > 0) {
                            var columnsProveedores = ["Id", "Nombre", "Contacto", "Teléfono", "Email", "Dirección"];
                            tituloProveedores = ["Registro de Proveedores"];
                            doc.autoTable({
                                head: [tituloProveedores],
                                styles: { fillColor: [150, 150, 255] } 
                            });
                            doc.autoTable({
                                head: [columnsProveedores],
                                body: data.proveedores,
                                startY: doc.lastAutoTable.finalY + 10,
                                headStyles: { fillColor: [150, 150, 255] }, 
                                bodyStyles: { fillColor: [230, 230, 255] }
                            });
                        }

                        doc.save('Registro_del_mes_' + mes + '.pdf');
                    } catch (error) {
                        alert('Error al procesar los datos del servidor.');
                    }
                },
                error: function(xhr, status, error) {
                    alert("Ocurrió un error al generar el PDF: " + error);
                }
            });
        }

    </script>
</body>
</html>
