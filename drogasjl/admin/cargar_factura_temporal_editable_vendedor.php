<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
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

$cod_factura = mysql_escape_string($_GET['cod_factura']);
$pagina = $_SERVER['PHP_SELF'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<?php
//require_once("informacion_factura_compra_vendedor2.php");

$suma_factura = "SELECT sum(vlr_total_venta) as vlr_total_venta, sum(vlr_total_compra) as vlr_total_compra, sum(descuento) as descuento,
sum(precio_compra_con_descuento) as precio_compra_con_descuento, sum(valor_iva) as valor_iva , sum(precio_costo) as precio_costo FROM productos2 WHERE cod_factura = '$cod_factura'";
$consulta_suma = mysql_query($suma_factura, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_suma);

$sqlss = "SELECT * FROM productos2 WHERE cod_factura = '$cod_factura'";
$consultass = mysql_query($sqlss, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consultass);
?>
<center>
<br>
<table width="100%">
<form method="post" name="formulario" action="../admin/guardar_factura_productos2.php">
<td ><a href="../admin/cargar_factura_temporal_editable_vendedor_check.php?cod_factura=<?php echo $cod_factura?>"><img src=../imagenes/check.png alt="check"></a></td>
<td><strong>FECHA: </strong><input type="text" style="font-size:15px" name="fecha" value="<?php echo date("d/m/Y")?>" size="10" required autofocus>

<td><strong>FECHA PAGO: </strong><br>
<input type="text" style="font-size:15px" name="fecha_pago" value="<?php echo date("d/m/Y")?>" size="10" required autofocus>

<td><strong>FACTURA: </strong><input type="text" style="font-size:15px" name="numero_factura" value="<?php echo $cod_factura ?>" size="7" required autofocus>
	
<td><strong>PROVEEDOR: </strong><select name="cod_proveedores">
<?php $sql_consulta="SELECT cod_proveedores, nombre_proveedores FROM proveedores ORDER BY proveedores.cod_proveedores ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:13px" value="<?php echo $contenedor['cod_proveedores'] ?>"><?php echo $contenedor['nombre_proveedores'] ?></option>
<?php }?></select></td>

<td><label><input type="radio" name="tipo_pago" value="contado" checked>
<strong>CONTADO</strong></label>
<br>
<label><input type="radio" name="tipo_pago" value="credito"> 
<strong>CREDITO</strong></label></td>

<td><strong>V.BRUTO:</strong><input type="text" style="font-size:15px" name="valor_bruto" value="<?php echo $suma['vlr_total_compra'];?>" size="6" required autofocus></td>
<td><strong>DESCUENTO:</strong><input type="text" style="font-size:15px" name="descuento_factura" value="<?php echo $suma['descuento'];?>" size="6" required autofocus></td>
<td><strong>V.NETO:</strong><input type="text" style="font-size:15px" name="valor_neto" value="<?php echo $suma['vlr_total_compra'] - $suma['descuento'];?>" size="6" required autofocus></td>
<td><strong>V.IVA:</strong><input type="text" style="font-size:15px" name="valor_iva" value="<?php echo $suma['valor_iva'];?>" size="6" required autofocus></td>
<td><strong>TOTAL:</strong><input type="text" style="font-size:15px" name="total" value="<?php echo $suma['precio_compra_con_descuento'];?>" size="6" required autofocus></td>
<input type="hidden" name="total_datos" value="<?php echo $total_datos?>" size="6">

