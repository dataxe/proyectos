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

include ("../registro_movimientos/registro_movimientos.php");
?>
<br>
<center>
<table>
<td><a href="../admin/reparar_tablas.php?tabla=productos"><font color='white' size='3px'>REPARAR TABLA PRODUCTOS</a></td>
<tr></tr>
<td><a href="../admin/reparar_tablas.php?tabla=ventas"><font color='white' size='3px'>REPARAR TABLA VENTAS</a></td>
<tr></tr>
<td><a href="../admin/reparar_tablas.php?tabla=info_impuesto_facturas"><font color='white' size='3px'>REPARAR TABLA FACTURAS</a></td>
<tr></tr>
<td><a href="../admin/reparar_tablas.php?tabla=sesiones"><font color='white' size='3px'>REPARAR TABLA SESIONES</a></td>
</table>
</center>
<?php
if ($_GET['tabla'] == 'productos') {

$chequeo_tabla = "CHECK TABLE productos EXTENDED";
$resultado_chequeo_tabla= mysql_query($chequeo_tabla, $conectar) or die(mysql_error());
sleep(5);
//echo "<img src='../imagenes/cargador.gif' width='50' height='50' />";

$reparar_sql1 = "REPAIR TABLE productos";
$resultado_reparar1 = mysql_query($reparar_sql1, $conectar) or die(mysql_error());
sleep(5);

$verificacion = "LA TABLA ".$_GET['tabla']." HA SIDO REPARADA";

echo "<strong><br><center><font color='yellow' size='5px'>".$verificacion."</font></center></strong>";
}
elseif ($_GET['tabla'] == 'ventas') {

$chequeo_tabla = "CHECK TABLE ventas EXTENDED";
$resultado_chequeo_tabla= mysql_query($chequeo_tabla, $conectar) or die(mysql_error());

sleep(5);

$reparar_sql1 = "REPAIR TABLE ventas";
$resultado_reparar1 = mysql_query($reparar_sql1, $conectar) or die(mysql_error());

sleep(5);

$verificacion = "LA TABLA ".$_GET['tabla']." HA SIDO REPARADA";

echo "<strong><br><center><font color='yellow' size='5px'>".$verificacion."</font></center></strong>";
}
elseif ($_GET['tabla'] == 'info_impuesto_facturas') {

$chequeo_tabla = "CHECK TABLE info_impuesto_facturas EXTENDED";
$resultado_chequeo_tabla= mysql_query($chequeo_tabla, $conectar) or die(mysql_error());

sleep(5);

$reparar_sql1 = "REPAIR TABLE info_impuesto_facturas";
$resultado_reparar1 = mysql_query($reparar_sql1, $conectar) or die(mysql_error());

sleep(5);

$verificacion = "LA TABLA ".$_GET['tabla']." HA SIDO REPARADA";

echo "<strong><br><center><font color='yellow' size='5px'>".$verificacion."</font></center></strong>";
}
elseif ($_GET['tabla'] == 'sesiones') {

$chequeo_tabla = "CHECK TABLE sesiones EXTENDED";
$resultado_chequeo_tabla= mysql_query($chequeo_tabla, $conectar) or die(mysql_error());

sleep(5);

$reparar_sql1 = "REPAIR TABLE sesiones";
$resultado_reparar1 = mysql_query($reparar_sql1, $conectar) or die(mysql_error());

sleep(5);

$verificacion = "LA TABLA ".$_GET['tabla']." HA SIDO REPARADA";

echo "<strong><br><center><font color='yellow' size='5px'>".$verificacion."</font></center></strong>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>SISTEMA DE CONTENIDOS VIRTUALES</title>
</head>
<body>
<br>
<center><table>
<tr>
</tr>
</table>
<br>