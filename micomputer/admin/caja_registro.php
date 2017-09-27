<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
date_default_timezone_set("America/Bogota");
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php
$fecha_hoy_ymd = date("Y/m/d");
$fecha_hoy_seg = strtotime($fecha_hoy_ymd);

$mostrar_datos_sql2 = "SELECT fecha, total_ventas_fisico FROM caja_registro_fisico WHERE usuario = '$cuenta_actual' AND fecha = '$fecha_hoy_seg'";
$consulta2 = mysql_query($mostrar_datos_sql2, $conectar) or die(mysql_error());
$datos2 = mysql_fetch_assoc($consulta2);

$total_ventas_fisico = $datos2['total_ventas_fisico'];
$total_registros = mysql_num_rows($consulta2);

if ($total_registros == 1) {
echo "<br><br><br><center><font color='yellow' size='+3'>USTED HA HECHO EL REGISTRO DE LA CAJA POR UN VALOR DE: ".number_format($total_ventas_fisico, 0, ",", ".")."</font><strong></center>";
} else {
?>
<center>
<br>
<td><strong><font color='yellow' size='+3'>REGISTRAR CAJA FISICA </font></strong></td><br>
<form method="POST" name="formulario" action="caja_registro_reg.php">
<br>
<table align="center">
<tr>
<td align="center"><strong><font color='yellow' size='+3'>TOTAL CAJA</font></strong></td>
<tr>
<td align="center"><input type="text" style="font-size:120px" name="total_ventas_fisico" value="" size="7" required autofocus></td>
</tr>
<td align="center" bordercolor="1"><input type="submit" id="boton1" value="REGISTRAR"></td>
</table>
<input type="hidden" name="insertar_datos" value="formulario">
</form>
</table>
<?php
}
?>