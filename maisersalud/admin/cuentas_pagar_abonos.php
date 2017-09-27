<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
$cuenta_actual = addslashes($_SESSION['usuario']);
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
<script language="javascript" src="isiAJAX.js"></script>
<script language="javascript">
var last;
function Focus(elemento, valor) {
$(elemento).className = 'inputon';
last = valor;
}
function Blur(elemento, valor, campo, id) {
$(elemento).className = 'inputoff';
if (last != valor)
myajax.Link('guardar_cuentas_pagar_abonos_editable.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>
<body onLoad="myajax = new isiAJAX();">

</head>
<?php
$cod_factura = $_GET['cod_factura'];
$nombre_proveedores = $_GET['nombre_proveedores'];

$sql = "SELECT * FROM cuentas_pagar_abonos WHERE cod_factura = '$cod_factura' ORDER BY fecha_invert DESC";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);

$sql_sum_abonos = "SELECT Sum(abonado) As sum_abonados FROM cuentas_pagar_abonos WHERE cod_factura = '$cod_factura'";
$consulta_sum_abonos  = mysql_query($sql_sum_abonos, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$monto_deuda = $total_venta['vlr_total_venta'];
$abonados = $sum_abonos['sum_abonados'];

mysql_query("UPDATE cuentas_pagar SET abonado = '$abonados' WHERE cod_factura = '$cod_factura'", $conectar);
?>
<center>
<td><strong><a href="../admin/cuentas_pagar.php"><font color='yellow' size="5px">REGRESAR</font></a></td><br><br>

<td><a href="../admin/nuevo_abono_cuenta_pagar.php?cod_factura=<?php echo $cod_factura?>&nombre_proveedores=<?php echo $nombre_proveedores?>"><font color='white' size="5px">NUEVO ABONO</font></a></td><br><br>

<td><strong><font color='yellow' size="6px">ABONOS PROVEEDOR: <?php echo $nombre_proveedores; ?>  - FACTURA: <?php echo $cod_factura; ?></font></strong></td><br><br>
<table width="80%">
<tr>
<td align="center"><strong>ABONOS</strong></td>
<td align="center"><strong>PAGO A</strong></td>
<td align="center"><strong>MENSAJE</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>HORA</strong></td>
</tr>
<?php
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_cuentas_pagar_abonos = $datos['cod_cuentas_pagar_abonos'];
$abonado = $datos['abonado'];
?>
<tr>
<td align='right'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'abonado', <?php echo $cod_cuentas_pagar_abonos;?>)" class="cajgrand" id="<?php echo $cod_cuentas_pagar_abonos;?>" value="<?php echo $abonado;?>" size="3"></td>
<td align="center"><font size="4px"><?php echo $datos['cuenta']; ?></font></td>
<td align="left"><font size="4px"><?php echo $datos['mensaje']; ?></font></td>
<td align="center"><font size="4px"><?php echo $datos['fecha_pago']; ?></font></td>
<td align="center"><font size="4px"><?php echo $datos['hora']; ?></font></td>
</tr>
<?php }
?>
</table>
<br>
<table>
<td align="right"><font size="7">TOTAL ABONO: <?php echo number_format($abonados); ?></font></td>
</table>
</form>