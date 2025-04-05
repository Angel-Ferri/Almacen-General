<?php
if (isset($_POST['buscar'])) {
    $campos = ['fechabus' => 'fecha', 'categoriabus' => 'categoria', 'productobus' => 'producto', 'proveedorbus' => 'proveedor'];
    $parametros = [];
    $filtros = [];

    foreach ($campos as $input => $columna) {
        if (!empty($_POST[$input])) {
            $filtros[] = "$columna LIKE :$input";
            $parametros[":$input"] = ($columna == 'fecha') ? $_POST[$input] : '%' . $_POST[$input] . '%';
        }
    }

    $query = "SELECT * FROM productos" . (count($filtros) ? " WHERE " . implode(' AND ', $filtros) : "");
    
    $stmt = $conexion->prepare($query);
    $stmt->execute($parametros);

    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($resultados) {
        foreach ($resultados as $fila) {
            echo "Producto: {$fila['producto']}<br>Categoría: {$fila['categoria']}<br>Proveedor: {$fila['proveedor']}<br>Fecha: {$fila['fecha']}<br><br>";
        }
    } else {
        echo "No se encontraron resultados.";
    }
}
?>

