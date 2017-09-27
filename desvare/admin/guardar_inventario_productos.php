<?php
if (isset($_GET['valor']) && isset($_GET['id'])) {
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar);
include_once('../evitar_mensaje_error/error.php');
date_default_timezone_set("America/Bogota");
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);

$valor_intro = strtoupper($_GET['valor']);
$campo = addslashes($_GET['campo']);
$cod_productos = addslashes($_GET['id']);
/*
$sql_productos = "SELECT * FROM productos WHERE cod_productos = '$cod_productos'";
$productos_consulta = mysql_query($conectar, $sql_productos) or die(mysql_error());
$productos = mysql_fetch_assoc($productos_consulta);
*/
if ($campo == 'nombre_productos') {

$nombre_productos0 = $valor_intro;
$nombre_productos1 = preg_replace("/,/", '.', $nombre_productos0);
$nombre_productos2 = preg_replace("/'/", ' PULG', $nombre_productos1);
$nombre_productos3 = preg_replace("/;/", ' :', $nombre_productos2);
$nombre_productos4 = preg_replace("/#/", 'NO', $nombre_productos3);
$nombre_productos = strtoupper(preg_replace('/"/', ' PULG', $nombre_productos4));

$data_sql = ("UPDATE productos SET nombre_productos = '$nombre_productos' WHERE cod_productos = '$cod_productos'");
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}
elseif ($campo == 'unidades_faltantes') {

$unidades_faltantes = $valor_intro;

$sql_modificar_consulta = "SELECT * FROM productos WHERE cod_productos = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$cod_productos_var = $datos['cod_productos_var'];
$nombre_productos = $datos['nombre_productos'];
$origen_operacion = 'productos';
$cod_marcas = $datos['cod_marcas'];
$cod_proveedores = $datos['cod_proveedores'];
$cod_nomenclatura = $datos['cod_nomenclatura'];
$cod_tipo = $datos['cod_tipo'];
$cod_lineas = $datos['cod_lineas'];
$cod_ccosto = $datos['cod_ccosto'];
$cod_paises = $datos['cod_paises'];

$descripcion = $datos['descripcion'];
$precio_compra = $datos['precio_compra'];
$precio_costo = $datos['precio_costo'];
$precio_venta = $datos['precio_venta'];
$vlr_total_compra = $datos['vlr_total_compra'];
$vlr_total_venta = $datos['vlr_total_venta'];
$codificacion = $datos['codificacion'];
$detalles = $datos['detalles'];
$comentario = 'productos por inventario ajax';
$unidades = $datos['unidades'];
$unidades_f = $unidades_faltantes;
$und_orig = $datos['unidades_faltantes'];
$unidades_nuevas = $unidades_faltantes - $und_orig;

$fecha_mes = date("m/Y");
$fecha_anyo = date("d/m/Y");
$fecha_dmy = $fecha_anyo;
$anyo = date("Y");
$fecha = strtotime(date("Y/m/d"));
$fecha_seg_ymd = $fecha;
$fecha_time = time();
$ip = $_SERVER['REMOTE_ADDR'];
$cuenta = $cuenta_actual;
$fecha_hora = date("H:i:s");
$fecha_orig = date("d/m/Y");

$agregar_operacion = "INSERT INTO operacion (cod_productos, nombre_productos, origen_operacion, cod_marcas, cod_proveedores, 
cod_nomenclatura, cod_tipo, cod_lineas, cod_ccosto, cod_paises, und_nuevas, und_inventario, unidades, unidades_faltantes, precio_compra, 
precio_costo, precio_venta, vlr_total_compra, vlr_total_venta, comentario, codificacion, detalles, descripcion, fecha_orig, fecha, fecha_mes, 
fecha_anyo, anyo, fecha_hora, ip, cuenta, fecha_time)
VALUES ('$cod_productos_var', '$nombre_productos', '$origen_operacion', '$cod_marcas', '$cod_proveedores', 
'$cod_nomenclatura', '$cod_tipo', '$cod_lineas', '$cod_ccosto', '$cod_paises', '$unidades_nuevas', '$und_orig', '$unidades', '$unidades_f', 
'$precio_compra', '$precio_costo', '$precio_venta', '$vlr_total_compra', '$vlr_total_venta', '$comentario', '$codificacion', '$detalles', 
'$descripcion', '$fecha_orig', '$fecha', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', '$ip', '$cuenta', '$fecha_time')";
$resultado_operacion = mysql_query($agregar_operacion, $conectar) or die(mysql_error());


$data_sql = ("UPDATE productos SET unidades_faltantes = '$unidades_faltantes' WHERE cod_productos = '$cod_productos'");
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
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
'$fecha_dmy', '$anyo', '$fecha_seg_ymd', '$fecha_time')";
$resultado_agregar_kardex = mysql_query($agregar_kardex, $conectar) or die(mysql_error());
//--------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------ACTUALIZAR REGISTRO SI YA EXISTE-----------------------------------------------//
} else {
//---------------------------------------------------------------ACTUALIZACION REGISTRO KARDEX-----------------------------------------------------------------------//
$actualizar_kardex = ("UPDATE kardex_venta_compra_invent SET und_venta = '$und_venta', und_compra = '$und_compra', und_transf = '$und_transf', 
und_transf_ent = '$und_transf_ent', und_invent = '$und_invent', fecha_dmy = '$fecha_dmy', anyo = '$anyo', fecha_seg_ymd = '$fecha_seg_ymd', 
fecha_time = '$fecha_time' WHERE cod_productos = '$cod_productos_var' AND fecha_mes = '$fecha_mes'");
$resultado_actualiza_kardex = mysql_query($actualizar_kardex, $conectar) or die(mysql_error());
}
//--------------------------------------------FIN DEL KARDEX-----------------------------------------------------------//
//----------------------------------------------------------------------- ----------------------------------------------------------//
} else {
$data_sql = ("UPDATE productos SET $campo = '$valor_intro' WHERE cod_productos = '$cod_productos'");
$exec_data = mysql_query($data_sql, $conectar) or die(mysql_error());
}
}
?>