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

$suma_total_sql = "SELECT Sum(monto_deuda - abonado) As total FROM cuentas_pagar";
$consulta_total_sql = mysql_query($suma_total_sql, $conectar) or die(mysql_error());
$datos_suma = mysql_fetch_assoc($consulta_total_sql);

$total = $datos_suma['total'];

$mostrar_datos_sql = "SELECT * FROM cuentas_pagar, proveedores WHERE cuentas_pagar.cod_proveedores = proveedores.cod_proveedores ORDER BY proveedores.nombre_proveedores ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
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
myajax.Link('guardar_cuentas_pagar_editable.php?valor='+valor+'&campo='+campo+'&id='+id);
}
</script>
<body onLoad="myajax = new isiAJAX();">
<body>
<center>
<br>
<td><strong><font color='yellow'>CUENTAS POR PAGAR - <a href="../admin/crear_cuentas_pagar_manual.php"><font color='white'>CREAR NUEVA</font></a></font></strong></td><br><br>
<table width="95%">
<td align="center"><strong><font size='6'>TOTAL: <?PHP echo number_format($total, 0, ",", ".")?></font></strong></td>
</table>
<br>
<table width="95%">
<tr>
<td align="center"><strong>ELIM</strong></td>
<td align="center"><strong>PROVEEDOR</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>VLR TOTAL</strong></td>
<td align="center"><strong>ABONADO</strong></td>
<td align="center"><strong>ABONAR</strong></td>
<td align="center"><strong>DEUDA</strong></td>
<td align="center"><strong>FECHA PAGO</strong></td>
<td align="center"><strong>FECHA REG</strong></td>
</tr>
<?php do {
$cod_cuentas_pagar = $datos['cod_cuentas_pagar'];
$cod_factura = $datos['cod_factura'];
$cod_proveedores = $datos['cod_proveedores'];
$nombre_proveedores = $datos['nombre_proveedores'];
$monto_deuda = $datos['monto_deuda'];
$abonado = $datos['abonado'];
$subtotal = $monto_deuda - $abonado;
$fecha_pago = $datos['fecha_pago'];
$fecha_invert = $datos['fecha_invert'];
?>
<tr>
<?php
if($subtotal > '0') {
?>
<td></td>
<?php
} else {?>
<td><a href="../modificar_eliminar/eliminar_cuentas_pagar.php?cod_cuentas_pagar=<?php echo $cod_cuentas_pagar; ?>&cod_factura=<?php echo $cod_factura; ?>&cod_proveedores=<?php echo $cod_proveedores; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<?php
}
?>
<td><font size='3'><?php echo $nombre_proveedores; ?></font></td>
<td align="right"><font size='3'><?php echo $cod_factura; ?></font></td>
<td align='right'><input onFocus="Focus(this.id, this.value)" onBlur="Blur(this.id, this.value, 'monto_deuda', <?php echo $cod_cuentas_pagar;?>)" class="cajgrand" id="<?php echo $cod_cuentas_pagar;?>" value="<?php echo $monto_deuda;?>" size="3"></td>
<td align="right"><font size='3'><?php echo number_format($abonado, 0, ",", ".");?></font></a></td>
<td align="center"><a href="../admin/cuentas_pagar_abonos.php?cod_factura=<?php echo $cod_factura;?>&nombre_proveedores=<?php echo $nombre_proveedores;?>"><img src=../imagenes/base_caja.png alt="Abonar"></font></a></td>
<td align="right"><font color='yellow' size='5'><?php echo number_format($subtotal, 0, ",", "."); ?></font></td>
<td align="center"><font size='3'><?php echo $fecha_pago; ?></font></td>
<td align="center"><font size='3'><?php echo $fecha_invert; ?></font></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>