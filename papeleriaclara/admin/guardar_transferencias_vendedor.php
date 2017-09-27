<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
      } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
date_default_timezone_set("America/Bogota");
include ("../formato_entrada_sql/funcion_env_val_sql.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php
$cod_transferencias_almacenes = intval($_POST['cod_transferencias_almacenes']);

$fecha = $_POST['fecha'];
$total_datos = intval($_POST['total_datos']);
$total = addslashes($_POST['total']);
$cod_factura = $_POST['cod_factura'];
$ip = $_SERVER['REMOTE_ADDR'];

$dato_fecha = explode('/', $fecha);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];

$fecha_invert = $anyo.'/'.$mes.'/'.$dia;
$fechas_mes = $mes.'/'.$anyo;
$fechas_anyo = $fecha;
$fechas_dia = $fecha_invert;
$fechas_hora = date("H:i:s");
$unidades_vendidas = "0";
$abonado = "0";
$tipo_pago = "0";

$fecha_actual_hoy = date("Y/m/d");
$fechas_agotado = date("d/m/Y");
$fechas_agotado_seg = strtotime($fecha_actual_hoy);

// -------------------  --------------------------------
if (isset($_POST['verificacion'])) {
/*
for ($i=0; $i < $total_datos; $i++) {
$cod_productos = $_POST['cod_productos'][$i];

$sqlr_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sqlr_consulta, $conectar) or die(mysql_error());
$datos_prod = mysql_fetch_assoc($modificar_consulta);

$sql_mconsulta = "SELECT * FROM cargar_transferencias_temporal2 WHERE cod_productos = '$cod_productos'";
$mconsulta = mysql_query($sql_mconsulta, $conectar) or die(mysql_error());
$datos_temp = mysql_fetch_assoc($mconsulta);

$unid = $datos_prod['precio_venta'];

$unidades_faltante = $datos_prod['unidades_faltantes'] - $datos_temp['unidades_total'];
$unidades_faltantes = $datos_prod['unidades_faltantes'] - $datos_temp['unidades_total'];
$unidades_vendidas = $datos_prod['unidades_vendidas'] + $datos_temp['unidades_total'];
$total_util1 = $datos_prod['unidades_vendidas'] * $datos_prod['precio_venta'];
$total_util2 = $datos_prod['unidades_vendidas'] * $datos_prod['precio_costo'];
$total_utilidad = $total_util1 - $total_util2;
$total_mercancia = $datos_prod['unidades_faltantes'] * $datos_prod['precio_costo'];
$total_venta = $datos_prod['unidades_vendidas'] * $datos_prod['precio_costo'];

//----------------------------- ACTUALIZAR INVENTARIO DE transferencias2 OPERACIONES -----------------------------//

if ($unidades_faltantes <= '0') {
$actualiza_transferencias2 = sprintf("UPDATE transferencias2, cargar_transferencias_temporal2 SET transferencias2.unidades_vendidas = '$unidades_vendidas', 
transferencias2.unidades_faltantes = '$unidades_faltante',
transferencias2.total_utilidad = '$total_utilidad',
transferencias2.total_mercancia = '$total_mercancia', 
transferencias2.total_venta = '$total_venta',
transferencias2.fechas_agotado_seg = '$fechas_agotado_seg',
transferencias2.fechas_agotado = '$fechas_agotado'
WHERE transferencias2.cod_productos = '$cod_productos'");
$resultado_actualiza_transferencias2 = mysql_query($actualiza_transferencias2, $conectar) or die(mysql_error());
} else {
$actualiza_transferencias2 = sprintf("UPDATE transferencias2, cargar_transferencias_temporal2 SET transferencias2.unidades_vendidas = '$unidades_vendidas', 
transferencias2.unidades_faltantes = '$unidades_faltante',
transferencias2.total_utilidad = '$total_utilidad',
transferencias2.total_mercancia = '$total_mercancia', 
transferencias2.total_venta = '$total_venta'
WHERE transferencias2.cod_productos = '$cod_productos'");
$resultado_actualiza_transferencias2 = mysql_query($actualiza_transferencias2, $conectar) or die(mysql_error());
}
}
*/

$agregar_reg_transferencias = "INSERT INTO transferencias2 (cod_productos, cajas, unidades, unidades_total, nombre_productos, cod_original, codificacion, unidades_vendidas, precio_compra, 
precio_costo, precio_venta, dto1, dto2, iva, iva_v, fecha, fecha_mes, fecha_anyo, fecha_hora, ip, cod_factura, cod_transferencias_almacenes, vendedor, vlr_total_compra, 
vlr_total_venta, detalles) 
SELECT cod_productos, cajas, unidades, unidades_total, nombre_productos, cod_original, codificacion, unidades_vendidas, precio_compra, precio_costo, precio_venta, dto1, dto2, 
iva, iva_v, '$fecha_invert', '$fechas_mes', '$fecha', '$fechas_hora', '$ip', '$cod_factura', '$cod_transferencias_almacenes', '$cuenta_actual', vlr_total_compra, 
vlr_total_venta, detalles FROM cargar_transferencias_temporal2 WHERE cargar_transferencias_temporal2.vendedor = '$cuenta_actual'";
$resultado_transferencias = mysql_query($agregar_reg_transferencias, $conectar) or die(mysql_error());


$agregar_regis = "INSERT INTO info_transferencias2 (cod_transferencias_almacenes, cod_factura, vendedor, fecha_dia, fecha_anyo, fecha_hora)
VALUES ('$cod_transferencias_almacenes', '$cod_factura', '$cuenta_actual', '$fecha_invert', ' $fecha', '$fechas_hora')";
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());


$borrar_sql = sprintf("DELETE FROM cargar_transferencias_temporal2 WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

echo "<br><br><center><font size='6' color='yellow'>LA TRANSFERENCIA DEL DIA: ".$fecha." SE HA HECHO EXITOSAMENTE</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; ../admin/transferencias_vendedor.php">';
}
?>
</body>
</html>