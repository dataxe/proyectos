<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar); 
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
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
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<head>
<?php
$consql = "SELECT * FROM temporal WHERE vendedor = '$cuenta_actual'";
$getanz = mysql_query($consql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($getanz);

require_once("bsucar_con_codigo_barras.php");
if ($total_datos <> 0) {
require_once("informacion_factura_venta_electronica_admin.php");
}
?>
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'cajhabiltada';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'cajdeshabiltada';
if (last != valor)
myajax.Link('guardar_cargar_factura_temporal1.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>
</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:0;">  
<?php
$unidad = 'unidad';
$caja = 'caja';
$pagina = $_SERVER['PHP_SELF'];

$sql = "SELECT * FROM temporal WHERE vendedor = '$cuenta_actual' ORDER BY cod_temporal DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());

if ($total_datos <> 0) {
?>
<table width="100%">
<tr>
<td align="center" title="Eliminar registro del carrito de venta"><strong><font size='3'>ELIM</font></strong></td>
<td align="center" title="Codigo del producto"><strong><font size='3'>C&Oacute;DIGO</font></strong></td>
<td align="center" title="Nombre del producto"><strong><font size='3'>PRODUCTO</font></strong></td>
<!--<td align="center" title="Total costo y total venta del producto"><strong><font size='3'>T.COST - T.VENT</font></strong></td>-->
<td align="center" title="Cantidad a vender"><strong><font size='3'>UND</font></strong></td>
<!--<td align="center" title="Forma de medida del producto, vender por metrica del producto"><strong><font size='3'>MET</font></strong></td>-->
<td align="center" title=""><strong><font size='3'>P.COSTO</font></strong></td>
<td align="center" title="Tipo de precio"><strong><font size='3'>T.P</font></strong></td>
<td align="center" title=""><strong><font size='3'>P.VENTA</font></strong></td>
<!--<td align="center" title="Porcentaje de ganancia en caso de vender menudiado"><strong><font size='3'>+%</font></strong></td>-->
<td align="center" title="Valor total de la venta"><strong><font size='3'>V.TOTAL</font></strong></td>
<td align="center" title="Aceptar los valores insertados"><strong><font size='3'>OK</font></strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_temporal = $datos['cod_temporal'];
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$unidades_cajas = $datos['unidades_cajas'];
$unidades_vendidas = $datos['unidades_vendidas'];
$precio_venta = $datos['precio_venta'];
$precio_costo = $datos['precio_costo'];
$vlr_total_venta = $datos['vlr_total_venta'];
$descripcion = $datos['fecha_mes'];
$detalles = $datos['detalles'];
$iva_v = $datos['iva_v'];
$precio_compra_con_descuento = $datos['precio_compra_con_descuento'];
$diferencia = $precio_venta - $precio_compra_con_descuento;
$ptj_difrencia = ($diferencia / $precio_compra_con_descuento) * 100;
$tipo_venta = $datos['tipo_venta'];
?>
<tr>
<td ><a href="../modificar_eliminar/eliminar_temporal_productos.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_temporal=<?php echo $datos['cod_temporal']?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td><?php echo $cod_productos;?></td>
<td><?php echo $nombre_productos;?></td>
<!--<td align='right'><?php echo $descripcion;?></td>-->
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades_vendidas', <?php echo $cod_temporal;?>)" class="cajund" id="<?php echo $cod_temporal;?>" value="<?php echo $unidades_vendidas;?>" size="3"></td>
<!--<td align='left'><font size='5'><?php echo $detalles;?></font></td>-->
<td align='right'><font size='6'><?php echo number_format($precio_costo, 0, ",", ".");?></font></td>

<?php if ($detalles == 'P.V') { ?> 
<td title="Precio venta al publico."><a href="../admin/actualizar_tipo_venta.php?tipo_precio=P.D&cod_temporal=<?php echo $cod_temporal?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/P.V.png alt="Precio venta"></center></a></td> 
<?php } 
elseif ($detalles == 'P.D') {?>
<td title="Precio venta con descuento."><a href="../admin/actualizar_tipo_venta.php?tipo_precio=P.M&cod_temporal=<?php echo $cod_temporal?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/P.D.png alt="Precio descuento"></center></a></td> 
<?php }
elseif ($detalles == 'P.M') {?>
<td title="Precio venta al por mayor."><a href="../admin/actualizar_tipo_venta.php?tipo_precio=P.V&cod_temporal=<?php echo $cod_temporal?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/P.M.png alt="Precio por mayor"></center></a></td> 
<?php } ?>

<td align='right'><font size='6'><?php echo number_format($precio_venta, 0, ",", ".");?></font></td>

<!--<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_temporal;?>)" class="cajvtotal" id="<?php echo $cod_temporal;?>" value="<?php echo $precio_venta;?>" size="3"></td>-->
<!--<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'iva_v', <?php echo $cod_temporal;?>)" class="cajund" id="<?php echo $cod_temporal;?>" value="<?php echo $iva_v;?>" size="3"></td>-->
<!-- 
<td align='right'><font size='6'><?php echo $ptj_difrencia.'%';?></font></td>
-->
<td align='right'><font size='6'><?php echo number_format($vlr_total_venta, 0, ",", ".");?></font></td>
<td><a href="<?php $_SERVER['PHP_SELF']?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td> 
</tr>
<?php } 
} else {
}
?>
</table>
</form>
</body>
</html>