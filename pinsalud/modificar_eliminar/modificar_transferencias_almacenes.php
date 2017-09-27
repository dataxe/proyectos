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
if (isset($_SERVER['QUERY_STRING'])) {
  $edicion_de_formulario .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$nombre_almacen = mysql_escape_string($_POST['nombre_almacen']);
$atiende = mysql_escape_string($_POST['atiende']);
$correo = mysql_escape_string($_POST['correo']);
$direccion = mysql_escape_string($_POST['direccion']);
$telefono = mysql_escape_string($_POST['telefono']);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
  $actualizar_sql = sprintf("UPDATE transferencias_almacenes SET nombre_almacen=%s, atiende=%s, correo=%s, direccion=%s, telefono=%s WHERE cod_transferencias_almacenes=%s",
                       envio_valores_tipo_sql($nombre_almacen, "text"),
					   envio_valores_tipo_sql($atiende, "text"),
					   envio_valores_tipo_sql($correo, "text"),
					   envio_valores_tipo_sql($direccion, "text"),
					   envio_valores_tipo_sql($telefono, "text"),
                  	   envio_valores_tipo_sql($_POST['cod_transferencias_almacenes'], "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.2; ../admin/transferencias_almacenes.php">';
}
$cod_transferencias_almacenes = $_GET['cod_transferencias_almacenes'];

$sql_modificar_consulta = "SELECT * FROM transferencias_almacenes where cod_transferencias_almacenes = '$cod_transferencias_almacenes'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
$total_matriz_consulta = mysql_num_rows($modificar_consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>
<br>
<body>
<center>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<tr valign="baseline">
<td nowrap align="right">CODIGO:</td>
<td><?php echo $datos['cod_transferencias_almacenes']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">ALMACEN:</td>
<td><input type="text" name="nombre_almacen" value="<?php echo $datos['nombre_almacen']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">ATIENDE:</td>
<td><input type="text" name="atiende" value="<?php echo $datos['atiende']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">CORREO:</td>
<td><input type="text" name="correo" value="<?php echo $datos['correo']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">DIRECCION:</td>
<td><input type="text" name="direccion" value="<?php echo $datos['direccion']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">TELEFONO:</td>
<td><input type="text" name="telefono" value="<?php echo $datos['telefono']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">&nbsp;</td>
<td><input type="submit" id="boton1" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_transferencias_almacenes" value="<?php echo $datos['cod_transferencias_almacenes']; ?>">
</form>
</center>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
