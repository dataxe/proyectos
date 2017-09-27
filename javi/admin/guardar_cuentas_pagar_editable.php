<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = $_GET['valor'];
$campo = $_GET['campo'];
$cod_cuentas_pagar = $_GET['id'];

if ($campo == 'monto_deuda') {

$monto_deuda = $valor_intro;

$sql_cuentas_pagar = "UPDATE cuentas_pagar SET monto_deuda = '$monto_deuda' WHERE cod_cuentas_pagar = '$cod_cuentas_pagar'";
$modificar_cuentas_pagar = mysql_query($sql_cuentas_pagar, $conectar) or die(mysql_error());
}
if ($campo == 'fecha_pago') {

$fecha_pago = $valor_intro;
$fecha_frag_pago_seg = explode('/', $fecha_pago);

$dia = $fecha_frag_pago_seg[0];
$mes = $fecha_frag_pago_seg[1];
$anyo = $fecha_frag_pago_seg[2];

$fecha = strtotime($anyo.'/'.$mes.'/'.$dia);

$sql_cuentas_pagar = "UPDATE cuentas_pagar SET fecha_pago = '$fecha_pago', fecha = '$fecha' WHERE cod_cuentas_pagar = '$cod_cuentas_pagar'";
$modificar_cuentas_pagar = mysql_query($sql_cuentas_pagar, $conectar) or die(mysql_error());
}
if ($campo == 'fecha_invert') {

$fecha_invert = $valor_intro;
$fecha_de_registro_seg = explode('/', $fecha_invert);

$dia = $fecha_de_registro_seg[0];
$mes = $fecha_de_registro_seg[1];
$anyo = $fecha_de_registro_seg[2];

$fecha_seg = strtotime($anyo.'/'.$mes.'/'.$dia);

$sql_cuentas_pagar = "UPDATE cuentas_pagar SET fecha_invert = '$fecha_invert', fecha_seg = '$fecha_seg' WHERE cod_cuentas_pagar = '$cod_cuentas_pagar'";
$modificar_cuentas_pagar = mysql_query($sql_cuentas_pagar, $conectar) or die(mysql_error());
}
}
?>