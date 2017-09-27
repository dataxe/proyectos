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
$cod_factura = $_GET['cod_factura'];
$pagina = $_GET['pagina'];
$ip = $_SERVER['REMOTE_ADDR'];

$sql_modificar_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$total = mysql_num_rows($modificar_consulta);
$datos = mysql_fetch_assoc($modificar_consulta);

$cod_lineas =  $datos['cod_lineas'];
$cod_ccosto =  $datos['cod_ccosto'];
$detalles =  $datos['detalles'];

$sql_centro_costo = "SELECT * FROM centro_costo WHERE cod_ccosto = '$cod_ccosto'";
$consulta_centro_costo = mysql_query($sql_centro_costo, $conectar) or die(mysql_error());
$datos_centro_costo = mysql_fetch_assoc($consulta_centro_costo);
/*
$sql_lineas = "SELECT * FROM lineas WHERE cod_lineas = '$cod_lineas'";
$consulta_lineas = mysql_query($sql_lineas, $conectar) or die(mysql_error());
$datos_lineas = mysql_fetch_assoc($consulta_lineas);

$cod_lineas = $datos_lineas['cod_lineas'];
*/
$nombre_ccosto = $datos_centro_costo['nombre_ccosto'];
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
$datos_info = "SELECT * FROM info_impuesto_facturas WHERE estado = 'abierto' AND vendedor = '$cuenta_actual'";
$consulta_info = mysql_query($datos_info, $conectar) or die(mysql_error());
$info = mysql_fetch_assoc($consulta_info);
$cantidad_resultado = mysql_num_rows($consulta_info);

$maxima_factura = "SELECT Max(cod_factura) AS cod_factura FROM info_impuesto_facturas";
$consulta_maxima = mysql_query($maxima_factura, $conectar) or die(mysql_error());
$maxima = mysql_fetch_assoc($consulta_maxima);

$unidades_vendidas = '1';
$cajas = '0';
$vlr_total_venta = '0';
$iva_v = '0';
$tipo_venta = '0';
$fecha_anyo = date("d/m/Y");
$fecha_hora = date("H:i:s");

if (($detalles <> 'UND') && ($total <> '0')) {
$agregar_registros_sql2 = sprintf("INSERT INTO temporal (cod_productos, cod_factura, nombre_productos, unidades_vendidas, unidades_cajas, cajas, detalles, precio_compra, 
precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, tipo_venta, vendedor, nombre_lineas, nombre_ccosto, ip, 
porcentaje_vendedor, iva, iva_v, fecha_mes, fecha_anyo, fecha_hora) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
             envio_valores_tipo_sql($datos['cod_productos_var'], "text"),
             envio_valores_tipo_sql($cod_factura, "text"),
             envio_valores_tipo_sql($datos['nombre_productos'], "text"),
             envio_valores_tipo_sql($unidades_vendidas, "text"),
             envio_valores_tipo_sql($datos['unidades'], "text"),
             envio_valores_tipo_sql($cajas, "text"),
             envio_valores_tipo_sql($datos['detalles'], "text"),
             envio_valores_tipo_sql($datos['precio_costo'], "text"),
             envio_valores_tipo_sql($datos['precio_costo'], "text"),
             envio_valores_tipo_sql($datos['precio_venta'], "text"),
             envio_valores_tipo_sql($datos['precio_venta'], "text"),
             envio_valores_tipo_sql($datos['precio_costo'], "text"),
             envio_valores_tipo_sql($datos['precio_venta'], "text"),
             envio_valores_tipo_sql($tipo_venta, "text"),
             envio_valores_tipo_sql($cuenta_actual, "text"),
             envio_valores_tipo_sql($cod_lineas, "text"),
             envio_valores_tipo_sql($nombre_ccosto, "text"),
             envio_valores_tipo_sql($ip, "text"),
             envio_valores_tipo_sql($datos['porcentaje_vendedor'], "text"),
             envio_valores_tipo_sql($datos['iva'], "text"),
             envio_valores_tipo_sql($iva_v, "text"),
             envio_valores_tipo_sql($datos['descripcion'], "text"),
             envio_valores_tipo_sql($fecha_anyo, "text"),
             envio_valores_tipo_sql($fecha_hora, "text"));   
     
