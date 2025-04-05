<?php
// Consulta para obtener los productos y sus categorías
$trigopro = $conexion->query("SELECT producto, categoria, cantidad FROM `productos` WHERE usuario_id = '$id'");

$productos_array = [];
$productos_cantidades_array = [];
$categorias_array = [];
$categorias_cantidades_array = [];
$ventas_labels = [];
$ventas_data = [];
$ingresos_data = [];



// Llenamos los arrays con los datos de productos y categorías
while ($producto = $trigopro->fetch(PDO::FETCH_ASSOC)) {
    // Productos y cantidades
    $productos_array[] = $producto['producto'];  // Nombre del producto
    $productos_cantidades_array[] = $producto['cantidad'];

    // Categorías y sus cantidades
    if (!in_array($producto['categoria'], $categorias_array)) {
        $categorias_array[] = $producto['categoria'];
        $categorias_cantidades_array[] = 0;  // Inicializamos la cantidad en 0
    }

    // Incrementamos la cantidad de productos por categoría
    $index = array_search($producto['categoria'], $categorias_array);
    $categorias_cantidades_array[$index] += $producto['cantidad'];
}

// Consulta para las ventas (sin egresos)
$ventas_query = $conexion->query("
    SELECT 
        DATE_FORMAT(v.fecha, '%Y-%m') AS mes_anio,
        SUM(v.precio) AS total_ventas,
        (SELECT SUM(m.gasto) 
         FROM movimientos m 
         WHERE m.usuario_id = '$id' 
           AND m.tipo = 'ingreso' 
           AND DATE_FORMAT(m.fecha, '%Y-%m') = DATE_FORMAT(v.fecha, '%Y-%m')) AS total_ingresos
    FROM ventas v
    WHERE v.usuario_id = '$id'
    GROUP BY mes_anio
");

while ($venta = $ventas_query->fetch(PDO::FETCH_ASSOC)) {
    $ventas_labels[] = $venta['mes_anio'];
    $ventas_data[] = $venta['total_ventas'] ?? 0;
    $ingresos_data[] = $venta['total_ingresos'] ?? 0;
}

// Convertir arrays PHP a JSON para uso en JavaScript
$productos_array_json = json_encode($productos_array);
$productos_cantidades_json = json_encode($productos_cantidades_array);
$categorias_array_json = json_encode($categorias_array);
$categorias_cantidades_json = json_encode($categorias_cantidades_array);
$ventas_labels_json = json_encode($ventas_labels);
$ventas_data_json = json_encode($ventas_data);
$ingresos_data_json = json_encode($ingresos_data);


?>
<script>
// Datos convertidos desde PHP
const productosArray = <?php echo $productos_array_json; ?>;
const productosCantidades = <?php echo $productos_cantidades_json; ?>;
const categoriasArray = <?php echo $categorias_array_json; ?>;
const categoriasCantidades = <?php echo $categorias_cantidades_json; ?>;
const ventasLabels = <?php echo $ventas_labels_json; ?>;
const ventasData = <?php echo $ventas_data_json; ?>;
const ingresosData = <?php echo $ingresos_data_json; ?>;


// Configuración del primer gráfico (Cantidad por Producto)
const ctxProducto = document.getElementById('graficoProducto').getContext('2d');
const graficoProducto = new Chart(ctxProducto, {
    type: 'bar',
    data: {
        labels: productosArray,
        datasets: [{
            label: 'Cantidad',
            data: productosCantidades,
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Configuración del segundo gráfico (Cantidad por Categoría)
const ctxCategoria = document.getElementById('graficotortacategoria').getContext('2d');
const graficotortacategoria = new Chart(ctxCategoria, {
    type: 'pie',
    data: {
        labels: categoriasArray,
        datasets: [{
            label: 'Categorías',
            data: categoriasCantidades,
            backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)'],
            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)'],
            borderWidth: 1
        }]
    },
    options: {
        responsive: true
    }
});

// Configuración del tercer gráfico (Ingresos Anuales por Mes)
const ctxBarChart = document.getElementById('barChart').getContext('2d');
const barChart = new Chart(ctxBarChart, {
    type: 'bar',
    data: {
        labels: ventasLabels,
        datasets: [{
            label: 'Ingresos ($)',
            data: ventasData,
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Configuración del cuarto gráfico (Cantidad por Ventas)
const ctxVentas = document.getElementById('graficoVentas').getContext('2d');
const graficoVentas = new Chart(ctxVentas, {
    type: 'bar', // Gráfico principal de tipo barra
    data: {
        labels: ventasLabels,
        datasets: [
            {
                label: 'Total de Ventas',
                data: ventasData,
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                borderColor: 'rgba(255, 159, 64, 1)',
                borderWidth: 1
            },
            {
                label: 'Total de Gastos',
                data: ingresosData,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
