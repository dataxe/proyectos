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
$sql = "SELECT * FROM cargar_factura_temporal WHERE vendedor = '$cuenta_actual' ORDER BY cod_cargar_factura_temporal DESC";
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
require_once("busqueda_inmediata_cargar_factura_temporal_todo.php");
if ($total_datos <> 0) {
require_once("informacion_factura_compra.php");
}
?>
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'activo';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inactivo';
if (last != valor)
myajax.Link('guardar_cargar_factura_temporal.php?valor='+valor+'&campo='+campo+'&id='+id);
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
<td align='center' title="Eliminar registro de la factura."><strong>ELM</strong></td>
<td align='center' title="Codigo del producto."><font><strong>C&Oacute;DIGO</strong></font></td>
<td align='center' title="Nombre del producto."><font><strong>PRODUCTO</strong></font></td>
<td align='center' title="Cantidad que contiene el producto en su respectiva metrica."><strong>UN.MET</strong></td>
<td align='center' title="Unidad en que se mide el productos, metrica."><strong>MET</strong></td>
<td align='center' title="Presentacion. cantidad de unidades de ese producto."><strong>PRE</strong></td>
<td align='center' title="Total de producto en su respectiva unidad metrica. (UN.MET * PRE)"><strong>T.UN-MET</strong></td>
<td align='center' title="Precio costo en factura."><strong>P.COST-FACT</strong></td>
<td align='center' title="Iva en porcentaje. ej: 16."><strong>%IVA</strong></td>
<td align='center' title="Iva en pesos."><font><strong>$IVA</strong></font></td>
<td align='center' title="Total valor facturado. (P.COST-FACT * %IVA) * PRE"><font><strong>T.FACTDO</strong></font></td>
<!--
<td align='center' title="Total precio costo en factura. (PRE * P.COST-FACT)"><strong>T.COST-FACT</strong></td>

<td align='center' title="Descuento 1."><strong>%DT1</strong></td>
<td align='center' title="Descuento 2."><strong>%DT2</strong></td>
<td align='center' title="Total descuento."><strong>DESC</font></td>
-->
<td align='center' title="Precio costo en metrica. (P.COST-FACT / UN.MET) - (P.COST-FACT * %IVA)"><font><strong>COST.MET</strong></font></td>
<td align='center' title="Verificador para q el precio costo no sea mayor que el precio venta."><font><strong>ESD</strong></font></td>
<td align='center' title="Porcentaje de ganancia para el precio de venta."><font><strong>+%</strong></font></td>
<!--<td><strong>%VTA</strong></td>-->
<td align='center' title="Total precio venta del producto. (T.FACTDO * +%)"><strong>P.VENT-PUBLI</strong></td>
<td align='center' title="Precio venta del producto en metrica. (P.VENT-PUBLI / UN.MET)"><strong>P.VENT.M</strong></td>
<!--<td align='center'><strong>RYS</strong></td>
<td align='center'><strong>F.VENC</strong></td>-->
<td align='center' title="Stock minimo del producto."><strong>STK</strong></td>
<td align='center' title="Aceptar valores introducidos."><strong>OK</strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_cargar_factura_temporal = $datos['cod_cargar_factura_temporal'];
$cod_productos = $datos['cod_productos'];
$integrado = $cod_cargar_factura_temporal.'-'.$cod_productos;
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
$precio_compra_con_descuento = $datos['precio_compra_con_descuento'];
$descuento = $datos['descuento'];
$detalles = $datos['detalles'];
$total_precio_compra = $datos['precio_compra'] * $datos['cajas'];
$vlr_total_venta = $datos['vlr_total_venta'];
$ptj_ganancia = $datos['ptj_ganancia'];
?>
<tr>
<td title="Eliminar este registro de la factura."><a href="../modificar_eliminar/eliminar_cargar_productos_temporal.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_cargar_factura_temporal=<?php echo $cod_cargar_factura_temporal;?>" tabindex=3><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td title="Codigo del producto."><?php echo $cod_productos;?></td>
<td title="Nombre del producto."><?php echo $nombre_productos;?></td>
<td align='right'title="Cantidad que contiene el producto en su respectiva metrica."><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades', <?php echo $cod_cargar_factura_temporal;?>)" class="cajpequena" id="<?php echo $cod_cargar_factura_temporal;?>" value="<?php echo $unidades;?>" size="3"></td>
<td align='left' title="Unidad en que se mide el productos, metrica."><?php echo $detalles;?></td>
<td align='center' title="Presentacion. cantidad de unidades de ese producto."><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cajas', <?php echo $cod_cargar_factura_temporal;?>)" class="cajpequena" id="<?php echo $cod_cargar_factura_temporal;?>" value="<?php echo $cajas;?>" size="3"></td>
<td align='center' title="Total de producto en su respectiva unidad metrica. (UN.MET * PRE) (UN.MET * PRE)"><?php echo $unidades_total;?></td>
<td align='center' title="Precio costo en factura."><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_compra', <?php echo $cod_cargar_factura_temporal;?>)" class="cajgrand" id="<?php echo $cod_cargar_factura_temporal;?>" value="<?php echo $precio_compra;?>" size="4"></td>
<td align='center' title="Iva en porcentaje. ej: 16."><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'iva', <?php echo $cod_cargar_factura_temporal;?>)" class="cajpequena" id="<?php echo $cod_cargar_factura_temporal;?>" value="<?php echo $iva;?>" size="2"></td>
<td align='right' title="Iva en pesos."><?php echo number_format($valor_iva, 0, ",", ".");?></td>
<td align='right' title="Total valor facturado. (P.COST-FACT * %IVA) * PRE"><?php echo number_format($precio_compra_con_descuento, 0, ",", ".");?></td>
<!--
<td align='right'  title="Total precio costo en factura. (PRE * P.COST-FACT)"><?php echo number_format($total_precio_compra+($total_precio_compra*($iva/100)));?></td>

