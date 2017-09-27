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
$edicion_de_formulario = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $edicion_de_formulario .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$valor_caja = mysql_escape_string($_POST['valor_caja']);
$total_ventas = mysql_escape_string($_POST['total_ventas']);
$total_caja = mysql_escape_string($_POST['total_caja']);
$fecha = mysql_escape_string($_POST['fecha']);
$hora = mysql_escape_string($_POST['hora']);
$ip = mysql_escape_string($_POST['ip']);
$cuenta = mysql_escape_string($_POST['cuenta']);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion") && ($_POST["actualizar"])) {
$actualizar_sql = sprintf("UPDATE cajas_registros SET valor_caja=%s, total_ventas=%s, total_caja=%s, fecha=%s, hora=%s, ip=%s, cuenta=%s WHERE cod_cajas_registros=%s",
envio_valores_tipo_sql($valor_caja, "text"),
envio_valores_tipo_sql($total_ventas, "text"),
envio_valores_tipo_sql($total_caja, "text"),
envio_valores_tipo_sql($fecha, "text"),
envio_valores_tipo_sql($hora, "text"),
envio_valores_tipo_sql($ip, "text"),
envio_valores_tipo_sql($cuenta, "text"),
envio_valores_tipo_sql($_POST['cod_cajas_registros'], "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/registros_cierre_caja.php">';
}
$cod_cajas_registros = mysql_escape_string($_GET['cod_cajas_registros']);

$sql_modificar_consulta = "SELECT * FROM cajas_registros where cod_cajas_registros = '$cod_cajas_registros'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($modificar_consulta);
$total_matriz_consulta = mysql_num_rows($modificar_consulta);
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
if (isset($_GET["cod_cajas_registros"])) {
?>
<center>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<tr valign="baseline">
<td nowrap align="left">BASE CAJA:</td>
<td><input type="text" style="font-size:40px" name="valor_caja" value="<?php echo $matriz_consulta['valor_caja']; ?>" size="20"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">TOTAL VENTAS:</td>
<td><input type="text" style="font-size:40px" name="total_ventas" value="<?php echo $matriz_consulta['total_ventas']; ?>" size="20"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">TOTAL CAJA:</td>
<td><input type="text" style="font-size:40px" name="total_caja" value="<?php echo $matriz_consulta['total_caja']; ?>" size="20"></td>
 </tr>
<tr valign="baseline">
<td nowrap align="left">FECHA:</td>
<td><input type="text" style="font-size:40px" name="fecha" value="<?php echo $matriz_consulta['fecha']; ?>" size="20"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">HORA:</td>
<td><input type="text" style="font-size:40px" name="hora" value="<?php echo $matriz_consulta['hora']; ?>" size="20"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">IP:</td>
<td><input type="text" style="font-size:40px" name="ip" value="<?php echo $matriz_consulta['ip']; ?>" size="20"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">CUENTA:</td>
<td><input type="text" style="font-size:40px" name="cuenta" value="<?php echo $matriz_consulta['cuenta']; ?>" size="20"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">&nbsp;</td>
<td><input type="submit" id="boton1" name="actualizar" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_cajas_registros" value="<?php echo $matriz_consulta['cod_cajas_registros']; ?>">
</form>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_cajas_registros" value="<?php echo $matriz_consulta['cod_cajas_registros']; ?>">
</form>
</center>
<?php
}
?>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