<input type="hidden" style="font-size:15px" name="iva" value="0" size="1" required autofocus>
<input type="hidden" name="verificacion" value="verificacion" size="1">
<?php
while ($data = mysql_fetch_assoc($consultass)) {
?>
<input type="hidden" name="cod_productos[]" value="<?php echo $data['cod_productos']?>" size="6">
<input type="hidden" name="cod_cargar_factura_temporal[]" value="<?php echo $data['cod_cargar_factura_temporal']?>" size="6">
<?php
}
?>
<td><input type="image" src="../imagenes/guardar.png" name="vender" value="Guardar" /></td>
</form>
</table>
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'inputoff';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inputoff';
if (last != valor)
myajax.Link('guardar_cargar_factura_temporal_editable_vendedor.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<?php
$sql = "SELECT productos2.cod_cargar_factura_temporal, productos2.cod_productos, productos.cod_productos_var, productos2.nombre_productos, 
productos2.cajas, productos2.unidades, productos2.unidades_vendidas, productos2.unidades_total, productos2.tope_min, 
productos2.precio_compra, productos2.precio_costo, productos2.precio_venta, productos2.dto1, productos2.dto2, productos2.iva, 
productos2.valor_iva, productos2.ptj_ganancia, productos2.precio_costo, productos2.vlr_total_compra, productos2.porcentaje_vendedor, 
productos2.fechas_vencimiento, productos2.vlr_total_venta, productos2.precio_compra_con_descuento, productos2.descuento 
FROM productos RIGHT JOIN productos2 ON productos.cod_productos_var = productos2.cod_productos AND productos2.cod_factura = '$cod_factura' ORDER BY productos2.cod_cargar_factura_temporal ASC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
?>
<table width="100%">
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>OK</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>CAJ</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>T.UND</strong></td>
<td align="center"><strong>P.COMP</strong></td>
<td align="center"><strong>%DT1</strong></td>
<td align="center"><strong>%DT2</strong></td>
<!--<td><font size='1' color='yellow'><strong>DESC</strong></td>-->
<td align="center"><strong>%IVA</strong></td>
<td align="center"><strong>V.IVA</strong></td>
<td align="center"><strong>P.COST</strong></td>
<td align='center'><font><strong>+%</strong></font></td>
<td align="center"><strong>P.VENT</strong></td>
<td align='center'><strong>P.VENT2</strong></td>
<td align="center"><strong>RYS</strong></td>
<td align="center"><strong>F.VENC</strong></td>
<td align="center"><strong>STK</strong></td>
<td align="center"><strong>V.FACTDO</strong></td>
<td align="center"><strong>OK</strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
//$cod_productos = $datos['cod_productos'].'/'.$datos['cod_cargar_factura_temporal'];
$cod_cft = $datos['cod_cargar_factura_temporal'];
$cod_productos = $datos['cod_productos'];
$cod_productos_var = $datos['cod_productos_var'];
$nombre_productos = $datos['nombre_productos'];
$cajas = $datos['cajas'];
$unidades = $datos['unidades'];
$unidades_vendidas = $datos['unidades_vendidas'];
$unidades_total = $datos['unidades_total'];
$tope_min = $datos['tope_min'];
$precio_compra = $datos['precio_compra'];
$precio_costo = $datos['precio_costo'];
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
?>
<tr>
<td><a href="../modificar_eliminar/eliminar_cargar_productos_temporal_editable_vendedor.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_cargar_factura_temporal=<?php echo $datos['cod_cargar_factura_temporal']?>&cod_factura=<?php echo $cod_factura?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cod_productos', <?php echo $cod_cft;?>)" class="cajbarras" id="<?php echo $cod_cft;?>" value="<?php echo $cod_productos;?>" size="3"></td>
<?php if ($cod_productos_var == '') {
?>
<td><img src=../imagenes/advertencia1.gif alt="advertencia"></td>
<?php } else {?>
<td><img src=../imagenes/bien.png alt="bien"></td>
<?php } ?>
<td><?php echo $nombre_productos;?></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cajas', <?php echo $cod_cft;?>)" class="cajpequena" id="<?php echo $cod_cft;?>" value="<?php echo $cajas;?>" size="3"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades', <?php echo $cod_cft;?>)" class="cajpequena" id="<?php echo $cod_cft;?>" value="<?php echo $unidades;?>" size="3"></td>
<td align='center'><?php echo $unidades_total;?></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_compra', <?php echo $cod_cft;?>)" class="cajgrand" id="<?php echo $cod_cft;?>" value="<?php echo $precio_compra;?>" size="4"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'dto1', <?php echo $cod_cft;?>)" class="cajpequena" id="<?php echo $cod_cft;?>" value="<?php echo $dto1;?>" size="2"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'dto2', <?php echo $cod_cft;?>)" class="cajpequena" id="<?php echo $cod_cft;?>" value="<?php echo $dto2;?>" size="2"></td>
<!--<td><font size='2' color='yellow'><?php //echo number_format($descuento);?></td>-->
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'iva', <?php echo $cod_cft;?>)" class="cajpequena" id="<?php echo $cod_cft;?>" value="<?php echo $iva;?>" size="2"></td>
<td align='right'><?php echo number_format($valor_iva, 0, ",", ".");?></td>
<td align='right'><?php echo number_format($precio_costo, 0, ",", ".");?></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'ptj_ganancia', <?php echo $cod_cft;?>)" class="cajpequena" id="<?php echo $cod_cft;?>" value="<?php echo $ptj_ganancia;?>" size="4"></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_cft;?>)" class="cajgrand" id="<?php echo $cod_cft;?>" value="<?php echo $precio_venta;?>" size="4"></td>
<td align='center'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'vlr_total_venta', <?php echo $cod_cft;?>)" class="cajgrand" id="<?php echo $cod_cft;?>" value="<?php echo $vlr_total_venta;?>" size="4"></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'porcentaje_vendedor', <?php echo $cod_cft;?>)" class="cajpequena" id="<?php echo $cod_cft;?>" value="<?php echo $porcentaje_vendedor;?>" size="3" required placeholder="si/no"></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fechas_vencimiento', <?php echo $cod_cft;?>)" class="fechas" id="<?php echo $cod_cft;?>" value="<?php echo $fechas_vencimiento;?>" size="9" required placeholder="dia/mes/a&ntilde;o"></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'tope_min', <?php echo $cod_cft;?>)" class="cajpequena" id="<?php echo $cod_cft;?>" value="<?php echo $tope_min;?>" size="3"></td>
<td align="right"><?php echo number_format($precio_compra_con_descuento, 0, ",", ".");?></td>
<td><a href="../admin/cargar_factura_temporal_editable_vendedor.php?cod_factura=<?php echo $cod_factura?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td>
</tr>
<?php } ?>
</table>
</form>