$resultado_sql2 = mysql_query($agregar_registros_sql2, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/<?php echo $pagina ?>">
<?php
} elseif (($detalles == 'UND') && ($total <> '0')) {
$agregar_registros_sql2 = sprintf("INSERT INTO temporal (cod_temporal, cod_productos, cod_factura, nombre_productos, unidades_vendidas, unidades_cajas, 
cajas, detalles, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, tipo_venta, vendedor, nombre_lineas, nombre_ccosto, ip, 
porcentaje_vendedor, iva, iva_v, fecha_mes, fecha_anyo, fecha_hora) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
             envio_valores_tipo_sql($_POST['cod_temporal'], "text"),
             envio_valores_tipo_sql($datos['cod_productos_var'], "text"),
             envio_valores_tipo_sql($cod_factura, "text"),
             envio_valores_tipo_sql($datos['nombre_productos'], "text"),
             envio_valores_tipo_sql($unidades_vendidas, "text"),
             envio_valores_tipo_sql($datos['unidades'], "text"),
             envio_valores_tipo_sql($cajas, "text"),
             envio_valores_tipo_sql($datos['detalles'], "text"),
             envio_valores_tipo_sql($datos['precio_compra'], "text"),
             envio_valores_tipo_sql($datos['precio_compra'], "text"),
             envio_valores_tipo_sql($datos['vlr_total_venta'], "text"),
             envio_valores_tipo_sql($datos['vlr_total_venta'], "text"),
             envio_valores_tipo_sql($datos['precio_compra'], "text"),
             envio_valores_tipo_sql($datos['vlr_total_venta'], "text"),
             envio_valores_tipo_sql($tipo_venta, "text"),
             envio_valores_tipo_sql($cuenta_actual, "text"),
             envio_valores_tipo_sql($cod_lineas, "text"),
             envio_valores_tipo_sql($nombre_ccosto, "text"),
             envio_valores_tipo_sql($ip, "text"),
             envio_valores_tipo_sql($datos['porcentaje_vendedor'], "text"),
             envio_valores_tipo_sql($datos['iva'], "text"),
             envio_valores_tipo_sql($iva_v, "text"),
             envio_valores_tipo_sql($datos['descripcion'], "text"),
             envio_valores_tipo_sql(date("d/m/Y"), "text"),
             envio_valores_tipo_sql(date("H:i:s"), "text"));   
     
$resultado_sql2 = mysql_query($agregar_registros_sql2, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/<?php echo $pagina ?>">
<?php
} else {
?>
<br>
<center><font color='white' size= '+2'><img src=../imagenes/advertencia.gif alt='Advertencia'> <strong>EL CODIGO </font><font color='yellow' size= '+2'><?php echo $cod_productos?> </font><font color='white' size= '+2'>NO EXISTE EN EL INVENTARIO.</font></strong> <img src=../imagenes/advertencia.gif alt='Advertencia'><center><br>
<META HTTP-EQUIV="REFRESH" CONTENT="4; ../admin/<?php echo $pagina ?>">
<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='true'></embed>
<?php
}
if ($cantidad_resultado == 0) {
$valor_factura = $maxima['cod_factura']+1;
$estado = 'abierto';
$agregar_reg_ventas = "INSERT INTO info_impuesto_facturas (vendedor, estado, cod_factura) VALUES ('$cuenta_actual', '$estado', '$valor_factura')";
$resultado_reg_ventas = mysql_query($agregar_reg_ventas, $conectar) or die(mysql_error());
} else {

}
?>
</body>
</html>