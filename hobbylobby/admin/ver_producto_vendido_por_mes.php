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
<center>
<br>
<?php
$cod_productos = $_GET['cod_productos'];
$fecha_mes = $_GET['fecha_mes'];
$buscar = $_GET['buscar'];
?>
<td><a href="../admin/buscar_por_mes_o_producto.php?buscar=<?php echo $buscar?>"><font size='4' color='yellow'>REGRESAR</font></a></td>
<br>
</center>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>ALMACEN</title>
</head>
<body>
<?php
$sql = "SELECT * FROM ventas, clientes WHERE (ventas.cod_clientes = clientes.cod_clientes) AND 
(ventas.cod_productos = '$cod_productos' AND ventas.fecha_mes = '$fecha_mes') ORDER BY ventas.fecha DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$tota_reg = mysql_num_rows($consulta);
?>
<center>
<br>
<br>
<table width="100%">
<tr>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UNDS</strong></td>
<td align="center"><strong>MET</strong></td>
<td align="center"><strong>V. UNIT</strong></td>
<td align="center"><strong>%DESC</strong></td>
<td align="center"><strong>V. TOTAL</strong></td>
<td align="center"><strong>COMENTARIO</strong></td>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_ventas = $datos['cod_ventas'];
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$unidades_vendidas = $datos['unidades_vendidas'];
$detalles = $datos['detalles'];
$tope_min = $datos['tope_min'];
$precio_compra = $datos['precio_compra'];
$precio_venta = $datos['precio_venta'];
$fechas_vencimiento = $datos['fechas_vencimiento'];
$vlr_total_venta = $datos['vlr_total_venta'];
$cod_facturan = $datos['cod_factura'];
$fecha_anyo = $datos['fecha_anyo'];
$comentario = $datos['comentario'];
$descuento_ptj = $datos['descuento_ptj'];
$fecha_hora = $datos['fecha_hora'];
$vendedor = $datos['vendedor'];
?>
<tr>
<td align="center"><?php echo $cod_facturan; ?></td>
<td><?php echo $cod_productos; ?></td>
<td><?php echo $nombre_productos; ?></td>
<td align="center"><?php echo $unidades_vendidas; ?></td>
<td align="center"><?php echo $detalles; ?></td>
<td align="right"><?php echo number_format($precio_venta, 0, ",", "."); ?></td>
<td align="center"><?php echo $descuento_ptj; ?></td>
<td align="right"><?php echo number_format($vlr_total_venta, 0, ",", "."); ?></td>
<td align="center"><?php echo $comentario; ?></td>
<td><?php echo $datos['nombres']." ".$datos['apellidos']; ?></td>
<td align="center"><?php echo $fecha_anyo ?></td>
<td align="center"><?php echo $fecha_hora; ?></td>
<td align="center"><?php echo $vendedor; ?></td>
</tr>
<?php
}
?>
</table>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>
