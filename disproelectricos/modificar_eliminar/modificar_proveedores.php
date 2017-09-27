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
include ("../formato_entrada_sql/funcion_env_val_sql.php");

$edicion_de_formulario = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $edicion_de_formulario .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$nombre_proveedores = mysql_escape_string($_POST['nombre_proveedores']);
$identificacion_proveedores = mysql_escape_string($_POST['identificacion_proveedores']);
$correo_proveedores = mysql_escape_string($_POST['correo_proveedores']);
$telefono_proveedores = mysql_escape_string($_POST['telefono_proveedores']);
$ciudad_proveedores = mysql_escape_string($_POST['ciudad_proveedores']);
$direccion_proveedores = mysql_escape_string($_POST['direccion_proveedores']);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
$actualizar_sql = sprintf("UPDATE proveedores SET nombre_proveedores=%s, identificacion_proveedores=%s, correo_proveedores=%s, telefono_proveedores=%s, ciudad_proveedores=%s, direccion_proveedores=%s WHERE cod_proveedores=%s",
envio_valores_tipo_sql($nombre_proveedores, "text"),
envio_valores_tipo_sql($identificacion_proveedores, "text"),
envio_valores_tipo_sql($correo_proveedores, "text"),
envio_valores_tipo_sql($telefono_proveedores, "text"),
envio_valores_tipo_sql($ciudad_proveedores, "text"),
envio_valores_tipo_sql($direccion_proveedores, "text"),
envio_valores_tipo_sql($_POST['cod_proveedores'], "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/proveedores.php">';
}
$cod_proveedores = mysql_escape_string($_GET['cod_proveedores']);

$sql_modificar_consulta = "SELECT * FROM proveedores where cod_proveedores = '$cod_proveedores'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
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
<center>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center" >
<tr valign="baseline">
<td nowrap align="left">CODIGO:</td>
<td><?php echo $datos['cod_proveedores']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">NOMBRE:</td>
<td><input type="text" name="nombre_proveedores" value="<?php echo $datos['nombre_proveedores']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">NIT:</td>
<td><input type="text" name="identificacion_proveedores" value="<?php echo $datos['identificacion_proveedores']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">CORREO:</td>
<td><input type="text" name="correo_proveedores" value="<?php echo $datos['correo_proveedores']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">TELEFONO:</td>
<td><input type="text" name="telefono_proveedores" value="<?php echo $datos['telefono_proveedores']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">CIUDAD:</td>
<td><input type="text" name="ciudad_proveedores" value="<?php echo $datos['ciudad_proveedores']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">DIRECCION:</td>
<td><input type="text" name="direccion_proveedores" value="<?php echo $datos['direccion_proveedores']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">&nbsp;</td>
<td><input type="submit" id="boton1" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_proveedores" value="<?php echo $datos['cod_proveedores']; ?>">
</form>
</center>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
