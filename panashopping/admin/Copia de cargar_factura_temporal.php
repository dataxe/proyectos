<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 

$datos_factura = "SELECT * FROM cargar_factura_temporal";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$total_datos = mysql_num_rows($consulta);

$suma_temporal = "SELECT  Sum(precio_compra_con_descuento) As precio_compra_con_descuento FROM cargar_factura_temporal ";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$matriz_temporal = mysql_fetch_assoc($consulta_temporal);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="jquery.jeditable.js"></script>
<script type="text/javascript" src="js_guardar.js"></script>
</head>
<body>
<?php 
if ($total_datos == NULL) {

} else {
?>
<center>
<table id="table" width="1100">
<form method="post" name="formulario" action="../admin/cargar_factura.php">
<tr>
<td><div align="center"><strong>C&oacute;digo</strong></div></td>
<td><div align="center"><strong>Producto</strong></div></td>
<td><div align="center"><strong>Cant</strong></div></td>
<td><div align="center"><strong>V. unitario</strong></div></td>
<td><div align="center"><strong>V. total</strong></div></td>
<td><div align="center"><strong>Eliminar</strong></div></td>
<td><select name="cod_proveedores">
<?php $sql_consulta="SELECT cod_proveedores, nombre_proveedores FROM proveedores ORDER BY proveedores.nombre_proveedores ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['cod_proveedores'] ?>"><?php echo $contenedor['nombre_proveedores'] ?></option>
<?php }?>
</select><input type="submit" name="vender" value="Guardar Todo" /></td>
</tr>
<?php do { 
$id = $datos['cod_productos'];
$cod_cargar_factura_temporal = $datos['cod_cargar_factura_temporal'];
?>
<tr>
<td><div class="text" id="cod_productos-<?php echo $id ?>-<?php echo $cod_cargar_factura_temporal ?>"><?php echo $datos['cod_productos'];?></div></td>
<td><div class="text" id="nombre_productos-<?php echo $id ?>-<?php echo $cod_cargar_factura_temporal ?>"><?php echo $datos['nombre_productos'];?></div></td>
<td><div class="text" id="unidades_vendidas-<?php echo $id ?>-<?php echo $cod_cargar_factura_temporal ?>"><?php echo $datos['unidades_vendidas'];?></div></td>
<td><div class="text" id="precio_venta-<?php echo $id ?>-<?php echo $cod_cargar_factura_temporal ?>"><?php echo $datos['precio_venta'];?></div></td>
<td><div class="text" id="vlr_total_venta-<?php echo $id ?>-<?php echo $cod_cargar_factura_temporal ?>"><?php echo $datos['vlr_total_venta'];?></div></td>

<input type="hidden" name="cod_producto" value="<?php echo $datos['cod_productos'];?>">
<input type="hidden" name="cod_productos[]" value="<?php echo $datos['cod_productos'];?>">
<input type="hidden" name="nombre_productos[]" value="<?php echo $datos['nombre_productos'];?>">
<input type="hidden" name="unidades_vendidas[]" value="<?php echo $datos['unidades_vendidas'];?>">
<input type="hidden" name="precio_venta[]" value="<?php echo $datos['precio_venta'];?>">
<input type="hidden" name="vlr_total_venta[]" value="<?php echo $datos['vlr_total_venta'];?>">
<input type="hidden" name="precio_compra[]" value="<?php echo $datos['precio_compra'];?>">
<input type="hidden" name="precio_costo[]" value="<?php echo $datos['precio_costo'];?>">
<input type="hidden" name="vlr_total_compra[]" value="<?php echo $datos['vlr_total_compra'];?>">
<input type="hidden" name="precio_compra_con_descuento[]" value="<?php echo $datos['precio_compra_con_descuento'];?>">
<input type="hidden" name="descuento[]" value="<?php echo $datos['descuento'];?>">
<input type="hidden" name="total_datos" value="<?php echo $total_datos;?>">
<td nowrap><a href="../modificar_eliminar/eliminar_temporal_productos.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_cargar_factura_temporal=<?php echo $datos['cod_cargar_factura_temporal']?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
<!--
<table id="table" width="800">
<tr>
<td><div align="center"><strong>Subtotal</strong></div></td>
<td><div align="center"><strong>Descuento</strong></div></td>
<td><div align="center"><strong>Subtotal</strong></div></td>
<td><div align="center"><strong>Iva %</strong></div></td>-->
<!--<td><div align="center"><strong>FLETE</strong></div></td>-->
<!--<td><div align="center"><strong>Recibido</strong></div></td>
<td><div align="center"><strong>Total</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td><?php //echo number_format($matriz_total_consulta['vlr_totl']); ?></td>
<td width="10"><input type="text" name="descuento_factura" value="0" size="15" required autofocus></td>
<td><?php //echo number_format($calculo_subtotal); ?></td>
<td width="10"><input type="text" name="iva" value="0" size="15" required autofocus></td>
<input type="hidden" name="flete" value="0" size="15" required autofocus>
<td width="10"><input type="text" name="vlr_cancelado" value="" size="15"></td>
<td width="10"><input type="text" name="vlr_cancelado" value="" size="15" required autofocus></td>

<td><?php //echo '.'; ?></td>
<input type="hidden" name="confirmacion_envio" value="si" size="15">
</tr>-->
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
</form>
<!--<td align="center"><input type="submit" value="Vender Lista" /></td>-->
</table>
<?php
}
?>