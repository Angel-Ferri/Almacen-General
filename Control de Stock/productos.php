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
$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE email = :email");
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario) {
    $id = $usuario['id'];
    $_SESSION['usuario_id'] = $id;
}

// Trae la fuente de configuraciones
include("partespagina/fuente.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['eliminar_id'])) {
        $idEliminar = $_POST['eliminar_id'];
        // Ejecutar la eliminación en la base de datos
        $conexion->query("DELETE FROM `productos` WHERE `id` = '$idEliminar'");
        echo '<div class="exito"><p>Producto eliminado correctamente.</p></div>';
    }

    if (isset($_POST['Cargar_Producto_edita'])) {
        $editnombre = $_POST['nombre_edita'];
        $editcategoria = $_POST['categoria_edita'];
        $editcantidad = $_POST['cantidad_edita'];
        $editprecio = $_POST['precio_edita'];
        $editdni = $_POST['dni_carga_edita'];
        $idEditar = $_POST['id_edita'];
        $editarcarac = $_POST['caracteristicas_editar'];
        $editarparticularidad = $_POST['particularidad_editar'];
        $editaratributo = $_POST['atributos_editar'];
        $proeveedores_edita = $_POST['proveedores_edita'];

        $conexion->query("UPDATE `productos` SET 
            `producto` = '$editnombre', 
            `cantidad` = '$editcantidad', 
            `precio` = '$editprecio', 
            `dni` = '$editdni', 
            `categoria` = '$editcategoria',
            `caracteristicas` = '$editarcarac',
            `caracteristicas1` = '$editaratributo',
            `id_proveedor` = '$proeveedores_edita',
            `caracteristicas2` = '$editarparticularidad'
            WHERE `id` = '$idEditar'");

        echo '<div class="exito"><p>Editando producto con ID: ' . $idEditar . '</p></div>';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="estilo/dashboard.css">
</head>
<body>
<?php include("formulario/productos.php"); ?>
    <!-- Contenedor principal -->
    <div class="container">
        <!-- Barra lateral -->
        <?php include("partespagina/Barralateral.php"); ?>

        <div class="main-content">
            <header>
                <h2>Productos</h2>
            </header>
            <div class="dashboard">
                <!-- Tabla de productos -->
                <div class="tabla_pro">

                <div class="head_pro">
                <h2>Tabla de productos</h2>
                </div>    
                <form id="form-buscar">
                    <input type="hidden" name="formulario" value="buscador">
                    <input type="search" class="busca" id="buscador" name="busca" placeholder="Escribe para buscar productos...">
                </form>
                <table class="tabla-productos">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Fecha de carga</th>
                            <th>Categoría</th>
                            <th>Producto</th>
                            <th>Proveedor</th>
                            <th>Característica</th>
                            <th>Atributos</th>
                            <th>Particularidad</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th>Eliminar</th>
                            <th>Editar</th>
                        </tr>
                    </thead>
                    <tbody id="resultados">
                        <!-- Resultados aparecerán aquí -->
                    </tbody>
                </table>

                </div>
            </div>
        </div>
    </div>
    <!-- Botón para cargar producto -->
    <button id="openModal">Cargar producto</button>
</body>
</html>
<script>
        const buscador = document.getElementById("buscador");
        const resultados = document.getElementById("resultados");

        // Función para realizar la búsqueda
        const realizarBusqueda = (busqueda) => {
            fetch("buscar_productos.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: "query=" + encodeURIComponent(busqueda)
            })
            .then(response => response.text())
            .then(data => {
                resultados.innerHTML = data;
            })
            .catch(error => {
                console.error("Error en la búsqueda:", error);
                resultados.innerHTML = "<tr><td colspan='12' class='sin-resultados'>Error al procesar la búsqueda.</td></tr>";
            });
        };

        // Detectar cambios en el campo de búsqueda
        buscador.addEventListener("input", () => {
            realizarBusqueda(buscador.value.trim());
        });

        // Cargar todos los productos al cargar la página
        window.addEventListener("DOMContentLoaded", () => {
            realizarBusqueda("");
        });
</script>
