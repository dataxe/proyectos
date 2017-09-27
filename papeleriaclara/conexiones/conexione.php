<?php
$conexion_servidor = "localhost";
$base_datos = "papeles_y_mas";
$conexion_usuario = "clara";
$conexion_contrasena_descrip = "papeleria951";

$clave = stripslashes($conexion_contrasena_descrip);
$clave = strip_tags($clave);
$conexion_contrasena = md5($clave);

$conectar = mysql_pconnect($conexion_servidor, $conexion_usuario, $conexion_contrasena) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
