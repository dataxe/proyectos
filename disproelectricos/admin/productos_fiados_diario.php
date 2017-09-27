<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
$cuenta_actual = addslashes($_SESSION['usuario']);
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>

<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'activo';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inactivo';
if (last != valor)
myajax.Link('guardar_productos_fiados.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>
</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  

<center>
<br><br>
<td><strong><font color='yellow'>CUENTAS POR COBRAR - <a href="../admin/cuentas_cobrar_pagadas.php"><font color='white'>CUENTAS PAGADAS</a>
<br>
<?php
include ("../admin/menu_cuentas_cobrar.php");
?>
<br>

<form method="POST" name="formulario" action="">
<table align="center">
<td nowrap align="right">POR COBRAR DIARIO:</td>
<td bordercolor="0">
<select name="fecha" id="foco">
<?php $sql_consulta1 = "SELECT fecha, fecha_anyo FROM productos_fiados GROUP BY fecha DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor = mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['fecha'] ?>"><?php echo $contenedor['fecha_anyo'] ?></option>
<?php } ?>
</select></td></td>
</tr>
</table>
<br>
<td bordercolor="1"><input type="submit" id="boton1" value="Consultar Informacion"></td>
</form>
</center>

<br>
<center>
<?php
if (isset($_POST['fecha'])) {

$fecha = addslashes($_POST['fecha']);

$sql = "SELECT * FROM productos_fiados, clientes WHERE productos_fiados.cod_clientes = clientes.cod_clientes 
AND productos_fiados.fecha = '$fecha' ORDER BY fecha DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);
?>
<table width="100%">
<tr>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>MET</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
<td align="center"><strong>%DESC</strong></td>
<td align="center"><strong>TOTAL</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>IP</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<!--<td align="center"><strong>VENDER</strong></td>
<td><strong>OK</strong></td>-->
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos_fiados = $datos['cod_productos_fiados'];
$cod_productos = $datos['cod_productos'];
$cod_clientes = $datos['cod_clientes'];
$unidades_vendidas = $datos['unidades_vendidas'];
$precio_venta = $datos['precio_venta'];
$descuento_ptj = intval($datos['descuento_ptj']);
$vlr_total_venta = $datos['vlr_total_venta'];
$total_venta_desc = $vlr_total_venta-($vlr_total_venta*($descuento_ptj/100));
?>
<tr>
<td ><?php echo $datos['nombres'].' '.$datos['apellidos']; ?></td>
<td ><?php echo $datos['cod_factura']; ?></td>
<td ><?php echo $cod_productos; ?></td>
<td ><?php echo $datos['nombre_productos']; ?></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_vendidas', <?php echo $cod_productos_fiados;?>)" class="cajund" id="<?php echo $cod_productos_fiados;?>" value="<?php echo $unidades_vendidas;?>" size="5"></td>
<td ><?php echo $datos['detalles']; ?></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_productos_fiados;?>)" class="cajund" id="<?php echo $cod_productos_fiados;?>" value="<?php echo $precio_venta;?>" size="5"></td>
<td align="center"><font size="6"><?php echo $descuento_ptj; ?></font></td>
<td align="right"><font size="6"><?php echo $total_venta_desc; ?></font></td>
<td align="center"><?php echo $datos['vendedor']; ?></td>
<td align="right"><?php echo $datos['ip']; ?></td>
<td align="right"><?php echo $datos['fecha_anyo']; ?></td>
<td align="right"><?php echo $datos['fecha_hora']; ?></td>
</tr>
<?php 
}
?>
</table>
<?php
} else {
}
?>