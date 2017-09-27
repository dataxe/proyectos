<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
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

$fecha_mes = date("m/Y");

$mostrar_datos_sql = "SELECT nombre_productos, Sum(unidades_vendidas) AS unidades_vendidas, vlr_total_venta, cod_productos,  
precio_venta, fecha_mes FROM ventas WHERE fecha_mes = '$fecha_mes' GROUP BY  cod_productos ORDER BY unidades_vendidas DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
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
<strong><font size='+1' color='yellow'>PRODUCTOS MAS VENDIDOS DEL MES (<?php echo $fecha_mes?>) - </font><a href="../admin/productos_mas_vendidos.php"><font size='+1' color='white'>PRODUCTOS MAS VENDIDOS</a></font></strong>
<br><br>
<table widht='80%'>
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
<td align="center"><strong>TOTAL</strong></td>
<td align="center"><strong>FECHA - MES</strong></td>
</tr>
<?php do { ?>
<tr>
<td ><font size='+1'><?php echo $datos['cod_productos']; ?></font></td>
<td ><font size='+1'><?php echo $datos['nombre_productos']; ?></font></td>
<td align="right"><font size='+1'><?php echo number_format($datos['unidades_vendidas'], 0, ",", "."); ?></font></td>
<td align="right"><font size='+1'><?php echo number_format($datos['precio_venta'], 0, ",", "."); ?></font></td>
<td align="right"><font size='+1'><?php echo number_format($datos['unidades_vendidas'] * $datos['precio_venta'], 0, ",", "."); ?></font></td>
<td align="center"><font size='+1'><?php echo $datos['fecha_mes']; ?></font></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>