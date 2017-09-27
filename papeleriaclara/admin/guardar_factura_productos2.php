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
date_default_timezone_set("America/Bogota");
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
$fecha_pago = $_POST['fecha_pago'];
$total_datos = intval($_POST['total_datos']);

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
$abonado = "0";

$sql_proveedores = "SELECT nombre_proveedores FROM proveedores WHERE cod_proveedores = '$cod_proveedores'";
$mconsulta_proveedores = mysql_query($sql_proveedores, $conectar) or die(mysql_error());
$datos_proveedores = mysql_fetch_assoc($mconsulta_proveedores);

$nombre_proveedores = $datos_proveedores['nombre_proveedores'];

$dato_fecha = explode('/', $fecha);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];

$fecha_invert = $anyo.'/'.$mes.'/'.$dia;
$fechas_mes = $mes.'/'.$anyo;
$fechas_anyo = $fecha;
$fechas_dia = $fecha_invert;
$fecha_hora = date("H:i:s");

$dato_fecha_pago = explode('/', $fecha_pago);
$dia_pago = $dato_fecha_pago[0];
$mes_pago = $dato_fecha_pago[1];
$anyo_pago = $dato_fecha_pago[2];
$fecha_invert_pago = $anyo_pago.'/'.$mes_pago.'/'.$dia_pago;
$fecha_seg = strtotime($fecha_invert_pago);

