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
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion") && ($_POST["actualizar"])) {
$separador_fecha =explode('/', $_POST['fecha_anyo']);
$dias = $separador_fecha[0];
$meses = $separador_fecha[1];
$anyo = $separador_fecha[2];
$fecha_mes = $meses.'/'.$anyo;
$fecha_invert = $anyo.'/'.$meses.'/'.$dias;
$conceptos = mysql_escape_string($_POST['conceptos']);

$actualizar_sql = sprintf("UPDATE gastos_tabla SET conceptos=%s WHERE cod_gastos_tabla=%s",
envio_valores_tipo_sql($conceptos, "text"),
envio_valores_tipo_sql($_POST['cod_gastos_tabla'], "text"));
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/ver_lista_conceptos_egresos.php">';
}
$cod_gastos_tabla = mysql_escape_string($_GET['cod_gastos_tabla']);

$sql_modificar_consulta = "SELECT * FROM gastos_tabla where cod_gastos_tabla = '$cod_gastos_tabla'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
$total_datos = mysql_num_rows($modificar_consulta);
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
if (isset($_GET["cod_gastos_tabla"])) {
?>
<center>
<a href="../admin/ver_lista_conceptos_egresos.php"><FONT color='yellow'>REGRESAR</FONT></a><br><br>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<tr valign="baseline">
<td nowrap align="left">CONCEPTO:</td>
<td><input style="font-size:24px" type="text" name="conceptos" value="<?php echo $datos['conceptos']; ?>" size="60"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">&nbsp;</td>
<td><input type="submit" id="boton1" name="actualizar" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_gastos_tabla" value="<?php echo $datos['cod_gastos_tabla']; ?>">
</form>
</center>
<?php
}
?>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
