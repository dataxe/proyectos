<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
date_default_timezone_set("America/Bogota");
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar);
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
date_default_timezone_set("America/Bogota");

$valor_intro = addslashes($_GET['valor']);
$cod_ventas = intval($_GET['id']);
$campo = addslashes($_GET['campo']);

$sql_modificar_consulta = "SELECT * FROM ventas WHERE cod_ventas = '$cod_ventas'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$unidades_actuales = $datos['unidades_vendidas'];
$und_vend_orig = $datos['unidades_vendidas'];
$unidades_devueltas = $unidades_actuales - $valor_intro;
$cod_productos =  $datos['cod_productos'];
$cod_productos_var =  $datos['cod_productos'];

$fecha_devolucion = date("d/m/Y");
$hora_devolucion = date("H:i:s");

$sql = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$data = mysql_fetch_assoc($consulta);

$unidades_faltantes = $data['unidades_faltantes'] + $unidades_devueltas;
$unidades_vendidas = $data['unidades_vendidas'] - $unidades_devueltas;

$vlr_total_venta = $datos['precio_venta'] * $valor_intro;
$vlr_total_compra = $datos['precio_compra'] * $valor_intro;
$total_mercancia = $vlr_total_compra;
$total_venta = $vlr_total_venta;

$cod_factura = $datos['cod_factura'];
$cod_clientes = $datos['cod_clientes'];
$fecha = $datos['fecha'];
$tipo_pago = $datos['tipo_pago'];
$cod_uniq_credito = $datos['cod_uniq_credito'];

$comentario = $datos['comentario'];
$origen_operacion = 'ventas';
$fecha_orig = date("d/m/Y");
$fecha = strtotime(date("Y/m/d"));
$fecha_mes = date("m/Y");
$fecha_anyo = date("d/m/Y");
$anyo_ = date("Y");
$fecha_hora = date("H:i:s");
$ip = $_SERVER['REMOTE_ADDR'];
$cuenta = $cuenta_actual;
$fecha_time = time();

$fecha_dmy = $fecha_anyo;
$fecha_seg_ymd = $fecha;
//------------------------------------------------------------ unidades_vendidas ------------------------------------------------------------//
//------------------------------------------------------------ unidades_vendidas ------------------------------------------------------------//
if ($campo == 'unidades_vendidas') {
$unidades_vendidas_vent = $valor_intro;

$actualizar_operacion_ventas = "UPDATE ventas SET unidades_vendidas = '$unidades_vendidas_vent', vlr_total_venta = '$vlr_total_venta', vlr_total_compra = '$vlr_total_compra', 
und_vend_orig = '$und_vend_orig', devoluciones = '$unidades_devueltas', fecha_devolucion = '$fecha_devolucion', hora_devolucion = '$hora_devolucion', 
cuenta = '$cuenta_actual' WHERE cod_ventas = '$cod_ventas'";
$resultado_ventas = mysql_query($actualizar_operacion_ventas, $conectar) or die(mysql_error());

$actualizar_operacion_productos = "UPDATE productos SET unidades_vendidas = '$unidades_vendidas', unidades_faltantes = '$unidades_faltantes', 
total_mercancia = '$total_mercancia', total_venta = '$total_venta' WHERE cod_productos_var = '$cod_productos_var'";
$resultado_productos = mysql_query($actualizar_operacion_productos, $conectar) or die(mysql_error());

//------------------------------------------------------------ PARA HACER DUVOLUCIONES ------------------------------------------------------------//
//------------------------------------------------------------ PARA HACER DUVOLUCIONES ------------------------------------------------------------//

$nombre_productos = $datos['nombre_productos'];
$cod_factura = $datos['cod_factura'];
$cod_marcas = $datos['cod_marcas'];
$cod_proveedores = $datos['cod_proveedores'];
$nombre_ccosto = $datos['nombre_ccosto'];
$precio_compra = $datos['precio_compra'];
$precio_costo = $datos['precio_costo'];
$precio_venta = $datos['precio_venta'];
$detalles = $datos['detalles'];
$descuento_ptj = $datos['descuento_ptj'];
$iva = $datos['iva'];
$vendedor = $datos['vendedor'];

$agregar_operacion = "INSERT INTO operacion (cod_ventas, cod_productos, nombre_productos, origen_operacion, cod_factura, cod_marcas, cod_proveedores, 
nombre_ccosto, unidades_vendidas, und_vend_orig, devoluciones, precio_compra, precio_costo, precio_venta, vlr_total_compra, vlr_total_venta, 
comentario, cod_clientes, detalles, descuento_ptj, iva, fecha_devolucion, hora_devolucion, fecha_orig, fecha, fecha_mes, fecha_anyo, anyo, 
fecha_hora, ip, vendedor, cuenta, fecha_time) 
VALUES ('$cod_ventas', '$cod_productos', '$nombre_productos', '$origen_operacion', '$cod_factura', '$cod_marcas', '$cod_proveedores', 
'$nombre_ccosto', '$unidades_vendidas_vent', '$und_vend_orig', '$unidades_devueltas', '$precio_compra', '$precio_costo', '$precio_venta', 
'$vlr_total_compra', '$vlr_total_venta', '$comentario', '$cod_clientes', '$detalles', '$descuento_ptj', '$iva', '$fecha_devolucion', 
'$hora_devolucion', '$fecha_orig', '$fecha', '$fecha_mes', '$fecha_anyo', '$anyo_', '$fecha_hora', '$ip', '$vendedor', 
'$cuenta', '$fecha_time')";
$resultado_operacion = mysql_query($agregar_operacion, $conectar) or die(mysql_error());

