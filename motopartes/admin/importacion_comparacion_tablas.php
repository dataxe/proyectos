<?php
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar);
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
//$tamano_archivo = $_FILES['csv']['size'];

$cod_factura = intval($_POST['cod_factura']);

$fecha = date("d/m/Y");
$fecha_invert = date("Y/m/d");
$hora = date("H:i:s");

if (isset($cod_factura)) {

$agre_info_camparacion_tablas = "INSERT INTO info_camparacion_tablas (cod_factura, vendedor, fecha, fecha_invert, hora)
VALUES ('$cod_factura', '$cuenta_actual', '$fecha', '$fecha_invert', '$hora')";
$resultado_info_camparacion_tablas = mysql_query($agre_info_camparacion_tablas, $conectar) or die(mysql_error());

//get the csv file
$file = $_FILES[csv][tmp_name];
$handle = fopen($file,"r");
//loop through the csv file and insert into database
//cod_productos, cod_factura, cod_original, codificacion, nombre_productos, unidades, cajas, unidades_total, unidades_vendidas, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, detalles, porcentaje_vendedor, descuento, dto1, dto2, iva, iva_v, ptj_ganancia, valor_iva, tope_min, precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, fecha_hora, fechas_vencimiento, fechas_vencimiento_seg
do {
if ($data[0]) {

//$cod_productos = $data[0];
$cod_productos = $data[1];
$cod_original = $data[3];
$codificacion = $data[4];
$nombre_productos = $data[5];
$unidades = $data[6];
$cajas = $data[7];
$unidades_total = $data[8];
$unidades_vendidas = $data[9];
$precio_compra = $data[10];
$precio_costo = $data[11];
$precio_venta = $data[12];
$vlr_total_venta = $data[13];
$vlr_total_compra = $data[14];
$detalles = $data[15];
$porcentaje_vendedor = $data[16];
$descuento = $data[17];
$dto1 = $data[18];
$dto2 = $data[19];
$iva = $data[20];
$iva_v = $data[21];
$ptj_ganancia = $data[22];
$valor_iva = $data[23];
$tope_min = $data[24];
$precio_compra_con_descuento = $data[25];
$ip = $data[27];
$fecha = $data[28];
$fecha_mes = $data[29];
$fecha_anyo = $data[30];
$fecha_hora = $data[31];
$fechas_vencimiento = $data[32];
$fechas_vencimiento_seg = $data[33];

mysql_query("INSERT INTO camparacion_tablas (cod_productos, cod_factura, cod_original, codificacion, nombre_productos, unidades, cajas, unidades_total, unidades_vendidas, 
precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, detalles, porcentaje_vendedor, descuento, dto1, dto2, iva, iva_v, ptj_ganancia, valor_iva, 
tope_min, precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, fecha_hora, fechas_vencimiento, fechas_vencimiento_seg) 
VALUES ('".$cod_productos."', '".$cod_factura."', '".$cod_original."', '".$codificacion."', '".$nombre_productos."', '".$unidades."', '".$cajas."', 
'".$unidades_total."', '".$unidades_vendidas."', '".$precio_compra."', '".$precio_costo."', '".$precio_venta."', '".$vlr_total_venta."', 
'".$vlr_total_compra."', '".$detalles."', '".$porcentaje_vendedor."', '".$descuento."', '".$dto1."', '".$dto2."', '".$iva."', 
'".$iva_v."', '".$ptj_ganancia."', '".$valor_iva."', '".$tope_min."', '".$precio_compra_con_descuento."', '".$cuenta_actual."', '".$ip."', 
'".$fecha."', '".$fecha_mes."', '".$fecha_anyo."', '".$fecha_hora."', '".$fechas_vencimiento."', '".$fechas_vencimiento_seg."')");
}
} while ($data = fgetcsv($handle,1000,",","'"));
//redirect
header("Location: recibido_importacion_comparacion_tablas.php?cod_factura=$cod_factura"); die;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
</head>
<body>
<?php //if (!empty($_GET[success])) { echo "<b>Your file has been imported.</b><br><br>"; } //generic success notice ?>
</body>
</html> 