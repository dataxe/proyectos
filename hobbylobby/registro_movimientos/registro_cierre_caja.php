<?php
require_once('../conexiones/conexione.php'); 
date_default_timezone_set("America/Bogota");

$cuenta = $cuenta_actual;
$fecha_actual = date("d/m/Y");
$fecha_hoy = strtotime(date("Y/m/d"));
//------------------------------------------------------TOTAL VENTAS CONTADO -----------------------------------------------------------//
$consulta_total_ventas_contado = "SELECT Sum((precio_venta * unidades_vendidas)-((precio_venta * unidades_vendidas)*(descuento_ptj/100))) AS total_ventas_contado 
FROM ventas WHERE fecha = '$fecha_hoy' AND tipo_pago = '1' AND vendedor = '$cuenta'";
$cons_total_ventas_contado = mysql_query($consulta_total_ventas_contado, $conectar) or die(mysql_error());
$data_ventas_contado = mysql_fetch_assoc($cons_total_ventas_contado);

//------------------------------------------------------TOTAL VENTAS CREDITO -----------------------------------------------------------//
$consulta_total_ventas_credito = "SELECT Sum((precio_venta * unidades_vendidas)-((precio_venta * unidades_vendidas)*(descuento_ptj/100))) AS total_ventas_credito 
FROM ventas WHERE fecha = '$fecha_hoy' AND tipo_pago = '2' AND vendedor = '$cuenta'";
$cons_total_ventas_credito = mysql_query($consulta_total_ventas_credito, $conectar) or die(mysql_error());
$data_ventas_credito = mysql_fetch_assoc($cons_total_ventas_credito);

$s_cajas_registros = "SELECT * FROM cajas_registros WHERE fecha = '$fecha_actual' AND cuenta = '$cuenta'";
$cons_cajas_registros = mysql_query($s_cajas_registros, $conectar) or die(mysql_error());
$encontrado = mysql_num_rows($cons_cajas_registros);

$sql_admin = "SELECT cod_base_caja FROM administrador WHERE cuenta = '$cuenta'";
$consulta_admin = mysql_query($sql_admin, $conectar) or die(mysql_error());
$mat_admin = mysql_fetch_assoc($consulta_admin);

$cod_base_caja = $mat_admin['cod_base_caja'];


$sql_base_caja = "SELECT * FROM base_caja WHERE cod_base_caja = '$cod_base_caja'";
$consulta_base_caja = mysql_query($sql_base_caja, $conectar) or die(mysql_error());
$mat_base_caja = mysql_fetch_assoc($consulta_base_caja);

$base_caja = $mat_base_caja['valor_caja'];

$total_caja_com = explode(',', $mat_base_caja['total_caja_com']);
$monedas = end($total_caja_com);

$todo_a_cero = '0';
$fecha_invert = date("Y/m/d");
$hora = date("H:i:s");
$ips = $_SERVER['REMOTE_ADDR'];

$hora_hoy = strtotime(date("H:i:s"));
$hora_cierre = strtotime($mat_base_caja['hora']);

$resta_seg = $hora_hoy - $hora_cierre;

//--------------------------------------------------------IF CONDICIONAL-------------------------------------------------//
if (($resta_seg > 0) AND ($encontrado == 0)) {

$nombre_base_caja = $mat_base_caja['nombre_base_caja'];
$valor_caja = $mat_base_caja['valor_caja'] + $monedas;
$total_ventas_contado = $data_ventas_contado['total_ventas_contado'];
$total_ventas_credito = $data_ventas_credito['total_ventas_credito'];
$total_caja = ($base_caja + $total_ventas_contado) - $monedas;

$agre = "INSERT INTO cajas_registros (cod_base_caja, nombre_base_caja, valor_caja, total_ventas, total_venta_credito, total_caja, residuo, fecha, fecha_invert, fecha_seg, hora, ip, cuenta)
VALUES ('$cod_base_caja', '$nombre_base_caja', '$valor_caja', '$total_ventas_contado', '$total_ventas_credito', '$total_caja', '$monedas', '$fecha_actual', '$fecha_invert', '$fecha_hoy', '$hora', '$ips', '$cuenta')";
$resultado_cajas_registros = mysql_query($agre, $conectar) or die(mysql_error());

$actualiza = sprintf("UPDATE base_caja SET valor_caja = '$valor_caja', total_ventas = '$todo_a_cero', total_caja = '$valor_caja', total_caja_com = '$todo_a_cero'
WHERE cod_base_caja = '$cod_base_caja'");
$resultado_base_caja = mysql_query($actualiza, $conectar) or die(mysql_error());
} else {
}
?>