//-------------------------------------------------------------------------------------------------------------------------------------//
//----------------------------- INICIO ACTUALIZAR KARDEX VENTA - COMPRA E INVENTARIO -----------------------------//
//-------------------------------------------------------------------------------------------------------------------------------------//

$sql_kardex = "SELECT cod_productos, fecha_mes FROM kardex_venta_compra_invent WHERE cod_productos = '$cod_productos_var' AND fecha_mes = '$fecha_mes'";
$consulta_kardex = mysql_query($sql_kardex, $conectar) or die(mysql_error());
$datos_kardex = mysql_fetch_assoc($consulta_kardex);
$total_k = mysql_num_rows($consulta_kardex);
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
$sql_kardex_ventas = "SELECT SUM(unidades_vendidas) AS und_venta FROM ventas WHERE cod_productos = '$cod_productos_var' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_ventas = mysql_query($sql_kardex_ventas, $conectar) or die(mysql_error());
$datos_kardex_ventas = mysql_fetch_assoc($consulta_kardex_ventas);
//--------------------------------------------------------------CALCULO KARDEX COMPRAS------------------------------------------------------------------------//
$sql_kardex_compras = "SELECT SUM(unidades_total) AS und_compra FROM facturas_cargadas_inv WHERE cod_productos = '$cod_productos_var' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_compras = mysql_query($sql_kardex_compras, $conectar) or die(mysql_error());
$datos_kardex_compras = mysql_fetch_assoc($consulta_kardex_compras);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS------------------------------------------------------------------------//
$sql_kardex_transf = "SELECT SUM(unidades_total) AS und_transf FROM transferencias WHERE cod_productos = '$cod_productos_var' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf = mysql_query($sql_kardex_transf, $conectar) or die(mysql_error());
$datos_kardex_transf = mysql_fetch_assoc($consulta_kardex_transf);
//--------------------------------------------------------------CALCULO KARDEX TRANSFERENCIAS ENTRDAS------------------------------------------------------------------------//
$sql_kardex_transf_ent = "SELECT SUM(unidades_total) AS und_transf_ent FROM transferencias_entrada WHERE cod_productos = '$cod_productos_var' AND fecha_mes = '$fecha_mes'";
$consulta_kardex_transf_ent = mysql_query($sql_kardex_transf_ent, $conectar) or die(mysql_error());
$datos_kardex_transf_ent = mysql_fetch_assoc($consulta_kardex_transf_ent);
//---------------------------------------------------------------CALCULO KARDEX INVENTARIO-----------------------------------------------------------------------//
$sql_kardex_invent = "SELECT unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$consulta_kardex_invent = mysql_query($sql_kardex_invent, $conectar) or die(mysql_error());
$datos_kardex_invent = mysql_fetch_assoc($consulta_kardex_invent);
//---------------------------------------------------------------TOTALES CALCULOS KARDEX-----------------------------------------------------------------------//
$und_venta = $datos_kardex_ventas['und_venta'];
$und_compra = $datos_kardex_compras['und_compra'];
$und_transf = $datos_kardex_transf['und_transf'];
$und_transf_ent = $datos_kardex_transf_ent['und_transf_ent'];
$und_invent = $datos_kardex_invent['unidades_faltantes'];
//-------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------INSERTAR NUEVO KARDEX PARA PRODUCTO-----------------------------------------------//
if ($total_k == 0) {

$agregar_kardex = "INSERT INTO kardex_venta_compra_invent (cod_productos, nombre_productos, und_venta, und_compra, und_transf, und_transf_ent, 
und_invent, fecha_mes, fecha_dmy, anyo, fecha_seg_ymd, fecha_time)
VALUES ('$cod_productos_var', '$nombre_productos', '$und_venta', '$und_compra', '$und_transf', '$und_transf_ent', '$und_invent', '$fecha_mes', 
'$fecha_dmy', '$anyo_', '$fecha_seg_ymd', '$fecha_time')";
$resultado_agregar_kardex = mysql_query($agregar_kardex, $conectar) or die(mysql_error());
//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------ACTUALIZAR REGISTRO SI YA EXISTE-----------------------------------------------//
} else {
//---------------------------------------------------------------ACTUALIZACION REGISTRO KARDEX-----------------------------------------------------------------------//
$actualizar_kardex = ("UPDATE kardex_venta_compra_invent SET und_venta = '$und_venta', und_compra = '$und_compra', und_transf = '$und_transf', 
und_transf_ent = '$und_transf_ent', und_invent = '$und_invent', fecha_dmy = '$fecha_dmy', anyo = '$anyo_', fecha_seg_ymd = '$fecha_seg_ymd', 
fecha_time = '$fecha_time' WHERE cod_productos = '$cod_productos_var' AND fecha_mes = '$fecha_mes'");
$resultado_actualiza_kardex = mysql_query($actualizar_kardex, $conectar) or die(mysql_error());
}
//---------------------------------------------------------FIN DEL KARDEX-----------------------------------------------------------//
//----------------------------------------------------------------------- ----------------------------------------------------------//

//------------------------------------------------------------ devoluciones en credito ------------------------------------------------------------//
//------------------------------------------------------------ devoluciones en credito ------------------------------------------------------------//
if ($tipo_pago == 2) {

$actualizar_datos = "UPDATE productos_fiados SET unidades_vendidas = '$unidades_vendidas_vent', vlr_total_venta = '$vlr_total_venta', 
vlr_total_compra = '$vlr_total_compra' WHERE cod_productos = '$cod_productos' AND cod_factura = '$cod_factura' AND cod_uniq_credito = '$cod_uniq_credito'";
$resultado_datos = mysql_query($actualizar_datos, $conectar) or die(mysql_error());

//------------------------------------------------ CALCULOS PARA EL TOTAL DE DEUDA Y LOS ABONOS ------------------------------------------------//
//------------------------------------------------ CALCULOS PARA EL TOTAL DE DEUDA Y LOS ABONOS ------------------------------------------------//
$sql_prod_fiados = "SELECT Sum(vlr_total_venta-(vlr_total_venta*(descuento_ptj/100))) As vlr_total_venta FROM productos_fiados 
WHERE cod_clientes = '$cod_clientes'";
$modificar_prod_fiados = mysql_query($sql_prod_fiados, $conectar) or die(mysql_error());
$datos_fiad = mysql_fetch_assoc($modificar_prod_fiados);

$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos WHERE cod_clientes = '$cod_clientes'";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$monto_deuda = $datos_fiad['vlr_total_venta'];
$abonado = $sum_abonos['abonado'];

$actualizar_cuentas_cobrar = "UPDATE cuentas_cobrar SET monto_deuda = '$monto_deuda', abonado = '$abonado' WHERE cod_clientes = '$cod_clientes'";
$resultado_cuentas_cobrar = mysql_query($actualizar_cuentas_cobrar, $conectar) or die(mysql_error());
}
//------------------------------------------------------------ fin devoluciones en credito ------------------------------------------------------------//
//------------------------------------------------------------ fin devoluciones en credito ------------------------------------------------------------//
}
//------------------------------------------------------------ precio_venta ------------------------------------------------------------//
//------------------------------------------------------------ precio_venta ------------------------------------------------------------//
if ($campo == 'precio_venta') {
$precio_venta = $valor_intro;
$vlr_total_venta = $datos['unidades_vendidas'] * $precio_venta;

$actualizar_datos = "UPDATE ventas SET precio_venta = '$precio_venta', vlr_total_venta = '$vlr_total_venta' WHERE cod_ventas = '$cod_ventas'";
$resultado_datos = mysql_query($actualizar_datos, $conectar) or die(mysql_error());
}
//------------------------------------------------------------ cod_productos ------------------------------------------------------------//
//------------------------------------------------------------ cod_productos ------------------------------------------------------------//
if ($campo == 'cod_productos') {
$cod_productos = $valor_intro;

$actualizar_datos = "UPDATE ventas SET cod_productos = '$cod_productos' WHERE cod_ventas = '$cod_ventas'";
$resultado_datos = mysql_query($actualizar_datos, $conectar) or die(mysql_error());
}
//------------------------------------------------------------ nombre_productos ------------------------------------------------------------//
//------------------------------------------------------------ nombre_productos ------------------------------------------------------------//
if ($campo == 'nombre_productos') {
$nombre_productos = strtoupper($valor_intro);

$actualizar_datos = "UPDATE ventas SET nombre_productos = '$nombre_productos' WHERE cod_ventas = '$cod_ventas'";
$resultado_datos = mysql_query($actualizar_datos, $conectar) or die(mysql_error());
}
//------------------------------------------------------------ fecha_anyo ------------------------------------------------------------//
//------------------------------------------------------------ fecha_anyo ------------------------------------------------------------//
if ($campo == 'fecha_anyo') {
$fecha_anyo_vect = explode('/', $valor_intro);
$dia = $fecha_anyo_vect[0];
$mes = $fecha_anyo_vect[1];
$anyos = $fecha_anyo_vect[2];
$invert = $anyos.'/'.$mes.'/'.$dia;
$fecha = strtotime($invert);
$fecha_mes = $mes.'/'.$anyos;
$anyo = $anyos;
$fecha_anyo = $valor_intro;

$actualizar_datos = "UPDATE ventas SET fecha_anyo = '$fecha_anyo', fecha = '$fecha', fecha_mes = '$fecha_mes', anyo = '$anyo' WHERE cod_ventas = '$cod_ventas'";
$resultado_datos = mysql_query($actualizar_datos, $conectar) or die(mysql_error());
}
//------------------------------------------------------------ comentario ------------------------------------------------------------//
//------------------------------------------------------------ comentario ------------------------------------------------------------//
if ($campo == 'comentario') {
$comentario = strtoupper($valor_intro);

$actualizar_datos = "UPDATE ventas SET comentario = '$comentario' WHERE cod_ventas = '$cod_ventas'";
$resultado_datos = mysql_query($actualizar_datos, $conectar) or die(mysql_error());

$actualizar_datos = "UPDATE operacion SET comentario = '$comentario' WHERE cod_ventas = '$cod_ventas'";
$resultado_datos = mysql_query($actualizar_datos, $conectar) or die(mysql_error());

$actualizar_datos = "UPDATE productos_fiados SET comentario = '$comentario' WHERE cod_productos = '$cod_productos' AND cod_factura = '$cod_factura' AND tipo_pago = '2' AND cod_uniq_credito = '$cod_uniq_credito'";
$resultado_datos = mysql_query($actualizar_datos, $conectar) or die(mysql_error());
}
}
?>