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
$actualizar_sql = sprintf("UPDATE administrador SET estilo_css=%s WHERE cod_administrador = 1",
envio_valores_tipo_sql($_POST['opcion_estilo'], "text"),
envio_valores_tipo_sql($_POST['cod_administrador'], "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

$ir_enlace = "../admin/estilos_css.php";
if (isset($_SERVER['QUERY_STRING'])) {
$ir_enlace .= (strpos($ir_enlace, '?')) ? "&" : "?";
 $ir_enlace .= $_SERVER['QUERY_STRING'];
}
header(sprintf("Location: %s", $ir_enlace));
}
$valor = 1;

$sql_modificar_consulta = "SELECT * FROM administrador where cod_administrador=$valor";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$matriz_modificar_consulta = mysql_fetch_assoc($modificar_consulta);
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
<table align="center"id="table">
<select name="opcion_estilo">
<option value="redandblack.css" selected>redandblack</option>
<option value="two-times-table.css">two-times-table</option>
<option value="tabular-table.css">tabular-table</option>
<option value="prettyinpink.css">prettyinpink</option>
<option value="poetryforbrowser.css">poetryforbrowser</option>
<option value="foggycountry.css">foggycountry</option>
<option value="iceblue.css">iceblue</option>
<option value="mediagroove.css">mediagroove</option>
<option value="round-corner.css">round-corner.css</option>
<option value="0m4r.table.css">0m4r.table.css</option>
<option value="alpha.css">alpha.css</option>
<option value="cuscosky.css">cuscosky.css</option>
<option value="cwtable.css">cwtable.css</option>
<option value="dramatic.css">dramatic.css</option>
<option value="inset.css">inset.css</option>
<option value="muffin.css">muffin.css</option>
<option value="orangebrownie.css">orangebrownie.css</option>
<option value="OrangeYouGlad.css">OrangeYouGlad.css</option>
<option value="spearmint-tints.css">spearmint-tints.css</option>
<option value="structure.css">structure.css</option>
<option value="tabla.css">tabla.css</option>
<option value="table.css">table.css</option>
<option value="tabular-table.css">tabular-table.css</option>
<option value="web20.css">web20.css</option>
</select> 
<input type="submit" value="Aceptar">
</table>
<center>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_administrador" value="<?php echo $matriz_modificar_consulta['cod_administrador']; ?>">
</form>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
