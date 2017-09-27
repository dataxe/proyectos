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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$buscar = addslashes($_POST['palabra']);

$mostrar_datos_sql = "SELECT clientes.nombres, clientes.apellidos, cuentas_cobrar.cod_clientes, Sum(cuentas_cobrar.monto_deuda) AS monto_deuda, 
Sum(cuentas_cobrar.abonado) AS abonado, cuentas_cobrar.mensaje, cuentas_cobrar.vendedor, cuentas_cobrar.fecha_pago
FROM clientes INNER JOIN cuentas_cobrar ON clientes.cod_clientes = cuentas_cobrar.cod_clientes AND (clientes.nombres LIKE '%$buscar%' 
OR clientes.apellidos LIKE '%$buscar%') GROUP BY cuentas_cobrar.cod_clientes ORDER BY clientes.nombres ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$total_datos = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<?php require_once('../busquedas/buscar_cuentas_cobrar.php');?>
<center>
<td><strong><font color='yellow'>CUENTAS POR COBRAR - <a href="../admin/cuentas_cobrar_pagadas.php"><font color='white'>CUENTAS PAGADAS</a>
<br>
<?php
include ("../admin/menu_cuentas_cobrar.php");
?>
<br>
<table width="100%">
<tr>
<td align="center"><strong>ELM</strong></td>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>PAGAR</strong></td>
<td align="center"><strong>VLR TOTAL</strong></td>
<td align="center"><strong>ABONADO</strong></td>
<td align="center"><strong>VER ABONOS</strong></td>
<td align="center"><strong>DEUDA</strong></td>
<td align="center"><strong>MENSAJE</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
</tr>
<?php do {
$cod_clientes = $datos['cod_clientes'];
$cliente = $datos['nombres']." ".$datos['apellidos'];
$monto_deuda = $datos['monto_deuda'];
$abonado = $datos['abonado'];
$subtotal = $monto_deuda - $abonado;
$mensaje = $datos['mensaje'];
$fecha_pago = $datos['fecha_pago'];
$vendedor = $datos['vendedor'];
?>
<tr>
<?php
if($subtotal > '0') {
?>
<td></td>
<?php
} else {?>
<td><a href="../modificar_eliminar/eliminar_cuentas_cobrar.php?cod_clientes=<?php echo $cod_clientes; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>
<?php
}
?>
<td><font size='3'><?php echo $cliente;?></font></td>
<td><a href="../modificar_eliminar/modificar_cuentas_cobrar.php?cod_clientes=<?php echo $cod_clientes; ?>"><center><img src=../imagenes/vender.png alt="Ver"></center></a></td>
<td align="right"><a href="../admin/productos_fiados.php?cod_clientes=<?php echo $cod_clientes;?>&cliente=<?php echo $cliente;?>"><font size='3'><?php echo number_format($monto_deuda, 0, ",", ".")?></font></a></td>
<td align="right"><font size='3'><?php echo number_format($abonado, 0, ",", ".");?></font></td>
<td align="center"><a href="../admin/cuentas_cobrar_abonos.php?cod_clientes=<?php echo $cod_clientes;?>&cliente=<?php echo $cliente;?>"><img src=../imagenes/base_caja.png alt="Abonar"></a></td>
<td align="right"><font color='yellow' size='5'><?php echo number_format($subtotal, 0, ",", "."); ?></font></td>
<td align="justify"><font size='3'><?php echo $mensaje; ?></font></td>
<td align="right"><font size='3'><?php echo $fecha_pago;?></font></td>
<td align="right"><font size='3'><?php echo $vendedor; ?></font></td>
</tr>
<?php 
} while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
