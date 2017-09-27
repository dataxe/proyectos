<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");

$cod_clientes = $_GET['cod_clientes'];

$mostrar_datos_sql = "SELECT * FROM productos_fiados WHERE cod_clientes = '$cod_clientes'";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
$total_datos = mysql_num_rows($consulta);

if (isset($_GET['cod_clientes'])) {

$borrar_cuentas_cobrar  = sprintf("DELETE FROM cuentas_cobrar WHERE cod_clientes = '$cod_clientes'");
$Resultado1 = mysql_query($borrar_cuentas_cobrar , $conectar) or die(mysql_error());

$borrar_productos_fiados  = sprintf("DELETE FROM productos_fiados WHERE cod_clientes = '$cod_clientes'");
$Resultado2 = mysql_query($borrar_productos_fiados , $conectar) or die(mysql_error());

$borrar_cuentas_cobrar_abonos  = sprintf("DELETE FROM cuentas_cobrar_abonos WHERE cod_clientes = '$cod_clientes'");
$Resultado3 = mysql_query($borrar_cuentas_cobrar_abonos , $conectar) or die(mysql_error());

echo "<META HTTP-EQUIV='REFRESH' CONTENT='0.1; ../admin/cuentas_cobrar_pagadas.php'>";
} else {
?>
<td><a href="../admin/cuentas_cobrar_pagadas.php"><center><strong><font color='white'>REGRESAR</font></strong></center></a></td>
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title></title>
</head>
<body>
</body>
</html>
