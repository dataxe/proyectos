<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
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

//require_once("menu_inventario.php");

$fecha = addslashes($_POST['fecha']);
$fecha_dmy = date("d/m/Y");
$fecha_seg = strtotime(date("Y/m/d"));
$hora = date("H:i:s");
$fecha_time = time();
//--------------------------------------------CUANDO LLEGUE EL DATO DE LA FECHA--------------------------------------------//
//--------------------------------------------CUANDO LLEGUE EL DATO DE LA FECHA--------------------------------------------//
if ($fecha <> NULL) {
//--------------------------------------------  --------------------------------------------//
//--------------------------------------------  --------------------------------------------//
$vista_reg_venta = "SELECT cuenta, num_entrada, fecha_dmy FROM acceso_vista_reg_venta WHERE (fecha_dmy = '$fecha') AND cuenta = '$cuenta_actual'";
$consulta_vista_reg_venta = mysql_query($vista_reg_venta, $conectar) or die(mysql_error());
$datos_vista_reg_venta = mysql_fetch_assoc($consulta_vista_reg_venta);
$total_resultados = mysql_num_rows($consulta_vista_reg_venta);

$num_entrada_verif = $datos_vista_reg_venta['num_entrada'];
//--------------------------------------------  --------------------------------------------//
//--------------------------------------------  --------------------------------------------//
if (($total_resultados == 0) && ($fecha == $fecha_dmy)) {
$num_entrada_ini = 1;

$agregar_reg_nuevo = "INSERT INTO acceso_vista_reg_venta (cuenta, num_entrada, fecha_dmy, fecha_seg, hora1, fecha_time)
VALUES ('$cuenta_actual', '$num_entrada_ini', '$fecha_dmy', '$fecha_seg', '$hora', '$fecha_time')";
$resultado_reg_nuevo = mysql_query($agregar_reg_nuevo, $conectar) or die(mysql_error());
} 
//--------------------------------------------  --------------------------------------------//
//--------------------------------------------  --------------------------------------------//
if (($total_resultados > 0 && $num_entrada_verif <= 1) && ($fecha == $fecha_dmy)) {

$num_entrada = $datos_vista_reg_venta['num_entrada']+1;

$actualizar_reg = sprintf("UPDATE acceso_vista_reg_venta SET num_entrada = '$num_entrada', hora2 = '$hora', fecha_time = '$fecha_time' 
WHERE cuenta = '$cuenta_actual' AND fecha_dmy = '$fecha_dmy'");
$resultado_reg = mysql_query($actualizar_reg, $conectar) or die(mysql_error());
}
//--------------------------------------------  --------------------------------------------//
//--------------------------------------------  --------------------------------------------//
if ($num_entrada_verif >= 2) {
} else {
//-------------------------------------------- FITRO PARA LOS DATOS DE LAS VENTAS --------------------------------------------//
$mostrar_datos_sql = "SELECT cod_productos, nombre_productos, unidades_vendidas, precio_venta, vlr_total_venta, tipo_pago, vendedor, fecha_anyo, fecha_hora 
FROM ventas WHERE fecha_anyo = '$fecha' AND vendedor = '$cuenta_actual' ORDER BY cod_ventas DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LAS VENTAS POR CONTADO --------------------------------------------//
$mostrar_datos_sql_venta_contado = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta_contado, 
sum(precio_costo*unidades_vendidas) As vlr_total_compra, Sum(((precio_venta/1.16)*(iva/100))*unidades_vendidas) As sum_iva FROM ventas 
WHERE (tipo_pago ='1' OR tipo_pago ='') AND fecha_anyo = '$fecha' AND vendedor = '$cuenta_actual'";
$consulta_venta_contado = mysql_query($mostrar_datos_sql_venta_contado, $conectar) or die(mysql_error());
$matriz_venta_contado = mysql_fetch_assoc($consulta_venta_contado);

//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LAS VENTAS CREDITO --------------------------------------------//
$mostrar_datos_sql_venta = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta_credito, 
sum(precio_costo*unidades_vendidas) As vlr_total_compra, Sum(((precio_venta/1.16)*(iva/100))*unidades_vendidas) As sum_iva FROM ventas 
WHERE fecha_anyo = '$fecha' AND tipo_pago ='2' AND vendedor = '$cuenta_actual'";
$consulta_venta = mysql_query($mostrar_datos_sql_venta, $conectar) or die(mysql_error());
$matriz_venta_credito = mysql_fetch_assoc($consulta_venta);

$total_venta_contado = $matriz_venta_contado['total_venta_contado'];
$total_venta_credito = $matriz_venta_credito['total_venta_credito'];

$campo = 'fecha_anyo';
//-------------------------------------------- FIN DEL IF num_entrada --------------------------------------------//
}
//-------------------------------------------- FIN DEL ISSET FECHA --------------------------------------------//
}
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
<form method="post" name="formulario" action="">
<table align="center">
<td nowrap align="right">VENTAS DIARIA:</td>
<td bordercolor="0">
<select name="fecha" id="foco">
<?php 
$sql_consulta1="SELECT fecha_anyo FROM ventas WHERE vendedor = '$cuenta_actual' GROUP BY fecha DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor = mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['fecha_anyo'] ?>"><?php echo $contenedor['fecha_anyo'] ?></option>
<?php }?>
</select></td></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Consultar Ventas"></td>
</tr>
</table>
</form>
</center>
<?php
if ($fecha <> NULL) {

if ($num_entrada_verif >= 2) {
echo "<br><br><center><td><font color='yellow' size='+2'>".strtoupper($cuenta_actual)." HAZ EXCEDIDO EL LIMITE DE ".$num_entrada_verif." VISITAS DIARIAS PARA EL DIA DE HOY: ".$fecha."</font></td></center>";
} else {

?>
<center>
<fieldset><legend><font color='yellow' size='+3'>VENTAS TOTALES DE <?php echo strtoupper($cuenta_actual) ?> DIA: <?php echo $fecha ?></font></legend>
<table width='40%'>
<tr>
<td align="center" title="Total ventas hechas en contado"><strong>TOTAL VENTA (CONTADO)</td>
<td align="center" title="Total ventas hechas en credito"><strong>TOTAL VENTA (CREDITO)</td>
</tr>
<tr>
<td align="center"><font color="yellow" size="+3"><strong><?php echo number_format($total_venta_contado, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+3"><strong><?php echo number_format($total_venta_credito, 0, ",", "."); ?></font></td>
</tr>
</fieldset>
</center>
<br>
<table width='90%'>
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>PRECIO VENTA</strong></td>
<td align="center"><strong>TOTAL VENTA</strong></td>
<td align="center"><strong>TIPO PAGO</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>FECHA VENTA</strong></td>
<td align="center"><strong>HORA</strong></td>
</tr>
<?php 
do {
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$unidades_vendidas = $datos['unidades_vendidas'];
$precio_venta = $datos['precio_venta'];
$vlr_total_venta = $datos['vlr_total_venta'];
$tipo_pago = $datos['tipo_pago'];
$vendedor = $datos['vendedor'];
$fecha_anyo = $datos['fecha_anyo'];
$fecha_hora = $datos['fecha_hora'];
?>
<tr>
<td ><?php echo $cod_productos; ?></td>
<td ><?php echo $nombre_productos; ?></td>
<td align="right"><?php echo $unidades_vendidas; ?></td>
<td align="right"><?php echo number_format($precio_venta, 0, ",", "."); ?></td>
<td align="right"><?php echo number_format($vlr_total_venta, 0, ",", "."); ?></td>
<td align="center"><?php echo $tipo_pago; ?></td>
<td align="center"><?php echo $vendedor; ?></td>
<td align="center"><?php echo $fecha_anyo; ?></td>
<td align="center"><?php echo $fecha_hora; ?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta));?>
</table>
</fieldset>
</body>
</html>
<?php
} 
} else {
}
?>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>