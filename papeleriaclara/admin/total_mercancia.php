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

$mostrar_datos_sql = "SELECT  Sum(total_mercancia) As total_mercancia, Sum(total_venta) As total_venta, Sum(utilidad) As total_utilidad FROM productos ";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<br>
<center>
<table border="1" id="table" >
<tr>
<td><div align="center" >Total Mercancia</div></td>
<td><div align="center" >Total Venta</div></td>
<td><div align="center" >Total Utilidad</div></td>
</tr>
<?php do { ?>
<?php 
$calculo_unidades_faltantes = $matriz_consulta['unidades'] - $matriz_consulta['unidades_faltantes'];
$calculo_gasto = $matriz_consulta['precio_costo'] *($matriz_consulta['unidades'] - $matriz_consulta['unidades_faltantes']);
$calculo_venta = (($matriz_consulta['unidades'] - $matriz_consulta['unidades_faltantes']) * $matriz_consulta['precio_venta']) - $matriz_consulta['descuento'];
$calculo_ganancia = ($calculo_venta - $calculo_gasto) - $matriz_consulta['descuento'];
?>
<tr>
<td ><?php echo $matriz_consulta['total_mercancia']; ?></td>
<td ><?php echo $matriz_consulta['total_venta']; ?></td>
<td ><?php echo $matriz_consulta['total_utilidad']; ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
</body>
</html>
<?php mysql_free_result($consulta);?>