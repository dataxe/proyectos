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
$fecha_anyo = addslashes($_POST['fecha']);
$fecha_pago = addslashes($_POST['fecha_pago']);
$total_datos = intval($_POST['total_datos']);

$tipo_pago = addslashes($_POST['tipo_pago']);
$cod_factura = intval($_POST['numero_factura']);
$cod_proveedores = intval($_POST['cod_proveedores']);
$valor_bruto = addslashes($_POST['valor_bruto']);
$descuento = addslashes($_POST['descuento_factura']);
$valor_neto = addslashes($_POST['valor_neto']);
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

$dato_fecha = explode('/', $fecha_anyo);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];

$fecha_invert = $anyo.'/'.$mes.'/'.$dia;
$fecha = strtotime($fecha_invert);
$fecha_mes = $mes.'/'.$anyo;
//$fecha_anyo = $fecha;
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

$sql_mconsulta = "SELECT * FROM cargar_factura_temporal WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal' ORDER BY cod_cargar_factura_temporal DESC";
$mconsulta = mysql_query($sql_mconsulta, $conectar) or die(mysql_error());
$datos_temp = mysql_fetch_assoc($mconsulta);

$cod_productos = $datos_temp['cod_productos'];
$nombre_productos = $datos_temp['nombre_productos'];
$unidades_vendidas = $datos_temp['unidades_vendidas'];
$precio_compra_con_descuento = $datos_temp['precio_compra_con_descuento'];
$descuento_prod2 = $datos_temp['descuento'];
$valor_iva = $datos_temp['valor_iva'];
$cod_original = $datos_temp['cod_original'];
$ptj_ganancia = $datos_temp['ptj_ganancia'];
$tope_min = $datos_temp['tope_min'];
//$fecha_mes = $datos_temp['fecha_mes'];
//$fecha_anyo = $datos_temp['fecha_anyo'];

$sqlr_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sqlr_consulta, $conectar) or die(mysql_error());
$datos_prod = mysql_fetch_assoc($modificar_consulta);

$cajas = $datos_temp['cajas'];
$unidades = $datos_temp['unidades'];
$unidades_total = $datos_temp['unidades_total'];
$tope_minimo = $datos_temp['tope_min'];
$codificacion = $datos_temp['codificacion'];
$unidades_faltantes_inv = $datos_prod['unidades_faltantes'];
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

