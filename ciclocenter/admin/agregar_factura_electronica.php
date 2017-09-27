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

$cod_productos = mysql_escape_string($_GET['cod_productos']);
$cod_factura = mysql_escape_string($_GET['cod_factura']);
$ip = $_SERVER['REMOTE_ADDR'];

$sql_modificar_consulta = "SELECT * FROM productos where cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
$numero_resultado = mysql_num_rows($modificar_consulta);
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

if (isset($cod_productos) && $cod_productos <> NULL && $numero_resultado <> '0') {
$agregar_registros_sql2 = sprintf("INSERT INTO temporal (cod_temporal, cod_productos, cod_factura, nombre_productos, unidades_vendidas, unidades_cajas, 
cajas, detalles, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, vendedor, ip, 
porcentaje_vendedor, fecha, fecha_mes, fecha_anyo, fecha_hora) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
             envio_valores_tipo_sql($_POST['cod_temporal'], "text"),
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
             $datos['precio_costo'],
             envio_valores_tipo_sql($datos['precio_venta'], "text"),
             envio_valores_tipo_sql($cuenta_actual, "text"),
             envio_valores_tipo_sql($ip, "text"),
             envio_valores_tipo_sql($datos['porcentaje_vendedor'], "text"),
             envio_valores_tipo_sql(date("Y/m/d"), "text"),
             envio_valores_tipo_sql($datos['descripcion'], "text"),
             envio_valores_tipo_sql(date("d/m/Y"), "text"),
             envio_valores_tipo_sql(date("H:i:s"), "text"));   
     
$resultado_sql2 = mysql_query($agregar_registros_sql2, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/factura_eletronica.php">';
}
if ($numero_resultado == '0') {
echo "<center><font color='yellow' size= '+2'><br><br>DENTRO DEL INVENTARIO NO SE ENCUENTRA EL CODIGO: $cod_productos</font></center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="2; ../admin/factura_eletronica.php">';
}
if ($cantidad_resultado == '0') {
$valor_factura = $maxima['cod_factura']+1;
$estado = 'abierto';
$agregar_reg_ventas = "INSERT INTO info_impuesto_facturas (vendedor, estado, cod_factura) VALUES ('$cuenta_actual','$estado','$valor_factura')";
$resultado_reg_ventas = mysql_query($agregar_reg_ventas, $conectar) or die(mysql_error());
} else {
}
?>
<p>&nbsp;</p>
</body>
</html>
<?php mysql_free_result($modificar_consulta);?>