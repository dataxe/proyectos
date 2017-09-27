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
$cedula = mysql_escape_string($_POST['cedula']);
$nombres = mysql_escape_string($_POST['nombres']);
$apellidos = mysql_escape_string($_POST['apellidos']);
$telefono = mysql_escape_string($_POST['telefono']);
$correo = mysql_escape_string($_POST['correo']);
$direccion = mysql_escape_string($_POST['direccion']);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
  $actualizar_sql = sprintf("UPDATE clientes SET cedula=%s, nombres=%s, apellidos=%s, telefono=%s, correo=%s, direccion=%s WHERE cod_clientes=%s",
                       envio_valores_tipo_sql($cedula, "text"),
					   envio_valores_tipo_sql($nombres, "text"),
					   envio_valores_tipo_sql($apellidos, "text"),
					   envio_valores_tipo_sql($telefono, "text"),
					   envio_valores_tipo_sql($correo, "text"),
					   envio_valores_tipo_sql($direccion, "text"),
                  	   envio_valores_tipo_sql($_POST['cod_clientes'], "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.2; ../admin/clientes.php">';
}
$cod_clientes = mysql_escape_string($_GET['cod_clientes']);

$sql_modificar_consulta = "SELECT * FROM clientes where cod_clientes = '$cod_clientes'";
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
<td><?php echo $datos['cod_clientes']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">CEDULA:</td>
<td><input type="text" name="cedula" value="<?php echo $datos['cedula']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">NOMBRES:</td>
<td><input type="text" name="nombres" value="<?php echo $datos['nombres']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">APELLIDOS:</td>
<td><input type="text" name="apellidos" value="<?php echo $datos['apellidos']; ?>" size="30"></td>
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
<input type="hidden" name="cod_clientes" value="<?php echo $datos['cod_clientes']; ?>">
</form>
</center>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
