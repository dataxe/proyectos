<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
date_default_timezone_set("America/Bogota");
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$fecha_ymd = date('Y/m/d');
$fecha = strtotime($fecha_ymd);
$fecha_mes = date('m/Y');
$fecha_anyo = date('d/m/Y');
$anyo = date('Y');
$fecha_hora = date('H:m:s');
$ip = $_SERVER['REMOTE_ADDR'];
$usuario = $cuenta_actual;

$mostrar_datos_sql1 = "SELECT SUM(vlr_total_venta) AS vlr_total_venta FROM ventas WHERE vendedor = '$usuario' AND fecha = '$fecha'";
$consulta1 = mysql_query($mostrar_datos_sql1, $conectar) or die(mysql_error());
$datos1 = mysql_fetch_assoc($consulta1);

$mostrar_datos_sql2 = "SELECT cod_base_caja FROM base_caja WHERE vendedor = '$usuario'";
$consulta2 = mysql_query($mostrar_datos_sql2, $conectar) or die(mysql_error());
$datos2 = mysql_fetch_assoc($consulta2);

$cod_base_caja = $datos2['cod_base_caja'];
$total_ventas_fisico = $_POST["total_ventas_fisico"];
$total_ventas_sistema = $datos1['vlr_total_venta'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title>ALMACEN</title>
</head>
<body>
<center>
<BR>
</body>
</html>
<?php
if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {

$insertar_registros = "INSERT INTO caja_registro_fisico (cod_base_caja, total_ventas_fisico, total_ventas_sistema, fecha, fecha_mes, fecha_anyo, 
anyo, fecha_hora, ip, usuario)
VALUES ('$cod_base_caja', '$total_ventas_fisico', '$total_ventas_sistema', '$fecha', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', '$ip', '$usuario')";
$resultado_insertar_registros = mysql_query($insertar_registros, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0;URL=caja_registro.php">
<br>
<font color='yellow' size='+3'>SE HA INGRESADO CORRECTAMENTE EL VALOR</font><strong>
<?php
}
?>