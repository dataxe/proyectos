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

if (isset($_GET["cod_factura"])) {

$cod_factura = $_GET['cod_factura'];
$pagina = $_GET['pagina'];

$suma_total_deuda = "SELECT Sum(precio_compra_con_descuento) As monto_deuda FROM facturas_cargadas_inv WHERE cod_factura = '$cod_factura'";
$consulta_total_deuda = mysql_query($suma_total_deuda, $conectar) or die(mysql_error());
$datos_deuda = mysql_fetch_assoc($consulta_total_deuda);

$monto_deuda = $datos_deuda['monto_deuda'];

$agregar_regis = sprintf("UPDATE cuentas_pagar SET monto_deuda = '$monto_deuda' WHERE cod_factura = '$cod_factura'");
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0; <?php echo $pagina ?>">
<?php
}
?>