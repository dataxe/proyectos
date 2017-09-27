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
include ("../registro_movimientos/registro_movimientos.php");

if (isset($_GET['cod_factura'])) {

$cod_factura = intval($_GET['cod_factura']);

$sql_exportacion = "SELECT cod_exportacion FROM exportacion WHERE cod_factura = '$cod_factura' ORDER BY cod_exportacion DESC";
$consulta_exportacion = mysql_query($sql_exportacion, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta_exportacion);
?>
<center>
<center><font color='yellow' size= '+2'>&iquest; SEGURO QUE HACER ESTA AUDITORIA ?</font></center><br>
<table>
<form method="post" name="formulario" action="../admin/recibido_importacion_comparacion_tablas.php">
<br>
<?php while ($data_exportacion = mysql_fetch_assoc($consulta_exportacion)) {?>
<input type="hidden" name="cod_exportacion[]" value="<?php echo $data_exportacion['cod_exportacion']; ?>" size="4">
<?php } ?>
<input type="hidden" name="total_datos" value="<?php echo $total_datos; ?>" size="4">
<br>
<input type="submit" name="si" value="SI" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" name="no" value="NO" />
</form>
</table>
</center>
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
</head>
<body>
<?php //if (!empty($_GET[success])) { echo "<b>Your file has been imported.</b><br><br>"; } //generic success notice ?>
</body>
</html> 