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

$cod_productos_var = mysql_escape_string($_GET['cod_productos']);
$cod_factura = intval($_GET['cod_factura']);
$pagina = $_GET['pagina'];
$ip = $_SERVER['REMOTE_ADDR'];

$sql_modificar_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$total = mysql_num_rows($modificar_consulta);
$datos = mysql_fetch_assoc($modificar_consulta);

$cod_ccosto =  $datos['cod_ccosto'];

$sql_centro_costo = "SELECT * FROM centro_costo WHERE cod_ccosto = '$cod_ccosto'";
$consulta_centro_costo = mysql_query($sql_centro_costo, $conectar) or die(mysql_error());
$datos_centro_costo = mysql_fetch_assoc($consulta_centro_costo);

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

$cod_productos = $datos['cod_productos_var'];
$nombre_productos = $datos['nombre_productos'];
$unidades_cajas = $datos['unidades'];
$detalles = $datos['detalles'];
$precio_costo = $datos['precio_costo'];
$precio_venta = $datos['precio_venta'];
$porcentaje_vendedor = $datos['porcentaje_vendedor'];
$iva = $datos['iva'];
$descripcion = $datos['descripcion'];

$fecha = strtotime(date("Y/m/d"));
$fecha_mes = date("m/Y");
$fecha_anyo = date("d/m/Y");
$fecha_hora = date("H:i:s");

if (isset($cod_productos_var) && $cod_productos_var <> NULL && $total <> '0') {

$agregar_registros_sql2 = "INSERT INTO temporal (cod_productos, cod_factura, nombre_productos, unidades_vendidas, unidades_cajas, cajas, detalles, 
precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, tipo_venta, vendedor, nombre_lineas, 
nombre_ccosto, ip, porcentaje_vendedor, iva, iva_v, fecha, fecha_mes, fecha_anyo, fecha_hora)
VALUES ('$cod_productos', '$cod_factura', '$nombre_productos', '$unidades_vendidas', '$unidades_cajas', '$cajas', '$detalles', '$precio_costo', 
'$precio_costo', '$precio_venta', '$precio_venta', '$precio_costo', '$precio_venta', '$tipo_venta', '$cuenta_actual', '$cod_lineas', 
'$nombre_ccosto', '$ip', '$porcentaje_vendedor', '$iva', '$iva_v', '$fecha', '$fecha_mes', '$fecha_anyo', '$fecha_hora')";

$resultado_sql2 = mysql_query($agregar_registros_sql2, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/<?php echo $pagina ?>">
<?php 
}
if ($total == '0') {
echo "<center><font color='yellow' size= '+2'><br><br>DENTRO DEL INVENTARIO NO SE ENCUENTRA EL CODIGO: $cod_productos</font></center>";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/<?php echo $pagina ?>">
<?php 
}
if ($cantidad_resultado == '0') {
$valor_factura = $maxima['cod_factura']+1;
$estado = 'abierto';
$agregar_reg_info_factura = "INSERT INTO info_impuesto_facturas (vendedor, estado, cod_factura) VALUES ('$cuenta_actual','$estado','$valor_factura')";
$resultado_reg_info = mysql_query($agregar_reg_info_factura, $conectar) or die(mysql_error());
} else {
}
?>
</body>
</html>