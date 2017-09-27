<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = $_GET['valor'];
$campo = $_GET['campo'];
$cod_caja_registro_fisico = $_GET['id'];

$total_ventas_fisico = $valor_intro;

$sql = "UPDATE caja_registro_fisico SET total_ventas_fisico = '$total_ventas_fisico' WHERE cod_caja_registro_fisico = '$cod_caja_registro_fisico'";
$actualizar_consulta = mysql_query($sql, $conectar) or die(mysql_error());
}
?>