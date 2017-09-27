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
//include ("../registro_movimientos/registro_cierre_caja.php");

$mostrar_datos_sql = "SELECT * FROM cuentas_pagar";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
<br>
<table width="95%">
<tr>
<td align="center"><strong>cod_cuentas_pagar</strong></td>
<td align="center"><strong>cod_factura</strong></td>
<td align="center"><strong>cod_proveedores</strong></td>
<td align="center"><strong>monto_deuda</strong></td>
<td align="center"><strong>subtotal</strong></td>
<td align="center"><strong>descuento</strong></td>
<td align="center"><strong>abonado</strong></td>
<td align="center"><strong>mensaje</strong></td>
<td align="center"><strong>vendedor</strong></td>
<td align="center"><strong>cuenta</strong></td>
<td align="center"><strong>fecha_pago</strong></td>
<td align="center"><strong>fecha</strong></td>
<td align="center"><strong>fecha_invert</strong></td>
<td align="center"><strong>fecha_seg</strong></td>
</tr>
<?php do {
$cod_cuentas_pagar = $datos['cod_cuentas_pagar'];
$cod_factura = $datos['cod_factura'];
$cod_proveedores = $datos['cod_proveedores'];
$monto_deuda = $datos['monto_deuda'];
$subtotal = $datos['subtotal'];
$descuento = $datos['descuento'];
$abonado = $datos['abonado'];
$mensaje = $datos['mensaje'];
$vendedor = $datos['vendedor'];
$cuenta = $datos['cuenta'];
$fecha_pago = $datos['fecha_pago'];
$fecha_seg = $datos['fecha_seg'];
$frag = explode('/', $fecha_pago);
$dia = $frag[0];
$mes = $frag[1];
$anyo = $frag[2];
$unio_Ymd = $anyo.'/'. $mes.'/'. $dia;
$fecha = strtotime($unio_Ymd);
$fecha_invert = date("d/m/Y", $fecha_seg);

?>
<tr>
<td><font size='3'><?php echo $cod_cuentas_pagar; ?></font></td>
<td><font size='3'><?php echo $cod_factura; ?></font></td>
<td><font size='3'><?php echo $cod_proveedores; ?></font></td>
<td><font size='3'><?php echo $monto_deuda; ?></font></td>
<td><font size='3'><?php echo $subtotal; ?></font></td>
<td><font size='3'><?php echo $descuento; ?></font></td>
<td><font size='3'><?php echo $abonado; ?></font></td>
<td><font size='3'><?php echo $mensaje; ?></font></td>
<td><font size='3'><?php echo $vendedor; ?></font></td>
<td><font size='3'><?php echo $cuenta; ?></font></td>
<td><font size='3'><?php echo $fecha_pago; ?></font></td>
<td><font size='3'><?php echo $fecha; ?></font></td>
<td><font size='3'><?php echo $fecha_invert; ?></font></td>
<td><font size='3'><?php echo $fecha_seg; ?></font></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>