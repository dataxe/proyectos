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

$buscar = $_GET['buscar'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>ALMACEN</title>
<center>
<br>
<td><a href="../admin/ver_factura_ventas.php"><font size='4' color='yellow'>REGRESAR</font></a></td>
<br><br>
<td><font size='4' color='yellow'>BUSCAR POR MES (MES/A&Ntilde;O), CODIGO O NOMBRE</font></td>
<br>
</center>
</head>
<body>
<?php
$sql = "SELECT ventas.cod_productos, ventas.nombre_productos, Sum(ventas.unidades_vendidas) AS unidades_vendidas, ventas.fecha_mes
FROM ventas GROUP BY ventas.cod_productos, ventas.nombre_productos, ventas.fecha_mes 
HAVING (((ventas.cod_productos)='$buscar') OR ((ventas.nombre_productos) LIKE '$buscar%') OR ((ventas.fecha_mes)='$buscar')) ORDER BY nombre_productos, fecha DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$tota_reg = mysql_num_rows($consulta);
?>
<center>
<br>
<form method="GET" name="formulario" action="">
<table align="center">
<input name="buscar" required autofocus>
<input type="submit" name="buscador" value="BUSCAR INFORMACION" />
</form>
<br>
<?php
 if (isset($_GET['buscar'])) { ?>
<br>
<table width="60%">
<tr>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UNDS</strong></td>
<td align="center"><strong>MES</strong></td>

</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$unidades_vendidas = $datos['unidades_vendidas'];
$fecha_mes = $datos['fecha_mes'];
?>
<tr>
<td><a href="../admin/ver_producto_vendido_por_mes.php?cod_productos=<?php echo $cod_productos ?>&fecha_mes=<?php echo $fecha_mes ?>&buscar=<?php echo $buscar ?>"><?php echo $cod_productos; ?></a></td>
<td><a href="../admin/ver_producto_vendido_por_mes.php?cod_productos=<?php echo $cod_productos ?>&fecha_mes=<?php echo $fecha_mes ?>&buscar=<?php echo $buscar ?>"><?php echo $nombre_productos; ?></a></td>
<td align='right'><a href="../admin/ver_producto_vendido_por_mes.php?cod_productos=<?php echo $cod_productos ?>&fecha_mes=<?php echo $fecha_mes ?>&buscar=<?php echo $buscar ?>"><?php echo $unidades_vendidas; ?></a></td>
<td align='center'><a href="../admin/ver_producto_vendido_por_mes.php?cod_productos=<?php echo $cod_productos ?>&fecha_mes=<?php echo $fecha_mes ?>&buscar=<?php echo $buscar ?>"><?php echo $fecha_mes; ?></a></td>
</tr>	 
<?php
}
} else {
}
?>
</form>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>
