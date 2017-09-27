<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");


if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
$cod_factura = intval($_GET['numero_factura']);
$descuento = addslashes($_GET['descuento']);
$tipo_pago = intval($_GET['tipo_pago']);
$cod_clientes = intval($_GET['cod_clientes']);

$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$dat = mysql_fetch_assoc($consultar_informacion);
?>
<link rel="stylesheet" type="text/css" href="../estilo_css/por_defecto.css">

<style type="text/css"> <!--body { background-color: #333333;}--></style>
<?php
$obtener_info_fact = "SELECT * FROM info_impuesto_facturas WHERE cod_factura = '$cod_factura'";
$resultado_info_fact = mysql_query($obtener_info_fact, $conectar) or die(mysql_error());
$info_fact = mysql_fetch_assoc($resultado_info_fact);

$obtener_cliente = "SELECT * FROM clientes WHERE cod_clientes = '$cod_clientes'";
$resultado_cliente = mysql_query($obtener_cliente, $conectar) or die(mysql_error());
$matriz_cliente = mysql_fetch_assoc($resultado_cliente);

$nombre_cliente = $matriz_cliente['nombres'].' '.$matriz_cliente['apellidos'];
$cedula = $matriz_cliente['cedula'];
$direccion = $matriz_cliente['direccion'];

//----------------- CALCULOS PARA TIPOS DE PAGOS -----------------//
//----------------- PAGO POR CONTADO -----------------//
if ($tipo_pago == '1') {
$obtener_info_venta = "SELECT * FROM ventas WHERE cod_factura = '$cod_factura'";
$resultado_info_venta = mysql_query($obtener_info_venta, $conectar) or die(mysql_error());
$info_venta = mysql_fetch_assoc($resultado_info_venta);

$obtener_calculo_fact = "SELECT sum(vlr_total_venta) as vlr_total_venta FROM ventas WHERE cod_factura = '$cod_factura'";
$resultado_calculo_fact = mysql_query($obtener_calculo_fact, $conectar) or die(mysql_error());
$calculo_fact = mysql_fetch_assoc($resultado_calculo_fact);

$calculo_subtotal = $calculo_fact['vlr_total_venta'] - ($calculo_fact['vlr_total_venta'] * ($descuento/100));

$suma_temporal = "SELECT Sum(vlr_total_venta -(vlr_total_venta*($descuento/100))) As total_venta, 
Sum((vlr_total_venta - (($descuento/100)*vlr_total_venta))/((iva/100)+(100/100))) As subtotal_base, 
Sum(((vlr_total_venta - (($descuento/100)*vlr_total_venta))/((iva/100)+(100/100)))*(iva/100)) As total_iva, 
Sum(vlr_total_venta*($descuento/100)) AS total_desc, Sum(vlr_total_venta) AS total_venta_neta FROM ventas WHERE cod_factura = '$cod_factura'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_temporal);

$total_venta_neta = ($suma['total_venta_neta']);
$subtotal_base = ($suma['subtotal_base']);
$total_desc = ($suma['total_desc']);
$total_iva = ($suma['total_iva']);
$total_venta_temp = ($suma['total_venta']);

//----------------- PAGO POR CREDITO -----------------//
} else {
$obtener_info_venta = "SELECT * FROM productos_fiados WHERE cod_factura = '$cod_factura'";
$resultado_info_venta = mysql_query($obtener_info_venta, $conectar) or die(mysql_error());
$info_venta = mysql_fetch_assoc($resultado_info_venta);

$obtener_calculo_fact = "SELECT sum(vlr_total_venta) as vlr_total_venta FROM productos_fiados WHERE cod_factura = '$cod_factura'";
$resultado_calculo_fact = mysql_query($obtener_calculo_fact, $conectar) or die(mysql_error());
$calculo_fact = mysql_fetch_assoc($resultado_calculo_fact);

$calculo_subtotal = $calculo_fact['vlr_total_venta'] - ($calculo_fact['vlr_total_venta'] * ($descuento/100));

$suma_temporal = "SELECT Sum(vlr_total_venta -(vlr_total_venta*($descuento/100))) As total_venta, 
Sum((vlr_total_venta - (($descuento/100)*vlr_total_venta))/((iva/100)+(100/100))) As subtotal_base, 
Sum(((vlr_total_venta - (($descuento/100)*vlr_total_venta))/((iva/100)+(100/100)))*(iva/100)) As total_iva, 
Sum(vlr_total_venta*($descuento/100)) AS total_desc, Sum(vlr_total_venta) AS total_venta_neta FROM productos_fiados WHERE cod_factura = '$cod_factura'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_temporal);

