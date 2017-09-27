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
$edicion_de_formulario = $_SERVER['PHP_SELF'];

$cod_clientes = $_GET['cod_clientes'];
$pagina = $_SERVER['PHP_SELF'];

$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos WHERE cod_clientes = '$cod_clientes'";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$sum_abonado_act = $sum_abonos['abonado'];

$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As vlr_total_venta FROM productos_fiados WHERE cod_clientes = '$cod_clientes'";
$modificar_prod_fiados = mysql_query($sql_prod_fiados, $conectar) or die(mysql_error());
$datos_fiad = mysql_fetch_assoc($modificar_prod_fiados);

$sql_modificar_consulta = "SELECT * FROM cuentas_cobrar, clientes 
WHERE (cuentas_cobrar.cod_clientes = clientes.cod_clientes) AND cuentas_cobrar.cod_clientes = '$cod_clientes'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$abonado_pos = $_POST['abonado'];
$abonado1 = $abonado_pos + $sum_abonado_act;
$vendedor = $_POST['vendedor'];
$monto_deuda_mostrar = $datos_fiad['vlr_total_venta'];
$subtotal = $monto_deuda_mostrar - $abonado1;
$descuento = $_POST['descuento'];

//-------------------------------------- REGISTRAR DATOS --------------------------------------//
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {

$monto_deuda = $_POST['monto_deuda_post'];
$sum_abonado_act = $_POST['sum_abonado_act'];
$abonado_cuenta_cobrar = $sum_abonado_act + $_POST['abonado'];
$abonado = $_POST['abonado'];
$cod_clientes = $_POST['cod_clientes'];
$fecha_pago = $_POST['fecha_pago'];
$mensaje = $_POST['mensaje'];
$hora = date("H:i:s");

$fecha_vector = explode('/', $fecha_pago);
$dia = $fecha_vector[0];
$mes = $fecha_vector[1];
$anyos = $fecha_vector[2];
$fecha_anyo = $fecha_pago;
$fecha_mes = $mes.'/'.$anyos;
$anyo = $anyos;
$fecha_invert = strtotime($anyos.'/'.$mes.'/'.$dia);

$actualizar_sql = sprintf("UPDATE cuentas_cobrar SET monto_deuda = '$monto_deuda', abonado = '$abonado_cuenta_cobrar', subtotal = '$subtotal', 
mensaje = '$mensaje' WHERE cod_clientes = '$cod_clientes'");
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

$agregar_reg_cuentas_cobrar_abonos = "INSERT INTO cuentas_cobrar_abonos (cod_clientes, abonado, monto_deuda, subtotal, descuento, 
cuenta, fecha_pago, fecha_anyo, fecha_mes, anyo, fecha_invert, hora, mensaje) 
VALUES ('$cod_clientes', '$abonado', '$monto_deuda', '$subtotal', '$descuento', '$cuenta_actual', 
'$fecha_pago', '$fecha_anyo', '$fecha_mes', '$anyo', '$fecha_invert', '$hora', '$mensaje')";
$resultado_cuentas_cobrar_abonos = mysql_query($agregar_reg_cuentas_cobrar_abonos, $conectar) or die(mysql_error());

$sql_sum_abonos = "SELECT Sum(abonado) As abonado FROM cuentas_cobrar_abonos WHERE cod_clientes = '$cod_clientes'";
$consulta_sum_abonos  = mysql_query($sql_sum_abonos, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$abonado_sum = $sum_abonos['abonado'];
$deuda = $monto_deuda - $abonado_sum;

if ($deuda <= '0') {
$borrar_alerta  = sprintf("DELETE FROM notificacion_alerta WHERE cod_clientes = '$cod_clientes'");
$Resultado1 = mysql_query($borrar_alerta , $conectar) or die(mysql_error());
}
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/cuentas_cobrar.php">
<?php
}
$cliente = $datos['nombres'].' '.$datos['apellidos'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title></title>
</head>
<body>
<center>

<table>
<td><strong><a href="../admin/cuentas_cobrar.php"><font color='yellow' size="5px">REGRESAR</font></a></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><a href="../admin/productos_fiados.php?cod_clientes=<?php echo $datos['cod_clientes'];?>&cliente=<?php echo $cliente;?>"><center><strong><font color='yellow' size="5px">VER PRODUCTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></strong></center></a></td>
<td><a href="../admin/cuentas_cobrar_abonos.php?cod_clientes=<?php echo $datos['cod_clientes'];?>&cliente=<?php echo $cliente;?>"><center><strong><font color='yellow' size="5px">VER ABONOS</font></strong></center></a></td>
</table>

<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
</tr>
<tr valign="baseline">
<td nowrap align="left"><font size="6">CLIENTE:</font></td>
<td><font size="6"><?php echo $cliente; ?></font></td>
</tr>
<td nowrap align="left"><font size="6">MONTO DEUDA:</font></td>
<td><font size="6"><?php echo number_format($monto_deuda_mostrar, 0, ",", "."); ?></font></td>
<input type="hidden" name="monto_deuda_post" value="<?php echo $monto_deuda_mostrar; ?>" size="30">
</tr>
<tr valign="baseline">
<td nowrap align="left"><font size="6">DEUDA:</font></td>
<td><font size="6"><?php echo number_format($subtotal, 0, ",", "."); ?></font></td>
</tr>
<tr valign="baseline">
<td nowrap align="left"><font size="6">ABONADO:</font></td>
<td><input type="text" name="abonado" style="font-size:28px" value="" size="30"  required autofocus></td>
<input type="hidden" name="sum_abonado_act" value="<?php echo $sum_abonado_act; ?>" size="30">
</tr>
<tr valign="baseline">
<td nowrap align="left"><font size="6">DESCUENTO:</font></td>
<td><font size="6"><?php echo $datos['descuento']; ?></font></td>
</tr>
<input type="hidden" name="descuento" style="font-size:28px" value="<?php echo $datos['descuento']; ?>" size="30">
<tr valign="baseline">
<td nowrap align="left"><font size="6">MENSAJE:</font></td>
<td><input type="text" name="mensaje" style="font-size:28px" value="" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left"><font size="6">FECHA PAGO:</font></td>
<td><input type="text" name="fecha_pago" style="font-size:28px" value="<?php echo date("d/m/Y"); ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">&nbsp;</td>
<td><input type="submit" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_clientes" value="<?php echo $datos['cod_clientes']; ?>">
</form>
</center>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>