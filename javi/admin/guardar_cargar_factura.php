<?php
include('../conexiones/conexione.php');
$data  = explode("-",$_POST['id']);

$campo = $data[0]; // nombre del campo
$cod_productos    = $data[1]; // cod_productos del registro
$cod_cargar_factura_temporal  = $data[2]; // cod_productos del registro
$value = $_POST['value']; // valor por el cual reemplazar

$datos_cargar_factura_temporal = "SELECT * FROM cargar_factura_temporal WHERE cod_productos LIKE '$cod_productos' AND cod_cargar_factura_temporal LIKE '$cod_cargar_factura_temporal'";
$consulta = mysql_query($datos_cargar_factura_temporal, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$vlr_total_venta = $datos['precio_venta'] * $value;
$precio_con_descuento = $datos['precio_venta'] * $value;
//$paises = array("1"=>"Argentina", "2"=>"Bolivia", "3"=>"Peru", "4"=>"Chile" );
// sql para actualizar el registro
$query = mysql_query("UPDATE cargar_factura_temporal SET $campo = '$value' WHERE cod_productos = '$cod_productos' 
	AND cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'");
echo ($campo == $value) ?: $value;
/*echo ('precio_compra_con_descuento' == 'precio_compra_con_descuento') ?: $value;
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; save.php">';*/
?>