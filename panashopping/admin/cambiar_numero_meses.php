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

$pagina_get = ($_GET['pagina']);

$edicion_de_formulario = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $edicion_de_formulario .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {

$meses = intval($_POST['meses']);
$cod_meses_vencimiento = intval($_POST['cod_meses_vencimiento']);
$pagina = ($_POST['pagina']);

$actualizar_sql = sprintf("UPDATE meses_vencimiento SET meses = '$meses' WHERE cod_meses_vencimiento = '$cod_meses_vencimiento'");
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php
}
$sql_modificar_consulta = "SELECT * FROM meses_vencimiento WHERE cod_meses_vencimiento = 1";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$matriz_modificar_consulta = mysql_fetch_assoc($modificar_consulta);
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
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<tr valign="baseline">
<td nowrap align="right">NUMERO DE MESES:</td>
<td><input type="text" name="meses" value="<?php echo $matriz_modificar_consulta['meses']; ?>" size="30" required autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td><input type="submit" id="boton1" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_meses_vencimiento" value="<?php echo $matriz_modificar_consulta['cod_meses_vencimiento']; ?>">
<input type="hidden" name="pagina" value="<?php echo $pagina_get; ?>">
</form>
</center>
<p>&nbsp;</p>
</body>
</html>
