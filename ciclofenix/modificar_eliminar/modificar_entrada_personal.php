<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php include_once('../conexiones/conexione.php'); 
include_once('../evitar_mensaje_error/error.php');  
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");


$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
$cod_entrada_personal = intval($_GET['cod_entrada_personal']);
$fecha_ = addslashes($_GET['fecha']);
$pagina = $_GET['pagina'].'?fecha='.$fecha_;

$conceptos = addslashes($_POST['conceptos']);
$costo = addslashes($_POST['costo']);
$comentarios = addslashes($_POST['comentarios']);
$nombre_ccosto = addslashes($_POST['nombre_ccosto']);
$fecha_dmy = addslashes($_POST['fecha_dmy']);

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion") && ($_POST["actualizar"])) {
$separador_fecha =explode('/', $_POST['fecha_dmy']);
$dias = $separador_fecha[0];
$meses = $separador_fecha[1];
$anyo = $separador_fecha[2];
$fecha_mes = $meses.'/'.$anyo;
$fecha_invert = $anyo.'/'.$meses.'/'.$dias;
$fecha_seg = strtotime($fecha_invert);
$fecha = strtotime($fecha_invert);

$cod_entrada_personal = intval($_POST['cod_entrada_personal']);

$sql_data = sprintf("UPDATE entrada_personal SET conceptos = '$conceptos', costo = '$costo', comentarios = '$comentarios', nombre_ccosto = '$nombre_ccosto', 
fecha_dmy = '$fecha_dmy', fecha_mes = '$fecha_mes', anyo = '$anyo', fecha_seg = '$fecha_seg' WHERE cod_entrada_personal = '$cod_entrada_personal'");
$exec_data = mysql_query($sql_data, $conectar) or die(mysql_error());

?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1;URL=<?php echo $pagina?>">
<?php
}
$sql_modificar_consulta = "SELECT * FROM entrada_personal WHERE cod_entrada_personal = '$cod_entrada_personal'";
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
if (isset($_GET["cod_entrada_personal"])) {
?>
<center>
<a href="<?php echo $pagina ?>"><FONT color='yellow'>REGRESAR</FONT></a><br><br>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<tr valign="baseline">
<td nowrap align="left">CONCEPTO:</td>
<td><input style="font-size:24px" type="text" name="conceptos" value="<?php echo $datos['conceptos']; ?>" size="60"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">COSTO:</td>
<td><input style="font-size:24px" type="text" name="costo" value="<?php echo $datos['costo']; ?>" size="60"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">COMENTARIO:</td>
<td><input style="font-size:24px" type="text" name="comentarios" value="<?php echo $datos['comentarios']; ?>" size="60"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">CENTRO COSTO:</td>
<td><input style="font-size:24px" type="text" name="nombre_ccosto" value="<?php echo $datos['nombre_ccosto']; ?>" size="60"></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">FECHA:</td>
<td><input style="font-size:24px" type="text" name="fecha_dmy" value="<?php echo $datos['fecha_dmy']; ?>" size="60"></td>
</tr>
<input type="hidden" name="hora" value="<?php echo date("H:i:s");?>" size="30">
<tr valign="baseline">
<td nowrap align="left">&nbsp;</td>
<td><input type="submit" id="boton1" name="actualizar" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_entrada_personal" value="<?php echo $datos['cod_entrada_personal']; ?>">
</form>
</center>
<?php
}
?>
</body>
</html>

