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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");

if (isset($_POST["cod_factura"])) {

$cod_factura = $_POST['cod_factura'];
$cod_proveedores = $_POST['cod_proveedores'];
$monto_deuda = $_POST['monto_deuda'];
$abonado = '0';
$fecha_pago = $_POST['fecha'];
$fecha = date("d/m/Y");
$fecha_hora = date("H:i:s");

$sql_proveedores = "SELECT nombre_proveedores FROM proveedores WHERE cod_proveedores = '$cod_proveedores'";
$mconsulta_proveedores = mysql_query($sql_proveedores, $conectar) or die(mysql_error());
$datos_proveedores = mysql_fetch_assoc($mconsulta_proveedores);

$nombre_proveedores = $datos_proveedores['nombre_proveedores'];

$separador_fecha =explode('/', $fecha_pago);
$dias = $separador_fecha[0];
$meses = $separador_fecha[1];
$anyos = $separador_fecha[2];
$fecha_invert = $anyos.'/'.$meses.'/'.$dias;
$fecha_seg = strtotime($fecha_invert);

$agregar_reg_cuentas_pagar = "INSERT INTO cuentas_pagar (cod_factura, cod_proveedores, monto_deuda, abonado, fecha_pago, fecha, fecha_invert, fecha_seg)
VALUES ('$cod_factura', '$cod_proveedores', '$monto_deuda', '$abonado', '$fecha_pago', '$fecha', '$fecha_invert', '$fecha_seg')";
$resultado_cuentas_pagar = mysql_query($agregar_reg_cuentas_pagar, $conectar) or die(mysql_error());

$nombre_notificacion_alerta = 'HAY CUENTAS POR PAGAR A PUNTO DE VENCER';
$tipo_notificacion_alerta = 'white';
$agregar_notificacion_alerta = "INSERT INTO notificacion_alerta (nombre_notificacion_alerta, nombre_productos, cod_factura, nombre_proveedores, tipo_notificacion_alerta, fecha_dia, fecha_invert, fecha_hora, cuenta)
VALUES ('$nombre_notificacion_alerta', '$fecha_pago', '$cod_factura', '$nombre_proveedores', '$tipo_notificacion_alerta', '$fecha_pago', '$fecha_seg', '$fecha_hora', '$cuenta_actual')";
$resultado_notificacion_alerta = mysql_query($agregar_notificacion_alerta, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; cuentas_pagar.php">';
}
?>