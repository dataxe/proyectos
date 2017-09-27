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
include ("../formato_entrada_sql/funcion_env_val_sql.php");
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
<a href="../admin/exportacion_csv_vendedor.php"><font color="white" size='6px'>EXPORTAR TABLAS</font></a>&nbsp;&nbsp;&nbsp;&nbsp;
<td><strong><font color='yellow' size='6px'>IMPORTAR TABLAS </font></strong></td><br><br>
<table width="90%">
<tr>
<td align="center"><strong><a href="subida_importacion_ventas_vendedor.php"><font size='5px'>IMPORTAR VENTAS</font></a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="subida_importacion_productos_vendedor.php"><font size='5px'>IMPORTAR PRODUCTOS</font></a></strong></td>
<td></td><td></td>
<td align="center"><strong><a href="subida_importacion_info_impuesto_facturas_vendedor.php"><font size='5px'>IMPORTAR FACTURAS</font></a></strong></td>
</tr>
</table>
<br>