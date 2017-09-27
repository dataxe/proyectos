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
$nombre_nomenclatura = mysql_escape_string($_POST['nombre_nomenclatura']);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
  $actualizar_sql = sprintf("UPDATE nomenclatura SET nombre_nomenclatura=%s WHERE cod_nomenclatura=%s",
                       envio_valores_tipo_sql($nombre_nomenclatura, "text"),
                  	   envio_valores_tipo_sql($_POST['cod_nomenclatura'], "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/ordenamiento.php">';
}
$valor = $_GET['cod_nomenclatura'];

$sql_modificar_consulta = "SELECT * FROM nomenclatura where cod_nomenclatura = $valor";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$matriz_modificar_consulta = mysql_fetch_assoc($modificar_consulta);
$total_matriz_consulta = mysql_num_rows($modificar_consulta);
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
<td nowrap align="right">Codigo:</td>
<td><?php echo $matriz_modificar_consulta['cod_nomenclatura']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">Nombre:</td>
<td><input type="text" name="nombre_nomenclatura" value="<?php echo $matriz_modificar_consulta['nombre_nomenclatura']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td><input type="submit" id="boton1" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_nomenclatura" value="<?php echo $matriz_modificar_consulta['cod_nomenclatura']; ?>">
</form>
</center>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
