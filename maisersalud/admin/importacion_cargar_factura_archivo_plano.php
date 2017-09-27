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
//get the csv file
$file = $_FILES[csv][tmp_name];
$handle = fopen($file,"r");
//loop through the csv file and insert into database
do {
if ($data[0]) {
/*
cod_productos,cod_factura,cod_original,codificacion,nombre_productos,unidades,unidades_vendidas,cajas,unidades_total,precio_compra,precio_costo,	
precio_venta,vlr_total_venta,vlr_total_compra,detalles,porcentaje_vendedor,descuento,dto1,dto2,iva,iva_v,ptj_ganancia,valor_iva,tope_min,	
precio_compra_con_descuento,vendedor,ip,fecha,fecha_mes,fecha_anyo,fecha_hora,fechas_vencimiento,fechas_vencimiento_seg
*/
$cod_productos_var = $data[10];
$sql_productos = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$consulta_productos = mysql_query($sql_productos, $conectar) or die(mysql_error());
$productos = mysql_fetch_assoc($consulta_productos);

$fecha_vector = $data[1];
$cod_factura = $data[2];
$nombre_productos = $data[4];
$cajas = $data[5];
$precio_compra = $data[6] * $cajas;
$ivax = $data[8];
$iva_vect = explode('.', $ivax);
$iva = end($iva_vect);
$cod_productos = $data[10];
$detalles = $data[14];

$unidades = $productos['unidades'];
$unidades_total = $unidades * $cajas;
$precio_costo = $precio_compra / $unidades_total;
$precio_venta = $productos['precio_venta'];
$vlr_total_venta = $productos['vlr_total_venta'];
$vlr_total_compra = $productos['vlr_total_compra'];
$porcentaje_vendedor = $productos['porcentaje_vendedor'];
$dto1 = '0';
$dto2 = '0';
$iva_v = '0';
$ptj_ganancia = '0';
//$valor_iva = '0';
$tope_min = $productos['tope_min'];
$precio_compra_con_descuento = $data[6];
$ip = $_SERVER['REMOTE_ADDR'];
$fecha = strtotime($fecha_vector);
$fecha_mes = date("m/Y",$fecha);
$fecha_anyo = date("Y/m/d",$fecha);
$fecha_hora = date("H:i:s");
$fecha_pago = date("d/m/Y",$fecha);
$fechas_vencimiento = '00/00/0000';
$fechas_vencimiento_seg = '0';
//--------------------------------------------------------------------//
$calc_dto1 = $precio_compra - ($precio_compra * ($dto1/100));
$calc_precio_compra_descuento = (($calc_dto1 - ($calc_dto1 * ($dto2/100))));
$valor_iva = $calc_precio_compra_descuento * ($iva/100);
//--------------------------------------------------------------------//

$data_sql = "INSERT INTO productos2 (cod_productos, cod_factura, nombre_productos, unidades, cajas, unidades_total, precio_compra, 
precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, detalles, porcentaje_vendedor, dto1, dto2, iva, iva_v, ptj_ganancia, 
valor_iva, tope_min, precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, fecha_hora, fechas_vencimiento, 
fechas_vencimiento_seg)
VALUES ('".$cod_productos."', '".$cod_factura."', '".$nombre_productos."', '".$unidades."', '".$cajas."', '".$unidades_total."', 
'".$precio_compra."', '".$precio_costo."', '".$precio_venta."', '".$vlr_total_venta."', '".$vlr_total_compra."', '".$detalles."', 
'".$porcentaje_vendedor."', '".$dto1."', '".$dto2."', '".$iva."', '".$iva_v."', '".$ptj_ganancia."', '".$valor_iva."', '".$tope_min."', 
'".$precio_compra_con_descuento."', '".$cuenta_actual."', '".$ip."', '".$fecha."', '".$fecha_mes."', '".$fecha_anyo."', '".$fecha_hora."', 
'".$fechas_vencimiento."', '".$fechas_vencimiento_seg."')";
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}
} while ($data = fgetcsv($handle,1000,",","'"));

$agregar_cuentas_facturas2 = "INSERT INTO cuentas_facturas2 (cod_factura, fecha_pago) VALUES ('$cod_factura', '$fecha_pago')";
$resultado= mysql_query($agregar_cuentas_facturas2, $conectar) or die(mysql_error());
//redirect
header("Location: cargar_factura_temporal_editable_vendedor.php?cod_factura=$cod_factura"); die;
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