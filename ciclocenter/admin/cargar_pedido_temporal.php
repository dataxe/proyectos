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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");
/*
$datos_factura = "SELECT * FROM cargar_factura_temporal WHERE vendedor = '$cuenta_actual'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$total_datos = mysql_num_rows($consulta);
*/
$sql = "SELECT * FROM pedidos_temporal WHERE vendedor = '$cuenta_actual' ORDER BY cod_pedidos_temporal DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<?php
require_once("busqueda_inmediata_cargar_pedido_temporal.php");
if ($total_datos <> 0) {
require_once("informacion_factura_pedido.php");
}
?>
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
myajax.Link('guardar_cargar_pedido_temporal.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<?php
if ($total_datos <> 0) {
?>
<table width="100%">
<tr>
<td><div align="center"><strong>ELM</strong></div></td>
<td><div align="center"><font><strong>C&Oacute;DIGO</strong></font></div></td>
<td><div align="center"><font><strong>COD.FACT</strong></font></div></td>
<td><div align="center"><strong>BARRAS</strong></div></td>
<td><div align="center"><font><strong>PRODUCTO</strong></font></div></td>
<td><div align="center"><strong>CAJ</strong></div></td>
<td><div align="center"><strong>UND</strong></div></td>
<td><div align="center"><strong>T.UND</strong></div></td>
<td><div align="center"><strong>P.COMP</strong></div></td>
<td><div align="center"><strong>%DT1</strong></div></td>
<!--<td><div align="center"><strong>%DT2</strong></div></td>-->
<!--<td><div align="center"><font size='1' color='yellow'><strong>DESC</strong></font></div></td>-->
<td><div align="center"><strong>%IVA</strong></div></td>
<td><div align="center"><font><strong>V.IVA</strong></font></div></td>
<td><div align="center"><strong>IVA</strong></div></td>
<!--<td><div align="center"><strong>%VTA</strong></div></td>-->
<td><div align="center"><font><strong>P.COST</strong></font></div></td>
<td><div align="center"><strong>P.VENT</strong></div></td>
<td><div align="center"><strong>STK</strong></div></td>
<td><div align="center"><font><strong>V.FACTDO</strong></font></div></td>
<td><div align="center"><strong>OK</strong></div></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos = $datos['cod_productos'];
$cod_original = $datos['cod_original'];
$nombre_productos = $datos['nombre_productos'];
$cajas = $datos['cajas'];
$unidades = $datos['unidades'];
$unidades_vendidas = $datos['unidades_vendidas'];
$unidades_total = $datos['unidades_total'];
$tope_min = $datos['tope_min'];
$precio_compra = $datos['precio_compra'];
$precio_venta = $datos['precio_venta'];
$dto1 = $datos['dto1'];
$dto2 = $datos['dto2'];
$iva = $datos['iva'];
$valor_iva = $datos['valor_iva'];
$ptj_ganancia = $datos['ptj_ganancia'];
$precio_costo = $datos['precio_costo'];
$vlr_total_compra = $datos['vlr_total_compra'];
$porcentaje_vendedor = $datos['porcentaje_vendedor'];
$fechas_vencimiento = $datos['fechas_vencimiento'];
$vlr_total_venta = $datos['vlr_total_venta'];
$vlr_total_venta = $datos['vlr_total_venta'];
$descuento = $datos['descuento'];
$detalles = $datos['detalles'];
$iva_v = $datos['iva_v'];
?>
<tr>
<td><a href="../modificar_eliminar/eliminar_cargar_pedidos_temporal.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_pedidos_temporal=<?php echo $datos['cod_pedidos_temporal']?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td><?php echo $cod_productos;?></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_original', <?php echo $cod_productos;?>)" class="cajextragrand" id="<?php echo $cod_productos;?>" value="<?php echo $cod_original;?>" size="6"></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'detalles', <?php echo $cod_productos;?>)" class="cajbarras" id="<?php echo $cod_productos;?>" value="<?php echo $detalles;?>" size="3"></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'nombre_productos', <?php echo $cod_productos;?>)" class="cajsuper" id="<?php echo $cod_productos;?>" value="<?php echo $nombre_productos;?>" size="40"></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cajas', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $cajas;?>" size="3"></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $unidades;?>" size="3"></td>
<td><div align='right'><?php echo $unidades_total;?></div></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_compra', <?php echo $cod_productos;?>)" class="cajextragrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_compra;?>" size="4"></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'dto1', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $dto1;?>" size="2"></td>
<!--<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'dto2', <?php //echo $cod_productos;?>)" class="cajpequena" id="<?php //echo $cod_productos;?>" value="<?php //echo $dto2;?>" size="2"></td>-->
<!--<td><div align='right'><font size='2' color='yellow'><?php //echo number_format($descuento);?></div></td>-->
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'iva', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $iva;?>" size="2"></td>
<td><div align='right'><?php echo number_format($valor_iva);?></div></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'iva_v', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $iva_v;?>" size="3"></td>
<!--<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'ptj_ganancia', <?php //echo $cod_productos;?>)" class="alingcenter" id="<?php //echo $cod_productos;?>" value="<?php //echo $ptj_ganancia;?>" size="2"></td>-->
<td><div align='right'><?php echo number_format($precio_costo);?></div></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_productos;?>)" class="cajextragrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_venta;?>" size="4"></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'tope_min', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $tope_min;?>" size="3"></td>
<td><div align='right'><?php echo number_format($vlr_total_venta);?></div></td>
<td><a href="<?php $_SERVER['PHP_SELF']?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td>
</tr>
<?php } ?>
</table>
<?php
} else {
}
?>
</form>