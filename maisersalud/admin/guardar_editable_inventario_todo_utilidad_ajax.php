<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = $_GET['valor'];
$campo = $_GET['campo'];
$cod_productos = $_GET['id'];

mysql_query("UPDATE productos SET $campo = '$valor_intro' WHERE cod_productos = '$cod_productos'", $conectar);
}
?>