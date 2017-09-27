<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
date_default_timezone_set("America/Bogota");
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

$fecha_actual_en_segundos = time();
$fecha_actual_segundos = time();

$fecha_hoy_seg = strtotime(date("Y/m/d"));

$mostrar_agotados_meses = "SELECT * FROM agotados_meses WHERE cod_agotados_meses = '1'";
$consulta_agotados_meses = mysql_query($mostrar_agotados_meses, $conectar) or die(mysql_error());
$meses_agotados = mysql_fetch_assoc($consulta_agotados_meses);

//Queremos sumar 6 meses a la fecha actual:
$meses = $meses_agotados['meses'];
$meses_seg = $meses * 30 * 24 * 60 * 60;

$fecha_seg_hace_meses = $fecha_hoy_seg - $meses_seg;
// Convertimos los meses a segundos y se los sumamos sumamos a la fecha_actual_segundos:
//$fecha_actual_segundos -= ($meses * 30 * 24 * 60 * 60);
/*
// Le damos al resultado el formato deseado:
$fecha_6_meses = date("d/m/Y", $fecha_actual_segundos);
$fecha_actual_normal = date("d/m/Y", $fecha_actual_en_segundos);
$fecha_vencimiento = '2013/11/18';
$fecha_vencimiento_segundos =  strtotime($fecha_vencimiento);
$fecha_vencimeinto_normal = date("d/m/Y", $fecha_vencimiento_segundos);
$resta_total = $fecha_actual_segundos - $fecha_vencimiento_segundos;
$fecha_resta_normal = date("d/m/Y", $resta_total);
*/
$mostrar_datos_sql = "SELECT cod_productos_var, nombre_productos, unidades_faltantes, precio_venta, fechas_agotado_seg FROM productos 
WHERE (fechas_agotado_seg  >= '$fecha_seg_hace_meses') AND (fechas_agotado_seg <> '') AND (fechas_agotado_seg <> '0') ORDER BY fechas_agotado_seg ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<br>
<center>
<strong><font size='4' color='yellow'>PRODUCTOS AGOTADOS HACE: <?PHP echo $meses; ?> MESES</font></strong>

<a href="../admin/cambiar_agotados_numero_meses.php"><font size='3' color='yellow'>(CAMBIAR NUMERO DE MESES)</font></a>
<br>
<table width="70%">
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>NOMBRES</strong></td>
<td align="center"><strong>UNDS</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
<td align="center"><strong>FECHA</strong></td>
<?php do { 
$fechas_agotado_seg = $datos['fechas_agotado_seg'];
?>
<tr>
<td><?php echo $datos['cod_productos_var']; ?></td>
<td><?php echo $datos['nombre_productos']; ?></td>
<td align="center"><?php echo $datos['unidades_faltantes']; ?></td>
<td align="right"><?php echo number_format($datos['precio_venta'], 0, ",", "."); ?></td>
<td align="right"><?php echo date("d/m/Y", $fechas_agotado_seg); ?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>