<?php
include('../conexiones/conexione.php');
$data  = explode("-",$_POST['id']);

$campo = $data[0]; // nombre del campo
$cod_productos    = $data[1]; // cod_productos del registro
$value = $_POST['value']; // valor por el cual reemplazar

$datos_temporal = "SELECT * FROM productos WHERE cod_productos_var LIKE '$cod_productos'";
$consulta = mysql_query($datos_temporal, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$vlr_total_venta = $datos['precio_venta'] * $value;
$precio_con_descuento = $datos['precio_venta'] * $value;
//$paises = array("1"=>"Argentina", "2"=>"Bolivia", "3"=>"Peru", "4"=>"Chile" );
// sql para actualizar el registro
$query = mysql_query("UPDATE productos SET $campo = '$value' WHERE cod_productos_var = '$cod_productos'");
echo ($campo == 'id_pais') ? $paises[$value] : $value;
?>