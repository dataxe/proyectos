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
<form method="POST" name="formulario" action="../modificar_eliminar/eliminar_check.php">
<td align="center"><a href="../admin/cargar_factura_temporal_editable_vendedor.php?cod_factura=<?php echo $cod_factura?>"><img src=../imagenes/regresar.png alt="regresar"></a></td>

<input type="hidden" name="cod_factura" value="<?php echo $cod_factura?>" size="10" required autofocus>
<input type="hidden" name="tab" value="productos2_check" size="10" required autofocus>
<input type="hidden" name="tipo" value="eliminar" size="10" required autofocus>

<td><strong>FECHA: </strong><?php echo date("d/m/Y")?></td>

<td><strong>FECHA PAGO: </strong><br>
<td><strong>FACTURA: </strong><?php echo $cod_factura ?></td>
	
<td><strong>V.BRUTO:</strong><?php echo $suma['vlr_total_compra'];?></td>
<td><strong>DESCUENTO:</strong><?php echo $suma['descuento'];?></td>
<td><strong>V.NETO:</strong><?php echo $suma['vlr_total_compra'] - $suma['descuento'];?></td>
<td><strong>V.IVA:</strong><?php echo $suma['valor_iva'];?></td>
<td><strong>TOTAL:</strong<?php echo $suma['precio_compra_con_descuento'];?></td>
<td align="center"><a href="../modificar_eliminar/eliminar.php?tipo=eliminar&tab=productos2&cod_factura=<?php echo $cod_factura?>&pagina=<?php echo $pagina?>"><img src=../imagenes/vaciar.png alt="Vaciar"></a></td>
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
$sql = "SELECT * FROM productos2 WHERE cod_factura = '$cod_factura' ORDER BY cod_cargar_factura_temporal ASC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
?>
<table width="100%">
<tr>
<td align="center"><strong></strong></td>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
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
<td><input type="checkbox" name="check[]" value="<?php echo $cod_cft?>"></td>
<td><a href="../modificar_eliminar/eliminar_cargar_productos_temporal_editable_vendedor.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_cargar_factura_temporal=<?php echo $datos['cod_cargar_factura_temporal']?>&cod_factura=<?php echo $cod_factura?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td align='center'><?php echo $cod_productos;?></td>
<td><?php echo $nombre_productos;?></td>
<td align='center'><?php echo $cajas;?></td>
<td align='center'><?php echo $unidades;?></td>
<td align='center'><?php echo $unidades_total?></td>
<td align='center'><?php echo $precio_compra;?></td>
<td align='center'><?php echo $dto1;?></td>
<td align='center'><?php echo $dto2;?></td>
<td align='center'><?php echo $iva;?></td>
<td align='right'><?php echo number_format($valor_iva, 0, ",", ".");?></td>
<td align='right'><?php echo number_format($precio_costo, 0, ",", ".");?></td>
<td align='center'<?php echo $ptj_ganancia;?></td>
<td align="center"><?php echo $precio_venta;?></td>
<td align='center'<?php echo $vlr_total_venta;?></td>
<td align="center"><?php echo $porcentaje_vendedor;?></td>
<td align="center"><?php echo $fechas_vencimiento;?></td>
<td align="center"><?php echo $tope_min;?></td>
<td align="right"><?php echo number_format($precio_compra_con_descuento, 0, ",", ".");?></td>
<td><a href="../admin/cargar_factura_temporal_editable_vendedor.php?cod_factura=<?php echo $cod_factura?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td>
</tr>
<?php } ?>
</table>
<td><input type="image" src="../imagenes/vaciar_check.png" name="vaciar_check" value="Guardar" /></td>
</form>