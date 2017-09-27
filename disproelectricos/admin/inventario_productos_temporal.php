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
//include ("../registro_movimientos/registro_cierre_caja.php");

if (isset($_GET['campo'])) {
$campo = addslashes($_GET['campo']);
$ord = addslashes($_GET['ord']);
} else {
$campo = 'nombre_productos';
$ord = 'asc';
}
?>
<?php
//$buscar = $_POST['buscar'];

$mostrar_datos_sql = "SELECT productos.cod_productos, productos_temporal.cod_productos AS cod_productos_llave, 
productos_temporal.cod_productos_var AS cod_inventario_temp, productos_temporal.cod_interno, 
productos_temporal.nombre_productos, productos_temporal.unidades, productos_temporal.unidades_faltantes, 
productos_temporal.detalles, productos_temporal.precio_costo, productos_temporal.precio_venta, 
productos_temporal.precio_compra, productos_temporal.vlr_total_compra, productos_temporal.vlr_total_venta, 
productos.cod_productos_var AS cod_inventario_prod 
FROM productos_temporal LEFT JOIN productos ON productos_temporal.cod_productos_var = productos.cod_productos_var ORDER BY productos_temporal.$campo $ord";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$tota_datos = mysql_num_rows($consulta);

$pagina = $_SERVER['PHP_SELF'];
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
myajax.Link('guardar_inventario_productos_temporal.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<br>
<td align="center"><font color="yellow" size="+2">TOTAL REGISTROS: <?php echo $tota_datos ?> </font></td>

<table width='100%' border='1'>
<tr>
<?php
if ($ord == 'desc') {?>
<td align="center">ELM</td>
<td align="center">C&Oacute;DIGO <br><a href="../admin/inventario_productos_temporal.php?campo=cod_productos_var&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">PRODUCTO <br><a href="../admin/inventario_productos_temporal.php?campo=nombre_productos&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center" title='Si el producto se vende menudiado coloque aqui las unidades que contine la bolsa o el paquete donde viene, si se vende por unidades completas coloque el valor 1.'>MED <br><a href="../admin/inventario_productos_temporal.php?campo=unidades&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">T.UN.MET <br><a href="../admin/inventario_productos_temporal.php?campo=unidades_faltantes&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center" title='Unidad de medida del producto (UND, MT, CM, HJ, LT, KG)'>MET <br><a href="../admin/inventario_productos_temporal.php?campo=detalles&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center" title='Precio de costo de cada unidad que tiene la bolsa o el paquete donde viene. precio costo menudiado. (P.COSTO.UND / MED).'>P.COSTO.MET <br><a href="../admin/inventario_productos_temporal.php?campo=precio_costo&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center" title='Precio de venta de cada unidad que tiene la bolsa o el paquete donde viene. precio venta menudiado. (P.VENTA.UND / MED).'>P.VENTA.MET <br><a href="../admin/inventario_productos_temporal.php?campo=precio_venta&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center" title='Precio de costo de la unidad, la bolsa o el paquete completo.'>P.COSTO.UND <br><a href="../admin/inventario_productos_temporal.php?campo=precio_compra&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center" title='Precio de venta de la unidad, la bolsa o el paquete completo.'>P.VENTA.UND <br><a href="../admin/inventario_productos_temporal.php?campo=vlr_total_venta&ord=asc"><img src=../imagenes/asc.png alt="asc"></a></td>
<td align="center">ACCION</td>
<?php
} else {
?>
<td align="center">ELM</td>
<td align="center">C&Oacute;DIGO <br><a href="../admin/inventario_productos_temporal.php?campo=cod_productos_var&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">PRODUCTO <br><a href="../admin/inventario_productos_temporal.php?campo=nombre_productos&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center" title='Si el producto se vende menudiado coloque aqui las unidades que contine la bolsa o el paquete donde viene, si se vende por unidades completas coloque el valor 1.'>MED <br><a href="../admin/inventario_productos_temporal.php?campo=unidades&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">T.UN.MET <br><a href="../admin/inventario_productos_temporal.php?campo=unidades_faltantes&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center" title='Unidad de medida del producto (UND, MT, CM, HJ, LT, KG)'>MET <br><a href="../admin/inventario_productos_temporal.php?campo=detalles&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center" title='Precio de costo de cada unidad que tiene la bolsa o el paquete donde viene. precio costo menudiado. (P.COSTO.UND / MED).'>P.COSTO.MET <br><a href="../admin/inventario_productos_temporal.php?campo=precio_costo&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center" title='Precio de venta de cada unidad que tiene la bolsa o el paquete donde viene. precio venta menudiado. (P.VENTA.UND / MED).'>P.VENTA.MET <br><a href="../admin/inventario_productos_temporal.php?campo=precio_venta&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center" title='Precio de costo de la unidad, la bolsa o el paquete completo.'>P.COSTO.UND <br><a href="../admin/inventario_productos_temporal.php?campo=precio_compra&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center" title='Precio de venta de la unidad, la bolsa o el paquete completo.'>P.VENTA.UND <br><a href="../admin/inventario_productos_temporal.php?campo=vlr_total_venta&ord=desc"><img src=../imagenes/desc.png alt="desc"></a></td>
<td align="center">ACCION</td>
<?php }?>
</tr>
<?php 
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos = $datos['cod_productos'];
$cod_productos_llave = $datos['cod_productos_llave'];
$cod_productos_var = $datos['cod_inventario_temp'];
$cod_productos_var_inv = $datos['cod_inventario_prod'];
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
<td align="center"><a href="../modificar_eliminar/eliminar_productos_temporal.php?cod_productos=<?php echo $cod_productos_llave?>&pagina=<?php echo $pagina?>"><img src=../imagenes/eliminar.png alt="Eliminar"></a></td>
<td align='left' title='Nombre del producto'><?php echo $cod_productos_var;?></td>
<td align='left' title='Nombre del producto'><?php echo $nombre_productos;?></td>
<td align='center' title='Si el producto se vende menudiado coloque aqui las unidades que contine la bolsa o el paquete donde viene, si se vende por unidades completas coloque el valor 1.'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades', <?php echo $cod_productos_llave;?>)" class="cajpequena" id="<?php echo $cod_productos_llave;?>" value="<?php echo $unidades;?>" size="3"></td>
<td align='center' title='Total unidades en inventario'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_faltantes', <?php echo $cod_productos_llave;?>)" class="cajgrand" id="<?php echo $cod_productos_llave;?>" value="<?php echo $unidades_faltantes;?>" size="3"></td>
<td align='center' title='Unidad de medida del producto (UND, MT, CM, HJ, LT, KG)'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'detalles', <?php echo $cod_productos_llave;?>)" class="cajgrand" id="<?php echo $cod_productos_llave;?>" value="<?php echo $detalles;?>" size="3"></td>
<td align='center' title='Precio de costo de cada unidad que tiene la bolsa o el paquete donde viene. precio costo menudiado. (P.COSTO.UND / MED).'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_costo', <?php echo $cod_productos_llave;?>)" class="cajgrand" id="<?php echo $cod_productos_llave;?>" value="<?php echo $precio_costo;?>" size="3"></td>
<td align='center' title='Precio de venta de cada unidad que tiene la bolsa o el paquete donde viene. precio venta menudiado. (P.VENTA.UND / MED).'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_productos_llave;?>)" class="cajgrand" id="<?php echo $cod_productos_llave;?>" value="<?php echo $precio_venta;?>" size="3"></td>
<td align='center' title='Precio de costo de la unidad, la bolsa o el paquete completo.'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_compra', <?php echo $cod_productos_llave;?>)" class="cajgrand" id="<?php echo $cod_productos_llave;?>" value="<?php echo $precio_compra;?>" size="3"></td>
<td align='center' title='Precio de venta de la unidad, la bolsa o el paquete completo.'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'vlr_total_venta', <?php echo $cod_productos_llave;?>)" class="cajgrand" id="<?php echo $cod_productos_llave;?>" value="<?php echo $vlr_total_venta;?>" size="3"></td>
<?php
if ($cod_productos == '') { ?>
<td align="center" title='Insertar Registro'><a href="../admin/insertar_productos_desde_productos_temporal.php?cod_productos=<?php echo $cod_productos_llave?>&pagina=<?php echo $pagina?>"><img src=../imagenes/boton_insertar.png alt="Insertar"></a></td>
<?php
} else { ?>
<td align="center" title='Actualizar Registro'><a href="../admin/actualizar_productos_desde_productos_temporal.php?cod_productos=<?php echo $cod_productos_llave?>&pagina=<?php echo $pagina?>"><img src=../imagenes/boton_actualizar.png alt="Actualizar"></a></td>
<?php
}
?>
</tr>
<?php } ?>
</table>
</form>
</body>
</html>