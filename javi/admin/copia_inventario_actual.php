<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar);
date_default_timezone_set("America/Bogota");
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
      } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
$cuenta = $cuenta_actual;
include ("../seguridad/seguridad_diseno_plantillas.php");

include ("../registro_movimientos/registro_movimientos.php");
$fechas_anyo = time();

$ultimo_cod_factura_sql = "SELECT max(numero_factura) AS numero_factura FROM productos_copia_inventario";
$consulta_cod_factura = mysql_query($ultimo_cod_factura_sql, $conectar) or die(mysql_error());
$datos_cod_factura = mysql_fetch_assoc($consulta_cod_factura);

$numero_factura = $datos_cod_factura['numero_factura']+1;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php 
if (isset($_POST['si'])) {
//$actualizar_sql1 = sprintf("INSERT INTO productos_copia_inventario SELECT * FROM productos");
//$actualizar_sql1 = sprintf("INSERT INTO productos_copia_inventario (cod_productos_copia_inventario, cod_productos_var, nombre_productos) SELECT cod_productos, cod_productos_var, '$fecha_time' FROM productos");
$actualizar_sql1 = sprintf("INSERT INTO productos_copia_inventario (cod_productos_copia_inventario, cod_productos_var, nombre_productos, cod_marcas, 
cod_proveedores, cod_nomenclatura, cod_tipo, cod_lineas, cod_ccosto, cod_paises, numero_factura, unidades, cajas, unidades_total, 
unidades_faltantes, unidades_vendidas, und_orig, precio_compra, precio_costo, precio_venta, vlr_total_compra, vlr_total_venta, cod_interno, 
tope_minimo, utilidad, total_utilidad, total_mercancia, total_venta, gasto, descuento, tipo_pago, ip, codificacion, url, cod_original, detalles, 
descripcion, dto1, dto2, iva, iva_v, fechas_dia, fechas_mes, fechas_anyo, fechas_hora, fechas_vencimiento, porcentaje_vendedor, 
fechas_vencimiento_seg, fechas_agotado, fechas_agotado_seg, vendedor, cuenta) 
SELECT cod_productos, cod_productos_var, nombre_productos, cod_marcas, cod_proveedores, cod_nomenclatura, cod_tipo, cod_lineas, 
cod_ccosto, cod_paises, '$numero_factura', unidades, cajas, unidades_total, unidades_faltantes, unidades_vendidas, und_orig, precio_compra, 
precio_costo, precio_venta, vlr_total_compra, vlr_total_venta, cod_interno, tope_minimo, utilidad, total_utilidad, total_mercancia, total_venta, 
gasto, descuento, tipo_pago, ip, codificacion, url, cod_original, detalles, descripcion, dto1, dto2, iva, iva_v, fechas_dia, fechas_mes, '$fechas_anyo', 
fechas_hora, fechas_vencimiento, porcentaje_vendedor, fechas_vencimiento_seg, fechas_agotado, fechas_agotado_seg, vendedor, '$cuenta' 
FROM productos");
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());

echo "<br><br><center><font color='yellow' size= '+2'>LA OPERACION HA SIDO EXITOSA</font></center>";
echo "<META HTTP-EQUIV='REFRESH' CONTENT='2; inventario_productos_copia_estado_viejo.php'>";

} if (isset($_POST['no'])) {
echo "<META HTTP-EQUIV='REFRESH' CONTENT='0.1; inventario_productos_copia_estado_viejo.php'>";
}
?>
</body>
</html>