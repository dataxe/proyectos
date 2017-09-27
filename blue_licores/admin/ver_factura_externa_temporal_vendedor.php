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

$mostrar_datos_sql = "SELECT * FROM exportacion_vendedor WHERE cod_factura = '$cod_factura' ORDER BY cod_exportacion_vendedor DESC";
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
<a href="exportacion_lista_vendedor.php"><font color='yellow'>REGRESAR</font></a><br><br>
<td><strong><font color='yellow'>LISTA EXPORTACION: </font></strong></td><br><br>
<table width="90%">
<tr>
<th align="center"><strong>CODIGO</strong></th>
<th align="center"><strong>PRODUCTO</strong></th>
<th align="center"><strong>UND SISTEMA</strong></th>
<th align="center"><strong>UND FISICA</strong></th>
<th align="center"><strong>RESULTADO</strong></th>
<th align="center"><strong>OK</strong></th>
<th align="center"><strong>FACTURA</strong></th>
<th align="center"><strong>CUENTA</strong></th>
<th align="center"><strong>FECHA</strong></th>
<th align="center"><strong>HORA</strong></th>
<?php do { 
$unidades_faltantes = $datos['unidades_faltantes'];
$unidades_total = $datos['unidades_total'];
$resultado = $unidades_total - $unidades_faltantes;
?>
<tr>
<td><?php echo $datos['cod_productos']; ?></td>
<td><?php echo $datos['nombre_productos']; ?></td>
<td align="center"><?php echo $unidades_faltantes; ?></td>
<td align="center"><?php echo $unidades_total; ?></td>
<?php
if ($resultado < 0) {
?>
<td align="center"><?php echo $resultado * -1; ?> UNDS ESTRAVIADAS</td>
<td align="center"><img src=../imagenes/auxilio.gif alt="auxilio"></td>
<?php
}
?>
<?php
if ($resultado > 0) {
?>
<td align="center"></td>
<td align="center"></td>
<!--
<td align="center"><?php echo $resultado; ?> UNDS SOBRAN</td>
<td align="center"><img src=../imagenes/borrar.gif alt="borrar"></td>
-->
<?php
}
?>
<?php
if ($resultado == 0) {
?>
<td align="center"><?php echo $resultado; ?> BIEN</td>
<td align="center"><img src=../imagenes/bien.png alt="Bien"></td>
<?php
}
?>
<td align="center"><?php echo $datos['cod_factura']; ?></td>
<td align="center"><?php echo $datos['vendedor']; ?></td>
<td align="center"><?php echo $datos['fecha_anyo']; ?></td>
<td align="center"><?php echo $datos['fecha_hora']; ?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>