$fecha_pago_seg = strtotime($fecha_invert_pago);
$fecha_invert_compra_dmy = $fecha_anyo;
$fecha_seg_compra = strtotime($fecha_invert);
//----------------------------------------------------------------------- -----------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------- -----------------------------------------------------------------------------------------------//
// ------------------- FACTURAS INTRODUCIDAS POR CONTADO --------------------------------
if (isset($_POST['verificacion']) && ($_POST['tipo_pago'] == 'contado')) {

for ($i=0; $i < $total_datos; $i++) { 
$cod_cargar_factura_temporal = $_POST['cod_cargar_factura_temporal'][$i];

$sql_mconsulta = "SELECT * FROM productos2 WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'";
$mconsulta = mysql_query($sql_mconsulta, $conectar) or die(mysql_error());
$datos_temp = mysql_fetch_assoc($mconsulta);

$cod_productos = $datos_temp['cod_productos'];

$sqlr_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sqlr_consulta, $conectar) or die(mysql_error());
$datos_prod = mysql_fetch_assoc($modificar_consulta);

$cajas = $datos_temp['cajas'];
$unidades = $datos_temp['unidades'];
$unidades_total = $datos_temp['unidades_total'];
$tope_minimo = $datos_temp['tope_min'];
$codificacion = $datos_temp['codificacion'];
$unidades_faltantes = $datos_prod['unidades_faltantes'] + $datos_temp['unidades_total'];
$precio_costo = $datos_temp['precio_costo'];
$precio_compra = $datos_temp['precio_compra'];
$precio_venta = $datos_temp['precio_venta'];
$vlr_total_compra = $precio_compra;
$vlr_total_venta = $datos_temp['vlr_total_venta'];
$dto1 = $datos_temp['dto1'];
$dto2 = $datos_temp['dto2'];
$iva = $datos_temp['iva'];
$iva_v = $datos_temp['iva_v'];
$fechas_vencimiento = $datos_temp['fechas_vencimiento'];
$fechas_vencimiento_seg = $datos_temp['fechas_vencimiento_seg'];
$detalles = $datos_temp['detalles'];
$porcentaje_vendedor = $datos_temp['porcentaje_vendedor'];
$descripcion = $datos_temp['fecha_mes'];

//------------------------------------------ PARA ELIMINAR ALERTAS Q HALLAN ACTUALIZADO LOS PRODUCTOS ------------------------------------------//
/*
if ($unidades_faltantes > '0') {
$borrar_alerta  = sprintf("DELETE FROM notificacion_alerta WHERE cod_productos_var = '$cod_productos'", $cod_productos);
$Resultado2 = mysql_query($borrar_alerta , $conectar) or die(mysql_error());
} else {
}
*/
//----------------------------- ACTUALIZAR INVENTARIO DE PRODUCTOS OPERACIONES -----------------------------//
$actualiza_productos = sprintf("UPDATE productos SET productos.cajas = '$cajas',
productos.unidades = '$unidades',
productos.unidades_total = '$unidades_total',
productos.tope_minimo = '$tope_minimo',
productos.vlr_total_compra = '$vlr_total_compra',
productos.vlr_total_venta = '$vlr_total_venta',
productos.codificacion = '$codificacion',
productos.unidades_faltantes = '$unidades_faltantes',
productos.precio_costo = '$precio_costo',
productos.precio_compra = '$precio_compra',
productos.precio_venta = '$precio_venta',
productos.dto1 = '$dto1',
productos.dto2 = '$dto2',
productos.iva = '$iva',
productos.iva_v = '$iva_v',
productos.fechas_dia = '$fecha_invert', 
productos.fechas_mes = '$fechas_mes', 
productos.fechas_anyo = '$fecha', 
productos.fechas_hora = '$fechas_hora', 
productos.ip = '$ip', 
productos.numero_factura = '$cod_factura', 
productos.cod_proveedores = '$cod_proveedores', 
productos.tipo_pago = '$tipo_pago', 
productos.vendedor = '$cuenta_actual',
productos.fechas_vencimiento = '$fechas_vencimiento',
productos.fechas_vencimiento_seg = '$fechas_vencimiento_seg',
productos.detalles = '$detalles',
productos.descripcion = '$descripcion',
productos.porcentaje_vendedor = '$porcentaje_vendedor'
WHERE productos.cod_productos_var = '$cod_productos'");
$resultado_actualiza_productos = mysql_query($actualiza_productos, $conectar) or die(mysql_error());
}
$agregar_facturas_cargadas_inv = "INSERT INTO facturas_cargadas_inv (cod_productos, nombre_productos, unidades, cajas, unidades_total, unidades_vendidas, 
precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, detalles, cod_proveedores, tipo_pago, descuento, dto1, dto2, iva, 
iva_v, valor_iva, cod_factura, cod_original, codificacion, porcentaje_vendedor, ptj_ganancia, tope_min, vendedor, fecha, fecha_mes, fecha_anyo, 
fecha_hora, fechas_vencimiento, fechas_vencimiento_seg, ip) 
SELECT cod_productos, nombre_productos, unidades, cajas, unidades_total, unidades_vendidas, precio_compra, precio_costo, precio_venta, 
vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, detalles, '$cod_proveedores', '$tipo_pago', descuento, dto1, dto2, iva, iva_v, valor_iva, '$cod_factura', cod_original, 
codificacion, porcentaje_vendedor, ptj_ganancia, tope_min, vendedor, '$fecha_invert', '$fechas_mes', '$fechas_anyo', '$fechas_hora', fechas_vencimiento, 
fechas_vencimiento_seg, ip FROM productos2 WHERE cod_factura = '$cod_factura'";
$resultado_ventas = mysql_query($agregar_facturas_cargadas_inv, $conectar) or die(mysql_error());

$agre_cuentas_facturas = "INSERT INTO cuentas_facturas (cod_factura, tipo_pago, cod_proveedores, valor_bruto, descuento, valor_neto, valor_iva, 
total, fecha_pago, fecha_invert)
VALUES ('$cod_factura','$tipo_pago','$cod_proveedores','$valor_bruto','$descuento','$valor_neto','$valor_iva','$total','$fecha','$fecha_invert')";
$resultado_cuentas_facturas = mysql_query($agre_cuentas_facturas, $conectar) or die(mysql_error());

