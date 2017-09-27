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
<td align="center"><strong><a href="arreglar_fechas_invert.php">FECHAS INVERT</a></strong></td>
<td></td> <td></td> <td></td> <td></td>
<td align="center"><strong><a href="arreglar_fechas_ventas.php">FECHA VENTAS</a></strong></td>
<td></td> <td></td> <td></td> <td></td>
<td align="center"><strong><a href="arreglo_fecha_invert.php">FECHA VENTAS INVERT</a></strong></td>
<td></td> <td></td> <td></td> <td></td>
<td align="center"><strong><a href="arreglo_fecha_mes_ventas.php">FECHA VENTAS MES</a></strong></td>
<td></td> <td></td> <td></td> <td></td>
<td align="center"><strong><a href="arreglo_todo_ventas.php">ARREGLAR POR PAQUETE TODO VENTA</a></strong></td>
</tr>
</table>
<br>