<?php
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 
date_default_timezone_set("America/Bogota");
include ("../registro_movimientos/registro_movimientos.php");

//$cod_factura = $_GET['cod_factura'];

$fecha_ini = $_GET['fecha_ini'];
$fecha_fin = $_GET['fecha_fin'];
$tabla = $_GET['tabla'];
$tipo = $_GET['tipo'];
$fecha = date("Y/m/d");
$hora = date("H:i:s");

$salida	= "";
$nombre = $tabla.'_'.$fecha.'_Hora_'.$hora.'-'.$tipo.'-'.$fecha_ini.'-'.$fecha_fin.'.csv';

$sql = mysql_query("SELECT * FROM $tabla WHERE fecha_anyo BETWEEN '$fecha_ini' AND '$fecha_fin'");

$total_columnas = mysql_num_fields($sql);
// Obtener El Nombre de Campo
/*
for ($i = 0; $i < $total_columnas; $i++) {
	$encabezado	=	mysql_field_name($sql, $i);
	$salida		.= '"'.$encabezado.'",';
}
$salida .="\n";
*/
// Obtener los Registros de la tabla 
while ($datos = mysql_fetch_array($sql)) {
for ($i = 0; $i < $total_columnas; $i++) {
$salida .=''.$datos["$i"].',';
//$salida .='"'.$datos["$i"].'",';
}
$salida .="\n";
}
// DESCARGAR ARCHIVO
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$nombre);

echo $salida;
exit;
?>