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

$cod_factura = mysql_escape_string($_GET['cod_factura']);
//include ("../registro_movimientos/registro_cierre_caja.php");

$sql = "SELECT * FROM transferencias2 WHERE cod_factura = '$cod_factura' ORDER BY cod_transferencias2 DESC";
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
//require_once("busqueda_inmediata_transferecias_vendedor.php");
$suma_factura = "SELECT sum(vlr_total_venta) as vlr_total_venta FROM transferencias2 WHERE cod_factura = '$cod_factura'";
$consulta_suma = mysql_query($suma_factura, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_suma);

$transferencias_temporal = "SELECT * FROM transferencias2 WHERE cod_factura = '$cod_factura'";
$consulta_transferencias = mysql_query($transferencias_temporal, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta_transferencias);

$maxima_factura = "SELECT Max(cod_factura) AS cod_factura FROM info_transferencias";
$consulta_maxima = mysql_query($maxima_factura, $conectar) or die(mysql_error());
$maxima = mysql_fetch_assoc($consulta_maxima);
?>
<center>
<br>
<td><strong><a href="busq_transferencias_vendedor.php"><font color='yellow'>REGRESAR</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;

<table width="100%">
<form method="post" name="formulario" action="../admin/guardar_transferencias_vendedor_admin.php">

<input type="hidden" name="cod_factura" value="<?php echo $maxima['cod_factura']+1?>" size="1">

<input type="hidden" name="cod_factura_temp" value="<?php echo $cod_factura?>" size="1">

<td><strong>FECHA: </strong><input type="text" style="font-size:15px" name="fecha" value="<?php echo date("d/m/Y")?>" size="10" required autofocus>
	
<td><strong>ALMACEN: </strong><select name="cod_transferencias_almacenes">
<?php $sql_consulta="SELECT cod_transferencias_almacenes, nombre_almacen FROM transferencias_almacenes ORDER BY nombre_almacen ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:13px" value="<?php echo $contenedor['cod_transferencias_almacenes'] ?>"><?php echo $contenedor['nombre_almacen'] ?></option>
<?php }?></select></td>
<!--<td><strong>%DTO1:</strong><input type="text" style="font-size:15px" name="dto1" value="0" size="1" required autofocus></td>
<td><strong>%DTO2:</strong><input type="text" style="font-size:15px" name="dto2" value="0" size="1" required autofocus></td>
<td><strong>%IVA:</strong>-->
<!--</td>-->
<input type="hidden" name="verificacion" value="verificacion" size="1">
<td><div align="right"><strong>TOTAL:</strong><input type="text" style="font-size:15px" name="total" value="<?php echo $suma['vlr_total_venta'];?>" size="6" required autofocus></div></td>

<input type="hidden" name="total_datos" value="<?php echo $total_datos?>" size="1">

<?php while ($datos = mysql_fetch_assoc($consulta_transferencias)) {?>
<input type="hidden" name="cod_productos[]" value="<?php echo $datos['cod_productos']; ?>" size="15">
<?php } ?>

<td><div align="right"><input type="image" src="../imagenes/guardar.png" name="vender" value="Guardar" /></td>


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
myajax.Link('guardar_cargar_transferencias2_temporal_vendedor.php?valor='+valor+'&campo='+campo+'&id='+id);
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
<td><div align="center"><font><strong>PRODUCTO</strong></font></div></td>
<td><div align="center"><strong>CAJ</strong></div></td>
<td><div align="center"><strong>UND</strong></div></td>
<td><div align="center"><strong>T.UND</strong></div></td>
<td><div align="center"><strong>P.VENTA</strong></div></td>
<td><div align="center"><font><strong>TOTAL</strong></font></div></td>
<td><div align="center"><strong>OK</strong></div></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$cajas = $datos['cajas'];
$unidades = $datos['unidades'];
$unidades_vendidas = $datos['unidades_vendidas'];
$unidades_total = $datos['unidades_total'];
$precio_venta = $datos['precio_venta'];
$vlr_total_compra = $datos['vlr_total_compra'];
$vlr_total_venta = $datos['vlr_total_venta'];
?>
<tr>
<td><a href="../modificar_eliminar/eliminar_cargar_transferencias2.php?cod_productos=<?php echo $datos['cod_productos']?>&cod_transferencias2=<?php echo $datos['cod_transferencias2']?>&cod_factura=<?php echo $cod_factura?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<td><?php echo $cod_productos;?></td>
<td><?php echo $nombre_productos;?></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'cajas', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $cajas;?>" size="3"></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'unidades', <?php echo $cod_productos;?>)" class="cajpequena" id="<?php echo $cod_productos;?>" value="<?php echo $unidades;?>" size="3"></td>
<td align="center"><div align='center'><?php echo $unidades_total;?></div></td>
<td align="center"><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'precio_venta', <?php echo $cod_productos;?>)" class="cajgrand" id="<?php echo $cod_productos;?>" value="<?php echo $precio_venta;?>" size="3"></td>
<td align="center"><div align='right'><?php echo number_format($vlr_total_venta);?></div></td>
<td><a href="<?php $_SERVER['PHP_SELF']?>"><center><img src=../imagenes/correcto.png alt="Listo"></center></a></td>
</tr>
<?php } ?>
</table>
<?php
} else {
}
?>
</form>