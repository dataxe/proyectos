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
?>
<center>
<br>
<td><a href="../admin/ver_factura_ventas.php"><font color='yellow' size="4px">REGRESAR</font></a></td>
<br><br>
</center>
<?php
include ("../busquedas/buscar_devoluciones.php");
$buscar = $_POST['buscar'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>ALMACEN</title>
</head>
<body>
<?php 
$sql = "SELECT * FROM ventas WHERE (devoluciones <> 'NULL') AND  (nombre_productos like '$buscar%' OR fecha_anyo = '$buscar' OR cuenta like '$buscar%') ORDER BY fecha DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$tota_reg = mysql_num_rows($consulta);
?>
<center>
<br>
<table width="100%">
<tr>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND VEND</strong></td>
<td align="center"><strong>UND ORIG</strong></td>
<td align="center"><strong>UND DEVTS</strong></td>
<td align="center"><strong>V. UNIT</strong></td>
<td align="center"><strong>V. TOTAL</strong></td>
<td align="center"><strong>FECHA - VENTA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>FECHA - DEVOLUCION</strong></td>
<td align="center"><strong>DEVOLUCI&Oacute;N</strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_ventas = $datos['cod_ventas'];
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$unidades_vendidas = $datos['unidades_vendidas'];
$tope_min = $datos['tope_min'];
$precio_compra = $datos['precio_compra'];
$precio_venta = $datos['precio_venta'];
$fechas_vencimiento = $datos['fechas_vencimiento'];
$vlr_total_venta = $datos['vlr_total_venta'];
$cod_facturan = $datos['cod_factura'];
?>
<tr>
<td align="center"><font><?php echo $datos['cod_factura']; ?></td></font>
<td><font><?php echo $cod_productos; ?></td></font>
<td><font><?php echo $nombre_productos; ?></td></font>
<td align="center"><font><?php echo $unidades_vendidas; ?></td></font>
<td align="center"><font><?php echo $datos['und_vend_orig']; ?></td></font>
<td align="center"><font><?php echo $datos['devoluciones']; ?></td></font>
<td align="right"><font><?php echo $precio_venta; ?></td></font>
<td align="right"><font><?php echo $vlr_total_venta; ?></td></font>
<td align="center"><font><?php echo $datos['fecha_anyo'].' - '.$datos['fecha_hora']; ?></td></font>
<td align="center"><font><?php echo $datos['vendedor']; ?></td></font>
<td align="center"><font><?php echo $datos['fecha_devolucion'].' - '.$datos['hora_devolucion']; ?></td></font>
<td align="center"><font><?php echo $datos['cuenta']; ?></td></font>
</tr>
<?php } ?> 
</form>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>