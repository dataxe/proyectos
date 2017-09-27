<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar);

$result = mysql_query("SELECT vendedor, SUM(vlr_total_venta) AS vlr_total_venta FROM ventas GROUP BY vendedor DESC ORDER BY vlr_total_venta ASC");

$vector = array();
while($datos = mysql_fetch_array($result)) {
$row[0] = utf8_encode($datos['vendedor']);
$row[1] = ($datos['vlr_total_venta']);
array_push($vector,$row);
}
print json_encode($vector, JSON_NUMERIC_CHECK);
mysql_close($conectar);
?> 
