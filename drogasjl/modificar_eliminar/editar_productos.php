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

if(isset($_POST["MM_update"])) {

$cod_productos = $_POST['cod_productos'];

$sql_modificar_consulta = "SELECT * FROM productos WHERE cod_productos = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
$total_resultado = mysql_num_rows($modificar_consulta);

$cod_productos_var = $_POST['cod_productos_var'];
$nombre_productos0 = $_POST["nombre_productos"];
$nombre_productos1 = preg_replace('/,/', '.', $nombre_productos0);
$nombre_productos2 = preg_replace("/'/", ' -', $nombre_productos1);
$nombre_productos3 = preg_replace("/;/", ' :', $nombre_productos2);
$nombre_productos = strtoupper(preg_replace('/"/', ' -', $nombre_productos3));
$cod_marcas = $_POST['cod_marcas'];
$cod_proveedores = $_POST['cod_proveedores'];
$cod_lineas = $_POST['cod_lineas'];
$cod_ccosto = $_POST['cod_ccosto'];
$cod_nomenclatura = $_POST['cod_nomenclatura'];
$cod_tipo = $_POST['cod_tipo'];
$cod_original = $_POST['cod_original'];
$codificacion = $_POST['codificacion'];
$und_orig = $_POST['unidades_faltantes'];
$unidades = $_POST['unidades'];
$unidades_nuevas = $_POST['unidades_nuevas'];
$unidades_faltantes = $und_orig + $unidades_nuevas;
$precio_compra = $_POST['precio_costo'];
$precio_costo = $_POST['precio_costo'];
$precio_venta = $_POST['precio_venta'];
$vlr_total_venta = $_POST['vlr_total_venta'];
$vlr_total_compra = $precio_compra;
$detalles = $_POST['detalles'];
$descripcion0 = $_POST["descripcion"];
$descripcion1 = preg_replace('/,/', '.', $descripcion0);
$descripcion2 = preg_replace("/'/", ' -', $descripcion1);
$descripcion3 = preg_replace("/;/", ' :', $nombre_productos2);
$descripcion = strtoupper(preg_replace('/"/', ' -', $descripcion3));
$cod_factura = $_POST['numero_factura'];
$cod_paises = $_POST['cod_paises'];
$porcentaje_vendedor = strtoupper($_POST['porcentaje_vendedor']);
$fechas_vencimiento = $_POST['fechas_vencimiento'];
$pagina = $_POST['pagina'];

$comentario = $_POST['comentario'];
$origen_operacion = 'productos';
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

$dato_fecha = explode('/', $fechas_vencimiento);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];
$fechas_vencimiento_Y_m_d = $anyo.'/'.$mes.'/'.$dia;
$fechas_vencimiento_seg = strtotime($fechas_vencimiento_Y_m_d);

