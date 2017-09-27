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
include ("../registro_movimientos/registro_movimientos.php");

$cod_factura = mysql_escape_string($_GET['cod_factura']);
//include ("../registro_movimientos/registro_cierre_caja.php");

$sql = "SELECT * FROM transferencias_temporal WHERE cod_factura = '$cod_factura' ORDER BY cod_transferencias_temporal DESC";
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
$suma_factura = "SELECT sum(precio_venta*unidades_total) as vlr_total_venta FROM transferencias_temporal WHERE cod_factura = '$cod_factura'";
$consulta_suma = mysql_query($suma_factura, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_suma);

$transferencias_temporal = "SELECT * FROM transferencias_temporal WHERE cod_factura = '$cod_factura'";
$consulta_transferencias = mysql_query($transferencias_temporal, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta_transferencias);

$maxima_factura = "SELECT nombre_almacen FROM info_transferencias_temporal WHERE cod_factura = '$cod_factura'";
$consulta_maxima = mysql_query($maxima_factura, $conectar) or die(mysql_error());
$almacen = mysql_fetch_assoc($consulta_maxima);

$nombre_almacen = $almacen['nombre_almacen'];
$pagina = $_SERVER['PHP_SELF'];
?>
<center>
<br>
<td><strong><a href="transferencia_lista_archivo_plano.php"><font color='yellow'>REGRESAR</font></a></strong></td>

<table width="90%">
<form method="post" name="formulario" action="../admin/guardar_transferencias_archivo_plano_vendedor_admin.php">

<td><font size='+2'><strong>FECHA: </strong><?php echo date("d/m/Y")?></font></td>
<td><font size='+2'><strong>ALMACEN: </strong><?php echo $nombre_almacen?></font></td>
<td><font size='+2'><strong>TOTAL: </strong><?php echo number_format($suma['vlr_total_venta'], 0, ",", ".");?></font></td>

<input type="hidden" name="fecha" value="<?php echo date("d/m/Y")?>" size="1">
<input type="hidden" name="cod_factura_temp" value="<?php echo $cod_factura?>" size="1">
<input type="hidden" name="verificacion" value="verificacion" size="1">
<input type="hidden" name="total_datos" value="<?php echo $total_datos?>" size="1">

<?php while ($datos = mysql_fetch_assoc($consulta_transferencias)) {?>
<input type="hidden" name="cod_transferencias_temporal[]" value="<?php echo $datos['cod_transferencias_temporal']; ?>" size="15">
<?php } ?>

<td align="right"><input type="image" src="../imagenes/guardar.png" name="vender" value="Guardar" /></td>

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
myajax.Link('guardar_cargar_transferencias_temporal_archivo_plano_vendedor.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>
</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">
<?php
if ($total_datos <> 0) {
?>
<table width="90%">
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>CAJ</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>T.UND</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
<td align="center"><strong>TOTAL</strong></td>
<td align="center"><strong>OK</strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_transferencias_temporal = $datos['cod_transferencias_temporal'];
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$cajas = $datos['cajas'];
$unidades = $datos['unidades'];
$unidades_vendidas = $datos['unidades_vendidas'];
$unidades_total = $datos['unidades_total'];
$precio_venta = $datos['precio_venta'];
$vlr_total_compra = $datos['vlr_total_compra'];
$total = $precio_venta * $unidades_total;
?>
<tr>
<td><a href="../modificar_eliminar/eliminar.php?cod_transferencias_temporal=<?php echo $cod_transferencias_temporal?>&tipo=eliminar&tab=transferencias_temporal&pagina=<?php echo $pagina?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td><?php echo $cod_productos;?></td>
<td><?php echo $nombre_productos;?></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cajas', <?php echo $cod_transferencias_temporal;?>)" class="cajpequena" id="<?php echo $cod_transferencias_temporal;?>" value="<?php echo $cajas;?>" size="3"></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades', <?php echo $cod_transferencias_temporal;?>)" class="cajpequena" id="<?php echo $cod_transferencias_temporal;?>" value="<?php echo $unidades;?>" size="3"></td>
<td align="center"><?php echo $unidades_total;?></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_transferencias_temporal;?>)" class="cajgrand" id="<?php echo $cod_transferencias_temporal;?>" value="<?php echo $precio_venta;?>" size="3"></td>
<td align="right"><?php echo number_format($total, 0, ",", ".");?></td>
<td><a href="<?php $_SERVER['PHP_SELF']?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td>
</tr>
<?php } ?>
</table>
<?php
} else {
}
?>
</form>