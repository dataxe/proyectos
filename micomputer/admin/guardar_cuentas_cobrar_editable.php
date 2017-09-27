<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = $_GET['valor'];
$campo = $_GET['campo'];
$cod_cuentas_cobrar = $_GET['id'];

$sql_cuentas_cobrar = "SELECT * FROM  cuentas_cobrar WHERE cod_cuentas_cobrar = '$cod_cuentas_cobrar'";
$cuentas_cobrar_consulta = mysql_query($sql_cuentas_cobrar, $conectar) or die(mysql_error());
$data_cuentas_cobrar = mysql_fetch_assoc($cuentas_cobrar_consulta);

$cod_clientes = $data_cuentas_cobrar['cod_clientes'];
mysql_query("UPDATE cuentas_cobrar SET $campo = '$valor_intro' WHERE cod_clientes = '$cod_clientes'", $conectar);
}
?>