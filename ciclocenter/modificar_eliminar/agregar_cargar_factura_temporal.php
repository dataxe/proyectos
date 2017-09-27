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

$cod_productos = intval($_GET['cod_productos']);
$pagina = $_GET['pagina'];

$sql_modificar_consulta = "SELECT * FROM productos WHERE cod_productos = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<body>
<?php
$fechas_vencimiento = '00/00/0000';
$dia = $fechas_vencimiento[0];
$mes = $fechas_vencimiento[1];
$anyo = $fechas_vencimiento[2];
$fechas_vencimiento_Y_m_d = $anyo.'/'.$mes.'/'.$dia;
$fechas_vencimiento_seg = strtotime($fechas_vencimiento_Y_m_d);

$cod_productos_var = $datos['cod_productos_var']; 
$nombre_productos = $datos['nombre_productos']; 
$cod_original = $datos['cod_original']; 
$codificacion = $datos['codificacion']; 
$unidades = $datos['unidades'];
$cajas = '1';
$unidades_total = $unidades * $cajas;
$precio_compra = $datos['precio_compra']; 
$precio_venta = $datos['precio_venta']; 
$precio_costo = $datos['precio_costo']; 
$vlr_total_venta = $datos['vlr_total_venta']; 
$vlr_total_compra = $datos['vlr_total_compra']; 
$detalles = $datos['detalles'];
$cod_interno = $datos['cod_interno'];
$tope_min = $datos['tope_minimo']; 
$vendedor = $cuenta_actual; 
$ip = $_SERVER['REMOTE_ADDR'];
$fecha = strtotime(date("Y/m/d"));
$fecha_mes = date("m/Y"); 
$dto1 = '0';
$dto2 = '0'; 
$iva = $datos['iva']; 
$ptj_ganancia = '0'; 
$porcentaje_vendedor = $datos['porcentaje_vendedor'];
$fecha_anyo = date("d/m/Y"); 
$fecha_hora = date("H:i:s");
$unidades_faltantes = $datos['unidades_faltantes'];
//--------------------------------------------------------------------//
$calc_dto1 = $precio_compra - ($precio_compra * ($dto1/100));
$calc_precio_compra_descuento = (($calc_dto1 - ($calc_dto1 * ($dto2/100))));
$valor_iva = $calc_precio_compra_descuento * ($iva/100);
//--------------------------------------------------------------------//

if (isset($cod_productos) && $cod_productos <> NULL) {

$reg_factura_temp = "INSERT INTO cargar_factura_temporal (cod_productos, nombre_productos, cod_original, codificacion, unidades_total, 
unidades, cajas, precio_compra, precio_venta, precio_costo, vlr_total_venta, vlr_total_compra, 
precio_compra_con_descuento, detalles, tope_min, vendedor, ip, fecha, fecha_mes, fechas_vencimiento, fechas_vencimiento_seg, dto1, dto2, 
iva, valor_iva, ptj_ganancia, porcentaje_vendedor, fecha_anyo, fecha_hora)
VALUES ('$cod_productos_var', '$nombre_productos', '$cod_original', '$codificacion', '$unidades_total', 
'$unidades', '$cajas', '$precio_compra', '$precio_venta', '$precio_costo', '$vlr_total_venta', '$vlr_total_compra', 
'$precio_costo', '$detalles', '$tope_min', '$vendedor', '$ip', '$fecha', '$fecha_mes', '$fechas_vencimiento', '$fechas_vencimiento_seg', 
'$dto1', '$dto2', '$iva', '$valor_iva', '$ptj_ganancia', '$porcentaje_vendedor', '$fecha_anyo', '$fecha_hora')";
$resultado_sql2 = mysql_query($reg_factura_temp, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/<?php echo $pagina ?>">
<?php
}
if ($cod_productos == NULL) {
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/<?php echo $pagina ?>">
<?php
}
?>
</body>
</html>
