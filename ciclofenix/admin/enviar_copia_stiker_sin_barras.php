<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$cod_factura = intval($_GET['cod_factura']);
$proveedor = $_GET['proveedor'];
$pagina = 'ver_copia_stiker_sin_barras.php'.'?cod_factura='.$cod_factura.'&proveedor='.$proveedor;
//-------------------------------------------- CALCULO PARA EL TOTAL DE COMPRA --------------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<?php
if (isset($_GET['cod_factura'])) {

$mostrar_datos_factura = "SELECT * FROM facturas_cargadas_inv WHERE cod_factura = '$cod_factura'";
$consulta_factura = mysql_query($mostrar_datos_factura, $conectar) or die(mysql_error());
$total_factura = mysql_num_rows($consulta_factura);

while ($datos = mysql_fetch_assoc($consulta_factura)) {

$cod_productos = $datos['cod_productos'];
$cod_factura = $datos['cod_factura'];
$nombre_productos = $datos['nombre_productos'];
$unidades = $datos['unidades']; 
$cajas = $datos['cajas']; 
$unidades_total = $datos['unidades_total']; 
$unidades_vendidas = $datos['unidades_vendidas']; 
$precio_compra = $datos['precio_compra']; 
$precio_costo = $datos['precio_costo']; 
$precio_venta = $datos['precio_venta']; 
$vlr_total_venta = $datos['vlr_total_venta']; 
$vlr_total_compra = $datos['vlr_total_compra']; 
$precio_compra_con_descuento = $datos['precio_compra_con_descuento']; 
$cod_interno = $datos['cod_interno']; 
$detalles = $datos['detalles']; 
$cod_proveedores = $datos['cod_proveedores']; 
$tipo_pago = $datos['tipo_pago']; 
$descuento = $datos['descuento']; 
$dto1 = $datos['dto1']; 
$dto2 = $datos['dto2']; 
$iva = $datos['iva']; 
$iva_v = $datos['iva_v']; 
$valor_iva = $datos['valor_iva']; 
$cod_original = $datos['cod_original']; 
$codificacion = $datos['codificacion']; 
$porcentaje_vendedor = $datos['porcentaje_vendedor']; 
$ptj_ganancia = $datos['ptj_ganancia']; 
$tope_min = $datos['tope_min']; 
$vendedor = $datos['vendedor']; 
$fecha = $datos['fecha']; 
$fecha_mes = $datos['fecha_mes']; 
$fecha_anyo = $datos['fecha_anyo']; 
$anyo = $datos['anyo']; 
$fecha_hora = $datos['fecha_hora']; 
$fechas_vencimiento = $datos['fechas_vencimiento']; 
$fechas_vencimiento_seg = $datos['fechas_vencimiento_seg']; 
$ip = $datos['ip'];

$mostrar_datos_stiker = "SELECT cod_factura, cod_productos FROM facturas_cargadas_stiker WHERE cod_factura = '$cod_factura' AND cod_productos = '$cod_productos'";
$consulta_stiker = mysql_query($mostrar_datos_stiker, $conectar) or die(mysql_error());
//$datos_stiker = mysql_fetch_assoc($consulta_stiker)
$total_stiker = mysql_num_rows($consulta_stiker);

if ($total_stiker == 0) {
//echo "<br>".$cod_factura." - ".$cod_productos." - ".$nombre_productos."<br>";

$insertar_stiker = "INSERT INTO facturas_cargadas_stiker (cod_productos, nombre_productos, unidades, cajas, unidades_total, unidades_vendidas, 
precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, cod_interno, detalles, cod_proveedores, tipo_pago, 
descuento, dto1, dto2, iva, iva_v, valor_iva, cod_factura, cod_original, codificacion, porcentaje_vendedor, ptj_ganancia, tope_min, vendedor, fecha, 
fecha_mes, fecha_anyo, anyo, fecha_hora, fechas_vencimiento, fechas_vencimiento_seg, ip) 
VALUES ('$cod_productos', '$nombre_productos', '$unidades', '$cajas', '$unidades_total', '$unidades_vendidas', '$precio_compra', '$precio_costo', '$precio_venta', '$vlr_total_venta', 
'$vlr_total_compra', '$precio_compra_con_descuento', '$cod_interno', '$detalles', '$cod_proveedores', '$tipo_pago', '$descuento', '$dto1', '$dto2', '$iva', '$iva_v', '$valor_iva', '$cod_factura', 
'$cod_original', '$codificacion', '$porcentaje_vendedor', '$ptj_ganancia', '$tope_min', '$vendedor', '$fecha', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', '$fechas_vencimiento', 
'$fechas_vencimiento_seg', '$ip')";
$resultado_insertar_stiker = mysql_query($insertar_stiker, $conectar) or die(mysql_error());
} else {
}
}
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0; <?php echo $pagina?>">
<?php
} else {
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0; <?php echo $pagina?>">
<?php
}
?>