if ($total_resultado == 0) {
echo "<head>";
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='true'></embed>";
echo "<font color='yellow'><center><img src=../imagenes/advertencia.gif alt='Advertencia'>El producto <strong>$cod_productos_var</strong> no existe dentro del inventario del sistema. <img src=../imagenes/advertencia.gif alt='Advertencia'></center></font>";
?>
<META HTTP-EQUIV="REFRESH" CONTENT="5; ../admin/<?php echo $pagina ?>">
<?php
} 
else {
if ($unidades_faltantes > '0') {
$borrar_alerta  = sprintf("DELETE FROM notificacion_alerta WHERE cod_productos_var = '$cod_productos_var'");
$Resultado2 = mysql_query($borrar_alerta , $conectar) or die(mysql_error());
}
if ($unidades_faltantes <> $datos['unidades_faltantes']) {

$actualizar_sql1 = sprintf("UPDATE productos SET cod_productos_var = '$cod_productos_var', nombre_productos = '$nombre_productos', cod_marcas = '$cod_marcas', 
cod_proveedores = '$cod_proveedores', cod_lineas = '$cod_lineas', cod_ccosto = '$cod_ccosto',
cod_nomenclatura = '$cod_nomenclatura', cod_tipo = '$cod_tipo', cod_original = '$cod_original', codificacion = '$codificacion', 
unidades_faltantes = '$unidades_faltantes', unidades = '$unidades', und_orig = '$und_orig', precio_costo = '$precio_costo', 
precio_venta = '$precio_venta', vlr_total_venta = '$vlr_total_venta', detalles = '$detalles', numero_factura = '$cod_factura',
cod_paises = '$cod_paises', fechas_dia = '$fechas_dia', fechas_mes = '$fechas_mes', fechas_anyo = '$fecha_anyo', fechas_hora = '$fecha_hora', 
fechas_vencimiento = '$fechas_vencimiento',fechas_vencimiento_seg = '$fechas_vencimiento_seg',vendedor = '$cuenta_actual', porcentaje_vendedor = '$porcentaje_vendedor'
WHERE cod_productos = '$cod_productos'");
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());

$agregar_operacion = "INSERT INTO operacion (cod_productos, nombre_productos, origen_operacion, cod_factura, cod_marcas, cod_proveedores, 
cod_nomenclatura, cod_tipo, cod_lineas, cod_ccosto, cod_paises, und_nuevas, und_inventario, unidades, unidades_faltantes, precio_compra, precio_costo, precio_venta, 
vlr_total_compra, vlr_total_venta, comentario, codificacion, detalles, descripcion, fecha_orig, fecha, fecha_mes, fecha_anyo, 
anyo, fecha_hora, ip, cuenta, fecha_time)
VALUES ('$cod_productos_var', '$nombre_productos', '$origen_operacion', '$cod_factura', '$cod_marcas', '$cod_proveedores', 
'$cod_nomenclatura', '$cod_tipo', '$cod_lineas', '$cod_ccosto', '$cod_paises', '$unidades_nuevas', '$und_orig', '$unidades', '$unidades_faltantes', '$precio_compra', '$precio_costo', 
'$precio_venta', '$vlr_total_compra', '$vlr_total_venta', '$comentario', '$codificacion', '$detalles', '$descripcion', '$fecha_orig', '$fecha', 
'$fecha_mes', '$fecha_anyo', '$anyo_', '$fecha_hora', '$ip', '$cuenta', '$fecha_time')";
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
//--------------------------------------------FIN DEL KARDEX-----------------------------------------------------------//
//----------------------------------------------------------------------- ----------------------------------------------------------//
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/<?php echo $pagina ?>">
<?php
} 
if ($unidades_faltantes == $datos['unidades_faltantes']) {

$actualizar_sql1 = sprintf("UPDATE productos SET cod_productos_var = '$cod_productos_var', nombre_productos = '$nombre_productos', cod_marcas = '$cod_marcas', 
cod_proveedores = '$cod_proveedores', cod_lineas = '$cod_lineas', cod_ccosto = '$cod_ccosto',
cod_nomenclatura = '$cod_nomenclatura', cod_tipo = '$cod_tipo', cod_original = '$cod_original', codificacion = '$codificacion', 
unidades_faltantes = '$unidades_faltantes', unidades = '$unidades', und_orig = '$und_orig', precio_costo = '$precio_costo', 
precio_venta = '$precio_venta', vlr_total_venta = '$vlr_total_venta', detalles = '$detalles', numero_factura = '$cod_factura',
cod_paises = '$cod_paises', fechas_dia = '$fechas_dia', fechas_mes = '$fechas_mes', fechas_anyo = '$fecha_anyo', fechas_hora = '$fecha_hora', 
fechas_vencimiento = '$fechas_vencimiento', fechas_vencimiento_seg = '$fechas_vencimiento_seg', vendedor = '$cuenta_actual', porcentaje_vendedor = '$porcentaje_vendedor'
WHERE cod_productos = '$cod_productos'");
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/<?php echo $pagina ?>">
<?php
}
}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 