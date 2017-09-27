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
$numero_factura = $_POST['numero_factura'];
$file = $_FILES[csv][tmp_name];
$handle = fopen($file,"r");
//loop through the csv file and insert into database
do {
if ($data[0]) {

$cod_productos_var = $data[1]; 
$nombre_productos = $data[2]; 
$cod_marcas = $data[3]; 
$cod_proveedores = $data[4]; 
$cod_nomenclatura = $data[5]; 
$cod_tipo = $data[6]; 
$cod_lineas = $data[7]; 
$cod_ccosto = $data[8]; 
$cod_paises = $data[9]; 
//$numero_factura = $data[10]; 
$unidades = $data[11]; 
$cajas = $data[12]; 
$unidades_total = $data[13]; 
$unidades_faltantes = $data[14]; 
$unidades_vendidas = $data[15]; 
$und_orig = $data[15]; 
$precio_compra = $data[17]; 
$precio_costo = $data[18]; 
$precio_venta = $data[19]; 
$vlr_total_compra = $data[20]; 
$vlr_total_venta = $data[21]; 
$cod_interno = $data[22]; 
$tope_minimo = $data[23]; 
$utilidad = $data[24]; 
$total_utilidad = $data[25]; 
$total_mercancia = $data[26];
$total_venta = $data[27]; 
$gasto = $data[28]; 
$descuento = $data[29]; 
$tipo_pago = $data[30]; 
$ip = $data[31]; 
$codificacion = $data[32]; 
$url = $data[33]; 
$cod_original = $data[34]; 
$detalles = $data[35]; 
$descripcion = $data[36]; 
$dto1 = $data[37]; 
$dto2 = $data[38]; 
$iva = $data[39]; 
$iva_v = $data[40]; 
$fechas_dia = date("d/m/Y");
$fechas_mes = date("m/Y"); 
$fechas_anyo = strtotime(date("Y/m/d"));
$fechas_hora = date("H:i:s"); 
$fechas_vencimiento = $data[45]; 
$porcentaje_vendedor = $data[46]; 
$fechas_vencimiento_seg = $data[47]; 
$fechas_agotado = $data[48]; 
$fechas_agotado_seg = $data[49]; 
$vendedor = $data[50]; 
$cuenta = $cuenta_actual;


mysql_query("INSERT INTO productos_temporal (cod_productos_var, nombre_productos, cod_marcas, cod_proveedores, 
cod_nomenclatura, cod_tipo, cod_lineas, cod_ccosto, cod_paises, numero_factura, 
unidades, cajas, unidades_total, unidades_faltantes, unidades_vendidas, und_orig, 
precio_compra, precio_costo, precio_venta, vlr_total_compra, vlr_total_venta, 
cod_interno, tope_minimo, utilidad, total_utilidad, total_mercancia, total_venta, 
gasto, descuento, tipo_pago, ip, codificacion, url, cod_original, detalles, 
descripcion, dto1, dto2, iva, iva_v, fechas_dia, fechas_mes, fechas_anyo, 
fechas_hora, fechas_vencimiento, porcentaje_vendedor, fechas_vencimiento_seg, fechas_agotado, 
fechas_agotado_seg, vendedor, cuenta) 
VALUES ('".$cod_productos_var."', '".$nombre_productos."', '".$cod_marcas."', '".$cod_proveedores."', 
'".$cod_nomenclatura."', '".$cod_tipo."', '".$cod_lineas."', '".$cod_ccosto."', '".$cod_paises."', '".$numero_factura."', 
'".$unidades."', '".$cajas."', '".$unidades_total."', '".$unidades_faltantes."', '".$unidades_vendidas."', '".$und_orig."', 
'".$precio_compra."', '".$precio_costo."', '".$precio_venta."', '".$vlr_total_compra."', '".$vlr_total_venta."', 
'".$cod_interno."', '".$tope_minimo."', '".$utilidad."', '".$total_utilidad."', '".$total_mercancia."', '".$total_venta."', 
'".$gasto."', '".$descuento."', '".$tipo_pago."', '".$ip."', '".$codificacion."', '".$url."', '".$cod_original."', '".$detalles."', 
'".$descripcion."', '".$dto1."', '".$dto2."', '".$iva."', '".$iva_v."', '".$fechas_dia."', '".$fechas_mes."', '".$fechas_anyo."', 
'".$fechas_hora."', '".$fechas_vencimiento."', '".$porcentaje_vendedor."', '".$fechas_vencimiento_seg."', '".$fechas_agotado."', 
'".$fechas_agotado_seg."', '".$vendedor."', '".$cuenta."')");
}
} while ($data = fgetcsv($handle,1000,",","'"));
//redirect
header("Location: productos_temporal_para_revision_menu.php"); die;
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