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
date_default_timezone_set("America/Bogota");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<body>
<?php
$suma_temporal = "SELECT  Sum(precio_compra_con_descuento) As total_venta, cod_factura FROM temporal WHERE vendedor = '$cuenta_actual'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_temporal);

$cod_factura = $suma['cod_factura'];

$info_factura = "SELECT * FROM info_impuesto_facturas WHERE vendedor = '$cuenta_actual' AND cod_factura = '$cod_factura'";
$consulta_info = mysql_query($info_factura, $conectar) or die(mysql_error());
$info = mysql_fetch_assoc($consulta_info);

$venta_total = $suma['total_venta'] - $info['descuento'];
?>
<center>
<table>
<td><font color='green' size= "+2">Subtotal: </font></td><td><font color='green' size= "+2"><?php echo number_format($suma['total_venta']); ?></td>
<tr></tr>
<td><font color='green' size= "+2">Descuento: </font></td><td><font color='green' size= "+2"><?php echo number_format($info['descuento']); ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">Total Venta: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($venta_total); ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">Recibido: </font></td><td><font color='yellow' size= "+3"><?php echo number_format($info['vlr_cancelado']); ?></td>
<tr></tr>
<td><font color='yellow' size= "+4">Cambio: </font></td><td><font color='yellow' size= "+4"><?php echo number_format($info['vlr_cancelado'] - $venta_total); ?></td>
</table>
<br>
<form method="post" action="../admin/facturacion_electronica.php">
<input type="image" id ="foco" src="../imagenes/listo.png" name="listo" value="listo" />
</form>
</center>
<?php
$borrar_sql = sprintf("DELETE FROM temporal WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
?>
</body>
</html>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>