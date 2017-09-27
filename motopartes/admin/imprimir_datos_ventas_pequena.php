<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");

if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);

$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$matriz_informacion = mysql_fetch_assoc($consultar_informacion);

$fecha = $_GET['fecha'];
$campo = $_GET['campo'];
$fecha_hora = date("H:i:s");
?>
<link rel="stylesheet" type="text/css" href="../estilo_css/por_defecto.css">

<style type="text/css"> <!--body { background-color: #333333;}--></style>
<?php
//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LAS VENTAS POR CONTADO --------------------------------------------//
$mostrar_datos_sql_venta_contado = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta_contado, 
sum(vlr_total_compra) As vlr_total_compra, Sum(((precio_venta/1.16)*(iva/100))*unidades_vendidas) As sum_iva FROM ventas 
WHERE $campo = '$fecha' AND tipo_pago ='1'";
$consulta_venta_contado = mysql_query($mostrar_datos_sql_venta_contado, $conectar) or die(mysql_error());
$matriz_venta_contado = mysql_fetch_assoc($consulta_venta_contado);

//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LAS VENTAS CREDITO --------------------------------------------//
$mostrar_datos_sql_venta = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta_credito, 
sum(vlr_total_compra) As vlr_total_compra, Sum(((precio_venta/1.16)*(iva/100))*unidades_vendidas) As sum_iva FROM ventas 
WHERE $campo = '$fecha' AND tipo_pago ='2'";
$consulta_venta = mysql_query($mostrar_datos_sql_venta, $conectar) or die(mysql_error());
$matriz_venta_credito = mysql_fetch_assoc($consulta_venta);

//-------------------------------------------- CALCULOS PARA LOS TOTALES DEL INVENTARIO --------------------------------------------//
$mostrar_datos_sql_productos = "SELECT  Sum(precio_costo*unidades_faltantes) As total_mercancia, Sum(precio_venta*unidades_faltantes) As total_venta, 
Sum((precio_venta-precio_venta)*unidades_faltantes) As total_utilidad FROM productos";
$consulta_productos = mysql_query($mostrar_datos_sql_productos, $conectar) or die(mysql_error());
$datos_productos = mysql_fetch_assoc($consulta_productos);

//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LOS EGRESOS --------------------------------------------//
$egresos = "SELECT Sum(costo) As total_egreso FROM egresos WHERE $campo = '$fecha'";
$consulta_egresos= mysql_query($egresos, $conectar) or die(mysql_error());
$matriz_egresos = mysql_fetch_assoc($consulta_egresos);

//-------------------------------------------- CALCULOS PARA LOS ABONOS --------------------------------------------//
$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos WHERE $campo = '$fecha'";
//$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

//-------------------------------------------- CALCULOS PARA LOS PRODUCTOS FIADOS --------------------------------------------//
//$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As vlr_total_venta FROM productos_fiados WHERE $campo = '$fecha'";
//$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As vlr_total_venta FROM productos_fiados";
//$modificar_prod_fiados = mysql_query($sql_prod_fiados, $conectar) or die(mysql_error());
//$datos_fiad = mysql_fetch_assoc($modificar_prod_fiados);

//-------------------------------------------- CALCULOS PARA LAS CUENTAS POR PAGAR --------------------------------------------//
$sum_cuentas_pagar = "SELECT Sum(monto_deuda) AS monto_deuda, Sum(abonado) AS abonado FROM cuentas_pagar";
$consulta_cuentas_pagar = mysql_query($sum_cuentas_pagar, $conectar) or die(mysql_error());
$cuentas_pagar = mysql_fetch_assoc($consulta_cuentas_pagar);

//-------------------------------------------- CALCULO PARA LA CAJA --------------------------------------------//
$mostrar_caja = "SELECT SUM(valor_caja) as valor_caja FROM base_caja";
//$mostrar_caja = "SELECT * FROM base_caja WHERE vendedor = '$cuenta_actual'";
$consulta_caja = mysql_query($mostrar_caja, $conectar) or die(mysql_error());
$caja_base = mysql_fetch_assoc($consulta_caja);	

$total_venta_contado = $matriz_venta_contado['total_venta_contado'];
$sum_iva = $matriz_venta_contado['sum_iva'];
$vlr_total_compra = $matriz_venta_contado['vlr_total_compra'];
$total_egreso = $matriz_egresos['total_egreso'];
$total_caja = $caja_base['valor_caja'];

//$total_deuda_clientes = $datos_fiad['vlr_total_venta'];
$total_abonos_clientes = $sum_abonos['abonado'];
$total_caja_contado = $total_venta_contado + $total_abonos_clientes;

//$subtotal_deuda_clientes = $total_deuda_clientes - $total_abonos_clientes;

$monto_deuda_pagar = $cuentas_pagar['monto_deuda'];
$abonado_pagar = $cuentas_pagar['abonado'];
$subtotal_cuentas_pagar = $monto_deuda_pagar - $abonado_pagar;

$total_mercancia = $datos_productos['total_mercancia'];
$proyecion_total_venta_productos_inventario = $datos_productos['total_venta'];
$total_utilidad = $total_venta_contado - $vlr_total_compra;
$ptj_utilidad = (($total_venta_contado - $vlr_total_compra) / $vlr_total_compra) * 100;
$total_ganancia = $total_venta_contado - ($vlr_total_compra + $total_egreso);
$total_venta_credito = $matriz_venta_credito['total_venta_credito'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<link href="../imagenes/<?php echo $matriz_informacion['icono'];?>" type="image/x-icon" rel="shortcut icon" />
<title><?php echo "BALANCE - ".$fecha.' - '.$fecha_hora;?></title>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>
<body>
<table width="370" align="center">
<td align='center'><p style="font-size:20px"><strong>VENTAS - <?php echo $fecha.' - '.$fecha_hora;?> </td>
</table>

<table width="370" align="left">
<tr></tr><tr></tr><tr></tr><tr></tr>
<td align='left'><p style="font-size:15px"><strong>T.MERCANC&Iacute;A (INV): </td>
<td align='right'><p style="font-size:15px"><strong><?php echo number_format($total_mercancia, 0, ",", "."); ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:15px"><strong>T.MERCANC&Iacute;A VENTA (PROYEC): </td>
<td align='right'><p style="font-size:15px"><strong><?php echo number_format($proyecion_total_venta_productos_inventario, 0, ",", "."); ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:15px"><strong>T.COSTO: </td>
<td align='right'><p style="font-size:15px"><strong><?php echo number_format($vlr_total_compra, 0, ",", "."); ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:15px"><strong>T.VENTA (CONTADO):</td>
<td align='right'> <p style="font-size:15px"><strong><?php echo number_format($total_venta_contado, 0, ",", "."); ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:15px"><strong>T.VENTA (CREDITO):</td>
<td align='right'> <p style="font-size:15px"><strong><?php echo number_format($total_venta_credito, 0, ",", "."); ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:15px"><strong>ABONOS:</td>
<td align='right'> <p style="font-size:15px"><strong><?php echo number_format($total_abonos_clientes, 0, ",", "."); ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:15px"><strong>TOTAL CAJA (CONTADO):</td>
<td align='right'> <p style="font-size:15px"><strong><?php echo number_format($total_caja_contado, 0, ",", "."); ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:15px"><strong>T.IVA: </td>
<td align='right'><p style="font-size:15px"><strong><?php echo number_format($sum_iva, 0, ",", "."); ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:15px"><strong>T.UTILIDAD (T.U): </td>
<td align='right'><p style="font-size:15px"><strong><?php echo number_format($total_utilidad, 0, ",", "."); ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:15px"><strong>%.UTILIDAD: </td>
<td align='right'><p style="font-size:15px"><strong><?php echo intval($ptj_utilidad).'%'; ?></strong></td>
<tr></tr>
<!--
<td align='left'><p style="font-size:15px"><strong>BASE CAJA:</td>
<td align='right'> <p style="font-size:15px"><strong><?php echo number_format($total_caja); ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:15px"><strong>T.VENTA + BASE: </td>
<td align='right'><p style="font-size:15px"><strong><?php echo number_format(($total_venta) + $total_caja); ?></strong></td>
<tr></tr>
-->
<td align='left'><p style="font-size:15px"><strong>POR.PAGAR: </td>
<td align='right'><p style="font-size:15px"><strong><?php echo number_format($subtotal_cuentas_pagar, 0, ",", "."); ?></strong></td>
<tr></tr>

<td align='left'><p style="font-size:15px"><strong>EGRESO (E): </td>
<td align='right'><p style="font-size:15px"><strong><?php echo number_format($total_egreso, 0, ",", "."); ?></strong></td>
<tr></tr>
<td align='left'><p style="font-size:15px"><strong>T.GANANCIA (T.U - E): </td>
<td align='right'><p style="font-size:15px"><strong><?php echo number_format($total_ganancia, 0, ",", "."); ?></strong></td>
</tr>
<td><input align="left" type="image" id ="foco" src="../imagenes/imprimir.png" name="imprimir" onClick="window.print();"/></td>
</table>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>