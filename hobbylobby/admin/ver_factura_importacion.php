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

$cod_factura = intval($_GET['cod_factura']);

$mostrar_datos_sql = "SELECT * FROM camparacion_tablas WHERE cod_factura = '$cod_factura' ORDER BY cod_camparacion_tablas DESC";
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
<center>
<br>
<a href="exportacion_lista_subida.php"><font color='yellow'>REGRESAR</font></a><br><br>
<td><strong><font color='yellow'>LISTA IMPORTACION: </font></strong></td><br><br>
<table width="80%">
<tr>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>CAJAS</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>U.TOTAL</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>CUENTA</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
<?php do { ?>
<tr>
<td><?php echo $datos['cod_productos']; ?></td>
<td><?php echo utf8_decode($datos['nombre_productos']); ?></td>
<td align="center"><?php echo $datos['cajas']; ?></td>
<td align="center"><?php echo $datos['unidades']; ?></td>
<td align="center"><?php echo $datos['unidades_total']; ?></td>
<td align="center"><?php echo $datos['cod_factura']; ?></td>
<td><?php echo $datos['vendedor']; ?></td>
<td align="center"><?php echo $datos['fecha_anyo']; ?></td>
<td align="center"><?php echo $datos['fecha_hora']; ?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>