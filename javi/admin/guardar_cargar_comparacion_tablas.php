<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = $_GET['valor'];
$cod_camparacion_tablas = $_GET['id'];
$campo = $_GET['campo'];

if ($campo == 'unidades_correcion') {
$unidades_correcion = $valor_intro;
mysql_query("UPDATE camparacion_tablas SET unidades_correcion = '$unidades_correcion' WHERE cod_camparacion_tablas = '$cod_camparacion_tablas'", $conectar);
}
}
?>