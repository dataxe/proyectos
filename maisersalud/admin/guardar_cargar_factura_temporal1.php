<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = $_GET['valor'];
$cod_temporal = $_GET['id'];
$campo = $_GET['campo'];

$sql_modificar_consulta = "SELECT * FROM temporal WHERE cod_temporal = '$cod_temporal'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$precio_venta_antes_del_descuento = $datos['precio_compra_con_descuento'];

if ($campo == 'unidades_vendidas') {
$unidades_vendidas = $valor_intro;
$vlr_total_venta = $datos['precio_venta'] * $unidades_vendidas;
$vlr_total_compra = $datos['precio_costo'] * $unidades_vendidas;
$descuento = ($precio_venta_antes_del_descuento - $datos['precio_venta']) * $unidades_vendidas;
$cajas = '0';

mysql_query("UPDATE temporal SET unidades_vendidas = '$unidades_vendidas', vlr_total_venta = '$vlr_total_venta', 
vlr_total_compra = '$vlr_total_compra', cajas = '$cajas', descuento = '$descuento' WHERE cod_temporal = '$cod_temporal'", $conectar);
} 
if ($campo == 'precio_venta') {
$precio_venta = $valor_intro;
$descuento = ($precio_venta_antes_del_descuento - $precio_venta) * $datos['unidades_vendidas'];
$vlr_total_venta = $datos['unidades_vendidas'] * $precio_venta;

mysql_query("UPDATE temporal SET precio_venta = '$precio_venta', vlr_total_venta = '$vlr_total_venta', descuento = '$descuento' 
WHERE cod_temporal = '$cod_temporal'", $conectar);
} 
}
?>