$borrar_sql = sprintf("DELETE FROM cuentas_facturas2 WHERE cod_factura = '$cod_factura'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

$borrar_sql1 = sprintf("DELETE FROM productos2 WHERE cod_factura = '$cod_factura'");
$Result2 = mysql_query($borrar_sql1, $conectar) or die(mysql_error());

echo "<center><font size='6' color='yellow'>LA FACTURA NO: ".$cod_factura." SE HA INTRODUCIDO EXITOSAMENTE</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; ../admin/busq_facturas_compra_vendedor.php">';
}
// ------------------- FACTURAS INTRODUCIDAS POR CREDITO --------------------------------
if (isset($_POST['verificacion']) && ($_POST['tipo_pago'] == 'credito')) {

for ($i=0; $i < $total_datos; $i++) { 
$cod_cargar_factura_temporal = $_POST['cod_cargar_factura_temporal'][$i];

$sql_mconsulta = "SELECT * FROM productos2 WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal'";
$mconsulta = mysql_query($sql_mconsulta, $conectar) or die(mysql_error());
$datos_temp = mysql_fetch_assoc($mconsulta);

$cod_productos = $datos_temp['cod_productos'];

$sqlr_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sqlr_consulta, $conectar) or die(mysql_error());
$datos_prod = mysql_fetch_assoc($modificar_consulta);

$cajas = $datos_temp['cajas'];
$unidades = $datos_temp['unidades'];
$unidades_total = $datos_temp['unidades_total'];
$tope_minimo = $datos_temp['tope_min'];
$codificacion = $datos_temp['codificacion'];
$unidades_faltantes = $datos_prod['unidades_faltantes'] + $datos_temp['unidades_total'];
$precio_costo = $datos_temp['precio_costo'];
$precio_compra = $datos_temp['precio_compra'];
$precio_venta = $datos_temp['precio_venta'];
$vlr_total_compra = $precio_compra;
$vlr_total_venta = $datos_temp['vlr_total_venta'];
$dto1 = $datos_temp['dto1'];
$dto2 = $datos_temp['dto2'];
$iva = $datos_temp['iva'];
$iva_v = $datos_temp['iva_v'];
$fechas_vencimiento = $datos_temp['fechas_vencimiento'];
$fechas_vencimiento_seg = $datos_temp['fechas_vencimiento_seg'];
$detalles = $datos_temp['detalles'];
$porcentaje_vendedor = $datos_temp['porcentaje_vendedor'];
$descripcion = $datos_temp['fecha_mes'];

//------------------------------------------ PARA ELIMINAR ALERTAS Q HALLAN ACTUALIZADO LOS PRODUCTOS ------------------------------------------//
/*
if ($unidades_faltantes > '0') {
$borrar_alerta  = sprintf("DELETE FROM notificacion_alerta WHERE cod_productos_var = '$cod_productos'", $cod_productos);
$Resultado2 = mysql_query($borrar_alerta , $conectar) or die(mysql_error());
} else {
}
*/
//----------------------------- ACTUALIZAR INVENTARIO DE PRODUCTOS OPERACIONES -----------------------------//
$actualiza_productos = sprintf("UPDATE productos SET productos.cajas = '$cajas',
productos.unidades = '$unidades',
productos.unidades_total = '$unidades_total',
productos.tope_minimo = '$tope_minimo',
productos.vlr_total_compra = '$vlr_total_compra',
productos.vlr_total_venta = '$vlr_total_venta',
productos.codificacion = '$codificacion',
productos.unidades_faltantes = '$unidades_faltantes',
productos.precio_costo = '$precio_costo',
productos.precio_compra = '$precio_compra',
productos.precio_venta = '$precio_venta',
productos.dto1 = '$dto1',
productos.dto2 = '$dto2',
productos.iva = '$iva',
productos.iva_v = '$iva_v',
productos.fechas_dia = '$fecha_invert', 
productos.fechas_mes = '$fechas_mes', 
productos.fechas_anyo = '$fecha', 
productos.fechas_hora = '$fechas_hora', 
productos.ip = '$ip', 
productos.numero_factura = '$cod_factura', 
productos.cod_proveedores = '$cod_proveedores', 
productos.tipo_pago = '$tipo_pago', 
productos.vendedor = '$cuenta_actual',
productos.fechas_vencimiento = '$fechas_vencimiento',
productos.fechas_vencimiento_seg = '$fechas_vencimiento_seg',
productos.detalles = '$detalles',
productos.descripcion = '$descripcion',
productos.porcentaje_vendedor = '$porcentaje_vendedor'
WHERE productos.cod_productos_var = '$cod_productos'");
$resultado_actualiza_productos = mysql_query($actualiza_productos, $conectar) or die(mysql_error());
}
//------------------------------------------ PARA AGREGAR ALERTA DE CUENTAS POR PAGAR ------------------------------------------//
$nombre_notificacion_alerta = 'HAY CUENTAS POR PAGAR A PUNTO DE VENCER';
$tipo_notificacion_alerta = 'white';
$agregar_notificacion_alerta = "INSERT INTO notificacion_alerta (nombre_notificacion_alerta, nombre_productos, cod_factura, nombre_proveedores, tipo_notificacion_alerta, fecha_dia, fecha_invert, fecha_hora, cuenta)
VALUES ('$nombre_notificacion_alerta', '$fecha_pago', '$cod_factura', '$nombre_proveedores', '$tipo_notificacion_alerta', '$fecha_pago', '$fecha_seg', '$fecha_hora', '$cuenta_actual')";
$resultado_notificacion_alerta = mysql_query($agregar_notificacion_alerta, $conectar) or die(mysql_error());

//------------------------------------------ PARA AGREGAR REGISTRO DE LOS PRODUCTOS CARGADOS ------------------------------------------//
$agregar_facturas_cargadas_inv = "INSERT INTO facturas_cargadas_inv (cod_productos, nombre_productos, unidades, cajas, unidades_total, unidades_vendidas, 
precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, detalles, cod_proveedores, tipo_pago, descuento, dto1, dto2, iva, 
iva_v, valor_iva, cod_factura, cod_original, codificacion, porcentaje_vendedor, ptj_ganancia, tope_min, vendedor, fecha, fecha_mes, fecha_anyo, 
fecha_hora, fechas_vencimiento, fechas_vencimiento_seg, ip) 
SELECT cod_productos, nombre_productos, unidades, cajas, unidades_total, unidades_vendidas, precio_compra, precio_costo, precio_venta, 
vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, detalles, '$cod_proveedores', '$tipo_pago', descuento, dto1, dto2, iva, iva_v, valor_iva, '$cod_factura', cod_original, 
codificacion, porcentaje_vendedor, ptj_ganancia, tope_min, vendedor, '$fecha_invert', '$fechas_mes', '$fechas_anyo', '$fecha_hora', fechas_vencimiento, 
fechas_vencimiento_seg, ip FROM productos2 WHERE cod_factura = '$cod_factura'";
$resultado_ventas = mysql_query($agregar_facturas_cargadas_inv, $conectar) or die(mysql_error());

