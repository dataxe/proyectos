<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar);

$result = mysql_query("SELECT nombre_productos, sum(precio_costo) AS total FROM productos");

$vector = array();
while($datos = mysql_fetch_array($result)) {
$row[0] = utf8_encode($datos['nombre_productos']);
$row[1] = ($datos['total']);
array_push($vector,$row);
}
print json_encode($vector, JSON_NUMERIC_CHECK);
mysql_close($conectar);
?> 
