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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$fecha_actual_en_segundos = time();
$fecha_actual_segundos = time();

$mostrar_meses_vencer = "SELECT * FROM meses_vencimiento WHERE cod_meses_vencimiento = '1'";
$consulta_meses_vencer = mysql_query($mostrar_meses_vencer, $conectar) or die(mysql_error());
$meses_vencer = mysql_fetch_assoc($consulta_meses_vencer);

//Queremos sumar 6 meses a la fecha actual:
$meses = $meses_vencer['meses'];
// Convertimos los meses a segundos y se los sumamos sumamos a la fecha_actual_segundos:
$fecha_actual_segundos += ($meses * 30 * 24 * 60 * 60);
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
$mostrar_datos_sql = "SELECT * FROM productos WHERE fechas_vencimiento_seg  <= '$fecha_actual_segundos' AND fechas_vencimiento_seg <> '' ORDER BY fechas_vencimiento_seg";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$pagina = $_SERVER['PHP_SELF'];
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
<strong><font size='4' color='yellow'>PRODUCTOS A VENCER DENTRO DE <?php echo $meses; ?> MESES</font></strong>

<a href="../admin/cambiar_numero_meses.php?pagina=<?php echo $pagina?>"><font size='3' color='yellow'>(CAMBIAR NUMERO DE MESES)</font></a>
<br>
<table>
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>NOMBRES</strong></td>
<td align="center"><strong>UNDS</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
<td align="center"><strong>VENCIMIENTO</strong></td>
<?php do { ?>
<tr>
<td><?php echo $datos['cod_productos_var']; ?></td>
<td><?php echo $datos['nombre_productos']; ?></td>
<td align="center"><?php echo $datos['unidades_faltantes']; ?></td>
<td align="right"><?php echo $datos['precio_venta']; ?></td>
<td align="right"><?php echo $datos['fechas_vencimiento']; ?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>