$nombre_productos = $datos_temp['nombre_productos'];
$unidades_vendidas = $datos_temp['unidades_vendidas'];
$precio_compra_con_descuento = $datos_temp['precio_compra_con_descuento'];
$valor_iva = $datos_temp['valor_iva'];
$cod_original = $datos_temp['cod_original'];
$ptj_ganancia = $datos_temp['ptj_ganancia'];
//------------------------------------------ PARA ELIMINAR ALERTAS Q HALLAN ACTUALIZADO LOS PRODUCTOS ------------------------------------------//
/*
if ($unidades_faltantes > '0') {
$borrar_alerta  = sprintf("DELETE FROM notificacion_alerta WHERE cod_productos_var = '$cod_productos'", $cod_productos);
$Resultado2 = mysql_query($borrar_alerta , $conectar) or die(mysql_error());
} else {
}
*/
//------------------------------------------ PARA AGREGAR REGISTRO DE LOS PRODUCTOS CARGADOS ------------------------------------------//
// ------------------------------------------------------------------------------------------------------------//
$agregar_facturas_cargadas_inv = "INSERT INTO facturas_cargadas_inv (cod_productos, nombre_productos, unidades, cajas, unidades_total, 
unidades_vendidas, unidades_faltantes, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, 
precio_compra_con_descuento, detalles, cod_proveedores, tipo_pago, dto1, dto2, iva, iva_v, valor_iva, cod_factura, 
cod_original, codificacion, porcentaje_vendedor, ptj_ganancia, vendedor, fecha, fecha_mes, fecha_anyo, anyo, fecha_hora, 
fechas_vencimiento, fechas_vencimiento_seg, ip)
VALUES ('$cod_productos', '$nombre_productos', '$unidades', '$cajas', '$unidades_total', '$unidades_vendidas', '$unidades_faltantes_inv', 
'$precio_compra', '$precio_costo', '$precio_venta', '$vlr_total_venta', '$vlr_total_compra', '$precio_compra_con_descuento', 
'$detalles', '$cod_proveedores', '$tipo_pago', '$dto1', '$dto2', '$iva', '$iva_v', '$valor_iva', '$cod_factura', '$cod_original', 
'$codificacion', '$porcentaje_vendedor', '$ptj_ganancia', '$vendedor', '$fecha', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', 
'$fechas_vencimiento', '$fechas_vencimiento_seg', '$ip')";
$resultado_facturas_cargadas_inv = mysql_query($agregar_facturas_cargadas_inv, $conectar) or die(mysql_error());
//----------------------------- ACTUALIZAR INVENTARIO DE PRODUCTOS OPERACIONES -----------------------------//
// ------------------------------------------------------------------------------------------------------------//
$actualiza_productos = sprintf("UPDATE productos SET 
cajas = '$cajas',
unidades = '$unidades',
unidades_total = '$unidades_total',
tope_minimo = '$tope_minimo',
vlr_total_compra = '$vlr_total_compra',
vlr_total_venta = '$vlr_total_venta',
codificacion = '$codificacion',
unidades_faltantes = '$unidades_faltantes',
precio_costo = '$precio_costo',
precio_compra = '$precio_compra',
precio_venta = '$precio_venta',
dto1 = '$dto1',
dto2 = '$dto2',
iva = '$iva',
iva_v = '$iva_v',
fechas_dia = '$fecha_invert', 
fechas_mes = '$fecha_mes', 
fechas_anyo = '$fecha_anyo', 
fechas_hora = '$fecha_hora', 
ip = '$ip', 
numero_factura = '$cod_factura', 
cod_proveedores = '$cod_proveedores', 
tipo_pago = '$tipo_pago', 
vendedor = '$vendedor',
fechas_vencimiento = '$fechas_vencimiento',
fechas_vencimiento_seg = '$fechas_vencimiento_seg',
detalles = '$detalles',
descripcion = '$descripcion',
porcentaje_vendedor = '$porcentaje_vendedor'
WHERE cod_productos_var = '$cod_productos'");
$resultado_actualiza_productos = mysql_query($actualiza_productos, $conectar) or die(mysql_error());
//----------------------------------------------------------------------- -----------------------------------------------------------------------------------------------//
//----------------------------- INICIO ACTUALIZAR KARDEX VENTA - COMPRA E INVENTARIO -----------------------------//
//-------------------------------------------------------------------------------------------------------------------------------------//
$fecha_dmy = $fecha_anyo;
$fecha_seg_ymd = $fecha;
$fecha_time = time();

$sql_kardex = "SELECT cod_productos, fecha_mes FROM kardex_venta_compra_invent WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex = mysql_query($sql_kardex, $conectar) or die(mysql_error());
$datos_kardex = mysql_fetch_assoc($consulta_kardex);
$total_k = mysql_num_rows($consulta_kardex);
//-------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------INSERTAR NUEVO KARDEX PARA PRODUCTO-----------------------------------------------//
if ($total_k == 0) {
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
$sql_kardex_ventas = "SELECT SUM(unidades_vendidas) AS und_venta FROM ventas WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_ventas = mysql_query($sql_kardex_ventas, $conectar) or die(mysql_error());
$datos_kardex_ventas = mysql_fetch_assoc($consulta_kardex_ventas);
//--------------------------------------------------------------CALCULO KARDEX COMPRAS------------------------------------------------------------------------//
$sql_kardex_compras = "SELECT SUM(unidades_total) AS und_compra FROM facturas_cargadas_inv WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_compras = mysql_query($sql_kardex_compras, $conectar) or die(mysql_error());
$datos_kardex_compras = mysql_fetch_assoc($consulta_kardex_compras);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS------------------------------------------------------------------------//
$sql_kardex_transf = "SELECT SUM(unidades_total) AS und_transf FROM transferencias WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf = mysql_query($sql_kardex_transf, $conectar) or die(mysql_error());
$datos_kardex_transf = mysql_fetch_assoc($consulta_kardex_transf);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS ENTRDAS------------------------------------------------------------------------//
$sql_kardex_transf_ent = "SELECT SUM(unidades_total) AS und_transf_ent FROM transferencias_entrada WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf_ent = mysql_query($sql_kardex_transf_ent, $conectar) or die(mysql_error());
$datos_kardex_transf_ent = mysql_fetch_assoc($consulta_kardex_transf_ent);
//---------------------------------------------------------------CALCULO KARDEX INVENTARIO-----------------------------------------------------------------------//
$sql_kardex_invent = "SELECT unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta_kardex_invent = mysql_query($sql_kardex_invent, $conectar) or die(mysql_error());
$datos_kardex_invent = mysql_fetch_assoc($consulta_kardex_invent);
//---------------------------------------------------------------TOTALES CALCULOS KARDEX-----------------------------------------------------------------------//
$und_venta = $datos_kardex_ventas['und_venta'];
$und_compra = $datos_kardex_compras['und_compra'];
$und_transf = $datos_kardex_transf['und_transf'];
$und_transf_ent = $datos_kardex_transf_ent['und_transf_ent'];
$und_invent = $datos_kardex_invent['unidades_faltantes'];

$agregar_kardex = "INSERT INTO kardex_venta_compra_invent (cod_productos, nombre_productos, und_venta, und_compra, und_transf, und_transf_ent, und_invent, fecha_mes, fecha_dmy, anyo, fecha_seg_ymd, fecha_time)
VALUES ('$cod_productos', '$nombre_productos', '$und_venta', '$und_compra', '$und_transf', '$und_transf_ent', '$und_invent', '$fecha_mes', '$fecha_dmy', '$anyo', '$fecha_seg_ymd', '$fecha_time')";
$resultado_agregar_kardex = mysql_query($agregar_kardex, $conectar) or die(mysql_error());
//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------ACTUALIZAR REGISTRO SI YA EXISTE-----------------------------------------------//
} else {
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
$sql_kardex_ventas = "SELECT SUM(unidades_vendidas) AS und_venta FROM ventas WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_ventas = mysql_query($sql_kardex_ventas, $conectar) or die(mysql_error());
$datos_kardex_ventas = mysql_fetch_assoc($consulta_kardex_ventas);
//--------------------------------------------------------------CALCULO KARDEX COMPRAS------------------------------------------------------------------------//
$sql_kardex_compras = "SELECT SUM(unidades_total) AS und_compra FROM facturas_cargadas_inv WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_compras = mysql_query($sql_kardex_compras, $conectar) or die(mysql_error());
$datos_kardex_compras = mysql_fetch_assoc($consulta_kardex_compras);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS------------------------------------------------------------------------//
$sql_kardex_transf = "SELECT SUM(unidades_total) AS und_transf FROM transferencias WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf = mysql_query($sql_kardex_transf, $conectar) or die(mysql_error());
$datos_kardex_transf = mysql_fetch_assoc($consulta_kardex_transf);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS ENTRDAS------------------------------------------------------------------------//
$sql_kardex_transf_ent = "SELECT SUM(unidades_total) AS und_transf_ent FROM transferencias_entrada WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf_ent = mysql_query($sql_kardex_transf_ent, $conectar) or die(mysql_error());
$datos_kardex_transf_ent = mysql_fetch_assoc($consulta_kardex_transf_ent);
//---------------------------------------------------------------CALCULO KARDEX INVENTARIO-----------------------------------------------------------------------//
$sql_kardex_invent = "SELECT unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta_kardex_invent = mysql_query($sql_kardex_invent, $conectar) or die(mysql_error());
$datos_kardex_invent = mysql_fetch_assoc($consulta_kardex_invent);
//---------------------------------------------------------------TOTALES CALCULOS KARDEX-----------------------------------------------------------------------//
$und_venta = $datos_kardex_ventas['und_venta'];
$und_compra = $datos_kardex_compras['und_compra'];
$und_transf = $datos_kardex_transf['und_transf'];
$und_transf_ent = $datos_kardex_transf_ent['und_transf_ent'];
$und_invent = $datos_kardex_invent['unidades_faltantes'];
//---------------------------------------------------------------ACTUALIZACION REGISTRO KARDEX-----------------------------------------------------------------------//
$actualizar_kardex = sprintf("UPDATE kardex_venta_compra_invent SET und_venta = '$und_venta', und_compra = '$und_compra', und_transf = '$und_transf', 
und_transf_ent = '$und_transf_ent', und_invent = '$und_invent', fecha_dmy = '$fecha_dmy', anyo = '$anyo', fecha_seg_ymd = '$fecha_seg_ymd', fecha_time = '$fecha_time' 
WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'");
$resultado_actualiza_kardex = mysql_query($actualizar_kardex, $conectar) or die(mysql_error());
}
//require_once("../admin/kardex_venta_compra_invent_actualizar_valor.php"); 
//--------------------------------------------FIN DEL KARDEX-----------------------------------------------------------//
//----------------------------------------------------------------------- -----------------------------------------------------------------------------------------------//
} //--------------------------------------------FIN CICLO FOR QUE RECORRE LOS DATOS------------------------------- -----------------------------//

$agre_cuentas_facturas = "INSERT INTO cuentas_facturas (cod_factura, tipo_pago, cod_proveedores, valor_bruto, descuento, valor_neto, valor_iva, 
total, fecha_pago, fecha_invert)
VALUES ('$cod_factura','$tipo_pago','$cod_proveedores','$valor_bruto','$descuento','$valor_neto','$valor_iva','$total','$fecha_pago','$fecha_invert')";
$resultado_cuentas_facturas = mysql_query($agre_cuentas_facturas, $conectar) or die(mysql_error());

