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
$contrasena = stripslashes($_POST['contrasena']);
$contrasena = strip_tags($contrasena);
$clave_encriptada = sha1($contrasena);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion") && ($_POST["actualizar"])) {
$actualizar_sql = sprintf("UPDATE administrador SET contrasena=%s WHERE cod_administrador=%s",
envio_valores_tipo_sql($clave_encriptada, "text"),
envio_valores_tipo_sql($_GET['cod_administrador'], "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/ver_administrador.php">';
}
$cod_administrador = $_GET['cod_administrador'];

$sql_modificar_consulta = "SELECT * FROM administrador where cod_administrador = '$cod_administrador'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($modificar_consulta);
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
if (isset($_GET["cod_administrador"])) {
?>
<center>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<tr valign="baseline">
<tr valign="baseline">
<td nowrap align="left">CUENTA:</td>
<td><font size= "+1"><?php echo $matriz_consulta['cuenta']; ?></font></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">CONTRASE&Ntilde;A:</td>
<td><input type=password placeholder="Intruduzca la nueva contrase&ntilde;a" style="font-size:20px" name="contrasena" value="" size="50"  required autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">&nbsp;</td>
<td><input type="submit" id="boton1" name="actualizar" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_administrador" value="<?php echo $matriz_consulta['cod_administrador']; ?>">
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
