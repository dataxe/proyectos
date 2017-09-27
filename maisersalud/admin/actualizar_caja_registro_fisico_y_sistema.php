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

$cod_caja_registro_fisico = intval($_GET['cod_caja_registro_fisico']);
$pagina = $_GET['pagina'];

if (isset($_GET['cod_caja_registro_fisico'])) {
//------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------//
$sql_caj_reg_fis = "SELECT usuario, fecha FROM caja_registro_fisico WHERE cod_caja_registro_fisico = '$cod_caja_registro_fisico'";
$consulta_caj_reg_fis = mysql_query($sql_caj_reg_fis, $conectar) or die(mysql_error());
$datos_reg_fisico = mysql_fetch_assoc($consulta_caj_reg_fis);

$fecha = $datos_reg_fisico['fecha'];
$usuario = $datos_reg_fisico['usuario'];
//------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------//
$sql_suma_ventas = "SELECT SUM(unidades_vendidas * precio_venta) AS total_ventas_sistema FROM ventas WHERE fecha = '$fecha' AND vendedor = '$usuario'";
$consulta_suma_ventas = mysql_query($sql_suma_ventas, $conectar) or die(mysql_error());
$datos_suma_ventas = mysql_fetch_assoc($consulta_suma_ventas);

//$total_ventas_fisico = $datos_suma_ventas['total_ventas_fisico'];
$total_ventas_sistema = $datos_suma_ventas['total_ventas_sistema'];
//------------------------------------------------------------------------------------------------//
//------------------------------------------------------------------------------------------------//
$actualizar_sql1 = sprintf("UPDATE caja_registro_fisico SET total_ventas_sistema = '$total_ventas_sistema' WHERE cod_caja_registro_fisico = '$cod_caja_registro_fisico'");
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
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
