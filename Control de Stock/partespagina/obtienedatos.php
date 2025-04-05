<?php
session_start();
include("conexion.php"); // Asegúrate de incluir tu archivo de conexión a la base de datos

$id = $_SESSION['usuario_id']; // Asegúrate de obtener el ID del usuario de la sesión
$mes = $_POST['mes'];

$proim = $conexion->query("SELECT id, categoria, producto, cantidad, precio, DATE_FORMAT(fecha, '%m-%d') as fecha, caracteristicas, caracteristicas1, caracteristicas2, dni FROM productos WHERE usuario_id = '$id' AND DATE_FORMAT(fecha, '%Y-%m') = '$mes'");
$rows = [];
foreach ($proim as $value) {
    $rows[] = [$value['id'], $value['categoria'], $value['producto'], $value['cantidad'], $value['precio'], $value['fecha'], $value['caracteristicas'], $value['caracteristicas1'], $value['caracteristicas2'], $value['dni']];
}

echo json_encode($rows);
?>