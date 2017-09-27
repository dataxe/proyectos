<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar);

$result = mysql_query("SELECT nombre_productos, SUM(unidades_vendidas) AS unidades_vendidas FROM ventas GROUP BY nombre_productos DESC 
ORDER BY unidades_vendidas ASC LIMIT 0,100");

$vector = array();
while($datos = mysql_fetch_array($result)) {
$row[0] = utf8_encode($datos['nombre_productos']);
$row[1] = ($datos['unidades_vendidas']);
array_push($vector,$row);
}
print json_encode($vector, JSON_NUMERIC_CHECK);
mysql_close($conectar);
?> 
