<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 

$mostrar_datos_sql = "SELECT  Sum(total_mercancia) As total_mercancia, Sum(total_venta) As total_venta, Sum(total_utilidad) As total_utilidad, 
Sum(descuento) As descuento FROM productos ";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$mostrar_datos_sqll = "SELECT  Sum(precio_compra_con_descuento) As total_venta FROM ventas";
$consultal = mysql_query($mostrar_datos_sqll, $conectar) or die(mysql_error());
$matriz_consultal = mysql_fetch_assoc($consultal);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<center>
<table border="1" id="table">
<tr>
<td><div align="center">Total Mercancia</div></td>
<td><div align="center">Total Venta</div></td>
<td><div align="center">Total Utilidad</div></td>
</tr>
<?php do { ?>
<tr>
<td ><?php echo $matriz_consulta['total_mercancia']; ?></td>
<td ><?php echo $matriz_consultal['total_venta']; ?></td>
<td ><?php echo $matriz_consulta['total_utilidad'] - $matriz_consulta['descuento']; ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
</body>
</html>
<?php mysql_free_result($consulta);?>