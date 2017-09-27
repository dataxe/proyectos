<?php
include('../conexiones/conexione.php');
$data  = explode("-",$_POST['id']);

$campo = $data[0]; // nombre del campo
$cod_productos    = $data[1]; // cod_productos del registro
$cod_temporal  = $data[2]; // cod_tempora del registro
$valor = $_POST['value']; // valor por el cual reemplazar

$datos_temporal = "SELECT * FROM temporal WHERE cod_productos LIKE '$cod_productos' AND cod_temporal LIKE '$cod_temporal'";
$consulta = mysql_query($datos_temporal, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$vlr_total_venta = $datos['precio_venta'] * $valor;
$precio_con_descuento = $datos['precio_venta'] * $valor;

$paises = array("1"=>"Argentina", "2"=>"Bolivia", "3"=>"Peru", "4"=>"Chile" );
// sql para actualizar el registro
$editar = mysql_query("UPDATE temporal SET unidades_vendidas = '$valor', vlr_total_venta = '$vlr_total_venta', 
	precio_compra_con_descuento = '$precio_con_descuento' WHERE cod_productos = '$cod_productos' AND cod_temporal = '$cod_temporal'");
echo ($campo == 'id_pais') ? $paises[$valor] : $valor;
//echo ($campo == $valor) ?: $valor;
/*
if ($campo == 'cod_factura' || $campo == 'descuento' || $campo == 'iva' || $campo == 'vlr_cancelado' ) {
$query = mysql_query("UPDATE info_impuesto_facturas SET $campo = '$valor'");
echo ($campo == $valor) ?: $valor;
}
echo ($campo == 'id_pais') ? $paises[$valor] : $valor;
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; save.php">';*/
?>