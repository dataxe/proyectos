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

//--------------------------------------------CUANDO LLEGUE EL DATO DE LA FECHA--------------------------------------------//
//--------------------------------------------CUANDO LLEGUE EL DATO DE LA FECHA--------------------------------------------//
if ($_POST['fecha_anyo'] <> NULL) {
$fecha_anyo = addslashes($_POST['fecha_anyo']);
//--------------------------------------------  --------------------------------------------//
//--------------------------------------------  --------------------------------------------//
$vista_reg_venta = "SELECT total_ventas_fisico, total_ventas_sistema, fecha_anyo, usuario FROM caja_registro_fisico 
WHERE (fecha_anyo = '$fecha_anyo') AND usuario = '$cuenta_actual'";
$consulta_vista_reg_venta = mysql_query($vista_reg_venta, $conectar) or die(mysql_error());
$datos_vista_reg_venta = mysql_fetch_assoc($consulta_vista_reg_venta);

$total_ventas_fisico = $datos_vista_reg_venta['total_ventas_fisico'];
$total_ventas_sistema = $datos_vista_reg_venta['total_ventas_sistema'];
$resta = $total_ventas_fisico - $total_ventas_sistema;
}
//-------------------------------------------- FIN DEL ISSET FECHA --------------------------------------------//
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
<td nowrap align="right">TOTAL DIARIO:</td>
<td bordercolor="0">
<select name="fecha_anyo" id="foco">
<?php 
$sql_consulta1="SELECT fecha, fecha_anyo, usuario FROM caja_registro_fisico WHERE usuario = '$cuenta_actual' GROUP BY fecha DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor = mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['fecha_anyo'] ?>"><?php echo $contenedor['fecha_anyo'].' - '.$contenedor['usuario'] ?></option>
<?php }?>
</select></td></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Consultar"></td>
</tr>
</table>
</form>
</center>
<?php
if ($_POST['fecha_anyo'] <> NULL) { ?>
<center>
<fieldset><legend><font color='yellow' size='+3'>TOTAL <?php echo strtoupper($cuenta_actual) ?> DIA: <?php echo $fecha_anyo ?></font></legend>
<table width='40%'>
<tr>
<td align="center"><strong>TOTAL VENTA</td>
<td align="center"><strong>TOTAL CAJA REG</td>
<td align="center"><strong>RESULTADO</td>
</tr>
<tr>
<td align="center"><font color="yellow" size="+3"><strong><?php echo number_format($total_ventas_fisico, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+3"><strong><?php echo number_format($total_ventas_sistema, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+3"><strong><?php echo number_format($resta, 0, ",", "."); ?></font></td>
</tr>
</fieldset>
</center>
<?php
}
?>