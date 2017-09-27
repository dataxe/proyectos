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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
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
<br><br>
<center>
<td align="center"><font color="yellow" size="+2"><strong>DEVOLUCIONES VENTAS MENSUAL</font></td>
<br>
<?php require_once("menu_devolucion_ventas.php");?>
<br>
<form method="GET" name="formulario" action="">
<table>
<tr>
<td align="center"><strong>MES</strong></td>
<td><select name="fecha" require autofocus>
<?php 
$sql_consulta = "SELECT DISTINCT anyo FROM operacion WHERE origen_operacion = 'ventas' ORDER BY anyo DESC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor = mysql_fetch_array($resultado)) {?>
<option style="font-size:20px" value="<?php echo $contenedor['anyo'] ?>"><?php echo $contenedor['anyo']?></option>
<?php }?></select></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Ver"></td>
</tr>
</table>
</form>
<br>
<?php
if (isset($_GET['fecha'])) {

$fecha = addslashes($_GET['fecha']);

$deboluciones_inventario = "SELECT * FROM operacion WHERE origen_operacion = 'ventas' AND anyo = '$fecha'";
$consulta = mysql_query($deboluciones_inventario, $conectar) or die(mysql_error());
?>
<table>
<th>FECHA: <?php echo $fecha?></th>
</table>
<br>
<table width='100%'>
<tr>
<th align="center">CODIGO</th>
<th align="center">PRODUCTO</th>
<th align="center">UND ANTES DE DEVOLUCI&Oacute;N</th>
<th align="center">UND DEVUELTAS</th>
<th align="center">UND DESPUES DE DEVOLUCI&Oacute;N</th>
<th align="center">COMENTARIO</th>
<th align="center">PRECIO COSTO</th>
<th align="center">PRECIO VENTA</th>
<th align="center">FECHA VENTA</th>
<th align="center">VENDEDOR</th>
<th align="center">FECHA DEVOLUCI&Oacute;N</th>
<th align="center">DEVOLUCI&Oacute;N</th>
</tr>
<?php 
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos  = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$und_vend_orig = $datos['und_vend_orig'];
$devoluciones  = $datos['devoluciones'];
$unidades_vendidas  = $datos['unidades_vendidas'];
$comentario = $datos['comentario'];
$precio_costo = $datos['precio_costo'];
$precio_venta = $datos['precio_venta'];
$vendedor = $datos['vendedor'];
$cuenta = $datos['cuenta'];
$fecha_orig = $datos['fecha_orig'];
$fecha_hora = $datos['fecha_hora'];
$fecha_time = $datos['fecha_time'];
$fecha_devolucion = date("d/m/Y", $fecha_time);
$hora_devolucion = date("H:i:s", $fecha_time);
?>
<tr>
<td align='left'><?php echo $cod_productos ?></td>
<td align='left'><?php echo $nombre_productos ?></td>
<td align='center'><?php echo number_format($und_vend_orig, 0, ",", ".") ?></td>
<td align='center'><?php echo number_format($devoluciones, 0, ",", ".") ?></td>
<td align='center'><?php echo number_format($unidades_vendidas, 0, ",", ".") ?></td>
<td align='left'><?php echo $comentario ?></td>
<td align='right'><?php echo number_format($precio_costo, 0, ",", ".") ?></td>
<td align='right'><?php echo number_format($precio_venta, 0, ",", ".") ?></td>
<td align='center'><?php echo $fecha_orig.' - '.$fecha_hora ?></td>
<td align='center'><?php echo $vendedor ?></td>
<td align='center'><?php echo $fecha_devolucion.' - '.$hora_devolucion ?></td>
<td align='center'><?php echo $cuenta ?></td>
</tr>
<?php 
} 
} else {
}
?>
</table>
</center>
</body>
</html>