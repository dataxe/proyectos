<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar);
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
date_default_timezone_set("America/Bogota");
$cuenta_actual = addslashes($_SESSION['usuario']);

$campo = $_GET['campo'];
$cod_productos = $_GET['id'];
$valor_intro = $_GET['valor'];

$fechas_vencimiento = $valor_intro;

$frag_fecha_vencimiento = explode('/', $fechas_vencimiento);
$dia = $frag_fecha_vencimiento[0];
$mes = $frag_fecha_vencimiento[1];
$anyos = $frag_fecha_vencimiento[2];

$fecha_ymd = $anyos.'/'.$mes.'/'.$dia;
$fechas_vencimiento_seg = strtotime($fecha_ymd);
$fecha = strtotime(date("Y/m/d"));
$fecha_time = time();
$fecha_mes = date("m/Y");
$fecha_anyo = date("d/m/Y");
$anyo = date("Y");
$fecha_hora = date("H:i:s");
$cuenta = $cuenta_actual;

if ($campo == 'fechas_vencimiento') {

mysql_query("UPDATE productos SET fechas_vencimiento = '$fechas_vencimiento', fechas_vencimiento_seg = '$fechas_vencimiento_seg' 
WHERE cod_productos = '$cod_productos'", $conectar);
//--------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------//
$obtener_productos= "SELECT * FROM productos WHERE cod_productos = '$cod_productos'";
$resultado_productos = mysql_query($obtener_productos, $conectar) or die(mysql_error());
$matriz_productos = mysql_fetch_assoc($resultado_productos);

$cod_productos_var = $matriz_productos['cod_productos_var'];
$nombre_productos = $matriz_productos['nombre_productos'];
$cod_marcas = $matriz_productos['cod_marcas'];
$cod_proveedores = $matriz_productos['cod_proveedores'];
$numero_factura = $matriz_productos['numero_factura'];
$unidades = $matriz_productos['unidades'];
$cajas = $matriz_productos['cajas'];
$unidades_total = $matriz_productos['unidades_total'];
$unidades_faltantes = $matriz_productos['unidades_faltantes'];
$precio_compra = $matriz_productos['precio_compra'];
$precio_costo = $matriz_productos['precio_costo'];
$precio_venta = $matriz_productos['precio_venta'];
$vlr_total_compra = $matriz_productos['vlr_total_compra'];
$vlr_total_venta = $matriz_productos['vlr_total_venta'];
$fecha_factura = $matriz_productos['fechas_dia'];


$agregar_actual_fech_ven = "INSERT INTO actualizado_fecha_vencimiento (cod_productos, nombre_productos, cod_marcas, cod_proveedores, numero_factura, 
unidades, cajas, unidades_total, unidades_faltantes, precio_compra, precio_costo,	precio_venta, vlr_total_compra,	vlr_total_venta, fecha_factura,
fechas_vencimiento, fechas_vencimiento_seg, fecha, fecha_time, fecha_mes, fecha_anyo, anyo, fecha_hora, cuenta)
VALUES ('$cod_productos_var', '$nombre_productos', '$cod_marcas', '$cod_proveedores', '$numero_factura', '$unidades', '$cajas', 
'$unidades_total', '$unidades_faltantes', '$precio_compra', '$precio_costo', '$precio_venta', '$vlr_total_compra', '$vlr_total_venta', '$fecha_factura', 
'$fechas_vencimiento', '$fechas_vencimiento_seg', '$fecha', '$fecha_time', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', '$cuenta')";
$resultado_agregar_actual_fech_ven = mysql_query($agregar_actual_fech_ven, $conectar) or die(mysql_error());
}
}
?>