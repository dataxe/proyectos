<?php
$cuenta = $cuenta_actual;
$fecha = date("d/m/Y");
$fecha_invert = date("Y/m/d");

$filtro_ventas_contado = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta_contado, 
sum(vlr_total_compra) As vlr_total_compra, Sum(((precio_venta/1.16)*(iva/100))*unidades_vendidas) As sum_iva FROM ventas 
WHERE fecha_anyo = '$fecha' AND vendedor = '$cuenta' AND tipo_pago ='1'";
$consulta_filtro_contado = mysql_query($filtro_ventas_contado, $conectar) or die(mysql_error());
$filtro_contado = mysql_fetch_assoc($consulta_filtro_contado);

$filtro_ventas_credito = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As total_venta_credito, 
sum(vlr_total_compra) As vlr_total_compra, Sum(((precio_venta/1.16)*(iva/100))*unidades_vendidas) As sum_iva FROM ventas 
WHERE fecha_anyo = '$fecha' AND vendedor = '$cuenta' AND tipo_pago ='2'";
$consulta_filtro_credito = mysql_query($filtro_ventas_credito, $conectar) or die(mysql_error());
$filtro_credito = mysql_fetch_assoc($consulta_filtro_credito);

$sql_adm = "SELECT * FROM administrador WHERE cuenta = '$cuenta'";
$consulta_adm = mysql_query($sql_adm, $conectar) or die(mysql_error());
$matriz_adm = mysql_fetch_assoc($consulta_adm);

$cod_base_caja = $matriz_adm['cod_base_caja'];

$sql_base = "SELECT * FROM base_caja WHERE cod_base_caja = '$cod_base_caja'";
$consulta_base = mysql_query($sql_base, $conectar) or die(mysql_error());
$matriz_base = mysql_fetch_assoc($consulta_base);


$total_venta_contado = $filtro_contado['total_venta_contado'];
$total_venta_credito = $filtro_credito['total_venta_credito'];
$total_caj = $matriz_base['valor_caja'];
$total_caja = $total_caj + $total_venta_contado;
$total_caja_com = number_format($total_venta_contado);

$actualiza_base_caja = sprintf("UPDATE base_caja SET total_ventas = '$total_venta_contado', total_venta_credito = '$total_venta_credito', 
total_caja = '$total_caja', total_caja_com = '$total_caja_com', fecha = '$fecha', fecha_invert = '$fecha_invert' 
WHERE cod_base_caja = '$cod_base_caja'");
$resultado_base_caja = mysql_query($actualiza_base_caja, $conectar) or die(mysql_error());
?>