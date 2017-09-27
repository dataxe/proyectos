<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<?php
$cod_factura = $_GET['cod_factura'];

echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; importar_factura_externa.php">';
echo "<br><br><center><font color='yellow' size='6px'>SE HA INGRESADO CORRECTAMENTE LA FACTURA: <strong>".$cod_factura.".</strong></font></center>";
?>
<center><br><br><br><a href="../admin/cargar_factura_temporal.php"><font size="6px" color="yellow">VER FACTURA</font></a></center>