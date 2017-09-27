<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
date_default_timezone_set("America/Bogota");
//$cuenta_actual = addslashes($_SESSION['usuario']);
$obtener_diseno = "SELECT * FROM disenos WHERE nombre_disenos LIKE 'por_defecto.css'";
$resultado_diseno = mysql_query($obtener_diseno, $conectar) or die(mysql_error());
$matriz_diseno = mysql_fetch_assoc($resultado_diseno); ?>
<link rel="stylesheet" type="text/css" href="../estilo_css/por_defecto.css">

<style type="text/css"><!--body { background-color: #333333;}--></style>
<?php
$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$matriz_informacion = mysql_fetch_assoc($consultar_informacion);
$num_informacion = mysql_num_rows($consultar_informacion); 

$factura_act = $_POST['numero_factura'];
$fecha = $_POST['envio_fecha'];
$hora = date("H:i:s");

$datos_factura = "SELECT * FROM facturacion WHERE cod_facturacion = '$factura_act'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$datos_total_factura = "SELECT  Sum(vlr_total) as vlr_totl FROM facturacion WHERE cod_facturacion like '$factura_act'";
$consulta_total = mysql_query($datos_total_factura, $conectar) or die(mysql_error());
$matriz_total_consulta = mysql_fetch_assoc($consulta_total);

$datos_facturacion = "SELECT * FROM facturacion WHERE cod_facturacion like '$factura_act'";
$consulta_total = mysql_query($datos_facturacion, $conectar) or die(mysql_error());
$matriz_facturacion = mysql_fetch_assoc($consulta_total);

$calculo_subtotal = $matriz_total_consulta['vlr_totl'] - $_POST['descuento_factura']; 
$calculo_total = $calculo_subtotal;
$calculo_iva = $calculo_subtotal * ($_POST['iva'] / 100);
$calculo_flete = $calculo_subtotal * ($_POST['flete'] / 100);
$vlr_cancelado = $_POST['vlr_cancelado'];
$vlr_vuelto = $vlr_cancelado - $calculo_total;
?>
<center>
<table id="table" width="290" align="left">
<td><div align="center"><p style="font-size:8px"><?php echo $matriz_informacion['cabecera'];?> - <?php echo $matriz_informacion['localidad'];?>
<p style="font-size:8px">Direccion: <?php echo $matriz_informacion['direccion'];?> - Tel: <?php echo $matriz_informacion['telefono'];?>
<br><?php echo $matriz_informacion['nit'];?></div></td>
</table>
<br><br><br><br>
<table id="tabla" width="290" align="left">
<td nowrap align="left"><p style="font-size:8px"><strong>Factura N&ordm;:</strong> <?php echo $factura_act;?> | 
<strong>Fecha:</strong> <?php echo $fecha.' | <strong>Hora:</strong> '.$hora.' | <strong>Vendedor:</strong> '.$matriz_facturacion['vendedor']; ?>
</td>
</table>
</left>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<link href="../imagenes/<?php echo $matriz_informacion['icono'];?>" type="image/x-icon" rel="shortcut icon" />
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title><?php echo "Factura No ".$factura_act;?></title>
</head>
<body>
<br>
<center>
<table id="table" width="290" align="left">
<tr>
<td><strong><div align="left"><p style="font-size:8px">C&oacute;digo</div></strong></td>
<td><strong><div align="left"><p style="font-size:8px">Descripci&oacute;n</div></strong></td>
<td></td>
<td></td>
<td><strong><div align="left"><p style="font-size:8px">Cant</div></strong></td>
<td><strong><div align="left"><p style="font-size:8px">V.unit</div></strong></td>
<td><strong><div align="left"><p style="font-size:8px">V.total</div></strong></td>
</tr>
<?php do { ?>
<tr>
<td align="left"><p style="font-size:8px"><?php echo $matriz_consulta['cod_producto']; ?></td>
<td align="left"><p style="font-size:8px"><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td></td>
<td></td>
<td align="center"><p style="font-size:8px"><?php echo $matriz_consulta['cantidad']; ?></td>
<td align="right"><p style="font-size:8px"><?php echo number_format($matriz_consulta['vlr_unitario']); ?></td>
<td align="right"><p style="font-size:8px"><?php echo number_format($matriz_consulta['vlr_total']); ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta));?>
<!--
<br>
<tr>
<td><strong><div align="left"><p style="font-size:8px">Subtot</div></strong></td>
<td><strong><div align="left"><p style="font-size:8px">Desc</div></strong></td>
<td><strong><div align="left"><p style="font-size:8px">Subtot</div></strong></td>
<td><strong><div align="left"><p style="font-size:8px">Iva inc</div></strong></td>
<td><strong><div align="left"><p style="font-size:8px">Recibido</div></strong></td>
<td><strong><div align="left"><p style="font-size:8px">Cambio</div></strong></td>
<td><strong><div align="left"><p style="font-size:8px">Total</div></strong></td>
</tr>
<?php do { ?>
<tr>
<td align="left"><p style="font-size:8px"><?php //echo number_format($matriz_total_consulta['vlr_totl']); ?></td>
<td align="left"><p style="font-size:8px"><?php //echo number_format($_POST['descuento_factura']); ?></td>
<td align="left"><p style="font-size:8px"><?php //echo number_format($calculo_subtotal); ?></td>
<td align="left"><p style="font-size:8px"><?php //echo number_format($calculo_iva); ?></td>
<td align="left"><p style="font-size:8px"><?php //echo number_format($vlr_cancelado); ?></td>
<td align="left"><p style="font-size:8px"><?php //echo number_format($vlr_vuelto); ?></td>
<td align="left"><p style="font-size:8px"><?php //echo number_format($calculo_total); ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>

<td></td>
<td><div align="justify"><p style="font-size:8px"><?php //echo $matriz_informacion['info_legal'];?></div></td>
-->
<td><input align="left" type="image" id ="foco" src="../imagenes/imprimir.png" name="imprimir" onClick="window.print();"/></td>
</table>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>