<td align='center' title="Descuento 1."><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'dto1', <?php echo $cod_cargar_factura_temporal;?>)" class="cajpequena" id="<?php echo $cod_cargar_factura_temporal;?>" value="<?php echo $dto1;?>" size="2"></td>
<td align='center' title="Descuento 2."><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'dto2', <?php echo $cod_cargar_factura_temporal;?>)" class="cajpequena" id="<?php echo $cod_cargar_factura_temporal;?>" value="<?php echo $dto2;?>" size="2"></td>
<td align='right' title="Total descuento."><?php echo number_format($descuento);?></td>
-->
<td align='right' title="Precio costo en metrica. (P.COST-FACT / UN.MET) - (P.COST-FACT * %IVA)"><?php echo number_format($precio_costo, 0, ",", ".");?></td>
<?php
// SI HAY MAS UNIADES FISICAS QUE EN EL SISTEMA. 
if ($precio_costo > $precio_venta) { ?> <td title="Advertencia el precio costo es mayor que el precio venta."><center><img src=../imagenes/admiracion.png alt="admiracion"></center></td>
<?php } else {?> <td title="Correcto. (P.VENT.M >= COST.MET)"><center><img src=../imagenes/bien.png alt="bien"></center></td> <?php } ?>
<!--<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'ptj_ganancia', <?php //echo $cod_cargar_factura_temporal;?>)" class="alingcenter" id="<?php //echo $cod_cargar_factura_temporal;?>" value="<?php //echo $ptj_ganancia;?>" size="2"></td>-->
<td align='right' title="Porcentaje de ganancia para el precio de venta."><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'ptj_ganancia', <?php echo $cod_cargar_factura_temporal;?>)" class="cajpequena" id="<?php echo $cod_cargar_factura_temporal;?>" value="<?php echo $ptj_ganancia;?>" size="4"></td>
<td align='right' title="Total precio venta del producto. (T.FACTDO * +%)"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'vlr_total_venta', <?php echo $cod_cargar_factura_temporal;?>)" class="cajgrand" id="<?php echo $cod_cargar_factura_temporal;?>" value="<?php echo $vlr_total_venta;?>" size="1"></td>
<td align='right' title="Precio venta del producto en metrica. (P.VENT-PUBLI / UN.MET)"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_cargar_factura_temporal;?>)" class="cajgrand" id="<?php echo $cod_cargar_factura_temporal;?>" value="<?php echo $precio_venta;?>" size="4"></td>
<!--
<td align='right'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'porcentaje_vendedor', <?php echo $cod_cargar_factura_temporal;?>)" class="cajpequena" id="<?php echo $cod_cargar_factura_temporal;?>" value="<?php echo $porcentaje_vendedor;?>" size="3" required placeholder="si/no"></td>
<td align='right'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fechas_vencimiento', <?php echo $cod_cargar_factura_temporal;?>)" class="fechas" id="<?php echo $cod_cargar_factura_temporal;?>" value="<?php echo $fechas_vencimiento;?>" size="9" required placeholder="dia/mes/a&ntilde;o"></td>
-->
<td align='right' title="Stock minimo del producto."><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'tope_min', <?php echo $cod_cargar_factura_temporal;?>)" class="cajpequena" id="<?php echo $cod_cargar_factura_temporal;?>" value="<?php echo $tope_min;?>" size="3"></td>
<td title="Aceptar valores introducidos."><a href="<?php $_SERVER['PHP_SELF']?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td>
</tr>
<?php } ?>
</table>
<?php
} else {
}
?>
</form>