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
include ("../formato_entrada_sql/funcion_env_val_sql.php");

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$cod_productos = $_GET['cod_productos'];
$cod_factura = $_GET['cod_factura'];

$mostrar_datos_sql = "SELECT * FROM ventas WHERE cod_productos = '$cod_productos' ORDER BY vendedor";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$mostrar_dato = "SELECT sum(unidades_vendidas) as suma_unidades_vendidas FROM ventas WHERE cod_productos = '$cod_productos'";
$consultance = mysql_query($mostrar_dato, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consultance);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<?php
if ($cod_productos <> NULL) {?>
<center>
<br>
<!--<font color='yellow' size='+3'>INVENTARIO DIA: <?php echo $cod_productos;?></font>-->
<td ><a href="../admin/resultado_comparacion_tablas.php?cod_factura=<?php echo $cod_factura?>"><font color='yellow' size='+1'>REGRESAR</font></a></td>
<br><br>
<table width='80%'>
<tr>
<td><div align="center">C&Oacute;DIGO</div></td>
<td><div align="center">FACTURA</div></td>
<td><div align="center">PRODUCTO</div></td>
<td><div align="center">UNDS</div></td>
<!--<td><div align="center">P.COMPRA</div></td>-->
<td><div align="center">P.VENTA</div></td>
<td><div align="center">TOTAL</div></td>
<td><div align="center">VENDEDOR</div></td>
<td><div align="center">FECHA</div></td>
<td><div align="center">HORA</div></td>
</tr>
<?php 
do { ?>
<tr>
<td ><?php echo $datos['cod_productos']; ?></td>
<td ><div align="center"><?php echo $datos['cod_factura']; ?></div></td>
<td ><?php echo utf8_decode($datos['nombre_productos']); ?></td>
<td><div align="center"><?php echo $datos['unidades_vendidas']; ?></div></td>
<!--<td><div align="right"><?php echo $datos['precio_compra']; ?></div></td>-->
<td><div align="right"><?php echo $datos['precio_venta']; ?></div></td>
<td><div align="right"><?php echo $datos['vlr_total_venta']; ?></div></td>
<td><div align="center"><?php echo $datos['vendedor']; ?></div></td>
<td><div align="center"><?php echo $datos['fecha_anyo']; ?></div></td>
<td><div align="center"><?php echo $datos['fecha_hora']; ?></div></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta));
?>
</table>
<br>
<table>
<strong><font color='yellow' size='+2'>TOTAL UND VENDIDAS: <?php echo $suma['suma_unidades_vendidas'];?></font></strong>
</table>
</body>
</html>
<?php mysql_free_result($consulta);
}
?>