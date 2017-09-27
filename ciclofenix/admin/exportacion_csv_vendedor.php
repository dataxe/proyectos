<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
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
<a href="../admin/importacion_csv_vendedor.php"><font color="white" size='6px'>IMPORTAR TABLAS</font></a>&nbsp;&nbsp;&nbsp;&nbsp;
<td><strong><font color='yellow' size='6px'>EXPORTAR TABLAS </font></strong></td><br><br>
<table width="90%">
<tr>
<td align="center"><strong><a href="descargar_factura_externa_temporal_archivo_csv_ventas.php"><font size='5px'>EXPORTAR VENTAS</font></a></strong></td>
<td align="center"><strong><a href="descargar_factura_externa_temporal_archivo_csv_productos.php"><font size='5px'>EXPORTAR PRODUCTOS</font></a></strong></td>
<td align="center"><strong><a href="descargar_factura_externa_temporal_archivo_csv_facturas.php"><font size='5px'>EXPORTAR FACTURAS</font></a></strong></td>
</tr>
<tr>
<td align="center"><strong><a href="exportacion_ventas_por_horas.php"><font size='4px'>POR HORA</font></a></strong></td>
<td></td>
<td align="center"><strong><a href="exportacion_info_impuesto_facturas_por_horas.php"><font size='4px'>POR HORA</font></a></strong></td>
</tr>
<tr>
<td align="center"><strong><a href="exportacion_ventas_por_dias.php"><font size='4px'>POR DIA</font></a></strong></td>
<td></td>
<td align="center"><strong><a href="exportacion_info_impuesto_facturas_por_dias.php"><font size='4px'>POR DIA</font></a></strong></td>
</tr>
<tr>
<td align="center"><strong><a href="exportacion_ventas_por_meses.php"><font size='4px'>POR MES</font></a></strong></td>
<td></td>
<td align="center"><strong><a href="exportacion_info_impuesto_facturas_por_meses.php"><font size='4px'>POR MES</font></a></strong></td>
</tr>
</table>
<br>