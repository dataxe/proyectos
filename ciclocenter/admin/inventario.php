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
<br>
<table>
<tr>
<td align="center"><strong><a href="inventario_diario.php">DIARIO</a></strong></td>
<td></td> <td></td> <td></td> <td></td>
<td align="center"><strong><a href="inventario_mensual.php">MENSUAL</a></strong></td>
<td></td> <td></td> <td></td> <td></td>
<td align="center"><strong><a href="inventario_anual.php">ANUAL</a></strong></td>
<td></td> <td></td> <td></td> <td></td>
<td align="center"><strong><a href="inventario_todos.php">TODOS</a></strong></td>
<td></td> <td></td> <td></td> <td></td>
<td align="center"><strong><a href="inventario_productos.php">INVENTARIO PRODUCTOS</a></strong></td>
<td></td> <td></td> <td></td> <td></td>
<td align="center"><strong><a href="descargar_productos.php">DESCARGAR INVENTARIO</a></strong></td>
<td></td> <td></td> <td></td> <td></td>
<td align="center"><strong><a href="descargar_ventas.php">DESCARGAR VENTAS</a></strong></td>
</tr>
</table>
<br>