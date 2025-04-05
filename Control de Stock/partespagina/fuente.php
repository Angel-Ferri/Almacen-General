<?php
include("tema.php");
//Formulario de cambio de fuente
if (isset($_POST['cam_form'])) {
    $nuforn = $_POST['fuente'];
    if ($nuforn == TRUE) {
        $conexion->query("UPDATE `tema` SET `fuente` = '$nuforn' WHERE `usuario_id` = '$id'");
    }
}

$stmt = $conexion->prepare("SELECT fuente FROM `tema` WHERE `usuario_id` = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();

$fuente = $stmt->fetchColumn();

if ($fuente == 1 ) {
    echo '
    <style>
        *{
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
    ';
}
if ($fuente == 2 ) {
    echo"
    <style>
        *{
            font-family: 'Times New Roman', Times, serif;
        }
    </style>
    ";
}
if ($fuente == 3 ) {
    echo"
    <style>
        *{
            font-family: Georgia, 'Times New Roman', Times, serif;
        }
    </style>
    ";
}
if ($fuente == 4 ) {
    echo'
    <style>
        *{
            font-family: Verdana, Geneva, Tahoma, sans-serif;
        }
    </style>
    ';
}
if ($fuente == 5 ) {
    echo"
    <style>
        *{
            font-family:'Trebuchet MS', 'Lucida Sans Unicode', 'Lucida Grande', 'Lucida Sans', Arial, sans-serif;
        }
    </style>
    ";
}


?>