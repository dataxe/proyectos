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
$cod_transferencias_almacenes = intval($_POST['cod_transferencias_almacenes']);

$fecha = $_POST['fecha'];
$total_datos = intval($_POST['total_datos']);
$total = addslashes($_POST['total']);
$cod_factura = $_POST['cod_factura'];
$ip = $_SERVER['REMOTE_ADDR'];

$dato_fecha = explode('/', $fecha);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];

$fecha_invert = $anyo.'/'.$mes.'/'.$dia;
$fecha_seg = strtotime($fecha_invert);
$fecha_mes = $mes.'/'.$anyo;
$fechas_anyo = $fecha;
$fechas_dia = $fecha_invert;
$fechas_hora = date("H:i:s");
$unidades_vendidas = "0";
$abonado = "0";
$tipo_pago = "0";

$fechas_agotado = date("d/m/Y");
$fechas_agotado_seg = strtotime(date("Y/m/d"));

// -------------------  --------------------------------
if (isset($_POST['verificacion'])) {
//------------------------------------------------------------------------------------------------------------------------------------- -----------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------- -----------------------------------//
$agregar_reg_transferencias = "INSERT INTO transferencias (cod_productos, cajas, unidades, unidades_total, nombre_productos, cod_original, 
codificacion, unidades_vendidas, precio_compra, precio_costo, precio_venta, dto1, dto2, iva, iva_v, fecha, fecha_mes, fecha_anyo, fecha_hora, 
ip, cod_factura, cod_transferencias_almacenes, vendedor, vlr_total_compra, vlr_total_venta, detalles) 
SELECT cod_productos, cajas, unidades, unidades_total, nombre_productos, cod_original, codificacion, unidades_vendidas, precio_compra, precio_costo, 
precio_venta, dto1, dto2, iva, iva_v, '$fecha_invert', '$fecha_mes', '$fecha', '$fechas_hora', '$ip', '$cod_factura', 
'$cod_transferencias_almacenes', '$cuenta_actual', vlr_total_compra, vlr_total_venta, detalles 
FROM cargar_transferencias_temporal WHERE cargar_transferencias_temporal.vendedor = '$cuenta_actual'";
$resultado_transferencias = mysql_query($agregar_reg_transferencias, $conectar) or die(mysql_error());
//------------------------------------------------------------------------------------------------------------------------------------- -----------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------- -----------------------------------//

$agregar_regis = "INSERT INTO info_transferencias (cod_transferencias_almacenes, cod_factura, vendedor, fecha_dia, fecha_anyo, fecha_hora)
VALUES ('$cod_transferencias_almacenes', '$cod_factura', '$cuenta_actual', '$fecha_invert', ' $fecha', '$fechas_hora')";
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());
//------------------------------------------------------------------------------------------------------------------------------------- -----------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------- -----------------------------------//
for ($i=0; $i < $total_datos; $i++) {
$cod_productos = $_POST['cod_productos'][$i];

$sqlr_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sqlr_consulta, $conectar) or die(mysql_error());
$datos_prod = mysql_fetch_assoc($modificar_consulta);

$sql_mconsulta = "SELECT * FROM cargar_transferencias_temporal WHERE cod_productos = '$cod_productos'";
$mconsulta = mysql_query($sql_mconsulta, $conectar) or die(mysql_error());
$datos_temp = mysql_fetch_assoc($mconsulta);

$unid = $datos_prod['precio_venta'];

$unidades_faltante = $datos_prod['unidades_faltantes'] - $datos_temp['unidades_total'];
$unidades_faltantes = $datos_prod['unidades_faltantes'] - $datos_temp['unidades_total'];
$unidades_vendidas = $datos_prod['unidades_vendidas'] + $datos_temp['unidades_total'];
$total_util1 = $datos_prod['unidades_vendidas'] * $datos_prod['precio_venta'];
$total_util2 = $datos_prod['unidades_vendidas'] * $datos_prod['precio_costo'];
$total_utilidad = $total_util1 - $total_util2;
$total_mercancia = $datos_prod['unidades_faltantes'] * $datos_prod['precio_costo'];
$total_venta = $datos_prod['unidades_vendidas'] * $datos_prod['precio_costo'];

//----------------------------- ACTUALIZAR INVENTARIO DE PRODUCTOS OPERACIONES -----------------------------//
if ($unidades_faltantes <= '0') {
$actualiza_productos = sprintf("UPDATE productos, cargar_transferencias_temporal SET productos.unidades_vendidas = '$unidades_vendidas', 
productos.unidades_faltantes = '$unidades_faltante',
productos.fechas_agotado_seg = '$fechas_agotado_seg',
productos.fechas_agotado = '$fechas_agotado'
WHERE productos.cod_productos_var = '$cod_productos'");
$resultado_actualiza_productos = mysql_query($actualiza_productos, $conectar) or die(mysql_error());
} else {
$actualiza_productos = sprintf("UPDATE productos, cargar_transferencias_temporal SET productos.unidades_vendidas = '$unidades_vendidas', 
productos.unidades_faltantes = '$unidades_faltante'
WHERE productos.cod_productos_var = '$cod_productos'");
$resultado_actualiza_productos = mysql_query($actualiza_productos, $conectar) or die(mysql_error());
}
//------------------------------------------------------------------------------------------------------------------------------------- -----------------------------------//
//----------------------------- INICIO ACTUALIZAR KARDEX VENTA - COMPRA E INVENTARIO -----------------------------//
//-------------------------------------------------------------------------------------------------------------------------------------//
$fecha_dmy = $fechas_anyo;
$fecha_seg_ymd = $fecha_seg;
$fecha_time = time();

$sql_kardex = "SELECT cod_productos, fecha_mes FROM kardex_venta_compra_invent WHERE cod_productos = '$cod_productos' AND fecha_mes = '$fecha_mes'";
$consulta_kardex = mysql_query($sql_kardex, $conectar) or die(mysql_error());
$datos_kardex = mysql_fetch_assoc($consulta_kardex);
$total_k = mysql_num_rows($consulta_kardex);
//-------------------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------INSERTAR NUEVO KARDEX PARA PRODUCTO-----------------------------------------------//
if ($total_k == 0) {
//--------------------------------------------------------------CALCULO KARDEX VENTAS------------------------------------------------------------------------//
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
//--------------------------------------------FIN CICLO FOR QUE RECORRE LOS DATOS------------------------------- -----------------------------//
}
//------------------------------------------------------------------------------------------------------------------------------------- -----------------------------------//
//------------------------------------------------------------------------------------------------------------------------------------- -----------------------------------//
$borrar_sql = sprintf("DELETE FROM cargar_transferencias_temporal WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

echo "<br><br><center><font size='6' color='yellow'>LA TRANSFERENCIA DEL DIA: ".$fecha." SE HA HECHO EXITOSAMENTE</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; ../admin/transferencias.php">';
}
?>
</body>
</html>