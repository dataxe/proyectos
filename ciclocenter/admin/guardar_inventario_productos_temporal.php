<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = strtoupper(addslashes($_GET['valor']));
$campo = $_GET['campo'];
$cod_productos = $_GET['id'];
/*
$sql_productos = "SELECT * FROM productos WHERE cod_productos = '$cod_productos'";
$productos_consulta = mysql_query($sql_productos, $conectar) or die(mysql_error());
$productos = mysql_fetch_assoc($productos_consulta);
*/
if ($campo == 'nombre_productos') {

$nombre_productos0 = $valor_intro;
$nombre_productos1 = preg_replace("/,/", '.', $nombre_productos0);
$nombre_productos2 = preg_replace("/'/", ' PULG', $nombre_productos1);
$nombre_productos3 = preg_replace("/;/", ' :', $nombre_productos2);
$nombre_productos4 = preg_replace("/#/", 'NO', $nombre_productos3);
$nombre_productos = strtoupper(preg_replace('/"/', ' PULG', $nombre_productos4));


$actualizar_sql1 = sprintf("UPDATE productos_temporal SET nombre_productos = '$nombre_productos' WHERE cod_productos = '$cod_productos'");
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());

} else {
$actualizar_sql1 = sprintf("UPDATE productos_temporal SET $campo = '$valor_intro' WHERE cod_productos = '$cod_productos'");
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
}
}
?>