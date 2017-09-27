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

$cod_productos = $_GET['cod_productos'];

$sql_modificar_consulta = "SELECT * FROM productos where cod_productos_var = '$cod_productos'";
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
$dato_fecha = explode('/', $datos['fechas_vencimiento']);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];
$fechas_vencimiento_Y_m_d = $anyo.'/'.$mes.'/'.$dia;
$fechas_vencimiento_seg = strtotime($fechas_vencimiento_Y_m_d);
$cajas = '1';
$unidades = '0';
$unidades_total = '0';
$dto1 = '0';
$dto2 = '0';
$valor_iva = '0';
$iva = '0';
$unidades_vendidas = '0';
$ip = $_SERVER['REMOTE_ADDR'];
$vlr_total_venta = '0';
$vlr_total_compra = '0';
$precio_compra = '0';

if (isset($cod_productos) && $cod_productos <> NULL) {
$agregar_registros_sql2 = sprintf("INSERT INTO cargar_factura_temporal2 (cod_productos, nombre_productos, cod_original, codificacion, cajas, unidades, unidades_total, 
unidades_vendidas, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, detalles, tope_min, vendedor, ip, fecha, fecha_mes, fechas_vencimiento, 
fechas_vencimiento_seg, dto1, dto2, iva, iva_v, valor_iva, porcentaje_vendedor, fecha_anyo, fecha_hora) 
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
             envio_valores_tipo_sql($datos['cod_productos_var'], "text"),
             envio_valores_tipo_sql($datos['nombre_productos'], "text"),
             envio_valores_tipo_sql($datos['cod_original'], "text"),
             envio_valores_tipo_sql($datos['codificacion'], "text"),
             envio_valores_tipo_sql($cajas, "text"),
             envio_valores_tipo_sql($unidades, "text"),
             envio_valores_tipo_sql($unidades_total, "text"),
             envio_valores_tipo_sql($unidades_vendidas, "text"), 
             envio_valores_tipo_sql($datos['precio_compra'], "text"),
             envio_valores_tipo_sql($datos['precio_costo'], "text"),
             envio_valores_tipo_sql($datos['precio_venta'], "text"),
             envio_valores_tipo_sql($vlr_total_venta, "text"),
             envio_valores_tipo_sql($vlr_total_compra, "text"),
             envio_valores_tipo_sql($datos['detalles'], "text"),
             envio_valores_tipo_sql($datos['tope_minimo'], "text"),
             envio_valores_tipo_sql($cuenta_actual, "text"),
             envio_valores_tipo_sql($ip, "text"),
             envio_valores_tipo_sql(date("Y/m/d"), "text"),
             envio_valores_tipo_sql(date("m/Y"), "text"),
             envio_valores_tipo_sql($datos['fechas_vencimiento'], "text"),
             envio_valores_tipo_sql($datos['fechas_vencimiento_seg'], "text"),
             envio_valores_tipo_sql($dto1, "text"),
             envio_valores_tipo_sql($dto2, "text"),
             envio_valores_tipo_sql($iva, "text"),
             envio_valores_tipo_sql($datos['iva_v'], "text"),
             envio_valores_tipo_sql($valor_iva, "text"),
             envio_valores_tipo_sql($datos['porcentaje_vendedor'], "text"),
             envio_valores_tipo_sql(date("d/m/Y"), "text"),
             envio_valores_tipo_sql(date("H:i:s"), "text"));   
     
$resultado_sql2 = mysql_query($agregar_registros_sql2, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/cargar_factura_temporal_vendedor.php">';
}
if ($cod_productos == NULL) {
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/cargar_factura_temporal_vendedor.php">';
}
?>
</body>
</html>
<?php mysql_free_result($modificar_consulta);?>