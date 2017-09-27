<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");

$fecha_actual = date("d/m/Y");

$mostrar_datos_sql = "SELECT * FROM ventas WHERE vendedor = '$cuenta_actual' AND fecha_anyo = '$fecha_actual' ORDER BY fecha, fecha_hora DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<br>
<?php echo "<strong><font color='yellow' size='+3'>VENTA DIA ".$fecha_actual." - ".strtoupper($cuenta_actual)."</font></strong>";?>
<table width="100%">
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>%DESC</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>FECHA VENTA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
</tr>
<?php do { ?>
<tr>
<td ><?php echo $matriz_consulta['cod_productos']; ?></td>
<td ><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td align="right"><?php echo $matriz_consulta['unidades_vendidas']; ?></td>
<td align="right"><?php echo $matriz_consulta['descuento_ptj']; ?></td>
<td align="center"><?php echo $matriz_consulta['cod_factura']; ?></td>
<td align="center"><?php echo $matriz_consulta['fecha_orig']; ?></td>
<td align="center"><?php echo $matriz_consulta['fecha_hora']; ?></td>
<td align="center"><?php echo $matriz_consulta['vendedor']; ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>