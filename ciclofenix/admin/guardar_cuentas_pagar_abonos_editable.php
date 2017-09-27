<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = $_GET['valor'];
$campo = $_GET['campo'];
$cod_cuentas_pagar_abonos = $_GET['id'];

mysql_query("UPDATE cuentas_pagar_abonos SET $campo = '$valor_intro' WHERE cod_cuentas_pagar_abonos = '$cod_cuentas_pagar_abonos'", $conectar);

$sql = "SELECT cod_factura, cod_proveedores FROM cuentas_pagar_abonos WHERE cod_cuentas_pagar_abonos  = '$cod_cuentas_pagar_abonos '";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$cod_factura = $datos['cod_factura'];
$cod_proveedores = $datos['cod_proveedores'];

$sql_sum = "SELECT SUM(abonado) AS abonado FROM cuentas_pagar_abonos WHERE cod_factura  = '$cod_factura '";
$consulta_sum = mysql_query($sql_sum, $conectar) or die(mysql_error());
$datos_sum = mysql_fetch_assoc($consulta_sum);

$abonado = $datos_sum['abonado'];

mysql_query("UPDATE cuentas_pagar SET abonado = '$abonado' WHERE cod_factura = '$cod_factura'", $conectar);
}
?>