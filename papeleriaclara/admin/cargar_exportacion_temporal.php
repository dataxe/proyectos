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
$sql = "SELECT * FROM exportacion_temporal WHERE vendedor = '$cuenta_actual' ORDER BY cod_exportacion_temporal DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<?php
require_once("busqueda_inmediata_exportacion_temporal.php");
if ($total_datos <> 0) {
require_once("informacion_exportacion_temporal.php");
}
$pagina = 'cargar_exportacion_temporal.php';
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
myajax.Link('guardar_exportacion_temporal.php?valor='+valor+'&campo='+campo+'&id='+id);
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
<td align="center"><strong>ELM</strong></td>
<td align="center"><font><strong>C&Oacute;DIGO</strong></font></td>
<td align="center"><font><strong>PRODUCTO</strong></font></td>
<td align="center"><strong>CAJ</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>T.UND</strong></td>
<td align="center"><strong>P.COMP</strong></td>
<td align="center"><strong>%DT1</strong></td>
<td align="center"><strong>%DT2</strong></td>
<!--<td><font size='1' color='yellow'><strong>DESC</strong></font></td>-->
<td align="center"><font><strong>P.COST</strong></font></td>
<td align="center"><strong>%IVA</strong></td>
<td align="center"><font><strong>V.IVA</strong></font></td>
<!--<td><strong>%VTA</strong></td>-->
<td align="center"><strong>P.VENT</strong></td>
<td align="center"><strong>RYS</strong></td>
<td align="center"><strong>F.VENC</strong></td>
<td align="center"><strong>STK</strong></td>
<td align="center"><font><strong>V.FACTDO</strong></font></td>
<td align="center"><strong>OK</strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos = $datos['cod_productos'];
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
?>
<tr>
<td><a href="../modificar_eliminar/eliminar_cargar_exportacion_temporal.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_exportacion_temporal=<?php echo $datos['cod_exportacion_temporal']?>&pagina=<?php echo $pagina?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td><?php echo $cod_productos;?></td>
<td><?php echo $nombre_productos;?></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cajas', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $cajas;?>" size="3"></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $unidades;?>" size="3"></td>
<td align='center'><?php echo $unidades_total;?></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_compra', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_compra;?>" size="4"></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'dto1', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $dto1;?>" size="2"></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'dto2', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $dto2;?>" size="2"></td>
<!--<td><div align='right'><font size='2' color='yellow'><?php //echo number_format($descuento);?></div></td>-->
<td align='right'><?php echo number_format($precio_costo);?></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'iva', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $iva;?>" size="2"></td>
<td align='right'><?php echo number_format($valor_iva);?></td>
<!--<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'ptj_ganancia', <?php //echo $cod_productos;?>)" class="alingcenter" id="<?php //echo $cod_productos;?>" value="<?php //echo $ptj_ganancia;?>" size="2"></td>-->
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_venta;?>" size="4"></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'porcentaje_vendedor', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $porcentaje_vendedor;?>" size="3" required placeholder="si/no"></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'fechas_vencimiento', <?php echo $cod_productos;?>)" class="fechas" id="<?php echo $cod_productos;?>" value="<?php echo $fechas_vencimiento;?>" size="9" required placeholder="dia/mes/a&ntilde;o"></td>
<td><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'tope_min', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $tope_min;?>" size="3"></td>
<td align='right'><?php echo number_format($precio_compra_con_descuento);?></td>
<td><a href="<?php $_SERVER['PHP_SELF']?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td>
</tr>
<?php } ?>
</table>
<?php
} else {
}
?>
</form>