<?php error_reporting(E_ALL ^ E_NOTICE);
require_once("menu_inventario.php");

if (isset($_GET['campo'])) {
$campo = $_GET['campo'];
$ord = $_GET['ord'];
} else {
$campo = 'nombre_productos';
$ord = 'asc';
}
?>
<center>
<form action="" method="post">
<td><strong><font color='white'>BUSCAR PRODUCTOS: </font></strong></td><input name="buscar" required autofocus />
<input type="submit" name="buscador" value="Buscar productos" />
</form>
</center>
<?php
$buscar = $_POST['buscar'];

$mostrar_datos_sql = "SELECT * FROM productos WHERE (cod_productos_var = '$buscar' OR nombre_productos LIKE '%$buscar%') ORDER BY $campo $ord";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());

$total_productos_inventario = "SELECT * FROM productos";
$consulta_inventario = mysql_query($total_productos_inventario, $conectar) or die(mysql_error());
$total_productos = mysql_num_rows($consulta_inventario);

$total_unidades = "SELECT Sum(unidades_faltantes) AS unidades_faltantes FROM productos";
$consulta_inventario_total_unidades = mysql_query($total_unidades, $conectar) or die(mysql_error());
$inventario_total_unidades = mysql_fetch_assoc($consulta_inventario_total_unidades);

$calculos_inventario = "SELECT Sum(precio_costo * unidades_faltantes) As tot_precio_costo, Sum(precio_venta * unidades_faltantes) As tot_precio_venta, 
Sum((unidades_faltantes / unidades) * precio_compra) As tot_precio_compra, Sum((unidades_faltantes / unidades) * vlr_total_venta) As tot_vlr_total_venta FROM productos";
$consulta_calculos_inventario = mysql_query($calculos_inventario, $conectar) or die(mysql_error());
$matriz_inventario = mysql_fetch_assoc($consulta_calculos_inventario);

$sql_ultima_llave_productos = "SELECT max(cod_productos) AS ultimo FROM productos";
$consulta_ultima_llave_productos = mysql_query($sql_ultima_llave_productos, $conectar) or die(mysql_error());
$resultado = mysql_fetch_assoc($consulta_ultima_llave_productos);

