<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php');
require_once('../session/funciones_admin.php');
date_default_timezone_set("America/Bogota");
mysql_select_db($base_datos, $conectar);
verificar_usuario();

$cuenta_actual = addslashes($_SESSION['usuario']);

include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$obtener_cod_sesion = "SELECT cod_sesiones FROM sesiones WHERE usuario = '$cuenta_actual' ORDER BY cod_sesiones DESC LIMIT 1";
$resultado_cod_sesion = mysql_query($obtener_cod_sesion, $conectar) or die(mysql_error());
$matriz_cod_sesion = mysql_fetch_assoc($resultado_cod_sesion);

$cod_sesiones = $matriz_cod_sesion['cod_sesiones'];
$ips = $_SERVER['REMOTE_ADDR'];
$fecha_salida = date("d/m/Y - H:i:s");
$fecha_fin_time = time();

$agregar_regis = sprintf("UPDATE sesiones SET fecha_salida = '$fecha_salida', fecha_fin_time = '$fecha_fin_time' WHERE cod_sesiones = '$cod_sesiones'");
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());

if (verificar_usuario()){
session_unset();
session_destroy();
session_start();
session_regenerate_id(true);
header ("Location:../index.php");
} else {
header ("Location:../index.php");
}
?>