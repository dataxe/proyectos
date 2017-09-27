<?php
$cuenta = $cuenta_actual;
$fecha = date("d/m/Y");
$fecha_invert = date("Y/m/d");

$filtro_ventas = "SELECT SUM(vlr_total_venta) AS vlr_total_venta FROM ventas WHERE fecha_anyo = '$fecha' AND vendedor = '$cuenta'";
$consulta_filtro = mysql_query($filtro_ventas, $conectar) or die(mysql_error());
$filtro = mysql_fetch_assoc($consulta_filtro);

$sql_adm = "SELECT * FROM administrador WHERE cuenta = '$cuenta'";
$consulta_adm = mysql_query($sql_adm, $conectar) or die(mysql_error());
$matriz_adm = mysql_fetch_assoc($consulta_adm);

$cod_base_caja = $matriz_adm['cod_base_caja'];

$sql_base = "SELECT * FROM base_caja WHERE cod_base_caja = '$cod_base_caja'";
$consulta_base = mysql_query($sql_base, $conectar) or die(mysql_error());
$matriz_base = mysql_fetch_assoc($consulta_base);


$vlr_total_venta = $filtro['vlr_total_venta'];
$total_caj = $matriz_base['valor_caja'];
$total_caja = $total_caj + $vlr_total_venta;
$total_caja_com = number_format($vlr_total_venta);

$actualiza_base_caja = sprintf("UPDATE base_caja SET total_ventas = '$vlr_total_venta', total_caja = '$total_caja', total_caja_com = '$total_caja_com', 
fecha = '$fecha', fecha_invert = '$fecha_invert' WHERE cod_base_caja = '$cod_base_caja'");
$resultado_base_caja = mysql_query($actualiza_base_caja, $conectar) or die(mysql_error());
?>