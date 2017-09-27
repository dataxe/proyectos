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
$fecha = $_POST['fecha'];

$tipo_pago = $_POST['tipo_pago'];
//$cod_factura = $_POST['cod_factura'];
$cod_proveedores = $_POST['cod_proveedores'];
$valor_bruto = $_POST['valor_bruto'];
$descuento = $_POST['descuento_factura'];
$valor_neto = $_POST['valor_neto'];
$valor_iva = addslashes($_POST['valor_iva']);
$total = addslashes($_POST['total']);
$vendedor = $cuenta_actual;
$ip = $_SERVER['REMOTE_ADDR'];

$unidades_vendidas = "0";
$abonado = "0";

$dato_fecha = explode('/', $fecha);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];

$fecha_invert = $anyo.'/'.$mes.'/'.$dia;
$fechas_mes = $mes.'/'.$anyo;
$fechas_anyo = $fecha;
$fechas_dia = $fecha_invert;
$fechas_hora = date("H:i:s");

$sql_cod_factura_exportacion = "SELECT MAX(cod_factura) AS cod_factura FROM exportacion";
$consulta_cod_factura = mysql_query($sql_cod_factura_exportacion, $conectar) or die(mysql_error());
$cod_factura_exportacion = mysql_fetch_assoc($consulta_cod_factura);

$cod_factura = $cod_factura_exportacion['cod_factura'] + 1;

// ------------------- FACTURAS INTRODUCIDAS POR CONTADO --------------------------------
if (isset($_POST['verificacion'])) {

$actualizar_sql1 = "INSERT INTO exportacion (cod_productos, cod_factura, nombre_productos, cod_original, codificacion, cajas, unidades, 
unidades_total, unidades_vendidas, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, detalles, porcentaje_vendedor, 
descuento, dto1, dto2, iva, iva_v, ptj_ganancia, valor_iva, tope_min, precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, 
fecha_hora, fechas_vencimiento, fechas_vencimiento_seg) SELECT cod_productos, '$cod_factura', nombre_productos, cod_original, codificacion, cajas, 
unidades, unidades_total, unidades_vendidas, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, detalles, 
porcentaje_vendedor, descuento, dto1, dto2, iva, iva_v, ptj_ganancia, valor_iva, tope_min, precio_compra_con_descuento, vendedor, ip, fecha, 
fecha_mes, fecha_anyo, fecha_hora, fechas_vencimiento, fechas_vencimiento_seg FROM exportacion_temporal WHERE vendedor = '$cuenta_actual'";
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());


$agre_cuentas_facturas = "INSERT INTO info_exportacion (cod_factura, vendedor, tipo_pago, cod_proveedores, valor_bruto, descuento, valor_neto, valor_iva, total, fecha, fecha_invert, hora)
VALUES ('$cod_factura', '$cuenta_actual', '$tipo_pago', '$cod_proveedores', '$valor_bruto', '$descuento', '$valor_neto', '$valor_iva', '$total', '$fecha', '$fecha_invert', '$hora')";
$resultado_cuentas_facturas = mysql_query($agre_cuentas_facturas, $conectar) or die(mysql_error());


$borrar_sql = sprintf("DELETE FROM exportacion_temporal WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

echo "<center><font size='6' color='yellow'>LA FACTURA SE HA INTRODUCIDO EXITOSAMENTE</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; ../admin/cargar_exportacion_temporal.php">';
}
?>
</body>
</html>