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

$cod_base_caja = $_GET['cod_base_caja'];

$sql_modificar_consulta = "SELECT * FROM base_caja where cod_base_caja = '$cod_base_caja'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
  $actualizar_sql = sprintf("UPDATE base_caja SET hora=%s WHERE cod_base_caja=%s",
                       envio_valores_tipo_sql($_POST['hora'], "text"),
                  	   envio_valores_tipo_sql($_POST['cod_base_caja'], "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.2; ../admin/caja_base.php">';
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
<td><a href="../admin/caja_base.php"><font color='yellow' size= "+3">REGRESAR A CAJA</font></a></td><br><br>
<td><strong><font color='yellow'>HORA:MINUTOS:SEGUNDOS (24 horas)</font></strong></td><br>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<tr valign="baseline">
<td nowrap align="left">HORA:</td>
<td><input type="text" style="font-size:50px" name="hora" value="<?php echo $datos['hora']; ?>" size="10"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">NOMBRE CAJA:</td>
<td><font size="10"><?php echo $datos['nombre_base_caja']; ?></font></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">VENDEDOR:</td>
<td><font size="10"><?php echo $datos['vendedor']; ?></font></td>
</tr>
<input type="hidden" name="cod_base_caja" value="<?php echo $datos['cod_base_caja'];?>">
<tr valign="baseline">
<td nowrap align="left">&nbsp;</td>
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
