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

$cod_bancos = mysql_escape_string($_GET['cod_bancos']);

$sql_modificar_consulta = "SELECT * FROM bancos where cod_bancos like '$cod_bancos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
$total_datos = mysql_num_rows($modificar_consulta);

$nombre_bancos = mysql_escape_string($_POST['nombre_bancos']);
$numero_cuenta_bancos = mysql_escape_string($_POST['numero_cuenta_bancos']);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
  $actualizar_sql = sprintf("UPDATE bancos SET nombre_bancos=%s, numero_cuenta_bancos=%s WHERE cod_bancos=%s",
                       envio_valores_tipo_sql($nombre_bancos, "text"),
					   envio_valores_tipo_sql($numero_cuenta_bancos, "text"),
                  	   envio_valores_tipo_sql($cod_bancos, "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.2; ../admin/bancos.php">';
}
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
<td nowrap align="right">Codigo:</td>
<td><?php echo $datos['cod_bancos']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">Nombre:</td>
<td><input type="text" name="nombre_bancos" value="<?php echo $datos['nombre_bancos']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">Cuenta:</td>
<td><input type="text" name="numero_cuenta_bancos" value="<?php echo $datos['numero_cuenta_bancos']; ?>" size="30"></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td><input type="submit" id="boton1" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
</form>
</center>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
