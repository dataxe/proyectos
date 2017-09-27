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

$cod_base_caja = $_GET['cod_base_caja'];

$fecha_invert = date("Y/m/d");
$fecha = date("d/m/Y");
$hora = date("H:i:s");

$sql_modificar_consulta = "SELECT * FROM base_caja where cod_base_caja = '$cod_base_caja'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($modificar_consulta);

$edicion_de_formulario = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
$edicion_de_formulario .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
$total_caja_real = $matriz_consulta['total_caja'];
$sacar_caja = $_POST['sacar_caja'];
$total_caja = $total_caja_real - $sacar_caja;
$total_ventas = '0';

$actualizar_sql = sprintf("UPDATE base_caja SET total_caja=%s, total_ventas=%s WHERE cod_base_caja=%s",
envio_valores_tipo_sql($total_caja, "text"),
envio_valores_tipo_sql($total_ventas, "text"),
envio_valores_tipo_sql($_POST['cod_base_caja'], "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

$agregar_caja_salidas = "INSERT INTO caja_salidas (cod_base_caja, abonado, vendedor, cuenta, fecha, fecha_invert, hora)
VALUES ('$cod_base_caja', '$sacar_caja', '$cuenta_actual', '$cuenta_actual', '$fecha', '$fecha_invert', '$hora')";

$resultado_caja_salidas = mysql_query($agregar_caja_salidas, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/caja_base.php">';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title></title>
</head>
<br>
<body>
<center>
<td><a href="../admin/caja_base.php"><font color='yellow' size= "+3">REGRESAR A CAJA</font></a></td>

<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">

<tr valign="baseline">
<td nowrap align="right">TOTAL CAJA:</td>
<td><font size="+5"><?php echo number_format($matriz_consulta['total_caja']); ?></font></td>
</tr>

<tr valign="baseline">
<td nowrap align="right">SACAR CAJA:</td>
<td><input type="text" style="font-size:50px" name="sacar_caja" value="" size="10" required autofocus></td>
</tr>

<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td><input type="submit" id="boton1" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_base_caja" value="<?php echo $matriz_consulta['cod_base_caja']; ?>">
</form>
</center>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
