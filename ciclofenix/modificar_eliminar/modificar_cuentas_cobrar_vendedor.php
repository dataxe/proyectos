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
$edicion_de_formulario = $_SERVER['PHP_SELF'];

$cod_factura = $_GET['cod_factura'];

$maximo_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos WHERE (cod_factura = '$cod_factura')";
$consulta_maximo = mysql_query($maximo_valor, $conectar) or die(mysql_error());
$maximo = mysql_fetch_assoc($consulta_maximo);

$sql_modificar_consulta = "SELECT * FROM cuentas_cobrar, clientes WHERE (cuentas_cobrar.cod_clientes = clientes.cod_clientes) AND cod_factura = '$cod_factura'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);


if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {

$fecha_pago = $_POST['fecha_pago'];
$mensaje = $_POST['mensaje'];
$abonado = $_POST['abonado'];
$total_abonado = $_POST['abonado'] + $_POST['total_abonado'];
$vendedor = $_POST['vendedor'];
$monto_deuda = $_POST['monto_deuda'];
$cod_factura = $_POST['cod_factura'];
$cod_clientes = $_POST['cod_clientes'];
$descuento = $_POST['descuento'];
$subtotal = ($_POST['monto_deuda'] - $total_abonado);
$hora = date("H:i:s");

$fecha_vector = explode('/', $fecha_pago);
$dia = $fecha_vector[0];
$mes = $fecha_vector[1];
$anyo = $fecha_vector[2];
$fecha_invert = $anyo.'/'.$mes.'/'.$dia;

$actualizar_sql = sprintf("UPDATE cuentas_cobrar SET monto_deuda=%s, abonado=%s, subtotal=%s, mensaje=%s WHERE cod_factura=%s",
envio_valores_tipo_sql($_POST['monto_deuda'], "text"),
envio_valores_tipo_sql($total_abonado, "text"),
envio_valores_tipo_sql($subtotal, "text"),
envio_valores_tipo_sql($mensaje, "text"),
envio_valores_tipo_sql($cod_factura, "text"));
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

$agregar_reg_cuentas_cobrar_abonos = "INSERT INTO cuentas_cobrar_abonos (cod_clientes, cod_factura, abonado, monto_deuda, subtotal, descuento, vendedor, cuenta, fecha_pago, 
fecha, fecha_invert, hora, mensaje) VALUES ('$cod_clientes', '$cod_factura', '$abonado', '$monto_deuda', '$subtotal', '$descuento', '$vendedor', '$cuenta_actual', 
'$fecha_pago', '$fecha_pago', '$fecha_invert', '$hora', '$mensaje')";
$resultado_cuentas_cobrar_abonos = mysql_query($agregar_reg_cuentas_cobrar_abonos, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/cuentas_cobrar_vendedor.php">';
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
<td><strong><a href="../admin/cuentas_cobrar_vendedor.php"><font color='yellow' size="5px">REGRESAR</font></a></strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td><a href="../admin/productos_fiados_vendedor.php?cod_factura=<?php echo $datos['cod_factura'];?>&cliente=<?php echo $cliente;?>"><center><strong><font color='yellow' size="5px">VER PRODUCTOS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</font></strong></center></a></td>
<td><a href="../admin/cuentas_cobrar_abonos_vendedor.php?cod_factura=<?php echo $datos['cod_factura'];?>"><center><strong><font color='yellow' size="5px">VER ABONOS</font></strong></center></a></td>
</table>

<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<tr valign="baseline">
<td nowrap align="left"><font size="6">FACTURA:</font></td>
<td><font size="6"><?php echo $cod_factura; ?></font></td>
</tr>
<input type="hidden" name="cod_factura" style="font-size:28px" value="<?php echo $cod_factura; ?>" size="30">
<input type="hidden" name="cod_clientes" style="font-size:28px" value="<?php echo $datos['cod_clientes']; ?>" size="30">
<input type="hidden" name="vendedor" style="font-size:28px" value="<?php echo $datos['vendedor']; ?>" size="30">
<input type="hidden" name="total_abonado" style="font-size:28px" value="<?php echo $maximo['abonado']; ?>" size="30">

<tr valign="baseline">
<td nowrap align="left"><font size="6">CLIENTE:</font></td>
<td><font size="6"><?php echo $datos['nombres'].' '.$datos['apellidos'].' '.$datos['cedula'] ; ?></font></td>
</tr>
<td nowrap align="left"><font size="6">MONTO DEUDA:</font></td>
<td><font size="6"><?php echo number_format($datos['monto_deuda']); ?></font></td>
<input type="hidden" name="monto_deuda" style="font-size:28px" value="<?php echo $datos['monto_deuda']; ?>" size="30">
</tr>
<tr valign="baseline">
<td nowrap align="left"><font size="6">DEUDA:</font></td>
<td><font size="6"><?php echo number_format($datos['subtotal']); ?></font></td>
</tr>
<tr valign="baseline">
<td nowrap align="left"><font size="6">ABONADO:</font></td>
<td><input type="text" name="abonado" style="font-size:28px" value="" size="30"  required autofocus></td>
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
<input type="hidden" name="cod_factura" value="<?php echo $datos['cod_factura']; ?>">
</form>
</center>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
