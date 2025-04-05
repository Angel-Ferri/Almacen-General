<div id="modal" class="modal-ventas" style="display: none;">
    <div class="formuventa">
        <div class="modal-content">
            <span class="close-btn1">&times;</span>
            <h2>Ventas</h2>
                <form method="post" class="form-model">                
                    <div class="parte1">
                        <h2>Cargar ventas</h2>
                        <h2>Selecciona un producto</h2>
                        <select name="productos" id="producto" class="seleccargarventa" onchange="actualizarDatos()">
                            <option value="">-- Seleccionar producto --</option>
                            <?php
                            $productos = $conexion->query("SELECT * FROM `productos` WHERE usuario_id = '$id'");
                            foreach ($productos as $producto) {
                                $data = htmlspecialchars(json_encode([
                                    'id' => $producto['id'],
                                    'cantidad' => $producto['cantidad'],
                                    'precio' => $producto['precio']
                                ]), ENT_QUOTES, 'UTF-8');
                                echo "<option value='{$data}'>{$producto['producto']}</option>";
                            }
                            ?>
                        </select>
                        <h2>Cantidad</h2>
                        <p id="cantidadinfo">De este producto tienes cargado una cantidad de {cantidad}</p>
                        <input type="number" id="cantidad" name="cantidad" min="1" disabled>
                        
                    </div>
                    <div class="parte2">
                        <h2>Precio</h2>
                        <p id="precioInfo">El precio de este producto es {precioproducto}</p>
                        <input type="number" id="precio" name="precioproducto" disabled>

                        <h2>Detalles de la compra</h2>
                        <p>No es obligatorio</p>
                        <input type="text" name="comentario">
                        <br><br>
                        <input type="submit" value="Cargar venta" name="cargarventa">
                    </div>
                </form>
        </div>
    </div> <!-- Aquí se cierra correctamente la etiqueta div -->
</div>
<button id="openModal">Agregar Producto</button>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('modal');
        const openModalBtn = document.getElementById('openModal');
        const closeModalBtn = document.querySelector('.close-btn1');

        openModalBtn.addEventListener('click', () => {
            modal.style.display = 'block';
        });

        closeModalBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });

        window.addEventListener('click', (event) => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });

        actualizarDatos = () => {
            const select = document.getElementById('producto');
            const selectedOption = select.options[select.selectedIndex].value;

            if (selectedOption) {
                try {
                    const producto = JSON.parse(selectedOption);
                    document.getElementById('cantidad').max = producto.cantidad;
                    document.getElementById('cantidad').disabled = false;
                    document.getElementById('precio').value = producto.precio;
                    document.getElementById('precio').disabled = false;

                    document.getElementById('cantidadinfo').textContent = `De este producto tienes ${producto.cantidad}`;
                    document.getElementById('precioInfo').textContent = `El precio de este producto es ${producto.precio}`;
                } catch (error) {
                    console.error('Error al procesar la selección:', error);
                }
            } else {
                document.getElementById('cantidad').disabled = true;
                document.getElementById('precio').disabled = true;

                document.getElementById('cantidadinfo').textContent = 'De este producto tienes cargado una cantidad de {cantidad}';
                document.getElementById('precioInfo').textContent = 'El precio de este producto es {precioproducto}';
            }
        };
    });
</script>

<?php
if (isset($_POST['cargarventa'])) {
    $producto = $_POST['productos'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precioproducto'];
    $comentario = $_POST['comentario'] ?? null;

    if ($producto && $cantidad && $precio) {
        $productoData = json_decode($producto, true);
        if ($productoData && isset($productoData['id'])) {
            $id_producto = $productoData['id'];
            try {
                $stmt = $conexion->prepare("SELECT cantidad FROM `productos` WHERE id = :id_producto AND usuario_id = :id");
                $stmt->execute([':id_producto' => $id_producto, ':id' => $id]);
                $cantidadvieja = $stmt->fetchColumn();

                if ($cantidadvieja !== false && $cantidadvieja >= $cantidad) {
                    $stmt = $conexion->prepare("INSERT INTO `ventas` (`producto_id`, `usuario_id`, `cantidad`, `precio`, `comentario`) 
                                                VALUES (:id_producto, :id, :cantidad, :precio, :comentario)");
                    $stmt->execute([
                        ':id_producto' => $id_producto,
                        ':id' => $id,
                        ':cantidad' => $cantidad,
                        ':precio' => $precio,
                        ':comentario' => $comentario
                    ]);

                    $queda = (float)$cantidadvieja - (float)$cantidad;
                    $stmt = $conexion->prepare("UPDATE `productos` SET `cantidad` = :queda WHERE usuario_id = :id AND id = :id_producto");
                    $stmt->execute([':queda' => $queda, ':id' => $id, ':id_producto' => $id_producto]);

                    echo '<p class="exito" id="exito">Venta cargada con éxito</p>';
                } else {
                    echo '<p class="error" id="error">Error: No tienes suficiente stock disponible.</p>';
                }
            } catch (PDOException $e) {
                echo '<p class="error" id="error">Error al procesar la venta: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES) . '</p>';
            }
        } else {
            echo '<p class="error" id="error">Error: Producto inválido seleccionado.</p>';
        }
    } else {
        echo '<p class="error" id="error">Error: Por favor, completa todos los campos.</p>';
    }
}
?>