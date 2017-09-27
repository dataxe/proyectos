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

$hora = date("H:i:s");

//-------------------------------------------- FITRO PARA LOS DATOS DE LAS VENTAS --------------------------------------------//
$mostrar_datos_sql = "SELECT * FROM ventas ORDER BY cod_ventas DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LAS VENTAS POR CONTADO --------------------------------------------//
$mostrar_datos_sql_venta_contado = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta_contado, 
sum(vlr_total_compra) As vlr_total_compra, Sum(((precio_venta/1.16)*(iva/100))*unidades_vendidas) As sum_iva FROM ventas 
WHERE tipo_pago ='1'";
$consulta_venta_contado = mysql_query($mostrar_datos_sql_venta_contado, $conectar) or die(mysql_error());
$matriz_venta_contado = mysql_fetch_assoc($consulta_venta_contado);

//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LAS VENTAS CREDITO --------------------------------------------//
$mostrar_datos_sql_venta = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta_credito, 
sum(vlr_total_compra) As vlr_total_compra, Sum(((precio_venta/1.16)*(iva/100))*unidades_vendidas) As sum_iva FROM ventas 
WHERE tipo_pago ='2'";
$consulta_venta = mysql_query($mostrar_datos_sql_venta, $conectar) or die(mysql_error());
$matriz_venta_credito = mysql_fetch_assoc($consulta_venta);

//-------------------------------------------- CALCULOS PARA LOS TOTALES DEL INVENTARIO --------------------------------------------//
$mostrar_datos_sql_productos = "SELECT  Sum(precio_costo*unidades_faltantes) As total_mercancia, Sum(precio_venta*unidades_faltantes) As total_venta, 
Sum((precio_venta-precio_venta)*unidades_faltantes) As total_utilidad FROM productos";
$consulta_productos = mysql_query($mostrar_datos_sql_productos, $conectar) or die(mysql_error());
$datos_productos = mysql_fetch_assoc($consulta_productos);

//-------------------------------------------- CALCULOS PARA LOS TOTALES DE LOS EGRESOS --------------------------------------------//
$egresos = "SELECT Sum(costo) As total_egreso FROM egresos";
$consulta_egresos= mysql_query($egresos, $conectar) or die(mysql_error());
$matriz_egresos = mysql_fetch_assoc($consulta_egresos);

//-------------------------------------------- CALCULOS PARA LOS ABONOS --------------------------------------------//
$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos";
//$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

//-------------------------------------------- CALCULOS PARA LOS PRODUCTOS FIADOS --------------------------------------------//
$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As vlr_total_venta FROM productos_fiados";
//$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As vlr_total_venta FROM productos_fiados";
$modificar_prod_fiados = mysql_query($sql_prod_fiados, $conectar) or die(mysql_error());
$datos_fiad = mysql_fetch_assoc($modificar_prod_fiados);

//-------------------------------------------- CALCULOS PARA LAS CUENTAS POR PAGAR --------------------------------------------//
$sum_cuentas_pagar = "SELECT Sum(monto_deuda) AS monto_deuda, Sum(abonado) AS abonado FROM cuentas_pagar";
$consulta_cuentas_pagar = mysql_query($sum_cuentas_pagar, $conectar) or die(mysql_error());
$cuentas_pagar = mysql_fetch_assoc($consulta_cuentas_pagar);

//-------------------------------------------- CALCULO PARA LA CAJA --------------------------------------------//
$mostrar_caja = "SELECT SUM(valor_caja) as valor_caja FROM base_caja, administrador WHERE administrador.cod_base_caja = base_caja.cod_base_caja";
$consulta_caja = mysql_query($mostrar_caja, $conectar) or die(mysql_error());
$caja_base = mysql_fetch_assoc($consulta_caja);

$mostrar_numero_cajas = "SELECT base_caja.valor_caja FROM base_caja, administrador WHERE administrador.cod_base_caja = base_caja.cod_base_caja";
$consulta_numero_cajas = mysql_query($mostrar_numero_cajas, $conectar) or die(mysql_error());
$numero_cajas = mysql_num_rows($consulta_numero_cajas);


$total_venta_contado = $matriz_venta_contado['total_venta_contado'];
$sum_iva = $matriz_venta_contado['sum_iva'];
$vlr_total_compra = $matriz_venta_contado['vlr_total_compra'];
$total_egreso = $matriz_egresos['total_egreso'];
$total_base_caja = $caja_base['valor_caja'];

$total_deuda_clientes = $datos_fiad['vlr_total_venta'];
$total_abonos_clientes = $sum_abonos['abonado'];
$total_caja_contado = $total_venta_contado + $total_abonos_clientes + $total_base_caja;

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

