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
<td align="center"><font color='yellow' size='+2'>FACTURAS DE COMPRA</font></strong></td>
<table>
<br><br>
<td align="center"><strong><a href="factura_compra_diario.php">DIARIO</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="factura_compra_mensual.php">MENSUAL</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="factura_compra_anual.php">ANUAL</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="factura_compra_todo.php">TODO</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="factura_compra_por_factura.php">POR FACTURA</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="buscar_para_hacer_compra_de_producto_historial.php">HISTORIAL PRODUCTO</a></strong></td>
</tr>
</table>