$ultimo_id = $resultado['ultimo'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'inputon';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inputoff';
if (last != valor)
myajax.Link('guardar_inventario_productos.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<td align="center"><font color="yellow" size="+2"><strong>INVENTARIO DE PRODUCTOS - </font><a href="../admin/inventario_productos_no_editable.php"><font color="yellow" size="+2">OTRO</strong></font></a></td>
<br><br>
<!-- Total mercancia venta y utilidad mes -->
<table width='70%'  border='1'>
<td align="center">TOTAL CODIGOS</td>
<td align="center">TOTAL ITEMS</td>
<td align="center">TOTAL COSTO MET</td>
<td align="center">TOTAL VENTA MET (PROYEC)</td>
<td align="center">TOTAL COSTO UND (PROYEC)</td>
<td align="center">TOTAL VENTA UND (PROYEC)</td>
<td align="center">ULTIMO ID</td>
<tr>
<td align="center"><font color="yellow" size="+1"><strong><?php echo $total_productos; ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($inventario_total_unidades['unidades_faltantes'], 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($matriz_inventario['tot_precio_costo'], 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($matriz_inventario['tot_precio_venta'], 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($matriz_inventario['tot_precio_compra'], 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($matriz_inventario['tot_vlr_total_venta'], 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo $ultimo_id; ?></font></td>

</tr>
</table>
<br>
<table width='100%' border='1'>
<tr>
<?php
if ($ord == 'desc') {?>
<td align="center">C&Oacute;DIGO <br><a href="../admin/inventario_productos.php?campo=cod_productos_var&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">PRODUCTO <br><a href="../admin/inventario_productos.php?campo=nombre_productos&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center" title='Si el producto se vende menudiado coloque aqui las unidades que contine la bolsa o el paquete donde viene, si se vende por unidades completas coloque el valor 1.'>MED <br><a href="../admin/inventario_productos.php?campo=unidades&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">T.UN.MET <br><a href="../admin/inventario_productos.php?campo=unidades_faltantes&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center" title='Unidad de medida del producto (UND, MT, CM, HJ, LT, KG)'>MET <br><a href="../admin/inventario_productos.php?campo=detalles&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center" title='Precio de costo de cada unidad que tiene la bolsa o el paquete donde viene. precio costo menudiado. (P.COSTO.UND / MED).'>P.COSTO.MET <br><a href="../admin/inventario_productos.php?campo=precio_costo&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center" title='Precio de venta de cada unidad que tiene la bolsa o el paquete donde viene. precio venta menudiado. (P.VENTA.UND / MED).'>P.VENTA.MET <br><a href="../admin/inventario_productos.php?campo=precio_venta&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center" title='Precio de costo de la unidad, la bolsa o el paquete completo.'>P.COSTO.UND <br><a href="../admin/inventario_productos.php?campo=precio_compra&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center" title='Precio de venta de la unidad, la bolsa o el paquete completo.'>P.VENTA.UND <br><a href="../admin/inventario_productos.php?campo=vlr_total_venta&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<?php
} else {
?>
<td align="center">C&Oacute;DIGO <br><a href="../admin/inventario_productos.php?campo=cod_productos_var&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">PRODUCTO <br><a href="../admin/inventario_productos.php?campo=nombre_productos&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center" title='Si el producto se vende menudiado coloque aqui las unidades que contine la bolsa o el paquete donde viene, si se vende por unidades completas coloque el valor 1.'>MED <br><a href="../admin/inventario_productos.php?campo=unidades&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">T.UN.MET <br><a href="../admin/inventario_productos.php?campo=unidades_faltantes&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center" title='Unidad de medida del producto (UND, MT, CM, HJ, LT, KG)'>MET <br><a href="../admin/inventario_productos.php?campo=detalles&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center" title='Precio de costo de cada unidad que tiene la bolsa o el paquete donde viene. precio costo menudiado. (P.COSTO.UND / MED).'>P.COSTO.MET <br><a href="../admin/inventario_productos.php?campo=precio_costo&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center" title='Precio de venta de cada unidad que tiene la bolsa o el paquete donde viene. precio venta menudiado. (P.VENTA.UND / MED).'>P.VENTA.MET <br><a href="../admin/inventario_productos.php?campo=precio_venta&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center" title='Precio de costo de la unidad, la bolsa o el paquete completo.'>P.COSTO.UND <br><a href="../admin/inventario_productos.php?campo=precio_compra&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center" title='Precio de venta de la unidad, la bolsa o el paquete completo.'>P.VENTA.UND <br><a href="../admin/inventario_productos.php?campo=vlr_total_venta&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<?php }?>
</tr>
<?php 
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos = $datos['cod_productos'];
$cod_productos_var = $datos['cod_productos_var'];
$nombre_productos = $datos['nombre_productos'];
$unidades = $datos['unidades'];
$unidades_faltantes = $datos['unidades_faltantes'];
$detalles = $datos['detalles'];
$precio_costo = $datos['precio_costo'];
$precio_venta = $datos['precio_venta'];
$precio_compra = $datos['precio_compra'];
$vlr_total_venta = $datos['vlr_total_venta'];
?>
<tr>
<td align='center' title='Codigo del producto'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_productos_var', <?php echo $cod_productos;?>)" class="cajbarras" id="<?php echo $cod_productos;?>" value="<?php echo $cod_productos_var;?>" size="3"></td>
<td align='left' title='Nombre del producto'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'nombre_productos', <?php echo $cod_productos;?>)" class="cajsuper" id="<?php echo $cod_productos;?>" value="<?php echo $nombre_productos;?>" size="3"></td>
<td align='center' title='Si el producto se vende menudiado coloque aqui las unidades que contine la bolsa o el paquete donde viene, si se vende por unidades completas coloque el valor 1.'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $unidades;?>" size="3"></td>
<td align='center' title='Total unidades en inventario'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_faltantes', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $unidades_faltantes;?>" size="3"></td>
<td align='center' title='Unidad de medida del producto (UND, MT, CM, HJ, LT, KG)'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'detalles', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $detalles;?>" size="3"></td>
<td align='center' title='Precio de costo de cada unidad que tiene la bolsa o el paquete donde viene. precio costo menudiado. (P.COSTO.UND / MED).'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_costo', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_costo;?>" size="3"></td>
<td align='center' title='Precio de venta de cada unidad que tiene la bolsa o el paquete donde viene. precio venta menudiado. (P.VENTA.UND / MED).'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_venta;?>" size="3"></td>
<td align='center' title='Precio de costo de la unidad, la bolsa o el paquete completo.'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_compra', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_compra;?>" size="3"></td>
<td align='center' title='Precio de venta de la unidad, la bolsa o el paquete completo.'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'vlr_total_venta', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $vlr_total_venta;?>" size="3"></td>
</tr>
<?php } ?>
</table>
</form>
</body>
</html>