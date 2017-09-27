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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$mostrar_datos_sql = "SELECT * FROM ventas ORDER BY fecha DESC";
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
<?php
require_once("menu_ventas.php");
?>
<br>
<center>
<table width="100%">
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>MET</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
<td align="center"><strong>TOTAL</strong></td>
<td align="center"><strong>%DESC</strong></td>
<td align="center"><strong>CENT.COSTO</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>FECHA VENTA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>FECHA PAGO</strong></td>
<td align="center"><strong>PAGO A</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>IP</strong></td>
</tr>
<?php do { ?>
<tr>
<td ><?php echo $matriz_consulta['cod_productos']; ?></td>
<td ><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td align="right"><?php echo $matriz_consulta['unidades_vendidas']; ?></td>
<td align="center"><?php echo $matriz_consulta['detalles']; ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_venta'], 0, ",", "."); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['vlr_total_venta'], 0, ",", "."); ?></td>
<td align="right"><?php echo $matriz_consulta['descuento_ptj']; ?></td>
<td align="center"><?php echo $matriz_consulta['nombre_ccosto']; ?></td>
<td align="center"><?php echo $matriz_consulta['cod_factura']; ?></td>
<td align="center"><?php echo $matriz_consulta['fecha_orig']; ?></td>
<td align="center"><?php echo $matriz_consulta['vendedor']; ?></td>
<td align="right"><?php echo $matriz_consulta['fecha_anyo']; ?></td>
<td align="center"><?php echo $matriz_consulta['cuenta']; ?></td>
<td align="right"><?php echo $matriz_consulta['fecha_hora']; ?></td>
<td align="right"><?php echo $matriz_consulta['ip']; ?></td>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>