//------------------------------------------ PARA AGREGAR UN REGISTRO DE UNA CUENTA POR COBRAR ------------------------------------------//
// ------------------------------------------------------------------------------------------------------------//
$agregar_reg_cuentas_pagar = "INSERT INTO cuentas_pagar (cod_factura, cod_proveedores, monto_deuda, subtotal, abonado, fecha_pago, fecha, fecha_invert, fecha_seg)
VALUES ('$cod_factura', '$cod_proveedores', '$total', '$total', '$abonado', '$fecha_pago', '$fecha_pago_seg', '$fecha_invert_compra_dmy', '$fecha_seg_compra')";
$resultado_cuentas_pagar = mysql_query($agregar_reg_cuentas_pagar, $conectar) or die(mysql_error());

//------------------------------------------ PARA AGREGAR UN REGISTRO A CUENTAS FACTURAS ------------------------------------------//
$agre_cuentas_facturas = "INSERT INTO cuentas_facturas (cod_factura, tipo_pago, cod_proveedores, valor_bruto, descuento, valor_neto, valor_iva, 
total, fecha_pago, fecha_invert)
VALUES ('$cod_factura', '$tipo_pago', '$cod_proveedores', '$valor_bruto', '$descuento', '$valor_neto', '$valor_iva', '$total', '$fecha', '$fecha_invert')";
$resultado_cuentas_facturas = mysql_query($agre_cuentas_facturas, $conectar) or die(mysql_error());

//------------------------------------------ ELIMINAR LOS REGISTRO Q ESTABAN CARGADOS TEMPORALMENTE ------------------------------------------//
$borrar_sqls = sprintf("DELETE FROM cuentas_facturas2 WHERE cod_factura = '$cod_factura'");
$Result1s = mysql_query($borrar_sqls, $conectar) or die(mysql_error());

echo "<center><font size='6' color='yellow'>LA FACTURA NO: ".$cod_factura." SE HA INTRODUCIDO EXITOSAMENTE</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; ../admin/busq_facturas_compra_vendedor.php">';
}
?>
</body>
</html>