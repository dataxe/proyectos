<?php
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 
date_default_timezone_set("America/Bogota");
include ("../registro_movimientos/registro_movimientos.php");

//$cod_factura = $_GET['cod_factura'];

$fecha = date("Y/m/d");
$hora = date("H:i:s");

$salida	= "";
$tabla = "productos";
$nombre = $tabla.'_'.$fecha.'_Hora_'.$hora.'.csv';

$sql = mysql_query("SELECT * FROM $tabla");

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