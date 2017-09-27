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

$cod_factura = intval($_GET['cod_factura']);
$pagina = $_GET['pagina'];

if (isset($_GET['cod_factura'])) {
$borrar_sql1 = sprintf("DELETE FROM info_exportacion WHERE cod_factura = '$cod_factura'");
$Result1 = mysql_query($borrar_sql1, $conectar) or die(mysql_error());

$borrar_sql2 = sprintf("DELETE FROM exportacion WHERE cod_factura = '$cod_factura'");
$Result2 = mysql_query($borrar_sql2, $conectar) or die(mysql_error());

$borrar_sql3 = sprintf("DELETE FROM exportacion_vendedor WHERE cod_factura = '$cod_factura'");
$Result3 = mysql_query($borrar_sql3, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/<?php echo $pagina ?>">
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
