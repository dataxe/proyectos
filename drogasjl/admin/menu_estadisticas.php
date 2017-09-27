<?php error_reporting(E_ALL ^ E_NOTICE);
$cuenta_actual = addslashes($_SESSION['usuario']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<table>
<br>
<td align="center"><strong><a href="grafico_ventas_dias.php">VENTAS</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="grafico_compras_y_ventas_meses.php">COMPRAS Y VENTAS</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="grafico_ventas_y_costos_dias.php">COSTO DE VENTA</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="grafico_utilidad_y_gastos_meses.php">UTILIDAD Y GASTOS</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="grafico_ganancias_dias.php">GANANCIAS</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="grafico_productos_mas_vendidos.php">PRODUCTOS</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="grafico_ventas_por_vendedor_dias.php">VENDEDORES</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="grafico_egresos_dias.php">EGRESOS</a></strong></td>
</tr>
</table>