<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar);

$result = mysql_query("SELECT nombre_productos, fechas_vencimiento_seg, fechas_vencimiento FROM productos WHERE fechas_vencimiento_seg <> '0' 
OR fechas_vencimiento_seg <> 'NULL' GROUP BY nombre_productos DESC ORDER BY fechas_vencimiento_seg DESC LIMIT 0,30");

$vector = array();
while($datos = mysql_fetch_array($result)) {
$nombre_productos = $datos['nombre_productos'];
$fechas_vencimiento_seg = $datos['fechas_vencimiento_seg'];
$fechas_vencimiento = $datos['fechas_vencimiento'];

$row[0] = utf8_encode($nombre_productos);
$row[1] = $fechas_vencimiento_seg;
$row[2] = $fechas_vencimiento;
array_push($vector,$row);
}
print json_encode($vector, JSON_NUMERIC_CHECK);
mysql_close($conectar);
?>