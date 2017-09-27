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

$tab = $_POST['tab'];
$tipo = $_POST['tipo'];
$cod_factura = $_POST['cod_factura'];

if ($tipo == 'eliminar') {

if ($tab == 'productos2_check') {
$cod_cargar_factura_temporal = $_POST['check'];

foreach ($cod_cargar_factura_temporal as $valor) {
 $resultado = mysql_query("DELETE FROM productos2 WHERE cod_cargar_factura_temporal = '$valor'");
}
}
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/cargar_factura_temporal_editable_vendedor_check.php?cod_factura=<?php echo $cod_factura?>">
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