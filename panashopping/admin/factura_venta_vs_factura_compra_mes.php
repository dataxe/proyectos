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

$fecha = $_POST['fecha'];
$hora = date("H:i:s");
$campo = 'fecha_mes';

//-------------------------------------------- CALCULO PARA EL TOTAL DE COMPRA --------------------------------------------//
$mostrar_datos_sql_compras = "SELECT Sum(facturas_cargadas_inv.precio_compra_con_descuento) AS precio_compra_con_descuento, facturas_cargadas_inv.fecha_mes
FROM facturas_cargadas_inv GROUP BY facturas_cargadas_inv.fecha_mes ORDER BY fecha DESC";
//$mostrar_datos_sql = "SELECT * FROM facturas_cargadas_inv WHERE fecha_mes = '$fecha'";
$consulta_compras = mysql_query($mostrar_datos_sql_compras, $conectar) or die(mysql_error());
$total_resultados_compras = mysql_num_rows($consulta_compras);
$datos_compras = mysql_fetch_assoc($consulta_compras);

//-------------------------------------------- CALCULO PARA EL TOTAL DE COMPRA --------------------------------------------//
$mostrar_datos_sql_ventas = "SELECT Sum(ventas.vlr_total_venta) AS vlr_total_venta, ventas.fecha_mes
FROM ventas GROUP BY ventas.fecha_mes ORDER BY fecha DESC";
//$mostrar_datos_sql = "SELECT * FROM facturas_cargadas_inv WHERE fecha_mes = '$fecha'";
$consulta_ventas = mysql_query($mostrar_datos_sql_ventas, $conectar) or die(mysql_error());
$total_resultados_ventas = mysql_num_rows($consulta_ventas);
$datos_ventas = mysql_fetch_assoc($consulta_ventas);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
<br><br>
<fieldset><legend><font color='yellow' size='+3'>VENTAS Y COMPRAS MENSUALES</font></legend>
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<table width='40%'>
<tr>
<td align="center"><strong>TOTAL COMPRA</strong></td>
<td align="center"><strong>MES</strong></td>
</tr>
<?php 
do { 
$precio_compra_con_descuento = $datos_compras['precio_compra_con_descuento'];
$fecha_mes = $datos_compras['fecha_mes'];
?>
<tr>
<!--<td align="center"><a href="../admin/ver_factura_compra.php?cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $nombre_proveedores?>"><img src=../imagenes/ver.png alt="ver"></a></td>-->
<td align="right"><font size='+2'><?php echo number_format($precio_compra_con_descuento, 0, ",", "."); ?></font></td>
<td align="center"><font size='+2'><?php echo $fecha_mes; ?></font></td>
<?php } while ($datos_compras = mysql_fetch_assoc($consulta_compras));?>
</tr>
</table>
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<!-- //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// -->
<table width='40%'>
<tr>
<td align="center"><strong>TOTAL VENTA</strong></td>
<td align="center"><strong>MES</strong></td>
</tr>
<?php 
do { 
$vlr_total_venta = $datos_ventas['vlr_total_venta'];
$fecha_mes = $datos_ventas['fecha_mes'];
?>
<tr>
<!--<td align="center"><a href="../admin/ver_factura_compra.php?cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $nombre_proveedores?>"><img src=../imagenes/ver.png alt="ver"></a></td>-->
<td align="right"><font size='+2'><?php echo number_format($vlr_total_venta, 0, ",", "."); ?></font></td>
<td align="center"><font size='+2'><?php echo $fecha_mes; ?></font></td>
</tr>
<?php } while ($datos_ventas = mysql_fetch_assoc($consulta_ventas));?>
</table>
</fieldset>
</body>
</html>
<?php mysql_free_result($consulta_compras);?>
<br>
<center>
<?php mysql_free_result($consulta_ventas);
?>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>