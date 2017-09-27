<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
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

require_once("menu_facturas_compra.php");
$fecha = $_GET['fecha'];
$hora = date("H:i:s");
$campo = 'fecha_mes';

//-------------------------------------------- CALCULO PARA EL TOTAL DE COMPRA --------------------------------------------//
$mostrar_datos_totales = "SELECT sum(precio_compra_con_descuento) AS tot_fact, sum(valor_iva) AS valor_iva, 
sum(vlr_total_venta * cajas) AS vlr_total_ventas FROM facturas_cargadas_inv WHERE fecha_mes = '$fecha'";
$consulta_totales = mysql_query($mostrar_datos_totales, $conectar) or die(mysql_error());
$totales = mysql_fetch_assoc($consulta_totales);

$tot_fact = $totales['tot_fact'];
$valor_iva = $totales['valor_iva'];
$vlr_total_ventas = $totales['vlr_total_ventas'];

$mostrar_datos_sql = "SELECT proveedores.nombre_proveedores, facturas_cargadas_inv.cod_proveedores, facturas_cargadas_inv.fecha_mes, SUM(precio_compra_con_descuento) AS total_compra
FROM facturas_cargadas_inv, proveedores WHERE facturas_cargadas_inv.fecha_mes = '$fecha' AND proveedores.cod_proveedores = facturas_cargadas_inv.cod_proveedores GROUP BY cod_proveedores";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
<form method="GET" name="formulario" action="">
<table align="center">
<td nowrap align="right">FACTURA COMPRA MENSUAL:</td>
<td bordercolor="0">
<select name="fecha" id="foco">
<?php $sql_consulta1="SELECT DISTINCT fecha_mes FROM facturas_cargadas_inv ORDER BY fecha DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['fecha_mes'] ?>"><?php echo $contenedor['fecha_mes'] ?></option>
<?php }?>
</select></td></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Consultar Compras"></td>
</tr>
</table>
</form>
</center>
<?php
if ($fecha <> NULL) {?>
<!--
<center>
<table>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_datos_ventas_pequena.php?fecha=<?php echo $fecha?>&campo=<?php echo $campo?>" target="_blank"><img src=../imagenes/imprimir_1.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_datos_ventas_grande.php?fecha=<?php echo $fecha?>&campo=<?php echo $campo?>" target="_blank"><img src=../imagenes/imprimir_.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</table>
</center>
-->
<center>
<fieldset><legend><font color='yellow' size='+3'>TOTAL COMPRAS MES: <?php echo $fecha?></font></legend>
<table width='40%'>
<br>
<tr>
<td align="center"><strong>TOTAL COMPRA</td>
<td align="center"><strong>TOTAL VENT PUBLIC (PROYEC)</td>
</tr>
<tr>
<td align="right"><font color="yellow" size="+2"><strong><?php echo number_format($tot_fact, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+2"><strong><?php echo number_format($vlr_total_ventas, 0, ",", "."); ?></font></td>
</tr>
</table>
</center>
</fieldset>


<br>
<fieldset><legend><font color='yellow' size='+3'></font></legend>
<table width=60%>
<tr>
<td align="center"><strong><font size='+2'>PROVEEDOR</font></strong></td>
<td align="center"><strong><font size='+2'>TOTAL COMPRA</font></strong></td>
<td align="center"><strong><font size='+2'>MES</font></strong></td>
</tr>
<?php 
do { 
$cod_proveedores = $datos['cod_proveedores'];
$nombre_proveedores = $datos['nombre_proveedores'];
$fecha_mes = $datos['fecha_mes'];
$total_compra = $datos['total_compra'];
?>
<tr>
<td><a href="../admin/factura_compra_mensual_ver.php?cod_proveedores=<?php echo $cod_proveedores?>&nombre_proveedores=<?php echo $nombre_proveedores?>&fecha=<?php echo $fecha_mes?>"><font size='+2'><?php echo $nombre_proveedores; ?></font></a></td>
<td align="right"><a href="../admin/factura_compra_mensual_ver.php?cod_proveedores=<?php echo $cod_proveedores?>&nombre_proveedores=<?php echo $nombre_proveedores?>&fecha=<?php echo $fecha_mes?>"><font size='+2'><?php echo number_format($total_compra, 0, ",", "."); ?></font></a></td>
<td align="center"><a href="../admin/factura_compra_mensual_ver.php?cod_proveedores=<?php echo $cod_proveedores?>&nombre_proveedores=<?php echo $nombre_proveedores?>&fecha=<?php echo $fecha_mes?>"><font size='+2'><?php echo $fecha_mes; ?></font></a></td>

</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta));?>
</table>
</fieldset>
</body>
</html>
<center>
<?php
} else {
}
?>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>