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
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
//$tamano_archivo = $_FILES['csv']['size'];

$nombre_actualizaciones = time().'-'.$_FILES['csv']['name'];
$fecha = date("d/m/Y");
$fecha_invert = date("Y/m/d");
$hora = date("H:i:s");
$ip = $_SERVER['REMOTE_ADDR'];

$fecha_cargue = date("Y/m/d - H:i:s");
$fecha_llegada = date("d/m/Y");
$url_archivo = "../facturas_cargadas/".$nombre_actualizaciones;
$nombre_archivo = $_FILES['csv']['name'];

$confirm = '1';
if ($confirm == '1') {
//get the csv file
$file = $_FILES[csv][tmp_name];
$handle = fopen($file,"r");
//loop through the csv file and insert into database
do {
if ($data[0]) {

mysql_query("INSERT INTO productos (cod_productos, cod_productos_var, nombre_productos, cod_marcas, cod_proveedores, cod_nomenclatura, cod_tipo, cod_lineas, 
cod_paises, numero_factura, unidades, cajas, unidades_total, unidades_faltantes, unidades_vendidas, und_orig, precio_compra, precio_costo, 
precio_venta, vlr_total_compra, vlr_total_venta, tope_minimo, utilidad, total_utilidad, total_mercancia, total_venta, gasto, descuento, tipo_pago, 
ip, codificacion, url, cod_original, detalles, descripcion, dto1, dto2, iva, iva_v, fechas_dia, fechas_mes, fechas_anyo, fechas_hora, 
fechas_vencimiento, porcentaje_vendedor, fechas_vencimiento_seg, fechas_agotado, fechas_agotado_seg, vendedor, cuenta) 
VALUES ('".$data[0]."', '".$data[1]."', '".$data[2]."', '".$data[3]."', '".$data[4]."', '".$data[5]."', '".$data[6]."', '".$data[7]."', '".$data[8]."', '".$data[9]."', 
'".$data[10]."', '".$data[11]."', '".$data[12]."', '".$data[13]."', '".$data[14]."', '".$data[15]."', '".$data[16]."', '".$data[17]."', '".$data[18]."', 
'".$data[19]."', '".$data[20]."', '".$data[21]."', '".$data[22]."', '".$data[23]."', '".$data[24]."', '".$data[25]."', '".$data[26]."', '".$data[27]."', 
'".$data[28]."', '".$data[29]."', '".$data[30]."', '".$data[31]."', '".$data[32]."','".$data[33]."', '".$data[34]."', '".$data[35]."', '".$data[36]."', 
'".$data[37]."', '".$data[38]."', '".$data[39]."', '".$data[40]."', '".$data[41]."', '".$data[42]."', '".$data[43]."', '".$data[44]."', '".$data[45]."', 
'".$data[46]."', '".$data[47]."', '".$data[48]."', '".$data[49]."')");
}
} while ($data = fgetcsv($handle,1000,",","'"));

$agregar_registros_sql1 = "INSERT INTO actualizaciones (nombre_actualizaciones, cuenta, fecha, fecha_invert, hora, ip) 
VALUES ('$nombre_actualizaciones', '$cuenta_actual', '$fecha', '$fecha_invert', '$hora', '$ip')";
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());

$agregar_registros_sql1 = ("INSERT INTO facturas_cargadas (fecha_llegada, nombre_archivo, cod_facturas, url_archivo, fecha_cargue) 
VALUES ('$fecha_llegada', '$nombre_archivo', '$cuenta_actual', '$url_archivo', '$fecha_cargue')");
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());

copy($_FILES['csv']['tmp_name'], "../facturas_cargadas/".$nombre_actualizaciones);
//redirect
echo "<br><br><center><font color='yellow' size= '+2'>SE HA ACTUALIZADO CORRECTAMENTE LA TABLA PRODUCTOS</font></center>";
echo "<META HTTP-EQUIV='REFRESH' CONTENT='4; importacion_csv.php'>";
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