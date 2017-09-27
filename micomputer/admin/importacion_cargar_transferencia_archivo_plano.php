<?php
require_once('../conexiones/conexione.php');
date_default_timezone_set("America/Bogota");
mysql_select_db($base_datos, $conectar);
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
//$tamano_archivo = $_FILES['csv']['size'];

$confirm = '1';

if ($confirm == '1') {

$fecha_dia =  strtotime(date("Y/m/d"));
$fecha_horas = date("H:i:s");

//get the csv file
$file = $_FILES[csv][tmp_name];
$handle = fopen($file,"r");
//loop through the csv file and insert into database
do {
if ($data[0]) {
$cod_transferencias_temporal_almacenes = $data[34]; 

$sql_transferencias_almacenes = "SELECT nombre_almacen, cod_transferencias_almacenes FROM transferencias_almacenes WHERE cod_transferencias_almacenes = '$cod_transferencias_temporal_almacenes'";
$consulta_transferencias_almacenes = mysql_query($sql_transferencias_almacenes, $conectar) or die(mysql_error());
$transferencias_almacenes = mysql_fetch_assoc($consulta_transferencias_almacenes);

$nombre_almacen = $transferencias_almacenes['nombre_almacen'];
$cod_productos = $data[1]; 
$cod_factura = $data[2]; 
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
$detalles = $data[16]; 
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
$vendedor = $data[26]; 
$ip = $data[27]; 
$fecha = $data[28]; 
$fecha_mes = $data[29]; 
$fecha_anyo = $data[30]; 
$fecha_hora = $data[31]; 
$fechas_vencimiento = $data[32]; 	
$fechas_vencimiento_seg = $data[33];

mysql_query("INSERT INTO transferencias_temporal (cod_productos, cod_factura, cod_original, codificacion, nombre_productos, unidades, cajas, unidades_total, 
unidades_vendidas, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, detalles, porcentaje_vendedor, descuento, dto1, dto2, iva, 
iva_v, ptj_ganancia, valor_iva, tope_min, precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, fecha_hora, fechas_vencimiento, 	
fechas_vencimiento_seg, cod_transferencias_temporal_almacenes) 
VALUES ('".$cod_productos."', '".$cod_factura."', '".$cod_original."', '".$codificacion."', '".$nombre_productos."', '".$unidades."', '".$cajas."', 
'".$unidades_total."', '".$unidades_vendidas."', '".$precio_compra."', '".$precio_costo."', '".$precio_venta."', '".$vlr_total_venta."', '".$vlr_total_compra."', 
'".$detalles."', '".$porcentaje_vendedor."', '".$descuento."', '".$dto1."', '".$dto2."', '".$iva."', '".$iva_v."', '".$ptj_ganancia."', '".$valor_iva."', '".$tope_min."', 
'".$precio_compra_con_descuento."', '".$vendedor."', '".$ip."', '".$fecha."', '".$fecha_mes."', '".$fecha_anyo."', '".$fecha_hora."', '".$fechas_vencimiento."', 
'".$fechas_vencimiento_seg."', '".$cod_transferencias_temporal_almacenes."')");
}
} while ($data = fgetcsv($handle,1000,",","'"));

$agregar_cuentas_facturas2 = "INSERT INTO info_transferencias_temporal (cod_factura, nombre_almacen, fecha_anyo, fecha_dia, fecha_hora, vendedor) 
VALUES ('$cod_factura', '$nombre_almacen', '$fecha_anyo', '$fecha_dia', '$fecha_horas', '$cuenta_actual')";
$resultado= mysql_query($agregar_cuentas_facturas2, $conectar) or die(mysql_error());
//redirect
header("Location: cargar_transferencia_temporal_archivo_plano_editable_vendedor.php?cod_factura=$cod_factura"); die;
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