$total_venta_neta = ($suma['total_venta_neta']);
$subtotal_base = ($suma['subtotal_base']);
$total_desc = ($suma['total_desc']);
$total_iva = ($suma['total_iva']);
$total_venta_temp = ($suma['total_venta']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<link href="../imagenes/<?php echo $dat['icono'];?>" type="image/x-icon" rel="shortcut icon" />
<title><?php echo "Factura No ".$cod_factura;?></title>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>
<body>

<table width="370" align="left">
<td align="center"><p style="font-size:10px"><?php echo $dat['cabecera'];?> - <?php echo $dat['localidad'];?>
<p style="font-size:10px">Res: <?php echo $dat['res'];?>, Del <?php echo $dat['res1'];?> Al <?php echo $dat['res2'];?> 
- Direccion: <?php echo $dat['direccion'];?> - Tel: <?php echo $dat['telefono'];?>
<br><?php echo $dat['nit'];?></td>
</table>
<br><br><br><br>

<table width="370" align="left">
<td align="left"><p style="font-size:10px"><strong>Fact N&ordm;:</strong> <?php echo $cod_factura;?> | 
<strong>Fecha:</strong> <?php echo $info_fact['fecha_anyo']?> | <strong>Hora:</strong> <?php echo date("H:i")?> | <br>
<strong>Cliente:</strong> <?php echo $nombre_cliente;?> | <strong>Nit:</strong> <?php echo $cedula;?> | <strong>Direccion:</strong> <?php echo $direccion;?> | 
<strong>Vendedor:</strong> <?php echo $info_fact['vendedor'];?> </p></td>
</table>
<br>
<br>
<table width="370" align="left">
<tr>
<td align="left"><strong><p style="font-size:9px">COD</strong></td>
<td align="left"><strong><p style="font-size:9px">DESCRIPCI&Oacute;N</strong></td>
<td align="left"><strong><p style="font-size:9px">UND</strong></td>
<!--<td align="left"><strong><p style="font-size:12px">.</strong></td> -->
<td align="right"><strong><p style="font-size:9px">V.UNIT</strong></td>
<td align="right"><strong><p style="font-size:9px">V.TOTAL</strong></td>
</tr>
<?php do { ?>
<tr>
<td align="left"><p style="font-size:9px"><?php echo $info_venta['cod_productos']; ?></td>
<td align="left"><p style="font-size:12px"><?php echo utf8_encode($info_venta['nombre_productos']); ?></td>
<td align="left"><p style="font-size:9px"><?php echo $info_venta['unidades_vendidas'].''.$info_venta['detalles']; ?></td>
<!--<td align="left"><p style="font-size:12px"><?php echo $info_venta['detalles']; ?></td> -->
<td align="right"><p style="font-size:9px"><?php echo number_format($info_venta['precio_venta'], 0, ",", ".").'|'; ?></td>
<td align="right"><p style="font-size:12px"><?php echo number_format($info_venta['vlr_total_venta'], 0, ",", "."); ?></td>
</tr>
<td>-<td>
<?php
?>
<?php } while ($info_venta = mysql_fetch_assoc($resultado_info_venta));?>
<br>
<tr>
<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>

<td align='left'><p style="font-size:12px"><strong>SUBTOT: </td> <td align='right'><p style="font-size:12px"><strong><?php echo number_format($subtotal_base, 0, ",", "."); ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:12px"><strong>%DESC: </td> <td align='right'><p style="font-size:12px"><strong><?php echo $descuento.'%'; ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:12px"><strong>$DESC: </td> <td align='right'><p style="font-size:12px"><strong><?php echo number_format($total_desc, 0, ",", "."); ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:12px"><strong>IVA:</td> <td align='right'> <p style="font-size:12px"><strong><?php echo number_format($total_iva, 0, ",", "."); ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:12px"><strong>TOTAL: </td> <td align='right'><p style="font-size:12px"><strong><?php echo number_format($total_venta_temp, 0, ",", "."); ?></strong>
</td>
</tr>
<!--<td align="justify"><p style="font-size:12px"><?php //echo $dat['info_legal'];?></td>-->
<!--<td><input align="left" type="image" src="../imagenes/atras.png" onclick="history.back()"/></td>-->
<td><input align="left" type="image" id ="foco" src="../imagenes/imprimir.png" name="imprimir" onClick="window.print();"/></td>
</table>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>
