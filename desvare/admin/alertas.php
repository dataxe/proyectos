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
//include ("../registro_movimientos/registro_cierre_caja.php");
$cod_notificacion_alerta = $_GET['cod_notificacion_alerta'];
$tipo = $_GET['tipo'];

$borrar  = sprintf("DELETE FROM notificacion_alerta WHERE cod_productos_var IN (SELECT cod_productos_var FROM productos WHERE unidades_faltantes > '0')"); 
$result_borrar = mysql_query($borrar , $conectar) or die(mysql_error());

$obtener_notificacion_alerta = "SELECT * FROM notificacion_alerta WHERE cod_notificacion_alerta = '$cod_notificacion_alerta'";
$resultado_notificacion_alerta = mysql_query($obtener_notificacion_alerta, $conectar) or die(mysql_error());
$total_alerta = mysql_num_rows($resultado_notificacion_alerta);
$alerta = mysql_fetch_assoc($resultado_notificacion_alerta);

$cod_productos_var = $alerta['cod_productos_var'];

if ($tipo == 'agotado') {
$mostrar = "SELECT * FROM notificacion_alerta, productos WHERE (productos.cod_productos_var = notificacion_alerta.cod_productos_var) 
AND productos.cod_productos_var = '$cod_productos_var'";
$consulta_most = mysql_query($mostrar, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta_most);
} else {
$mostrar = "SELECT * FROM notificacion_alerta WHERE cod_notificacion_alerta = '$cod_notificacion_alerta'";
$consulta_most = mysql_query($mostrar, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta_most);
}
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
<td><strong><font color='yellow'>ALERTAS - <a href="../admin/alertas_todas.php"><font color="yellow">TODAS LAS ALERTAS</a></font></strong></td><br><br>
</center>
<center>
<br>
<table width="80%">
<?php
if ($tipo == 'agotado') {
?>
<tr>
<td align="center"><strong>ALERTA</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>COD PRODUCTO</strong></td>
<td align="center"><strong>UND INV</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
</tr>
<?php do { ?>
<tr>
<td><font size='2'><?php echo $datos['nombre_notificacion_alerta'];?></font></td>
<td><font size='2'><?php echo $datos['nombre_productos']; ?></font></td>
<td><font size='2'><?php echo $datos['cod_productos_var']; ?></font></td>
<td align="center"><font size='2'><?php echo $datos['unidades_faltantes']; ?></font></td>
<td align="center"><font size='2'><?php echo $datos['fecha_dia']; ?></font></td>
<td align="center"><font size='2'><?php echo $datos['fecha_hora']; ?></font></td>
</tr>
<?php 
} while ($datos = mysql_fetch_assoc($consulta_most));
}
?>
<?php
if ($tipo == 'pagar') {
?>
<tr>
<td align="center"><strong>ALERTA</strong></td>
<td align="center"><strong>PROVEEDOR</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>FECHA PAGO</strong></td>
</tr>
<?php do { ?>
<tr>
<td><font size='2'><?php echo $datos['nombre_notificacion_alerta'];?></font></td>
<td align="center"><font size='2'><?php echo $datos['nombre_proveedores']; ?></font></td>
<td align="center"><font size='2'><?php echo $datos['cod_factura']; ?></font></td>
<td align="center"><font size='2'><?php echo $datos['nombre_productos']; ?></font></td>
</tr>
<?php 
} while ($datos = mysql_fetch_assoc($consulta_most));
}
?>
<?php
if ($tipo == 'cobrar') {
?>
<tr>
<td align="center"><strong>ALERTA</strong></td>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>FECHA PAGO</strong></td>
</tr>
<?php do { ?>
<tr>
<td><font size='2'><?php echo $datos['nombre_notificacion_alerta'];?></font></td>
<td align="center"><font size='2'><?php echo $datos['nombre_clientes']; ?></font></td>
<td align="center"><font size='2'><?php echo $datos['cod_factura']; ?></font></td>
<td align="center"><font size='2'><?php echo $datos['nombre_productos']; ?></font></td>
</tr>
<?php 
} while ($datos = mysql_fetch_assoc($consulta_most));
}
?>
</table>