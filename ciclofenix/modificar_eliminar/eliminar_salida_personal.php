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


if ((isset($_GET['cod_salida_personal'])) && ($_GET['cod_salida_personal'] != "")) {

$cod_salida_personal = intval($_GET['cod_salida_personal']);
$fecha = addslashes($_GET['fecha']);
$pagina = $_GET['pagina'].'?fecha='.$fecha;

$sql_data = sprintf("DELETE FROM salida_personal WHERE cod_salida_personal = '$cod_salida_personal'");
$exec_data = mysql_query($sql_data, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1;URL=<?php echo $pagina?>">
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
