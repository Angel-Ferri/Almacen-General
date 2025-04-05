<div id="modal" class="modal-proveedor" style="display: none;">
    <div class="modal-contenido">
        <span class="close-btn">&times;</span>
        <h2>Cargar un Gasto</h2>
        <form method="post" class="formulario-modal">
            <h3>Tipo de Gasto</h3>
            <select name="ingresooegreso" id="ingresooegreso" onchange="mostrarInputEspecificar()">
                <option value="Impuesto">Impuesto</option>
                <option value="Compradeproductos">Compra de productos</option>
                <option value="Sueldos">Sueldos</option>
                <option value="Otros">Otros</option>
            </select>
            <div id="especificar-container" style="display: none; margin-top: 10px;">
                <label for="especificar" id="especificar-label">Especifique:</label>
                <input type="text" id="especificar" name="especificar" placeholder="Describa aquí">
            </div>
        
            <h3>Cantidad</h3>
            <input type="number" name="cantidad" placeholder="Ej: 10" required>
            
            <h3>Precio</h3>
            <input type="number" name="precio" placeholder="Ej: 50.00" step="0.01" required>
            
            <h3>Características del gasto</h3>
            <input type="text" name="comentario" placeholder="Agrega la descripción del gasto" required>
            
            <br><br>
            <input type="submit" value="Cargar Gastos" class="enviarprove" name="Cargar_Gasto">
        </form>
    </div>
</div>

<?php
if (isset($_POST['Cargar_Gasto'])) {
    $ingresooegreso = $_POST['ingresooegreso'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $comentario = $_POST['comentario'];
    $fecha = date('Y-m-d');

    // Inicializar la variable para el tipo específico
    $tipo_especifico = '';

    // Determinar el tipo específico basado en la selección del usuario
    if (isset($_POST['tipo_impuesto'])) {
        $tipo_especifico = $_POST['tipo_impuesto'];
    } elseif (isset($_POST['tipo_producto'])) {
        $tipo_especifico = $_POST['tipo_producto'];
    } elseif (isset($_POST['tipo_sueldo'])) {
        $tipo_especifico = $_POST['tipo_sueldo'];
    } elseif (isset($_POST['tipo_gasto'])) {
        $tipo_especifico = $_POST['tipo_gasto'];
    }

    // Verificar si se ha especificado un tipo específico
    if (!empty($tipo_especifico)) {
        $conexion->query("INSERT INTO movimientos (usuario_id, tipo, cantidad, gasto, fecha, comentario) 
        VALUES ('$id', '$ingresooegreso', '$cantidad', '$precio', '$fecha', '$tipo_especifico')");
        echo "<div class='exito'>Se cargó el gasto</div>";
    } else {
        echo "<div class='error'>No se pudo cargar el gasto</div>";
    }
}
?>
<!-- Botón para abrir el modal -->
<button id="openModal">Agregar Producto</button>

<script>
    function mostrarInputEspecificar() {
        const select = document.getElementById("ingresooegreso");
        const especificarContainer = document.getElementById("especificar-container");
        const especificarLabel = document.getElementById("especificar-label");
        const especificarInput = document.getElementById("especificar");

        // Mostrar el input solo si la opción seleccionada es "Otros", "Compra de productos", "Sueldos" o "Impuesto"
        if (select.value === "Otros" || select.value === "Compradeproductos" || select.value === "Sueldos" || select.value === "Impuesto") {
            especificarContainer.style.display = "block"; // Mostrar el input
            // Cambiar el texto del label y el name del input según el valor seleccionado
            if (select.value === "Impuesto") {
                especificarLabel.textContent = "Especifique el tipo de impuesto:";
                especificarInput.name = "tipo_impuesto";
            } else if (select.value === "Compradeproductos") {
                especificarLabel.textContent = "Especifique el tipo de producto:";
                especificarInput.name = "tipo_producto";
            } else if (select.value === "Sueldos") {
                especificarLabel.textContent = "Especifique el tipo de sueldo:";
                especificarInput.name = "tipo_sueldo";
            } else if (select.value === "Otros") {
                especificarLabel.textContent = "Especifique el tipo de gasto:";
                especificarInput.name = "tipo_gasto";
            }
        } else {
            especificarContainer.style.display = "none"; // Ocultar el input
        }
    }

    // Para el botón agregar
    const openModalBtn = document.getElementById('openModal');
    const modal = document.getElementById('modal');
    const closeModalBtn = document.querySelector('.close-btn');
    openModalBtn.addEventListener('click', () => {
        modal.style.display = 'block';
    });
    closeModalBtn.addEventListener('click', () => {
        modal.style.display = 'none';
    });
    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
</script>