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
<td align="center"><strong><a href="productos_fiados_diario.php">DIARIO</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="productos_fiados_mensual.php">MENSUAL</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="productos_fiados_anual.php">ANUAL</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="cuentas_cobrar.php">POR CLIENTE</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="cuentas_cobrar_por_factura.php">POR FACTURA</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="cuentas_cobrar_por_abonos_diario.php">POR ABONOS</a></strong></td>
</tr>
</table>