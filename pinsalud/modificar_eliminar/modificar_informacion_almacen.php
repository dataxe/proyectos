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
$titulo = mysql_escape_string($_POST['titulo']);
$nombre = mysql_escape_string($_POST['nombre']);
$res = mysql_escape_string($_POST['res']);
$res1 = mysql_escape_string($_POST['res1']);
$res2 = mysql_escape_string($_POST['res2']);
$cabecera = mysql_escape_string($_POST['cabecera']);
$telefono = mysql_escape_string($_POST['telefono']);
$direccion = mysql_escape_string($_POST['direccion']);
$localidad = mysql_escape_string($_POST['localidad']);
$nit = mysql_escape_string($_POST['nit']);
$info_legal = mysql_escape_string($_POST['info_legal']);
$icono = mysql_escape_string($_POST['icono']);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
$actualizar_sql = sprintf("UPDATE informacion_almacen SET titulo=%s, nombre=%s, res=%s, res1=%s, res2=%s, cabecera=%s, telefono=%s, direccion=%s, localidad=%s, nit=%s, info_legal=%s, icono=%s WHERE cod_informacion_almacen=%s",
envio_valores_tipo_sql($titulo, "text"),
envio_valores_tipo_sql($nombre, "text"),
envio_valores_tipo_sql($res, "text"),
envio_valores_tipo_sql($res1, "text"),
envio_valores_tipo_sql($res2, "text"),
envio_valores_tipo_sql($cabecera, "text"),
envio_valores_tipo_sql($telefono, "text"),
envio_valores_tipo_sql($direccion, "text"),
envio_valores_tipo_sql($localidad, "text"),
envio_valores_tipo_sql($nit, "text"),
envio_valores_tipo_sql($info_legal, "text"),
envio_valores_tipo_sql($icono, "text"),
envio_valores_tipo_sql($_POST['cod_informacion_almacen'], "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/informacion_almacen.php">';
}
$cod_informacion_almacen = mysql_escape_string($_GET['cod_informacion_almacen']);

$sql_modificar_consulta = "SELECT * FROM informacion_almacen where cod_informacion_almacen = '$cod_informacion_almacen'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>Almacen</title>
</head>
<br>
<body>
<center>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<!--
<tr valign="baseline">
<td nowrap align="left">TITULO:</td>
<td><input type="text" name="titulo" value="<?php echo $datos['titulo']; ?>" size="100"></td>
</tr>
-->
<input type="hidden" name="titulo" value="<?php echo $datos['titulo']; ?>" size="100">
<tr valign="baseline">
<td nowrap align="left">NOMRE:</td>
<td><input type="text" name="nombre" value="<?php echo $datos['nombre']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">CABECERA FACTURA:</td>
<td><input type="text" name="cabecera" value="<?php echo $datos['cabecera']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">RES:</td>
<td><input type="text" name="res" value="<?php echo $datos['res']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">DE:</td>
<td><input type="text" name="res1" value="<?php echo $datos['res1']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">A:</td>
<td><input type="text" name="res2" value="<?php echo $datos['res2']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">TELEFONO:</td>
<td><input type="text" name="telefono" value="<?php echo $datos['telefono']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">DIRECCION:</td>
<td><input type="text" name="direccion" value="<?php echo $datos['direccion']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">LOCALIDAD:</td>
<td><input type="text" name="localidad" value="<?php echo $datos['localidad']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">NIT:</td>
<td><input type="text" name="nit" value="<?php echo $datos['nit']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">INFORMACION LEGAL:</td>
<td><textarea name="info_legal" cols="72" rows="6"><?php echo $datos['info_legal']; ?></textarea></td>
</tr>
<!--<td nowrap align="left">Logotipo:</td>
<td>
<?php
/*$sql_consulta = "SELECT * FROM icono_logo";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<input type="radio" name="logotipo" value="<?php echo $contenedor['nombre_icono_logo'] ?>"checked>
<img src=<?php echo $contenedor['url_icono_logo']?> width="30" height="30">
<?php 
}*/
?></td>-->
<tr>
<td nowrap align="left">ICONO:</td>
<td>
<?php
$sql_consulta = "SELECT * FROM icono_logo";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<input type="radio" name="icono" value="<?php echo $contenedor['nombre_icono_logo'] ?>"checked>
<img src=<?php echo $contenedor['url_icono_logo']?> width="30" height="30">
<?php 
}
?></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td><input type="submit" id="boton1" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_informacion_almacen" value="<?php echo $datos['cod_informacion_almacen']; ?>">
</form>
</center>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