$borrar_sql = sprintf("DELETE FROM cargar_factura_temporal WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

echo "<br><br><center><font size='6' color='yellow'>LA FACTURA NO: ".$cod_factura." SE HA INTRODUCIDO EXITOSAMENTE</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; ../admin/cargar_factura_temporal.php">';
}
// ------------------- FACTURAS INTRODUCIDAS POR CREDITO --------------------------------
// ------------------------------------------------------------------------------------------------------------//
if (isset($_POST['verificacion']) && ($_POST['tipo_pago'] == 'credito')) {

for ($i=0; $i < $total_datos; $i++) {
$cod_cargar_factura_temporal = $_POST['cod_cargar_factura_temporal'][$i];

$sql_mconsulta = "SELECT * FROM cargar_factura_temporal WHERE cod_cargar_factura_temporal = '$cod_cargar_factura_temporal' ORDER BY cod_cargar_factura_temporal DESC";
$mconsulta = mysql_query($sql_mconsulta, $conectar) or die(mysql_error());
$datos_temp = mysql_fetch_assoc($mconsulta);

$cod_productos = $datos_temp['cod_productos'];
$nombre_productos = $datos_temp['nombre_productos'];
$unidades_vendidas = $datos_temp['unidades_vendidas'];
$precio_compra_con_descuento = $datos_temp['precio_compra_con_descuento'];
$descuento_prod2 = $datos_temp['descuento'];
$valor_iva = $datos_temp['valor_iva'];
$cod_original = $datos_temp['cod_original'];
$ptj_ganancia = $datos_temp['ptj_ganancia'];
$tope_min = $datos_temp['tope_min'];
//$fecha_mes = $datos_temp['fecha_mes'];
//$fecha_anyo = $datos_temp['fecha_anyo'];

$sqlr_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sqlr_consulta, $conectar) or die(mysql_error());
$datos_prod = mysql_fetch_assoc($modificar_consulta);

$cajas = $datos_temp['cajas'];
$unidades = $datos_temp['unidades'];
$unidades_total = $datos_temp['unidades_total'];
$tope_minimo = $datos_temp['tope_min'];
$codificacion = $datos_temp['codificacion'];
$unidades_faltantes_inv = $datos_prod['unidades_faltantes'];
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

$nombre_productos = $datos_temp['nombre_productos'];
$unidades_vendidas = $datos_temp['unidades_vendidas'];
$precio_compra_con_descuento = $datos_temp['precio_compra_con_descuento'];
$valor_iva = $datos_temp['valor_iva'];
$cod_original = $datos_temp['cod_original'];
$ptj_ganancia = $datos_temp['ptj_ganancia'];
//------------------------------------------ PARA ELIMINAR ALERTAS Q HALLAN ACTUALIZADO LOS PRODUCTOS ------------------------------------------//
/*
if ($unidades_faltantes > '0') {
$borrar_alerta  = sprintf("DELETE FROM notificacion_alerta WHERE cod_productos_var = '$cod_productos'", $cod_productos);
$Resultado2 = mysql_query($borrar_alerta , $conectar) or die(mysql_error());
} else {
}
*/
//------------------------------------------ PARA AGREGAR REGISTRO DE LOS PRODUCTOS CARGADOS ------------------------------------------//
// ------------------------------------------------------------------------------------------------------------//
$agregar_facturas_cargadas_inv = "INSERT INTO facturas_cargadas_inv (cod_productos, nombre_productos, unidades, cajas, unidades_total, 
unidades_vendidas, unidades_faltantes, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, 
precio_compra_con_descuento, detalles, cod_proveedores, tipo_pago, dto1, dto2, iva, iva_v, valor_iva, cod_factura, 
cod_original, codificacion, porcentaje_vendedor, ptj_ganancia, vendedor, fecha, fecha_mes, fecha_anyo, anyo, fecha_hora, 
fechas_vencimiento, fechas_vencimiento_seg, ip)
VALUES ('$cod_productos', '$nombre_productos', '$unidades', '$cajas', '$unidades_total', '$unidades_vendidas', '$unidades_faltantes_inv', 
'$precio_compra', '$precio_costo', '$precio_venta', '$vlr_total_venta', '$vlr_total_compra', '$precio_compra_con_descuento', '$detalles', 
'$cod_proveedores', '$tipo_pago', '$dto1', '$dto2', '$iva', '$iva_v', '$valor_iva', '$cod_factura', '$cod_original', 
'$codificacion', '$porcentaje_vendedor', '$ptj_ganancia', '$vendedor', '$fecha', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', 
'$fechas_vencimiento', '$fechas_vencimiento_seg', '$ip')";
$resultado_facturas_cargadas_inv = mysql_query($agregar_facturas_cargadas_inv, $conectar) or die(mysql_error());
//----------------------------- ACTUALIZAR INVENTARIO DE PRODUCTOS OPERACIONES -----------------------------//
//----------------------------- ACTUALIZAR INVENTARIO DE PRODUCTOS OPERACIONES -----------------------------//
$actualiza_productos = sprintf("UPDATE productos SET 
cajas = '$cajas',
unidades = '$unidades',
unidades_total = '$unidades_total',
tope_minimo = '$tope_minimo',
vlr_total_compra = '$vlr_total_compra',
vlr_total_venta = '$vlr_total_venta',
codificacion = '$codificacion',
unidades_faltantes = '$unidades_faltantes',
precio_costo = '$precio_costo',
precio_compra = '$precio_compra',
precio_venta = '$precio_venta',
dto1 = '$dto1',
dto2 = '$dto2',
iva = '$iva',
iva_v = '$iva_v',
fechas_dia = '$fecha_invert', 
fechas_mes = '$fecha_mes', 
fechas_anyo = '$fecha_anyo', 
fechas_hora = '$fecha_hora', 
ip = '$ip', 
numero_factura = '$cod_factura', 
cod_proveedores = '$cod_proveedores', 
tipo_pago = '$tipo_pago', 
vendedor = '$vendedor',
fechas_vencimiento = '$fechas_vencimiento',
fechas_vencimiento_seg = '$fechas_vencimiento_seg',
detalles = '$detalles',
descripcion = '$descripcion',
porcentaje_vendedor = '$porcentaje_vendedor'
WHERE cod_productos_var = '$cod_productos'");
$resultado_actualiza_productos = mysql_query($actualiza_productos, $conectar) or die(mysql_error());
//----------------------------------------------------------------------- -----------------------------------------------------------------------------------------------//
//----------------------------- INICIO ACTUALIZAR KARDEX VENTA - COMPRA E INVENTARIO -----------------------------//
//-------------------------------------------------------------------------------------------------------------------------------------//
$und_venta = $unidades_vendidas;
$und_compra = 0;
$und_invent = $unidades_faltantes;
//$fecha_mes = date("m/Y");
$fecha_dmy = $fecha_anyo;
$fecha_seg_ymd = $fecha;
$fecha_time = time();

$sql_kardex = "SELECT cod_productos, fecha_mes FROM kardex_venta_compra_invent WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex = mysql_query($sql_kardex, $conectar) or die(mysql_error());
$datos_kardex = mysql_fetch_assoc($consulta_kardex);
$total_k = mysql_num_rows($consulta_kardex);
//-------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------INSERTAR NUEVO KARDEX PARA PRODUCTO-----------------------------------------------//
if ($total_k == 0) {
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
$sql_kardex_ventas = "SELECT SUM(unidades_vendidas) AS und_venta FROM ventas WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_ventas = mysql_query($sql_kardex_ventas, $conectar) or die(mysql_error());
$datos_kardex_ventas = mysql_fetch_assoc($consulta_kardex_ventas);
//--------------------------------------------------------------CALCULO KARDEX COMPRAS------------------------------------------------------------------------//
$sql_kardex_compras = "SELECT SUM(unidades_total) AS und_compra FROM facturas_cargadas_inv WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_compras = mysql_query($sql_kardex_compras, $conectar) or die(mysql_error());
$datos_kardex_compras = mysql_fetch_assoc($consulta_kardex_compras);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS------------------------------------------------------------------------//
$sql_kardex_transf = "SELECT SUM(unidades_total) AS und_transf FROM transferencias WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf = mysql_query($sql_kardex_transf, $conectar) or die(mysql_error());
$datos_kardex_transf = mysql_fetch_assoc($consulta_kardex_transf);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS ENTRDAS------------------------------------------------------------------------//
$sql_kardex_transf_ent = "SELECT SUM(unidades_total) AS und_transf_ent FROM transferencias_entrada WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf_ent = mysql_query($sql_kardex_transf_ent, $conectar) or die(mysql_error());
$datos_kardex_transf_ent = mysql_fetch_assoc($consulta_kardex_transf_ent);
//---------------------------------------------------------------CALCULO KARDEX INVENTARIO-----------------------------------------------------------------------//
$sql_kardex_invent = "SELECT unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta_kardex_invent = mysql_query($sql_kardex_invent, $conectar) or die(mysql_error());
$datos_kardex_invent = mysql_fetch_assoc($consulta_kardex_invent);
//---------------------------------------------------------------TOTALES CALCULOS KARDEX-----------------------------------------------------------------------//
$und_venta = $datos_kardex_ventas['und_venta'];
$und_compra = $datos_kardex_compras['und_compra'];
$und_transf = $datos_kardex_transf['und_transf'];
$und_transf_ent = $datos_kardex_transf_ent['und_transf_ent'];
$und_invent = $datos_kardex_invent['unidades_faltantes'];

$agregar_kardex = "INSERT INTO kardex_venta_compra_invent (cod_productos, nombre_productos, und_venta, und_compra, und_transf, und_transf_ent, und_invent, fecha_mes, fecha_dmy, anyo, fecha_seg_ymd, fecha_time)
VALUES ('$cod_productos', '$nombre_productos', '$und_venta', '$und_compra', '$und_transf', '$und_transf_ent', '$und_invent', '$fecha_mes', '$fecha_dmy', '$anyo', '$fecha_seg_ymd', '$fecha_time')";
$resultado_agregar_kardex = mysql_query($agregar_kardex, $conectar) or die(mysql_error());
//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------ACTUALIZAR REGISTRO SI YA EXISTE-----------------------------------------------//
} else {
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
$sql_kardex_ventas = "SELECT SUM(unidades_vendidas) AS und_venta FROM ventas WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_ventas = mysql_query($sql_kardex_ventas, $conectar) or die(mysql_error());
$datos_kardex_ventas = mysql_fetch_assoc($consulta_kardex_ventas);
//--------------------------------------------------------------CALCULO KARDEX COMPRAS------------------------------------------------------------------------//
$sql_kardex_compras = "SELECT SUM(unidades_total) AS und_compra FROM facturas_cargadas_inv WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_compras = mysql_query($sql_kardex_compras, $conectar) or die(mysql_error());
$datos_kardex_compras = mysql_fetch_assoc($consulta_kardex_compras);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS------------------------------------------------------------------------//
$sql_kardex_transf = "SELECT SUM(unidades_total) AS und_transf FROM transferencias WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf = mysql_query($sql_kardex_transf, $conectar) or die(mysql_error());
$datos_kardex_transf = mysql_fetch_assoc($consulta_kardex_transf);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS ENTRDAS------------------------------------------------------------------------//
$sql_kardex_transf_ent = "SELECT SUM(unidades_total) AS und_transf_ent FROM transferencias_entrada WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf_ent = mysql_query($sql_kardex_transf_ent, $conectar) or die(mysql_error());
$datos_kardex_transf_ent = mysql_fetch_assoc($consulta_kardex_transf_ent);
//---------------------------------------------------------------CALCULO KARDEX INVENTARIO-----------------------------------------------------------------------//
$sql_kardex_invent = "SELECT unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta_kardex_invent = mysql_query($sql_kardex_invent, $conectar) or die(mysql_error());
$datos_kardex_invent = mysql_fetch_assoc($consulta_kardex_invent);
//---------------------------------------------------------------TOTALES CALCULOS KARDEX-----------------------------------------------------------------------//
$und_venta = $datos_kardex_ventas['und_venta'];
$und_compra = $datos_kardex_compras['und_compra'];
$und_transf = $datos_kardex_transf['und_transf'];
$und_transf_ent = $datos_kardex_transf_ent['und_transf_ent'];
$und_invent = $datos_kardex_invent['unidades_faltantes'];
//---------------------------------------------------------------ACTUALIZACION REGISTRO KARDEX-----------------------------------------------------------------------//
$actualizar_kardex = sprintf("UPDATE kardex_venta_compra_invent SET und_venta = '$und_venta', und_compra = '$und_compra', und_transf = '$und_transf', 
und_transf_ent = '$und_transf_ent', und_invent = '$und_invent', fecha_dmy = '$fecha_dmy', anyo = '$anyo', fecha_seg_ymd = '$fecha_seg_ymd', fecha_time = '$fecha_time' 
WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'");
$resultado_actualiza_kardex = mysql_query($actualizar_kardex, $conectar) or die(mysql_error());
}
//require_once("../admin/kardex_venta_compra_invent_actualizar_valor.php"); 
//--------------------------------------------FIN DEL KARDEX-----------------------------------------------------------//
//----------------------------------------------------------------------- -----------------------------------------------------------------------------------------------//
} //--------------------------------------------FIN CICLO FOR QUE RECORRE LOS DATOS------------------------------- -----------------------------//
//------------------------------------------ PARA AGREGAR ALERTA DE CUENTAS POR PAGAR ------------------------------------------//
// ------------------------------------------------------------------------------------------------------------//
$nombre_notificacion_alerta = 'HAY CUENTAS POR PAGAR A PUNTO DE VENCER';
$tipo_notificacion_alerta = 'white';
$agregar_notificacion_alerta = "INSERT INTO notificacion_alerta (nombre_notificacion_alerta, nombre_productos, cod_factura, nombre_proveedores, tipo_notificacion_alerta, fecha_dia, fecha_invert, fecha_hora, cuenta)
VALUES ('$nombre_notificacion_alerta', '$fecha_pago', '$cod_factura', '$nombre_proveedores', '$tipo_notificacion_alerta', '$fecha_pago', '$fecha_seg', '$fecha_hora', '$cuenta_actual')";
$resultado_notificacion_alerta = mysql_query($agregar_notificacion_alerta, $conectar) or die(mysql_error());

//------------------------------------------ PARA AGREGAR UN REGISTRO DE UNA CUENTA POR COBRAR ------------------------------------------//
//------------------------------------------ PARA AGREGAR UN REGISTRO DE UNA CUENTA POR COBRAR ------------------------------------------//
$sql_cuentas_pagar = "SELECT cod_factura FROM cuentas_pagar WHERE cod_factura = '$cod_factura'";
$consulta_cod_factura = mysql_query($sql_cuentas_pagar, $conectar) or die(mysql_error());
$total_facturas = mysql_num_rows($consulta_cod_factura);

$suma_total_deuda = "SELECT Sum(precio_compra_con_descuento) As monto_deuda FROM facturas_cargadas_inv WHERE cod_factura = '$cod_factura'";
$consulta_total_deuda = mysql_query($suma_total_deuda, $conectar) or die(mysql_error());
$datos_deuda = mysql_fetch_assoc($consulta_total_deuda);

$monto_deuda = $datos_deuda['monto_deuda'];
//-------------------------------------------------------------------------------------------------//
//-------------------------------------------------------------------------------------------------//
if ($total_facturas == 0) {

$agregar_reg_cuentas_pagar = "INSERT INTO cuentas_pagar (cod_factura, cod_proveedores, monto_deuda, subtotal, abonado, fecha_pago, fecha, fecha_invert, fecha_seg)
VALUES ('$cod_factura', '$cod_proveedores', '$monto_deuda', '$monto_deuda', '$abonado', '$fecha_pago', '$fecha_pago_seg', '$fecha_invert_compra_dmy', '$fecha_seg_compra')";
$resultado_cuentas_pagar = mysql_query($agregar_reg_cuentas_pagar, $conectar) or die(mysql_error());
} else {
//-------------------------------------------------------------------------------------------------//
//-------------------------------------------------------------------------------------------------//
$agregar_regis = sprintf("UPDATE cuentas_pagar SET monto_deuda = '$monto_deuda' WHERE cod_factura = '$cod_factura'");
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());
}

//------------------------------------------ PARA AGREGAR UN REGISTRO A CUENTAS FACTURAS ------------------------------------------//
// ------------------------------------------------------------------------------------------------------------//
$agre_cuentas_facturas = "INSERT INTO cuentas_facturas (cod_factura, tipo_pago, cod_proveedores, valor_bruto, descuento, valor_neto, valor_iva, 
total, fecha_pago, fecha_invert)
VALUES ('$cod_factura', '$tipo_pago', '$cod_proveedores', '$valor_bruto', '$descuento', '$valor_neto', '$valor_iva', '$total', '$fecha_pago', '$fecha_invert')";
$resultado_cuentas_facturas = mysql_query($agre_cuentas_facturas, $conectar) or die(mysql_error());

//------------------------------------------ ELIMINAR LOS REGISTRO Q ESTABAN CARGADOS TEMPORALMENTE ------------------------------------------//
// ------------------------------------------------------------------------------------------------------------//
$borrar_sql = sprintf("DELETE FROM cargar_factura_temporal WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

echo "<br><br><center><font size='6' color='yellow'>LA FACTURA NO: ".$cod_factura." SE HA INTRODUCIDO EXITOSAMENTE</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; ../admin/cargar_factura_temporal.php">';
}
?>
</body>
</html>