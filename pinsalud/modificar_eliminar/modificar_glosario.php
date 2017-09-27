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
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
  $actualizar_sql = sprintf("UPDATE glosario SET descripcion=%s WHERE cod_glosario=%s",
                       envio_valores_tipo_sql($_POST['descripcion'], "text"),
                  	   envio_valores_tipo_sql($_POST['cod_glosario'], "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/glosario.php">';
}
$cod_glosario = mysql_escape_string($_GET['cod_glosario']);

$sql_modificar_consulta = "SELECT * FROM glosario where cod_glosario = '$cod_glosario'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>Documento sin t&iacute;tulo</title>
</head>
<br>
<body>
<center>
<td><strong><a href="../admin/glosario.php"><font color='white'>REGRESAR</font></a></strong></td><br><br>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<tr valign="baseline">
<td nowrap align="right">Boton:</td>
<td ><center><img src="<?php echo $datos['url_img']; ?>"></center></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">Descripcion:</td>
<td><input type="text" name="descripcion" value="<?php echo $datos['descripcion']; ?>" size="100"></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td><input type="submit" id="boton1" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_glosario" value="<?php echo $datos['cod_glosario']; ?>">
</form>
</center>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
