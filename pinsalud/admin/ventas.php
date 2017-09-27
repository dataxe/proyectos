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
<td><strong><font color='white'>VENTAS: </font></strong></td><br><br>
<table id="table">
<tr>
<td><div align="center"><strong><a href="ventas_diarias.php">VENTA DIARIA</a></strong></div></td>
<td></td><td></td>
<td><div align="center"><strong><a href="ventas_mensuales.php">VENTA MES</a></strong></div></td>
<td></td><td></td>
<td><div align="center"><strong><a href="ventas_anuales.php">VENTA A&Ntilde;O</a></strong></div></td>
<td></td><td></td>
<td><div align="center"><strong><a href="ventas_todas.php">VENTA TODAS</a></strong></div></td>
<td></td><td></td>
<td><div align="center"><strong><a href="devoluciones.php">DEVOLUCIONES</a></strong></div></td>
<td></td><td></td><td></td>
<td><div align="center"><strong><a href="ventas_vendedores_diario.php">VENTA DIARIA POR VENDEDOR</a></strong></div></td>
<td></td><td></td>
<td><div align="center"><strong><a href="ventas_vendedores_mes.php">VENTA MENSUAL POR VENDEDOR</a></strong></div></td>
<td></td><td></td>
<td><div align="center"><strong><a href="ventas_diarias_descargar.php">REP VENTA DIA</a></strong></div></td>
<td></td><td></td>
<td><div align="center"><strong><a href="ventas_mensuales_descargar.php">REP VENTA MES</a></strong></div></td>
</tr>
</table>
<br>