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
<td><strong><font color='yellow'>CENTRO COSTO - </font><a href="agregar_centro_costo.php?verificacion=SI"><font color='white'>AGREGAR CENTRO DE COSTO</font></a></strong></td><br><br>
<table>
<tr>
<td align="center"><strong><a href="centro_costo_dia.php">DIA</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="centro_costo_mes.php">MES</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="centro_costo_anyo.php">A&Ntilde;O</a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="resultado_centro_costo_todos.php">TODO</a></strong></td>
</tr>
</table>
<br>