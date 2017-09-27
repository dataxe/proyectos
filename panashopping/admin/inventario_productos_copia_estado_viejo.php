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


$mostrar_datos_sql = "SELECT * FROM productos_copia_inventario ORDER BY nombre_productos ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());

$total_productos_inventario = "SELECT * FROM productos_copia_inventario";
$consulta_inventario = mysql_query($total_productos_inventario, $conectar) or die(mysql_error());
$total_productos = mysql_num_rows($consulta_inventario);

$calculos_inventario = "SELECT Sum(precio_costo * unidades_faltantes) As tot_precio_costo, Sum(precio_venta * unidades_faltantes) As tot_precio_venta, 
Sum((unidades_faltantes / unidades) * precio_compra) As tot_precio_compra, Sum((unidades_faltantes / unidades) * vlr_total_venta) As tot_vlr_total_venta FROM productos";
$consulta_calculos_inventario = mysql_query($calculos_inventario, $conectar) or die(mysql_error());
$matriz_inventario = mysql_fetch_assoc($consulta_calculos_inventario);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<br>
<center>
<td align="center"><font color="yellow" size="+2"><strong>INVENTARIO DE PRODUCTOS COPIA</font></td>
<a href="../admin/pregunta_borrar_productos_copia_inventario.php?"><center><font color='yellow'>(BORRAR ESTA COPIA DE INVENTARIO)</font></center></a>
<br>
<!-- Total mercancia venta y utilidad mes -->
<table width='60%'  border='1'>
<td align="center">TOTAL PRODUCTOS</td>
<td align="center">TOTAL COSTO</td>
<td align="center">TOTAL VENTA</td>
<td align="center">TOTAL VENTA x CAJA</td>
<tr>
<td align="center"><font color="yellow" size="+1"><strong><?php echo $total_productos; ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($matriz_inventario['tot_precio_costo'], 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($matriz_inventario['tot_precio_venta'], 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($matriz_inventario['tot_vlr_total_venta'], 0, ",", "."); ?></font></td>
</tr>
</table>
<br>
<table width='100%' border='1'>
<tr>
<td align="center">C&Oacute;DIGO</td>
<td align="center">PRODUCTO</td>
<td align="center" title='Cuantas unidades trae la caja. Presentacion'>UND.CAJ</td>
<td align="center" title='Unidades totales en inventario'>UND.INV</td>
<td align="center">P.COSTO</td>
<td align="center">P.VENTA</td>
<td align="center">P.VENTA2</td>
</tr>
<?php 
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos = $datos['cod_productos'];
$cod_productos_var = $datos['cod_productos_var'];
$nombre_productos = $datos['nombre_productos'];
$unidades = $datos['unidades'];
$unidades_faltantes = $datos['unidades_faltantes'];
$detalles = $datos['detalles'];
$precio_costo = $datos['precio_costo'];
$precio_venta = $datos['precio_venta'];
$precio_compra = $datos['precio_compra'];
$vlr_total_venta = $datos['vlr_total_venta'];
?>
<tr>
<td align='left'><?php echo $cod_productos_var ?></td>
<td align='left'><?php echo $nombre_productos ?></td>
<td align='center'><?php echo $unidades ?></td>
<td align='center'><?php echo $unidades_faltantes ?></td>
<td align='right'><?php echo number_format($precio_costo, 0, ",", ".") ?></td>
<td align='right'><?php echo number_format($precio_venta, 0, ",", ".") ?></td>
<td align='right'><?php echo number_format($vlr_total_venta, 0, ",", ".") ?></td>
</tr>
<?php } ?>
</table>
</center>
</body>
</html>