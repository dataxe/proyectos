<?php
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 
date_default_timezone_set("America/Bogota");
include ("../registro_movimientos/registro_movimientos.php");

$cod_factura = $_GET['cod_factura'];
$nombre_almacen_vect = $_GET['nombre_almacen'];
$vector = explode(' ', $nombre_almacen_vect);
$vect1 = $vector[0];
$nombre_almacen = $vect1;
$fecha = date("Y/m/d");
$hora = date("H:i:s");

$salida	= "";
$tabla = "transferencias";
$nombre = $nombre_almacen.'_TRANSFERENCIA-['.$cod_factura.']-'.$fecha.'.csv';

$sql = mysql_query("SELECT * FROM $tabla WHERE cod_factura = '$cod_factura'");

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