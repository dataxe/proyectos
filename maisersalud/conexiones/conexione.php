<?php
$conexion_servidor = "localhost";
$base_datos = "maisersalud";
$conexion_usuario = "maisersalud";
$conexion_contrasena_descrip = "pinsalud951";

$clave = stripslashes($conexion_contrasena_descrip);
$clave = strip_tags($clave);
$conexion_contrasena = md5($clave);

$conectar = mysql_pconnect($conexion_servidor, $conexion_usuario, $conexion_contrasena) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
