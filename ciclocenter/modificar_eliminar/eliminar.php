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

$pagina = $_GET['pagina'];
$tab = $_GET['tab'];
$tipo = $_GET['tipo'];

if ($tipo == 'eliminar') {
// --------------------------------------------------------------------------------------------------------------//
if ($tab == 'info_y_trans_temp') {
$cod_factura = $_GET['cod_factura'];

$borrar_sql = sprintf("DELETE FROM info_transferencias_temporal WHERE cod_factura = '$cod_factura'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

$borrar_sql1 = sprintf("DELETE FROM transferencias_temporal WHERE cod_factura = '$cod_factura'");
$Result2 = mysql_query($borrar_sql1, $conectar) or die(mysql_error());
}
// --------------------------------------------------------------------------------------------------------------//
if ($tab == 'transferencias_temporal') {
$cod_transferencias_temporal = $_GET['cod_transferencias_temporal'];

$borrar_sql1 = sprintf("DELETE FROM transferencias_temporal WHERE cod_transferencias_temporal = '$cod_transferencias_temporal'");
$Result2 = mysql_query($borrar_sql1, $conectar) or die(mysql_error());
}
// --------------------------------------------------------------------------------------------------------------//
if ($tab == 'productos2') {
$cod_factura = $_GET['cod_factura'];

$borrar_sql2 = sprintf("DELETE FROM cuentas_facturas2 WHERE cod_factura = '$cod_factura'");
$Result2 = mysql_query($borrar_sql2, $conectar) or die(mysql_error());

$borrar_sql1 = sprintf("DELETE FROM productos2 WHERE cod_factura = '$cod_factura'");
$Result1 = mysql_query($borrar_sql1, $conectar) or die(mysql_error());
}
// --------------------------------------------------------------------------------------------------------------//
if ($tab == 'facturas_cargadas_inv') {
$cod_factura = $_GET['cod_factura'];

$borrar_sql2 = sprintf("DELETE FROM facturas_cargadas_inv WHERE cod_factura = '$cod_factura'");
$Result2 = mysql_query($borrar_sql2, $conectar) or die(mysql_error());
}
// --------------------------------------------------------------------------------------------------------------//
if ($tab == 'cuentas_facturas2') {
$cod_factura = $_GET['cod_factura'];

$borrar_sql2 = sprintf("DELETE FROM cuentas_facturas2 WHERE cod_factura = '$cod_factura'");
$Result2 = mysql_query($borrar_sql2, $conectar) or die(mysql_error());

$borrar_sql1 = sprintf("DELETE FROM productos2 WHERE cod_factura = '$cod_factura'");
$Result1 = mysql_query($borrar_sql1, $conectar) or die(mysql_error());
}
// --------------------------------------------------------------------------------------------------------------//
if ($tab == 'productos_copia_seguridad') {
$cod_productos_copia_seguridad = intval($_GET['cod_productos_copia_seguridad']);

$borrar_sql2 = sprintf("DELETE FROM productos_copia_seguridad WHERE cod_productos_copia_seguridad = '$cod_productos_copia_seguridad'");
$Result2 = mysql_query($borrar_sql2, $conectar) or die(mysql_error());
}
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
</body>
</html>