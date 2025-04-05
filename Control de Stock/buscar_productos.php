<?php
include("bs/conexion.php");

$id = 4; // ID del usuario
$query = isset($_POST['query']) ? trim($_POST['query']) : '';

// Preparar la consulta
$stmt = $conexion->prepare("
SELECT 
        p.*, 
        pr.nombre AS nombre_proveedor 
    FROM productos p
    LEFT JOIN proveedores pr ON p.id_proveedor = pr.id 
    WHERE p.usuario_id = :id 
    AND (:query = '' OR p.producto LIKE :queryLike)
    ORDER BY p.id DESC
");


$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->bindValue(':query', $query, PDO::PARAM_STR);
$stmt->bindValue(':queryLike', '%' . $query . '%', PDO::PARAM_STR);
$stmt->execute();

// Mostrar resultados
if ($stmt->rowCount() > 0) {
    while ($producto = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "
            <tr>
                <td>{$producto['id']}</td>
                <td>{$producto['fecha']}</td>
                <td>{$producto['categoria']}</td>
                <td>{$producto['producto']}</td>
                <td>{$producto['nombre_proveedor']}</td>
                <td>{$producto['caracteristicas']}</td>
                <td>{$producto['caracteristicas']}</td>
                <td>{$producto['caracteristicas']}</td>
                <td>{$producto['cantidad']}</td>
                <td>{$producto['precio']}</td>
                <td>
                    <form method='post' id='formEliminar'>
                        <input type='hidden' name='eliminar_id' id='eliminar_id' value='{$producto['id']}'>
                        <input type='submit' value='Eliminar' class='btn-eliminar'>
                    </form>
                </td>
                <td>
                    <input type='button' class='editar-btn' data-id='{$producto['id']}' data-producto='{$producto['producto']}' data-categoria='{$producto['categoria']}' data-cantidad='{$producto['cantidad']}' data-precio='{$producto['precio']}' value='Editar'>
                </td>
            </tr>
        ";
    }
} else {
    echo "<tr><td colspan='12' class='sin-resultados'>No se encontraron resultados.</td></tr>";
}
?>