$campo = 'anyo';

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
<td nowrap align="right">VENTAS ANUAL:</td>
<td bordercolor="0">
<select name="anyo" id="foco">
<?php $sql_consulta1="SELECT DISTINCT anyo FROM ventas ORDER BY anyo DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['anyo'] ?>"><?php echo $contenedor['anyo'] ?></option>
<?php }?>
</select></td></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Consultar Ventas"></td>
</tr>
</table>
</form>
<?php if ($fecha <> NULL) {?>
</center>
<center>
<fieldset><legend><font color='yellow' size='+3'>VENTAS TOTALES A&Ntilde;O: <?php echo $fecha.' - '.$hora;?></font></legend>
<table width='100%'>
<br>
<tr>
<td align="center"><strong>T.MERCANC&Iacute;A (INV)</td>
<td align="center"><strong>T.MERCANC&Iacute;A VENTA (PROYEC)</td>
<td align="center"><strong>T.COSTO</td>
<td align="center"><strong>T.VENTA (CONTADO)</td>
<td align="center"><strong>T.VENTA (CREDITO)</td>
<td align="center"><strong>ABONOS</td>
<td align="center"><strong># DE CAJAS</td>
<td align="center"><strong>BASE CAJAS</td>
<td align="center"><strong>TOTAL CAJA (CONTADO)</td>
<td align="center"><strong>T.IVA</td>
<td align="center"><strong>T.UTILIDAD (T.U)</td>
<td align="center"><strong>%.UTILIDAD</td>
<!--
<td align="center"><strong>BASE CAJA</td>
<td align="center"><strong>T.VENTA + BASE</td>
-->
<!--
<td align="center"><strong>T.CREDITO</td>
<td align="center"><strong>T.ABONADO</td>

<td align="center"><strong>POR.COBRAR</td>
-->
<td align="center"><strong>POR.PAGAR</td>
<td align="center"><strong>EGRESO (E)</td>
<td align="center"><strong>T.GANANCIA (T.U - E)</td>
</tr>
<tr>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_mercancia, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($proyecion_total_venta_productos_inventario, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($vlr_total_compra, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_venta_contado, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_venta_credito, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_abonos_clientes, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($numero_cajas, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_base_caja, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_caja_contado, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($sum_iva, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_utilidad, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo intval($ptj_utilidad).'%'; ?></font></td>
<!--
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_caja); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format(($total_venta) + $total_caja); ?></font></td>
-->
<!--
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_deuda_clientes, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_abonos_clientes, 0, ",", "."); ?></font></td>

<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($subtotal_deuda_clientes, 0, ",", "."); ?></font></td>
-->
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($subtotal_cuentas_pagar, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_egreso, 0, ",", "."); ?></font></td>
<td align="center"><font color="yellow" size="+1"><strong><?php echo number_format($total_ganancia, 0, ",", "."); ?></font></td>
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
<fieldset><legend><font color='yellow' size='+3'>VENTAS A&Ntilde;O: <?php echo $fecha.' - '.$hora;?></font></legend>
<table width='100%'>
<tr>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>MET</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
<td align="center"><strong>%DESC</strong></td>
<td align="center"><strong>TOTAL</strong></td>
<td align="center"><strong>%IVA</strong></td>
<td align="center"><strong>$IVA</strong></td>
<td align="center"><strong>TIPO PAGO</strong></td>
<td align="center"><strong>CENT.COSTO</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>FECHA VENTA</strong></td>
<td align="center"><strong>HORA</strong></td>
</tr>
<?php 
do {
$cod_factura = $datos['cod_factura'];
$cod_productos = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$unidades_vendidas = $datos['unidades_vendidas'];
$detalles = $datos['detalles'];
$precio_venta = $datos['precio_venta'];
$descuento_ptj = $datos['descuento_ptj'];
$vlr_total_venta = $datos['vlr_total_venta'];
$iva = $datos['iva'];
$tipo_pago = $datos['tipo_pago'];
$nombre_ccosto = $datos['nombre_ccosto'];
$vendedor = $datos['vendedor'];
$fecha_anyo = $datos['fecha_anyo'];
$fecha_hora = $datos['fecha_hora'];
$subtotal_base = (($vlr_total_venta - (($descuento_ptj/100) * $vlr_total_venta)) / (($iva/100) + (100/100)));
$total_desc = ($vlr_total_venta * ($descuento_ptj/100));
$total_iva = ((($vlr_total_venta - (($descuento_ptj/100) * $vlr_total_venta)) / (($iva/100) + (100/100))) * ($iva/100));
$total_venta_temp = ($vlr_total_venta - ($vlr_total_venta * ($descuento_ptj/100)));
?>
<tr>
<td align="center"><?php echo $cod_factura; ?></td>
<td ><?php echo $cod_productos; ?></td>
<td ><?php echo $nombre_productos; ?></td>
<td align="right"><?php echo $unidades_vendidas; ?></td>
<td align="center"><?php echo $detalles; ?></td>
<td align="right"><?php echo number_format($precio_venta, 0, ",", "."); ?></td>
<td align="right"><?php echo intval($descuento_ptj); ?></td>
<td align="right"><?php echo number_format($total_venta_temp, 0, ",", "."); ?></td>
<td align="center"><?php echo intval($iva); ?></td>
<td align="right"><?php echo number_format($total_iva, 0, ",", "."); ?></td>
<td align="center"><?php echo $tipo_pago; ?></td>
<td align="center"><?php echo $nombre_ccosto; ?></td>
<td align="center"><?php echo $vendedor; ?></td>
<td align="center"><?php echo $fecha_anyo; ?></td>
<td align="center"><?php echo $fecha_hora; ?></td>
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