<?php
$conexion_servidor = "localhost";
$base_datos = "syscontr_blue_licores";
$conexion_usuario = "editaxe";
$conexion_contrasena_descrip = "taste951";

$clave = stripslashes($conexion_contrasena_descrip);
$clave = strip_tags($clave);
$conexion_contrasena = md5($clave);

$conectar = mysql_pconnect($conexion_servidor, $conexion_contrasena_descrip, $conexion_contrasena) or trigger_error(mysql_error(),E_USER_ERROR); 
?>
