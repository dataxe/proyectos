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
$tipo_pago = $_POST['tipo_pago'];
$cod_factura = $_POST['numero_factura'];
$cod_proveedores = $_POST['cod_proveedores'];
$valor_bruto = $_POST['valor_bruto'];
$descuento = $_POST['descuento_factura'];
$valor_neto = $_POST['valor_neto'];
$valor_iva = addslashes($_POST['valor_iva']);
$total = addslashes($_POST['total']);
$vendedor = $cuenta_actual;
$ip = $_SERVER['REMOTE_ADDR'];
$unidades_vendidas = "0";

$fecha_anyo = $_POST['fecha'];

$dato_fecha = explode('/', $fecha_anyo);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];

$fecha_dia = $anyo.'/'.$mes.'/'.$dia;
$fecha_mes = $mes.'/'.$anyo;
$fecha_hora = date("H:i:s");

// ------------------- FACTURAS INTRODUCIDAS POR CONTADO --------------------------------
if (isset($_POST['verificacion']) && ($_POST['tipo_pago'] == 'contado')) {

$agregar_reg_pedidos = "INSERT INTO pedidos (cod_productos, cajas, unidades, unidades_total, nombre_productos, cod_original, codificacion, unidades_vendidas, precio_compra, 
precio_costo, precio_venta, dto1, dto2, iva, iva_v, fecha, fecha_mes, fecha_anyo, fecha_hora, ip, cod_factura, cod_proveedores, tipo_pago, vendedor, vlr_total_compra, detalles) 
SELECT cod_productos, cajas, unidades, unidades_total, nombre_productos, cod_original, codificacion, unidades_vendidas, precio_compra, precio_costo, precio_venta, dto1, dto2, 
iva, iva_v, '$fecha_dia', '$fecha_mes', '$fecha_anyo', '$fecha_hora', '$ip', '$cod_factura', '$cod_proveedores', '$tipo_pago', '$vendedor', vlr_total_compra, detalles 
FROM pedidos_temporal WHERE vendedor = '$cuenta_actual'";
$resultado_pedidos = mysql_query($agregar_reg_pedidos, $conectar) or die(mysql_error());

/*
$agre_cuentas_facturas = "INSERT INTO cuentas_facturas (cod_factura, tipo_pago, cod_proveedores, valor_bruto, descuento, valor_neto, valor_iva, 
total, fecha_pago, fecha_dia)
VALUES ('$cod_factura','$tipo_pago','$cod_proveedores','$valor_bruto','$descuento','$valor_neto','$valor_iva','$total','$fecha','$fecha_dia')";
$resultado_cuentas_facturas = mysql_query($agre_cuentas_facturas, $conectar) or die(mysql_error());
*/
$borrar_sql = sprintf("DELETE FROM pedidos_temporal WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

echo "<center><font size='6' color='yellow'>LA FACTURA NO: ".$cod_factura." SE HA INTRODUCIDO EXITOSAMENTE</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; ../admin/cargar_pedido_temporal.php">';
}
// ------------------- FACTURAS INTRODUCIDAS POR CREDITO --------------------------------
if (isset($_POST['verificacion']) && ($_POST['tipo_pago'] == 'credito')) {

$agregar_reg_pedidos = "INSERT INTO pedidos (cod_productos, cajas, unidades, unidades_total, nombre_productos, cod_original, codificacion, unidades_vendidas, precio_compra, 
precio_costo, precio_venta, dto1, dto2, iva, iva_v, fecha, fecha_mes, fecha_anyo, fecha_hora, ip, cod_factura, cod_proveedores, tipo_pago, vendedor, vlr_total_compra, detalles) 
SELECT cod_productos, cajas, unidades, unidades_total, nombre_productos, cod_original, codificacion, unidades_vendidas, precio_compra, precio_costo, precio_venta, dto1, dto2, 
iva, iva_v, '$fecha_dia', '$fecha_mes', '$fecha_anyo', '$fecha_hora', '$ip', '$cod_factura', '$cod_proveedores', '$tipo_pago', '$vendedor', vlr_total_compra, detalles 
FROM pedidos_temporal WHERE vendedor = '$cuenta_actual'";
$resultado_pedidos = mysql_query($agregar_reg_pedidos, $conectar) or die(mysql_error());

/*
$agregar_reg_cuentas_pagar = "INSERT INTO cuentas_pagar (cod_factura, cod_proveedores, monto_deuda, subtotal, abonado, fecha_pago, fecha, fecha_dia)
VALUES ('$cod_factura','$cod_proveedores','$monto_deuda','$monto_deuda','$abonado','$fecha','$fecha','$fecha_dia')";
$resultado_cuentas_pagar = mysql_query($agregar_reg_cuentas_pagar, $conectar) or die(mysql_error());

$agre_cuentas_facturas = "INSERT INTO cuentas_facturas (cod_factura, tipo_pago, cod_proveedores, valor_bruto, descuento, valor_neto, valor_iva, 
total, fecha_pago, fecha_dia)
VALUES ('$cod_factura','$tipo_pago','$cod_proveedores','$valor_bruto','$descuento','$valor_neto','$valor_iva','$total','$fecha','$fecha_dia')";
$resultado_cuentas_facturas = mysql_query($agre_cuentas_facturas, $conectar) or die(mysql_error());
*/
$borrar_sql = sprintf("DELETE FROM pedidos_temporal WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

echo "<center><font size='6' color='yellow'>LA FACTURA NO: ".$cod_factura." SE HA INTRODUCIDO EXITOSAMENTE</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; ../admin/cargar_pedido_temporal.php">';
}
?>
</body>
</html>