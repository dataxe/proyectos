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
//include ("../registro_movimientos/registro_cierre_caja.php");

$cod_productos = addslashes($_GET['cod_productos']);
$pagina = $_GET['pagina'];
//-------------------------------------------------------------------------------------------------------------------------------------//
//---------------------------------------------------------------ACTUALIZACION REGISTRO KARDEX-----------------------------------------------------------------------//
if (isset($_GET['cod_productos'])) {
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
$sql_productos = "SELECT nombre_productos FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta_productos = mysql_query($sql_productos, $conectar) or die(mysql_error());
$datos_productos = mysql_fetch_assoc($consulta_productos);

$nombre_productos = $datos_productos['nombre_productos'];
//---------------------------------------------------------------ACTUALIZACION REGISTRO KARDEX-----------------------------------------------------------------------//
$actualizar_nombre_productos = sprintf("UPDATE kardex_venta_compra_invent SET nombre_productos = '$nombre_productos' WHERE cod_productos = '$cod_productos'");
$resultado_actualizar_nombre_productos = mysql_query($actualizar_nombre_productos, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0; <?php echo $pagina?>">
<?php
}
?>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>