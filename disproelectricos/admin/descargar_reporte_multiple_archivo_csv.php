<?php
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 
date_default_timezone_set("America/Bogota");
include ("../registro_movimientos/registro_movimientos.php");

$fecha_ini_concat = $_GET['fecha_ini'];
$frag_fecha_ini = explode('-', $fecha_ini_concat);

$fecha_ini = $frag_fecha_ini[0];
$fecha_dmy_ini = $frag_fecha_ini[1];

$fecha_fin_concat = $_GET['fecha_fin'];
$frag_fecha_fin = explode('-', $fecha_fin_concat);

$fecha_fin = $frag_fecha_fin[0];
$fecha_dmy_fin = $frag_fecha_fin[1];

$tabla = $_GET['tabla'];
$campo = $_GET['campo'];
$tipo = $_GET['tipo'];
$fecha = date("Y/m/d");
$hora = date("H:i:s");

$salida	= "";
$nombre = 'facturas'.'_'.$fecha.'_Hora_'.$hora.'-'.$tipo.'-'.$fecha_dmy_ini.'--'.$fecha_dmy_fin.'.csv';

$sql = mysql_query("SELECT * FROM $tabla WHERE $campo BETWEEN '$fecha_ini' AND '$fecha_fin'");

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