<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar);

include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php
$buscar = mysql_escape_string($_POST['buscar']);

$mostrar_datos_sql = "SELECT ventas.cod_productos, ventas.nombre_productos, proveedores.nombre_proveedores, Sum(ventas.unidades_vendidas) 
AS SumaDeunidades_vendidas, Avg(ventas.precio_venta) AS PromedioDeprecio_venta, ventas.fecha_mes FROM proveedores RIGHT JOIN ventas 
ON proveedores.cod_proveedores = ventas.cod_proveedores GROUP BY ventas.cod_productos, proveedores.nombre_proveedores, ventas.fecha_mes
HAVING (((ventas.cod_productos)='$buscar')) OR (((ventas.nombre_productos) LIKE '$buscar%')) ORDER BY ventas.fecha DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$tota_reg = mysql_num_rows($consulta);

echo "<font color='yellow'><strong>".$tota_reg." Resultados para: ".$buscar."</strong></font><br>";
?>
<br>
<center>
<table width='90%'>
<tr>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>NOMBRE</strong></td>
<td align="center"><strong>PROVEEDOR</strong></td>
<td align="center"><strong>UND COMPRA</strong></td>
<td align="center"><strong>PRECIO VENTA (PRMDIO)</strong></td>
<td align="center"><strong>MES DE VENTA</strong></td>
</tr>
<?php do { ?>
<td><font size ='3px'><?php echo $datos['cod_productos']; ?></font></td>
<td><font size ='3px'><?php echo $datos['nombre_productos']; ?></font></td>
<td><font size ='3px'><?php echo $datos['nombre_proveedores']; ?></font></td>
<td align="right"><font size ='3px'><?php echo $datos['SumaDeunidades_vendidas']; ?></font></td>
<td align="right"><font size ='3px'><?php echo number_format($datos['PromedioDeprecio_venta'], 0, ",", "."); ?></font></td>
<td align="center"><font size ='3px'><?php echo $datos['fecha_mes']; ?></font></td>
</tr>
<?php
}
while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<?php
?>
