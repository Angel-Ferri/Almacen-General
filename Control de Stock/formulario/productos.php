<!-- Formulario para editar los productos -->
<!-- Modal para editar productos -->
<div id="modal1" class="modal">
    <div class="modal-content">
        <span class="close-btn1">&times;</span>
        <h2>Edita un producto</h2>
        <form method="post" class="form-model">
            <div class="parte1">
                <h3>Nombre de producto</h3>
                <input type="text" name="nombre_edita" id="nombre_edita" placeholder="Ej: Manzanas" required>
                <h3>Categoría</h3>
                <input type="text" name="categoria_edita" id="categoria_edita" placeholder="Ej: Limpieza" required>
                <h3>Cantidad</h3>
                <input type="number" name="cantidad_edita" id="cantidad_edita" placeholder="Ej: 10" required>
                <h3>Precio</h3>
                <input type="number" name="precio_edita" id="precio_edita" placeholder="Ej: 50.00" step="0.01" required>
                <h3>Proveedor</h3>
                <select name="proveedores_edita" id="">
                    <?php
                        $proveedor = $conexion->query("SELECT * FROM `proveedores` WHERE usuario_id = '$id' ");

                        if ($proveedor->rowCount() > 0) {
                            foreach ($proveedor as $prove) {
                                echo "<option value=". $prove['id'] .">" . $prove['nombre'] . "</option>";
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="parte2">
                <h3>Caracteristicas</h3>
                <input type="text" name="caracteristicas_editar" placeholder="Ej: es dulce" step="0.01" required>
                <h3>Atributos</h3>
                <input type="text" name="atributos_editar" placeholder="Ej: Mide 20cm" step="0.01" required>
                <h3>Particularidad</h3>
                <input type="text" name="particularidad_editar" placeholder="Ej: es roja" step="0.01" required>
                <h3>DNI del que lo carga</h3>
                <input type="number" name="dni_carga_edita" id="dni_carga_edita" placeholder="DNI SIN LOS - O ." required>
                <input type="hidden" name="id_edita" id="id_edita">
                <br><br>
                <input type="submit" value="Editar Producto" class="submit-btn" name="Cargar_Producto_edita">
            </div>
        </form>
    </div>
</div>

<!-- Modal para cargar productos -->
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close-btn">&times;</span>
        <h2>Cargar un producto</h2>
        <form method="post" class="form-model">
            <div class="parte1">
                <h3>Nombre de producto</h3>
                <input type="text" name="nombre_producto" placeholder="Ej: Manzanas" required>
                <h3>Categoría</h3>
                <input type="text" name="categoria" placeholder="Ej: Limpieza" required>
                <h3>Cantidad</h3>
                <input type="number" name="cantidad" placeholder="Ej: 10" required>
                <h3>Precio</h3>
                <input type="number" name="precio" placeholder="Ej: 50.00" step="0.01" required>
                <h3>Proveedor</h3>
                <select name="proveedores" id="">
                    <?php
                        $proveedor = $conexion->query("SELECT * FROM `proveedores` WHERE usuario_id = '$id' ");

                        if ($proveedor->rowCount() > 0) {
                            foreach ($proveedor as $prove) {
                                echo "<option value=" . $prove['id'] . ">" . $prove['nombre'] . "</option>";
                            }
                        }
                    ?>
                </select>
            </div>
            <div class="parte2">
                <h3>Caracteristicas</h3>
                <input type="text" name="caracteristicas" placeholder="Ej: Es dulce" step="0.01" required>
                <h3>Atributos</h3>
                <input type="text" name="atributos" placeholder="Ej: Mide 20 cm" step="0.01">
                <h3>Particularidad</h3>
                <input type="text" name="particularidad" placeholder="Ej: Es azul" step="0.01">
                <h3>DNI del que lo carga</h3>
                <input type="number" name="dni_carga" placeholder="DNI SIN LOS - O .">
                <br><br>
                <input type="submit" value="Cargar Producto" class="submit-btn" name="Cargar_Producto">
            </div>
        </form>
    </div>
</div>

<?php
    if (isset($_POST['Cargar_Producto'])) {
        $nombre_producto = $_POST['nombre_producto'];
        $categoria = $_POST['categoria'];
        $cantidad = $_POST['cantidad'];
        $precio = $_POST['precio'];
        $proveedores = $_POST['proveedores'];
        $caracteristicas = isset($_POST['caracteristicas']) ? $_POST['caracteristicas'] : 'No cargado';
        $atributos = isset($_POST['atributos']) ? $_POST['atributos'] : 'No cargado';
        $particularidad = isset($_POST['particularidad']) ? $_POST['particularidad'] : 'No cargado';
        $dni_carga = isset($_POST['dni_carga']) ? $_POST['dni_carga'] : 'No cargado';
        
        $fecha = date("Y-m-d"); 
        $hora = date("H:i");
        
        //Sube todo a la bs
        $conexion->query("INSERT INTO `productos` (`id`, `producto`, `usuario_id`, `id_proveedor`, `cantidad`, `precio`, `fecha`, `hora`, `dni`, `categoria`, `caracteristicas`, `caracteristicas1`, `caracteristicas2`) 
        VALUES (NULL, '$nombre_producto', '$id', '$proveedores', '$cantidad', '$precio', '$fecha', '$hora', '$dni_carga', '$categoria', '$caracteristicas', '$atributos', '$particularidad')");    
    }
?>
                
<!-- JS HACE FUNCIONAR TODO -->
<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Modal de carga de producto
        const modal = document.getElementById("modal");
        const openModalBtn = document.getElementById("openModal");
        const closeModalBtn = document.querySelector(".close-btn");

        openModalBtn.addEventListener("click", () => {
            modal.style.display = "flex";
        });

        closeModalBtn.addEventListener("click", () => {
            modal.style.display = "none";
        });

        window.addEventListener("click", (event) => {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });

        // Modal de edición de producto
        const modal1 = document.getElementById("modal1");
        const closeModal1Btn = document.querySelector(".close-btn1");

        document.querySelectorAll(".editar-btn").forEach((button) => {
            button.addEventListener("click", () => {
                const productoData = button.dataset;

                document.getElementById("nombre_edita").value = productoData.producto;
                document.getElementById("categoria_edita").value = productoData.categoria;
                document.getElementById("cantidad_edita").value = productoData.cantidad;
                document.getElementById("precio_edita").value = productoData.precio;
                document.getElementById("dni_carga_edita").value = productoData.dni;
                document.getElementById("id_edita").value = productoData.id;

                modal1.style.display = "flex";
            });
        });

        closeModal1Btn.addEventListener("click", () => {
            modal1.style.display = "none";
        });

        window.addEventListener("click", (event) => {
            if (event.target === modal1) {
                modal1.style.display = "none";
            }
        });
    });
</script>