<?php
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar);
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
//$tamano_archivo = $_FILES['csv']['size'];

$confirm = '1';
$cod_factura = $_POST['cod_factura'];

if ($confirm == '1') {
//get the csv file
$file = $_FILES[csv][tmp_name];
$handle = fopen($file,"r");
//loop through the csv file and insert into database
//cod_productos, cod_factura, cod_original, codificacion, nombre_productos, unidades, cajas, unidades_total, unidades_vendidas, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, detalles, porcentaje_vendedor, descuento, dto1, dto2, iva, iva_v, ptj_ganancia, valor_iva, tope_min, precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, fecha_hora, fechas_vencimiento, fechas_vencimiento_seg
do {
if ($data[0]) {
mysql_query("INSERT INTO cargar_factura_temporal (cod_productos, cod_factura, cod_original, codificacion, nombre_productos, unidades, cajas, unidades_total, unidades_vendidas, 
precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, detalles, porcentaje_vendedor, descuento, dto1, dto2, iva, iva_v, ptj_ganancia, valor_iva, 
tope_min, precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, fecha_hora, fechas_vencimiento, fechas_vencimiento_seg) 
VALUES ('".$data[1]."', '".$cod_factura."', '".$data[3]."', '".$data[4]."', '".$data[5]."', '".$data[6]."', '".$data[7]."', '".$data[8]."', '".$data[9]."', '".$data[10]."', 
'".$data[11]."', '".$data[12]."', '".$data[13]."', '".$data[14]."', '".$data[15]."', '".$data[16]."', '".$data[17]."', '".$data[18]."', '".$data[19]."', '".$data[20]."', 
'".$data[21]."', '".$data[22]."', '".$data[23]."', '".$data[24]."', '".$data[25]."', '".$cuenta_actual."', '".$data[27]."', '".$data[28]."', '".$data[29]."', '".$data[30]."', 
'".$data[31]."', '".$data[32]."', '".$data[33]."')");
}
} while ($data = fgetcsv($handle,1000,",","'"));
//redirect
header("Location: recibido_importacion_factura_externa_temporal.php?cod_factura=$cod_factura"); die;
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