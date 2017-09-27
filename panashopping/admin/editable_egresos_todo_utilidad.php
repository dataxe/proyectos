<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 

$valor_intro = addslashes(strtoupper($_GET['valor']));
$campo = $_GET['campo'];
$cod_egresos = $_GET['id'];

if ($campo == 'fecha_anyo') {

$fecha_anyo = $valor_intro;

$frag_fecha_anyo = explode('/', $fecha_anyo);

$dia = $frag_fecha_anyo[0];
$mes = $frag_fecha_anyo[1];
$anyo = $frag_fecha_anyo[2];

$fecha = strtotime($anyo.'/'.$mes.'/'.$dia);
$fecha_seg = strtotime($anyo.'/'.$mes.'/'.$dia);
$fecha_invert = $anyo.'/'.$mes.'/'.$dia;
$fecha_mes = $mes.'/'.$anyo;

$sql_actualizar_egresos = "UPDATE egresos SET fecha_anyo = '$fecha_anyo', fecha = '$fecha', fecha_seg = '$fecha_seg', fecha_invert = '$fecha_invert',
fecha_mes = '$fecha_mes', anyo = '$anyo' WHERE cod_egresos = '$cod_egresos'";
$consulta_actualizar_egresos = mysql_query($sql_actualizar_egresos, $conectar) or die(mysql_error());
$resultado = mysql_fetch_assoc($consulta_actualizar_egresos);
}
if ($campo == 'nombre_ccosto') {

$nombre_ccosto = $valor_intro;

$sql_actualizar_egresos = "UPDATE egresos SET nombre_ccosto = '$nombre_ccosto' WHERE cod_egresos = '$cod_egresos'";
$consulta_actualizar_egresos = mysql_query($sql_actualizar_egresos, $conectar) or die(mysql_error());
$resultado = mysql_fetch_assoc($consulta_actualizar_egresos);
}
}
?>