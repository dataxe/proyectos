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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>

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
myajax.Link('guardar_productos_fiados.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>

</head>
<body onLoad="myajax = new isiAJAX();">
<form name="form1" id="form1" action="#" method="post" style="margin:1;">  
<?php
$cod_factura = $_GET['cod_factura'];
$cliente = $_GET['cliente'];
$pagina = $_SERVER['PHP_SELF'];

$sql = "SELECT * FROM productos_fiados, clientes WHERE productos_fiados.cod_factura = '$cod_factura' AND productos_fiados.cod_clientes = clientes.cod_clientes";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);

$sql_consulta = "SELECT SUM(vlr_total_venta) AS vlr_total_venta  FROM productos_fiados WHERE cod_factura = '$cod_factura'";
$consulta_total_venta = mysql_query($sql_consulta, $conectar) or die(mysql_error());
$total_venta = mysql_fetch_assoc($consulta_total_venta);
?>
<br>
<center>
<td><strong><a href="cuentas_cobrar_vendedor.php"><font color='white'>REGRESAR</font></a></strong></td><br><br>
<?php
if ($total_datos <> 0) {
?>
<td><strong><font color='yellow' size='6'>PRODUCTOS EN CREDITO FACTURA N0: <?php echo $cod_factura; ?> </font></strong></td><br><br>

<table width="100%">
<tr>
<td><div align="center"><strong>CLIENTE</strong></div></td>
<td><div align="center"><strong>FACTURA</strong></div></td>
<td><div align="center"><strong>C&Oacute;DIGO</strong></div></td>
<td><div align="center"><strong>PRODUCTO</strong></div></td>
<td><div align="center"><strong>UND</strong></div></td>
<td><div align="center"><strong>P.VENTA</strong></div></td>
<td><div align="center"><strong>TOTAL</strong></div></td>
<td><div align="center"><strong>VENDEDOR</strong></div></td>
<td><div align="center"><strong>IP</strong></div></td>
<td><div align="center"><strong>FECHA</strong></div></td>
<td><div align="center"><strong>HORA</strong></div></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos_fiados = $datos['cod_productos_fiados'];
$precio_venta = $datos['precio_venta'];
?>
<tr>
<td ><?php echo $datos['nombres'].' '.$datos['apellidos']; ?></td>
<td align="center"><?php echo $datos['cod_factura']; ?></td>
<td ><?php echo $datos['cod_productos']; ?></td>
<td ><?php echo $datos['nombre_productos']; ?></td>
<td align="right"><font size="5"><?php echo $datos['unidades_vendidas']; ?></font></td>
<td align="right"><font size="5"><?php echo $datos['precio_venta']; ?></font></td>
<td align="right"><font size="6"><?php echo $datos['vlr_total_venta']; ?></font></td>
<td align="center"><?php echo $datos['vendedor']; ?></td>
<td align="right"><?php echo $datos['ip']; ?></td>
<td align="right"><?php echo $datos['fecha_anyo']; ?></td>
<td align="right"><?php echo $datos['fecha_hora']; ?></td>
</tr>
<?php }
?>
</table>
<br>
<table>
<td align="right"><font size="7">TOTAL VENTA: <?php echo number_format($total_venta['vlr_total_venta']); ?></font></td>
</table>
<?php
} else {
echo "<br><td><strong><font color='white'>LOS PRODUCTOS DEL CLIENTE: ".$cliente.", COMPRADOS POR CREDITO YA HAN SIDO ENVIADOS A LAS VENTAS</font></strong></td>";
}
?>
</form>