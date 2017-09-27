<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = $_GET['valor'];
$campo = $_GET['campo'];
$cod_facturas_cargadas_inv = $_GET['id'];

$sql_modificar_consulta = "SELECT * FROM facturas_cargadas_inv WHERE cod_facturas_cargadas_inv = '$cod_facturas_cargadas_inv'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

//PARA unidades_total
if ($campo == 'unidades_total') {
$unidades_total = $valor_intro;
$precio_compra_con_descuento = $datos['precio_costo'] * $unidades_total;

mysql_query("UPDATE facturas_cargadas_inv SET unidades_total = '$unidades_total', precio_compra_con_descuento = '$precio_compra_con_descuento' 
WHERE cod_facturas_cargadas_inv = '$cod_facturas_cargadas_inv'", $conectar);
}
//PARA detalles
if ($campo == 'detalles') {
$detalles = strtoupper($valor_intro);

mysql_query("UPDATE facturas_cargadas_inv SET detalles = '$detalles' WHERE cod_facturas_cargadas_inv = '$cod_facturas_cargadas_inv'", $conectar);
}

//PARA precio_costo
if ($campo == 'precio_costo') {
$precio_costo = $valor_intro;
$precio_compra_con_descuento = $datos['unidades_total'] * $precio_costo;

mysql_query("UPDATE facturas_cargadas_inv SET precio_costo = '$precio_costo', precio_compra_con_descuento = '$precio_compra_con_descuento' 
WHERE cod_facturas_cargadas_inv = '$cod_facturas_cargadas_inv'", $conectar);
}

//PARA cod_productos
if ($campo == 'cod_productos') {
$cod_productos = $valor_intro;

mysql_query("UPDATE facturas_cargadas_inv SET cod_productos = '$cod_productos' WHERE cod_facturas_cargadas_inv = '$cod_facturas_cargadas_inv'", $conectar);
}

//PARA nombre_productos
if ($campo == 'nombre_productos') {
$nombre_productos = strtoupper($valor_intro);

mysql_query("UPDATE facturas_cargadas_inv SET nombre_productos = '$nombre_productos' WHERE cod_facturas_cargadas_inv = '$cod_facturas_cargadas_inv'", $conectar);
}
}
?>