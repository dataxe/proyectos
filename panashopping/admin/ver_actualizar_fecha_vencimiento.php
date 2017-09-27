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

$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM actualizado_fecha_vencimiento ORDER BY fecha_time DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
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
<td align="center"><a href="../admin/actualizar_fecha_vencimiento.php"><font color="yellow" size="+2">REGRESAR</a></font></td>
<br><br>
<td><strong><font color='yellow' size="+2">LISTA DE PRODUCTOS CON FECHAS DE VENCIMIENTO</font></strong></td><br><br>
<table width='90%'>
<tr>
<td align="center">C&Oacute;DIGO</td>
<td align="center">PRODUCTO</td>
<td align="center">UND</td>
<td align="center">PRECIO VENTA</td>
<!--<td align="center">FACTURA</td>-->
<!--<td align="center">FECHA FACTURA</td>-->
<td align="center">FECHA DE REGISTRO</td>
<td align="center">FECHA VENCIMIENTO</td>
<td align="center">CUENTA</td>
</tr>
<?php do {
$cod_productos = $matriz_consulta['cod_productos'];
$nombre_productos = $matriz_consulta['nombre_productos'];
$unidades_faltantes = $matriz_consulta['unidades_faltantes'];
$precio_venta = $matriz_consulta['precio_venta'];
$fechas_vencimiento = $matriz_consulta['fechas_vencimiento'];
$fecha_anyo = $matriz_consulta['fecha_anyo'];
$cuenta = $matriz_consulta['cuenta'];
$fecha_time = date("d/m/Y - H:i:s", $matriz_consulta['fecha_time']);
$numero_factura = $matriz_consulta['numero_factura'];
$fecha_factura = $matriz_consulta['fecha_factura'];
?>
<tr>
<td><font size='3px'><?php echo $cod_productos ?></font></td>
<td><font size='3px'><?php echo $nombre_productos ?></font></td>
<td align="right"><font size='3px'><?php echo $unidades_faltantes ?></font></td>
<td align="right"><font size='3px'><?php echo number_format($precio_venta, 0, ",", "."); ?></font></td>
<!--<td align="center"><font size='3px'><?php echo $numero_factura ?></font></td>-->
<!--<td align="center"><font size='3px'><?php echo $fecha_factura ?></font></td>-->
<td align="center"><font size='3px'><?php echo $fecha_time ?></font></td>
<td align="center"><font size='3px'><?php echo $fechas_vencimiento ?></font></td>
<td align="center"><font size='3px'><?php echo $cuenta ?></font></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>