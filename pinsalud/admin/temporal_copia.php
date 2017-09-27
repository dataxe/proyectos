<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar); 

$cuenta_actual = addslashes($_SESSION['usuario']);

$datos_factura = "SELECT * FROM temporal WHERE vendedor = '$cuenta_actual'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$total_datos = mysql_num_rows($consulta);

$datos_info_factura = "SELECT * FROM info_impuesto_facturas WHERE vendedor = '$cuenta_actual' AND estado = '$valor_factura'";
$consulta_info_factura = mysql_query($datos_info_factura, $conectar) or die(mysql_error());
$info_factura = mysql_fetch_assoc($consulta_info_factura);

$suma_temporal = "SELECT  Sum(precio_compra_con_descuento) As total_venta FROM temporal WHERE vendedor = '$cuenta_actual'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$matriz_temporal = mysql_fetch_assoc($consulta_temporal);

$datos_info = "SELECT * FROM info_impuesto_facturas WHERE estado = 'abierto' AND vendedor = '$cuenta_actual'";
$consulta_info = mysql_query($datos_info, $conectar) or die(mysql_error());
$info = mysql_fetch_assoc($consulta_info);
$cantidad_resultado = mysql_num_rows($consulta_info);

$maxima_factura = "SELECT Max(cod_factura) AS cod_factura FROM info_impuesto_facturas";
$consulta_maxima = mysql_query($maxima_factura, $conectar) or die(mysql_error());
$maxima = mysql_fetch_assoc($consulta_maxima);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.jeditable.js"></script>
<script type="text/javascript" src="js.js"></script>

</head>
<body>
<?php
require("funcion_verificar.php");
$requerir_funcion = new bloquear_multiple_intento;
?>
<center>
<form method="post" name="formulario" action="../admin/venta_productos.php">
<table id="table" width="1100">
<td><a href="../admin/busq_facturas_fecha.php"><img src=../imagenes/deboluciones.png alt="Deboluciones"></a></td>

<td>Factura No <input type="text" style="font-size:20px" name="numero_factura" value="<?php if ($cantidad_resultado <> '0') {
echo $info['cod_factura']; } else { echo $maxima['cod_factura']+1;}?>" size="3" required autofocus></td>
<!--<td><strong>Factura No</strong></td>
<td><div class="text" id="cod_factura-<?php //echo $cuenta_actual?>-<?php //echo $cuenta_actual?>"><?php //if ($cantidad_resultado <> '0') {
//echo $info['cod_factura']; } else { echo $maxima['cod_factura']+1;}?>
</div></td>-->
<td><strong>Subtotal </strong><font color='yellow' size= "+1"><?php echo number_format($matriz_temporal['total_venta']); ?></font></td>
<!--<td>Descuento <input type="text" style="height:26" name="descuento_factura" value="0" size="4" required autofocus></td>-->
<td>$Descuento <input type="text" style="font-size:20px" name="descuento_factura" value="0" size="4" required autofocus></td>
<!--<td><strong>$Descuento</strong></td>
<td><div class="text" id="descuento-<?php// echo $cuenta_actual?>-<?php// echo $cuenta_actual?>"><?php //echo $info_factura['descuento'];?></div></td>
<td><strong>Subtotal </strong></td>
<td><font color='yellow' size= "+1"><?php //echo number_format($matriz_temporal['total_venta'] - $info_factura['descuento']); ?></font></td>
<<td>Iva % <input type="text" style="height:26" name="iva" value="0" size="3" required autofocus></td>-->
<td>%Iva <input type="text" style="font-size:20px" name="iva" value="0" size="4" required autofocus></td>
<!--<td><strong>%Iva</strong></strong></td>
<td><div class="text" id="iva-<?php //echo $cuenta_actual?>-<?php //echo $cuenta_actual?>"><?php//echo $info_factura['iva'];?></div></td>
<td>Cancelado <input type="text" style="height:26" name="vlr_cancelado" value="" size="4" required autofocus></td>-->

