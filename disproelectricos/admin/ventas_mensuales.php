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

require_once("menu_ventas.php");

$fecha = addslashes($_POST['fecha_mes']);
$hora = date("H:i:s");
//--------------------------------------------CUANDO LLEGUE EL DATO DE LA FECHA--------------------------------------------//
//--------------------------------------------CUANDO LLEGUE EL DATO DE LA FECHA--------------------------------------------//
if ($fecha <> NULL) {
//-------------------------------------------- FITRO PARA LOS DATOS DE LAS VENTAS --------------------------------------------//
$mostrar_datos_sql = "SELECT ventas.fecha_orig, ventas.cod_factura, ventas.iva, ventas.unidades_vendidas, ventas.precio_venta, ventas.descuento_ptj, 
clientes.nombres, clientes.apellidos, clientes.cedula, Sum(ventas.vlr_total_venta) AS vlr_total_venta, ventas.fecha_mes, 
Sum(((vlr_total_venta - ((descuento_ptj/100)*vlr_total_venta))/((iva/100)+(100/100)))*(iva/100)) As total_iva
FROM clientes RIGHT JOIN ventas ON clientes.cod_clientes = ventas.cod_clientes
GROUP BY ventas.cod_factura HAVING (((ventas.fecha_mes)='$fecha')) ORDER BY ventas.cod_factura ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
/*
$mostrar_datos_sql = "SELECT ventas.fecha_orig, ventas.cod_factura, clientes.nombres, clientes.apellidos, clientes.cedula, SUM(ventas.vlr_total_venta) AS vlr_total_venta, 
ventas.unidades_vendidas, ventas.detalles, ventas.descuento_ptj, ventas.tipo_pago, ventas.nombre_ccosto, ventas.vendedor, ventas.fecha_hora, 
ventas.iva, ventas.nombre_productos, ventas.cod_productos, ventas.precio_venta FROM clientes RIGHT JOIN ventas ON clientes.cod_clientes = ventas.cod_clientes 
WHERE fecha_mes = '$fecha' GROUP BY ventas.cod_factura ORDER BY fecha, fecha_hora DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$mostrar_datos_sql = "SELECT ventas.precio_venta, ventas.iva, ventas.unidades_vendidas, ventas.fecha_orig, ventas.cod_factura,
ventas.cod_productos, ventas.nombre_productos, ventas.detalles, ventas.vlr_total_venta, ventas.descuento_ptj, ventas.tipo_pago, 
ventas.nombre_ccosto, ventas.vendedor, ventas.fecha_hora, ventas.ip, clientes.nombres, clientes.apellidos, clientes.cedula
FROM ventas, clientes WHERE fecha_mes = '$fecha' ORDER BY fecha, fecha_hora DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
*/
//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LAS VENTAS --------------------------------------------//
$mostrar_datos_sql_venta = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta, 
sum(vlr_total_compra) As vlr_total_compra, Sum(((precio_venta/1.16)*(iva/100))*unidades_vendidas) As sum_iva FROM ventas 
WHERE fecha_mes = '$fecha'";
$consulta_venta = mysql_query($mostrar_datos_sql_venta, $conectar) or die(mysql_error());
$matriz_venta = mysql_fetch_assoc($consulta_venta);



//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LAS VENTAS POR CONTADO --------------------------------------------//
$mostrar_datos_sql_venta_contado = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta_contado, 
sum(vlr_total_compra) As vlr_total_compra, Sum(((precio_venta/1.16)*(iva/100))*unidades_vendidas) As sum_iva FROM ventas 
WHERE fecha_mes = '$fecha' AND tipo_pago ='1'";
$consulta_venta_contado = mysql_query($mostrar_datos_sql_venta_contado, $conectar) or die(mysql_error());
$matriz_venta_contado = mysql_fetch_assoc($consulta_venta_contado);

//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LAS VENTAS CREDITO --------------------------------------------//
$mostrar_datos_sql_venta = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta_credito, 
sum(vlr_total_compra) As vlr_total_compra, Sum(((precio_venta/1.16)*(iva/100))*unidades_vendidas) As sum_iva FROM ventas 
WHERE fecha_mes = '$fecha' AND tipo_pago ='2'";
$consulta_venta = mysql_query($mostrar_datos_sql_venta, $conectar) or die(mysql_error());
$matriz_venta_credito = mysql_fetch_assoc($consulta_venta);

//-------------------------------------------- CALCULOS PARA LOS TOTALES DEL INVENTARIO --------------------------------------------//
$mostrar_datos_sql_productos = "SELECT  Sum(precio_costo*unidades_faltantes) As total_mercancia, Sum(precio_venta*unidades_faltantes) As total_venta, 
Sum((precio_venta-precio_venta)*unidades_faltantes) As total_utilidad FROM productos";
$consulta_productos = mysql_query($mostrar_datos_sql_productos, $conectar) or die(mysql_error());
$datos_productos = mysql_fetch_assoc($consulta_productos);

//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LOS EGRESOS --------------------------------------------//
$egresos = "SELECT Sum(costo) As total_egreso FROM egresos WHERE fecha_mes = '$fecha'";
$consulta_egresos= mysql_query($egresos, $conectar) or die(mysql_error());
$matriz_egresos = mysql_fetch_assoc($consulta_egresos);

//-------------------------------------------- CALCULOS PARA LOS ABONOS --------------------------------------------//
$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos WHERE fecha_mes = '$fecha'";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

//-------------------------------------------- CALCULOS PARA LOS PRODUCTOS FIADOS --------------------------------------------//
$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As vlr_total_venta FROM productos_fiados WHERE fecha_mes = '$fecha'";
$modificar_prod_fiados = mysql_query($sql_prod_fiados, $conectar) or die(mysql_error());
$datos_fiad = mysql_fetch_assoc($modificar_prod_fiados);

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

$total_deuda_clientes = $datos_fiad['vlr_total_venta'];
$total_abonos_clientes = $sum_abonos['abonado'];
$total_caja_contado = ($total_venta_contado + $total_abonos_clientes) - $total_egreso;

$subtotal_deuda_clientes = $total_deuda_clientes - $total_abonos_clientes;

$monto_deuda_pagar = $cuentas_pagar['monto_deuda'];
$abonado_pagar = $cuentas_pagar['abonado'];
$subtotal_cuentas_pagar = $monto_deuda_pagar - $abonado_pagar;

$total_mercancia = $datos_productos['total_mercancia'];
$proyecion_total_venta_productos_inventario = $datos_productos['total_venta'];
$total_utilidad = $total_venta_contado - $vlr_total_compra;
$ptj_utilidad = (($total_venta_contado - $vlr_total_compra) / $vlr_total_compra) * 100;
$total_ganancia = $total_venta_contado - ($vlr_total_compra + $total_egreso);
$total_venta_credito = $matriz_venta_credito['total_venta_credito'];

$campo = 'fecha_mes';
//-------------------------------------------- FIN DEL ISSET FECHA --------------------------------------------//
}
require_once("menu_ventas.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
<form method="post" name="formulario" action="">
<table align="center">
<td nowrap align="right">VENTAS MENSUAL:</td>
<td bordercolor="0">
<select name="fecha_mes" id="foco">
<?php $sql_consulta1="SELECT DISTINCT fecha_mes FROM ventas ORDER BY fecha DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['fecha_mes'] ?>"><?php echo $contenedor['fecha_mes'] ?></option>
<?php }?>
</select></td></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Consultar Ventas"></td>
</tr>
</table>
</form>
<?php
if ($fecha <> NULL) {?>
</center>

<center>
<fieldset><legend><font color='yellow' size='+3'>VENTAS TOTALES MES: <?php echo $fecha.' - '.$hora;?></font></legend>
<table width='80%'>
<br>
<tr>
<td align="center" title="Total mercancia actual en inventario"><strong>T.MERCANC&Iacute;A (INV)</td>
<td align="center" title="Total mercancia proyectada en ventas"><strong>T.MERCANC&Iacute;A VENTA (PROYEC)</td>
<td align="center" title="Total mercancia vendida en precio costo"><strong>T.COSTO</td>
<td align="center" title="Total ventas hechas en contado"><strong>T.VENTA (CONTADO)</td>
<td align="center" title="Total ventas hechas en credito"><strong>T.VENTA (CREDITO)</td>
<td align="center" title="Total abonado de creditos (ventas credito)"><strong>ABONOS</td>
<td align="center" title="Total dinero que deberia haber en caja registradora ((T.VENTA CONTADO + ABONOS) - EGRESO)"><strong>TOTAL CAJA (CONTADO)</td>
<td align="center" title="Total iv"><strong>T.IVA</td>
<td align="center" title="Total utilidad"><strong>T.UTILIDAD (T.U)</td>
<td align="center"title="porcentaje de utilidad (aprox)"><strong>%.UTILIDAD</td>
<!--
<td align="center"><strong>BASE CAJA</td>
<td align="center"><strong>T.VENTA + BASE</td>
-->
<!--
<td align="center"><strong>T.CREDITO</td>
<td align="center"><strong>T.ABONADO</td>

<td align="center"><strong>POR.COBRAR</td>
-->
<td align="center"title="Total por pagar"><strong>POR.PAGAR</td>
<td align="center"title="Total egresos"><strong>EGRESO (E)</td>
<td align="center"title="Total ganancia"><strong>T.GANANCIA (T.U - E)</td>
</tr>
<tr>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($total_mercancia, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($proyecion_total_venta_productos_inventario, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($vlr_total_compra, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($total_venta_contado, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($total_venta_credito, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($total_abonos_clientes, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($total_caja_contado, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($sum_iva, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($total_utilidad, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo intval($ptj_utilidad).'%'; ?></font></td>
<!--
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($total_caja); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format(($total_venta) + $total_caja); ?></font></td>
-->
<!--
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($total_deuda_clientes, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($total_abonos_clientes, 0, ",", "."); ?></font></td>

<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($subtotal_deuda_clientes, 0, ",", "."); ?></font></td>
-->
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($subtotal_cuentas_pagar, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($total_egreso, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($total_ganancia, 0, ",", "."); ?></font></td>
</tr>
<table>
<center>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_datos_ventas_pequena.php?fecha=<?php echo $fecha?>&campo=<?php echo $campo?>" target="_blank"><img src=../imagenes/imprimir_1.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_datos_ventas_grande.php?fecha=<?php echo $fecha?>&campo=<?php echo $campo?>" target="_blank"><img src=../imagenes/imprimir_.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</table>
</fieldset>
</center>
<br>
<fieldset><legend><font color='yellow' size='+3'>VENTAS MES: <?php echo $fecha.' - '.$hora;?></font></legend>
<table width='80%'>
<tr>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>CLIENTE</strong></td>
<td align="center"><strong>NIT</strong></td>
<td align="center"><strong>SUBTOTAL</strong></td>
<td align="center"><strong>%IVA</strong></td>
<td align="center"><strong>TOTAL</strong></td>
<!--
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>MET</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
<td align="center"><strong>%DESC</strong></td>
<td align="center"><strong>$IVA</strong></td>
<td align="center"><strong>TIPO PAGO</strong></td>
<td align="center"><strong>CENT.COSTO</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>HORA</strong></td>
-->
</tr>
<?php 
do { 
$base = $datos['precio_venta']/1.16;
$iva_ptj = $datos['iva']/100;
$unidades_vendidas = $datos['unidades_vendidas'];
$iva_valor = ($base * $iva_ptj) * $unidades_vendidas;
$precio_venta = $datos['precio_venta'];
$vlr_total_venta = $datos['vlr_total_venta'];
$descuento_ptj = $datos['descuento_ptj'];
$iva = $datos['iva'];
$subtotal_base = (($vlr_total_venta - (($descuento_ptj/100) * $vlr_total_venta)) / (($iva/100)+(100/100)));
$diferencia_iva = $vlr_total_venta - $subtotal_base;
$total_iva = $datos['total_iva'];
?>
<tr>
<td align="center"><?php echo $datos['fecha_orig']; ?></td>
<td align="center"><?php echo $datos['cod_factura']; ?></td>
<td><?php echo $datos['nombres'].' '.$datos['apellidos']; ?></td>
<td align="right"><?php echo $datos['cedula']; ?></td>
<td align="right"><?php echo number_format($subtotal_base, 0, ",", ".") ?></td>
<td align="right"><?php echo number_format($total_iva, 0, ",", "."); ?></td>
<td align="right"><?php echo number_format($vlr_total_venta, 0, ",", "."); ?></td>
<!--
<td ><?php echo $datos['cod_productos']; ?></td>
<td ><?php echo $datos['nombre_productos']; ?></td>
<td align="right"><?php echo $datos['unidades_vendidas']; ?></td>
<td align="center"><?php echo $datos['detalles']; ?></td>
<td align="right"><?php echo number_format($precio_venta, 0, ",", "."); ?></td>
<td align="right"><?php echo intval($datos['descuento_ptj']); ?></td>
<td align="right"><?php echo number_format($iva_valor, 0, ",", "."); ?></td>
<td align="center"><?php echo $datos['tipo_pago']; ?></td>
<td align="center"><?php echo $datos['nombre_ccosto']; ?></td>
<td align="center"><?php echo $datos['vendedor']; ?></td>
<td align="right"><?php echo $datos['fecha_hora']; ?></td>
-->
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta));?>
</table>
</fieldset>
</body>
</html>
<?php
} else {
}
?>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>