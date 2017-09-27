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
$cod_administrador = mysql_escape_string($_GET['cod_administrador']);

$cuenta = mysql_escape_string($_POST['cuenta']);
$nombres = mysql_escape_string(strtoupper($_POST['nombres']));
$apellidos = mysql_escape_string(strtoupper($_POST['apellidos']));
$correo = mysql_escape_string($_POST['correo']);
$cod_seguridad = $_POST['cod_seguridad'];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion") && ($_POST["actualizar"])) {
$actualizar_sql = sprintf("UPDATE administrador SET nombres=%s, apellidos=%s, cuenta=%s, correo=%s, cod_seguridad=%s WHERE cod_administrador=%s",
envio_valores_tipo_sql($nombres, "text"),
envio_valores_tipo_sql($apellidos, "text"),
envio_valores_tipo_sql($cuenta, "text"),
envio_valores_tipo_sql($correo, "text"),
envio_valores_tipo_sql($cod_seguridad, "text"),
envio_valores_tipo_sql($cod_administrador, "text"));
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/ver_administrador.php">';
}
$sql_modificar_consulta = "SELECT * FROM administrador WHERE cod_administrador = '$cod_administrador'";
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
if (isset($_GET["verificacion"])) {
$cod_seguridad = $datos['cod_seguridad'];
?>
<center>
<a href="../admin/ver_administrador.php"><font size='+1' color='yellow'>REGRESAR</font></a>
<br><br>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<tr valign="baseline">
<td nowrap align="left">CODIGO:</td>
<td><?php echo $datos['cod_administrador']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">NOMBRES:</td>
<td><input type="text" name="nombres" value="<?php echo $datos['nombres']; ?>" size="50" required autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">APELLIDOS:</td>
<td><input type="text" name="apellidos" value="<?php echo $datos['apellidos']; ?>" size="50" required autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">CUENTA:</td>
<td><input type="text" name="cuenta" value="<?php echo $datos['cuenta']; ?>" size="50" required autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">CORREO:</td>
<td><input type="text" name="correo" value="<?php echo $datos['correo']; ?>" size="50"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">NIVEL SEGURIDAD:</td>
<td align="left">
<select name="cod_seguridad">
<?php
if (isset($cod_seguridad)) {
echo "<option style='font-size:20px' value='-1' >...</option>";
} else {
echo  "<option style='font-size:20px' value='-1' selected >...</option>";
}
$consulta1 = mysql_query("SELECT cod_seguridad, nombre_seguridad FROM seguridad ORDER BY cod_seguridad ASC");
while ($datos1 = mysql_fetch_array($consulta1)) {
if(isset($cod_seguridad) and $cod_seguridad == $datos1['cod_seguridad']) {
$seleccionado = "selected";
} else {
$seleccionado = "";
}
$nombre = $datos1['nombre_seguridad'];
echo "<option style='font-size:20px' value='".$datos1['cod_seguridad']."' $seleccionado >".$nombre."</option>";
}
?>
</td>

<!--<td><input type="text" name="cod_seguridad" value="<?php echo $datos['cod_seguridad']; ?>" size="50" required autofocus></td>-->
</tr>
<tr valign="baseline">
<td nowrap align="left">&nbsp;</td>
<td><input type="submit" name="actualizar" value="Actualizar registro">&nbsp;&nbsp;<a href="../modificar_eliminar/cambiar_contrasena.php?cod_administrador=<?php echo $datos['cod_administrador']; ?>"><img src=../imagenes/contrasena.png alt="cambiar contrase&ntilde;a"></a></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_administrador" value="<?php echo $datos['cod_administrador']; ?>">
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
