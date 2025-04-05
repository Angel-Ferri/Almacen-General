<?php
try { 
            $conexion = new PDO('mysql:host=localhost;dbname=almacen_general','root', '');
         //  echo "La conexion funciono perfectamente";
        } catch (PDOException $e) {
            echo 'FALLO LA CONEXION A LA BASE DE DATOS ' . $e->getMessage();
        }  
?>