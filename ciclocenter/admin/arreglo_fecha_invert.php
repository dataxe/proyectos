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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
if (isset($_POST['fecha_invert'])) {

$fecha = $_POST['fecha']; 
$fecha_invert = $_POST['fecha_invert'];

$actualiza_fecha_mes = sprintf("UPDATE ventas SET fecha = '$fecha_invert' WHERE fecha = '$fecha'");
$resultado_fecha_mes = mysql_query($actualiza_fecha_mes, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT=1; ../admin/arreglar_fechas_invert.php">';
} else {
echo '<META HTTP-EQUIV="REFRESH" CONTENT=1; ../admin/arreglar_fechas_invert.php">';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>