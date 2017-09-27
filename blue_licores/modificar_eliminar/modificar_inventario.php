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

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
$total = $_POST['numero'] * $_POST['nombre_valor'];

$actualizar_sql = sprintf("UPDATE inventario SET numero=%s, nombre_valor=%s, total=%s WHERE cod_inventario=%s",
envio_valores_tipo_sql($_POST['numero'], "text"),
envio_valores_tipo_sql($_POST['nombre_valor'], "text"),
envio_valores_tipo_sql($total, "text"),
envio_valores_tipo_sql($_POST['cod_inventario'], "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/crear_plata.php">';
}
$cod_inventario = mysql_escape_string($_GET['cod_inventario']);

$sql_modificar_consulta = "SELECT * FROM inventario where cod_inventario = '$cod_inventario'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$matriz_modificar_consulta = mysql_fetch_assoc($modificar_consulta);
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
<td nowrap align="right">PLATA:</td>
<td><input type="text" style="font-size:34px" name="nombre_valor" value="<?php echo $matriz_modificar_consulta['nombre_valor'];?>" size="6" required autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">UND:</td>
<td><input type="text" style="font-size:34px" name="numero" value="<?php echo $matriz_modificar_consulta['numero']; ?>" size="6"></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td><input type="submit" id="boton1" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_inventario" value="<?php echo $matriz_modificar_consulta['cod_inventario']; ?>">
</form>
</center>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
