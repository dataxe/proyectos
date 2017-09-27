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
mysql_query("INSERT INTO info_impuesto_facturas (descuento, iva, flete, cod_factura, cod_clientes, vlr_cancelado, vlr_vuelto, vendedor, estado, 
fecha_dia, fecha_mes, fecha_anyo, anyo, fecha_hora) 
VALUES ('".$data[1]."', '".$data[2]."', '".$data[3]."', '".$data[4]."', '".$data[5]."', '".$data[6]."', '".$data[7]."', '".$data[8]."', '".$data[9]."', 
'".$data[10]."', '".$data[11]."', '".$data[12]."', '".$data[13]."', '".$data[14]."')");
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
echo "<br><br><center><font color='yellow' size= '+2'>SE HA ACTUALIZADO CORRECTAMENTE LA TABLA FACTURAS</font></center>";
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