<td>Cliente<select name="cod_clientes">
<?php $sql_consulta="SELECT * FROM clientes ORDER BY clientes.cod_clientes ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:17px" value="<?php echo $contenedor['cod_clientes'] ?>"><?php echo $contenedor['nombres'].' '.$contenedor['apellidos'] ?></option>
<?php }?>
</select></td>

<td>Recibido <input type="text" style="font-size:28px" name="vlr_cancelado" value="" size="6" required autofocus></td>
<!--<td><strong>Recibido</strong></td>
<td><div class="text" id="vlr_cancelado-<?php //echo $cuenta_actual?>-<?php //echo $cuenta_actual?>"><?php //echo $info_factura['vlr_cancelado'];?></div></td>
<td><strong>Cambio </strong></td>
<td><font color='yellow' size= "+2"><?php //echo number_format($info_factura['vlr_cancelado'] - ($matriz_temporal['total_venta'] - $info_factura['descuento'])); ?></font></td>-->

<input type="hidden" name="flete" value="0" size="15">
<input type="hidden" name="verificacion_envio" value="1" size="15">
<td nowrap><a href="../admin/venta_directa.php"><img src=../imagenes/ver_total.png alt="ver total"></a></td>
<td><a><input type="image" src="../imagenes/ok.png" name="vender" value="Guardar" /></a></td>
</table>
<?php 
if ($total_datos <> '0') { ?>
<table id="table" width="1100">
<tr>
<td><div align="center"><strong>C&oacute;digo</strong></div></td>
<td><div align="center"><strong>Producto</strong></div></td>
<td><div align="center"><strong>Unds</strong></div></td>
<td><div align="center"><strong>V. unitario</strong></div></td>
<td><div align="center"><strong>V. total</strong></div></td>
<td><div align="center"><strong></strong></div></td>
</tr>
<?php do { 
$cod_productos = $datos['cod_productos'];
$cod_temporal = $datos['cod_temporal'];
?>
<tr>
<td><font color='yellow'><div id="-"><?php echo $datos['cod_productos']; ?></div></font></td>
<td><font color='yellow'><div id="-"><?php echo $datos['nombre_productos']; ?></div></font></td>
<td><div class="text" id="unidades_vendidas-<?php echo $cod_productos ?>-<?php echo $cod_temporal ?>"><?php echo $datos['unidades_vendidas'];?></div></td>
<td align="right"><font color='yellow'><div id="-"><?php echo number_format($datos['precio_venta']); ?></div></font></td>
<td align="right"><font color='yellow'><div id="-"><?php echo number_format($datos['vlr_total_venta']); ?></div></font></td>
<td ><a href="../modificar_eliminar/eliminar_temporal_productos.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_temporal=<?php echo $datos['cod_temporal']?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>

<input type="hidden" name="cod_producto" value="<?php echo $datos['cod_productos'];?>">
<input type="hidden" name="cod_productos[]" value="<?php echo $datos['cod_productos'];?>">
<input type="hidden" name="nombre_productos[]" value="<?php echo $datos['nombre_productos'];?>">
<input type="hidden" name="unidades_vendidas[]" value="<?php echo $datos['unidades_vendidas'];?>">
<input type="hidden" name="precio_venta[]" value="<?php echo $datos['precio_venta'];?>">
<input type="hidden" name="vlr_total_venta[]" value="<?php echo $datos['vlr_total_venta'];?>">
<input type="hidden" name="precio_compra[]" value="<?php echo $datos['precio_compra'];?>">
<input type="hidden" name="precio_costo[]" value="<?php echo $datos['precio_costo'];?>">
<input type="hidden" name="vlr_total_compra[]" value="<?php echo $datos['vlr_total_compra'];?>">
<input type="hidden" name="precio_compra_con_descuento[]" value="<?php echo $datos['total_venta'];?>">
<input type="hidden" name="descuento[]" value="<?php echo $datos['descuento'];?>">
<input type="hidden" name="total_datos" value="<?php echo $total_datos;?>">
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<?php } else {
}?>
<?php $requerir_funcion->iniciar(); ?>
</form>