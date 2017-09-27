<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = addslashes(strtoupper($_GET['valor']));
$cod_facturas_cargadas_stiker = $_GET['id'];
$campo = $_GET['campo'];

mysql_query("UPDATE facturas_cargadas_stiker SET $campo = '$valor_intro' WHERE cod_facturas_cargadas_stiker = '$cod_facturas_cargadas_stiker'", $conectar);
}
?>