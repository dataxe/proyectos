<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
?>
<center>
<table id="numero_factura" width="800">
<td nowrap align="right"><strong>FACTURA N&ordm;:</td>
<?php $obtener_facturacion = "SELECT cod_factura, numero_factura FROM factura_cod WHERE cod_factura = '1'";
$modificar_facturacion = mysql_query($obtener_facturacion, $conectar) or die(mysql_error());
$contenedor_factura = mysql_fetch_assoc($modificar_facturacion);
$num_facturacion = mysql_num_rows($modificar_facturacion); ?>
<td><input type="text" name="numero_factura" value="<?php echo $contenedor_factura['numero_factura']; ?>" size="8"></td>

<?php $factura_act = $contenedor_factura['numero_factura'];?>

<td nowrap align="right"><strong>FECHA:</td>
<?php $obtener_fecha = "SELECT * FROM facturacion WHERE cod_facturacion = '$factura_act'";
$consultar_fecha = mysql_query($obtener_fecha, $conectar) or die(mysql_error());
$matriz_fecha = mysql_fetch_assoc($consultar_fecha);
$num_fecha = mysql_num_rows($consultar_fecha); ?>
<td><input type="text" name="fech" value="<?php echo $matriz_fecha['fecha_anyo']; ?>" size="9"></td>
 </tr>
</table>
</center>
<?php
$buscar = $contenedor_factura['numero_factura'];

$datos_factura = "SELECT cantidad, cod_producto, cod_facturacion, nombre_productos, marca, descripcion, vlr_unitario, vlr_total FROM facturacion 
WHERE cod_facturacion like '$buscar'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$datos_total_factura = "SELECT  Sum(vlr_total) as vlr_totl FROM facturacion WHERE cod_facturacion like '$buscar'";
$consulta_total = mysql_query($datos_total_factura, $conectar) or die(mysql_error());
$matriz_total_consulta = mysql_fetch_assoc($consulta_total);

$datos_vendedor = "SELECT DISTINCT cod_facturacion, moto, nombre, direccion, ciudad, vendedor, vendedor_nombre FROM facturacion 
WHERE cod_facturacion = $buscar";
$total_vendedor = mysql_query($datos_vendedor, $conectar) or die(mysql_error());
$matriz_vendedor = mysql_fetch_assoc($total_vendedor);

$datos_impuesto = "SELECT cod_info_impuesto_facturas, descuento, iva, flete, cod_factura FROM info_impuesto_facturas 
WHERE cod_factura like '$factura_act'";
$total_impuesto = mysql_query($datos_impuesto, $conectar) or die(mysql_error());
$matriz_impuesto = mysql_fetch_assoc($total_impuesto);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php $calculo_subtotal = $matriz_total_consulta['vlr_totl'] - $_POST['descuento_factura']; 
$calculo_total = $calculo_subtotal;
$descuento_factura = $_POST['descuento_factura']; 
$iva = $_POST['iva']; 
$flete = $_POST['flete']; 
?>
<form method="post" name="name="formulario" action="../admin/imprimir_factura.php "target="_parent"">
<center>
</table>
<table id="table" width="800">
<tr>
<td><div align="center"><strong>CAN</strong></div></td>
<td><div align="center"><strong>REFERENCIA</strong></div></td>
<td><div align="center"><strong>PRODUCTO</strong></div></td>
<!--<td><div align="center"><strong>MARCA</strong></div></td>-->
<td><div align="center"><strong>DESCRIPCION</strong></div></td>
<td><div align="center"><strong>V. UNITARIO</strong></div></td>
<td><div align="center"><strong>V. TOTAL</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td><font color='yellow'><?php echo $matriz_consulta['cantidad']; ?></font></td>
<td><font color='yellow'><?php echo $matriz_consulta['cod_producto']; ?></font></td>
<td><font color='yellow'><?php echo $matriz_consulta['nombre_productos']; ?></font></td>
<!--<td><font color='yellow'><?php //echo $matriz_consulta['marca']; ?></font></td>-->
<td><font color='yellow'><?php echo $matriz_consulta['descripcion']; ?></font></td>
<td align="right"><font color='yellow'><?php echo number_format($matriz_consulta['vlr_unitario']); ?></font></td>
<td align="right"><font color='yellow'><?php echo number_format($matriz_consulta['vlr_total']); ?></font></td>
</tr>	 
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>	
<table id="table" width="800">
<tr>
<td><div align="center"><strong>SUBTOTAL</strong></div></td>
<td><div align="center"><strong>DESCUENTO</strong></div></td>
<td><div align="center"><strong>SUBTOTAL</strong></div></td>
<td><div align="center"><strong>IVA %</strong></div></td>
<!--<td><div align="center"><strong>FLETE</strong></div></td>-->
<td><div align="center"><strong>CANCELADO</strong></div></td>
<td><div align="center"><strong>TOTAL</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td><?php echo number_format($matriz_total_consulta['vlr_totl']); ?></td>
<td width="10"><input type="text" name="descuento_factura" value="0" size="15" required autofocus></td>
<td><?php echo number_format($calculo_subtotal); ?></td>
<td width="10"><input type="text" name="iva" value="0" size="15" required autofocus></td>
<input type="hidden" name="flete" value="0" size="15" required autofocus>
<td width="10"><input type="text" name="vlr_cancelado" value="" size="15" required autofocus></td>
<td><?php echo '.'; ?></td>
<input type="hidden" name="confirmacion_envio" value="si" size="15">
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
<td align="center"><input type="submit" value="Cerrar Factura" /></td>
</table>
</form>