<?php
// Formulario de cambio de tema
if (isset($_POST['cam_temas'])) {
    $tema = $_POST['tema'];
    
    if ($tema) {
        $conexion->query("UPDATE `tema` SET `tema` = '$tema' WHERE `usuario_id` = '$id'");
    }
    if ($tema == 0) {
        $conexion->query("UPDATE `tema` SET `tema` = '0' WHERE `usuario_id` = '$id'");
    }
}
$stmt = $conexion->prepare("SELECT tema FROM `tema` WHERE `usuario_id` = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$thema = $stmt->fetchColumn();
?>

<script>
    var thema = <?php echo json_encode($thema); ?>; // Convertimos PHP a JS

    function cambiothema() {
        const style = document.createElement('style');
        if (thema == 1) {
            style.textContent = `
                * {
                color:#000000;
                }
                .container {
                    background-color: #f4f4f9;
                }
                #mensaje {
                    background: rgba(87, 87, 225, 0.475);
                }
                .sidebar {
                    background-color: #2c3e50;
                }
                .sidebar ul li a {
                    background-color: #15395c;
                }
                .sidebar ul li a:hover {
                    background-color: #34495e;
                }
                header {
                    background-color: #3498db;
                }
                .card h3 {
                    color: #3498db;
                }
                .formulario-deshboard {
                    background-color: #f9f9f9;
                }
                .formulario-deshboard select:focus,
                .formulario-deshboard input[type="number"]:focus {
                    border-color: #007bff;
                    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
                }
                .formulario-deshboard input[type="submit"] {
                    background-color: #007bff;
                }
                .formulario-deshboard input[type="submit"]:hover {
                    background-color: #0056b3;
                }
                .exito {
                    background-color: #d4edda;
                    color: #155724;
                }
                .error {
                    background-color: #f8d7da;
                    color: #721c24;
                }
                .tabla_pro {
                    background-color: #fff7e6;
                }
                .tabla_pro h2 {
                }
                .tabla-productos th {
                    background-color: #e67e22;
                }
                .tabla-productos tr:nth-child(even) {
                    background-color: #fdebd0;
                }
                .tabla-productos tr:nth-child(odd) {
                    background-color: #fef5e7;
                }
                .tabla-productos tr:hover {
                    background-color: #f39c12;
                }
                button {
                    background-color: #ff8c42;
                }
                button:hover {
                    background-color: #e6762f;
                }
                .modal-content {
                    background: #fff;
                    border: 2px solid #ff8c42;
                }
                .modal-content h2 {
                }
                input[type="button"],
                input[type="submit"] {
                    background-color: #ff7f00;
                    color: #fff;
                }
                input[type="button"]:hover,
                input[type="submit"]:hover {
                    background-color: #e65c00;
                }
                .submit-btn {
                    background-color: #ff8c42;
                }
                .submit-btn:hover {
                    background-color: #e6762f;
                }
                .close-btn:hover {
                }
                select {
                    background-color: #f9f9f9;
                }
                select:focus {
                    background-color: #fdf7e7;
                }
                .cart-configuraciones {
                    background-color: white;
                }
            `;
        } else if (thema == 2) {
            style.textContent = `
                *{
                    color: #eaeaea;
                }
                #mensaje{
                    background-color:rgba(220, 126, 35, 0.8);
                }
                .formulario-deshboard h2{
                    color: #eaeaea;
                }
                .container {
                    background-color: #1e1e2f;
                }
                .sidebar {
                    background-color: #181824;
                }
                .sidebar ul li a {
                    background-color: #28293d;
                }
                .sidebar ul li a:hover {
                    background-color: #3a3b58;
                }
                header {
                    background-color: #3a3b58;
                }
                .card h3 {
                }
                .formulario-deshboard {
                    background-color: #242434;
                }
                .formulario-deshboard select:focus,
                .formulario-deshboard input[type="number"]:focus {
                    border-color: #7289da;
                    box-shadow: 0 0 5px rgba(114, 137, 218, 0.5);
                }
                .formulario-deshboard input[type="submit"] {
                    background-color: #7289da;
                }
                .formulario-deshboard input[type="submit"]:hover {
                    background-color: #5b6db2;
                }
                .tabla_pro {
                    background-color: #2f2f40;
                }
                .tabla_pro h2 {
                }
                .tabla-productos th {
                    background-color: #f39c12;
                }
                .tabla-productos tr:nth-child(even) {
                    background-color: #2f2f40;
                }
                .tabla-productos tr:nth-child(odd) {
                    background-color: #3a3b58;
                }
                .tabla-productos tr:hover {
                    background-color: #f39c12;
                }
                button {
                    background-color: #ff8c42;
                }
                button:hover {
                    background-color: #cc6e33;
                }
                .modal-content {
                    background: #2f2f40;
                    border: 2px solid #ff8c42;
                }
                .modal-content h2 {
                }
                input[type="button"],
                input[type="submit"] {
                    background-color: #7289da;
                }
                input[type="button"]:hover,
                input[type="submit"]:hover {
                    background-color: #5b6db2;
                }
                input[type="text"],input[type="number"]{
                    color:black;
                };
                .submit-btn {
                    background-color: #7289da;
                }
                .submit-btn:hover {
                    background-color: #5b6db2;
                }
                .close-btn:hover {
                }
                select {
                    background-color: #2f2f40;
                }
                select:focus {
                    background-color: #3a3b58;
                }
                    .imprimir{
                    background: #3a3b58;
                }
            `;
        }
        document.head.appendChild(style);
    }

    // Llama a la función automáticamente
    cambiothema();
</script>
