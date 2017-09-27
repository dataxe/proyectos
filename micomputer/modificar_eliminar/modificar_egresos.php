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
$cod_egresos = mysql_escape_string($_GET['cod_egresos']);
$fecha_ = mysql_escape_string($_GET['fecha']);
$pagina = $_GET['pagina'].'?fecha='.$fecha_;

$conceptos = mysql_escape_string($_POST['conceptos']);
$costo = mysql_escape_string($_POST['costo']);
$comentarios = mysql_escape_string($_POST['comentarios']);
$nombre_ccosto = mysql_escape_string($_POST['nombre_ccosto']);
$fecha_anyo = mysql_escape_string($_POST['fecha_anyo']);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion") && ($_POST["actualizar"])) {
$separador_fecha =explode('/', $_POST['fecha_anyo']);
$dias = $separador_fecha[0];
$meses = $separador_fecha[1];
$anyo = $separador_fecha[2];
$fecha_mes = $meses.'/'.$anyo;
$fecha_invert = $anyo.'/'.$meses.'/'.$dias;
$fecha_seg = strtotime($fecha_invert);
$fecha = strtotime($fecha_invert);

$actualizar_sql = sprintf("UPDATE egresos SET conceptos=%s, costo=%s, comentarios=%s, nombre_ccosto=%s, fecha=%s, fecha_seg=%s, 
fecha_anyo=%s, fecha_invert=%s, fecha_mes=%s, anyo=%s WHERE cod_egresos=%s",
envio_valores_tipo_sql($conceptos, "text"),
envio_valores_tipo_sql($costo, "text"),
envio_valores_tipo_sql($comentarios, "text"),
envio_valores_tipo_sql($nombre_ccosto, "text"),
envio_valores_tipo_sql($fecha, "text"),
envio_valores_tipo_sql($fecha_seg, "text"),
envio_valores_tipo_sql($fecha_anyo, "text"),
envio_valores_tipo_sql($fecha_invert, "text"),
envio_valores_tipo_sql($fecha_mes, "text"),
envio_valores_tipo_sql($anyo, "text"),
envio_valores_tipo_sql($_POST['cod_egresos'], "text"));
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1;URL=<?php echo $pagina?>">
<?php
}
$sql_modificar_consulta = "SELECT * FROM egresos where cod_egresos = '$cod_egresos'";
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
if (isset($_GET["cod_egresos"])) {
?>
<center>
<a href="../admin/egresos.php"><FONT color='yellow'>REGRESAR</FONT></a><br><br>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<tr valign="baseline">
<td nowrap align="left">CONCEPTO:</td>
<td><input style="font-size:24px" type="text" name="conceptos" value="<?php echo $datos['conceptos']; ?>" size="60"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">COSTO:</td>
<td><input style="font-size:24px" type="text" name="costo" value="<?php echo $datos['costo']; ?>" size="60"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">COMENTARIO:</td>
<td><input style="font-size:24px" type="text" name="comentarios" value="<?php echo $datos['comentarios']; ?>" size="60"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">CENTRO COSTO:</td>
<td><input style="font-size:24px" type="text" name="nombre_ccosto" value="<?php echo $datos['nombre_ccosto']; ?>" size="60"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">FECHA:</td>
<td><input style="font-size:24px" type="text" name="fecha_anyo" value="<?php echo $datos['fecha_anyo']; ?>" size="60"></td>
</tr>
<input type="hidden" name="hora" value="<?php echo date("H:i:s");?>" size="30">
<tr valign="baseline">
<td nowrap align="left">&nbsp;</td>
<td><input type="submit" id="boton1" name="actualizar" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_egresos" value="<?php echo $datos['cod_egresos']; ?>">
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
