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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php
$total_datos = intval($_POST['total_datos']);
$cod_factura = intval($_POST['cod_factura']);
$verificacion = $_POST['verificacion'];
$fecha_dmy = $_POST['fecha'];

$dato_fecha = explode('/', $fecha_dmy);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];

$fecha_invert = $anyo.'/'.$mes.'/'.$dia;
$fecha = strtotime($fecha_invert);
$fecha_mes = $mes.'/'.$anyo;
$fecha_hora = date("H:i:s");

// ------------------- -------------------------------- --------------------------------//
if (isset($_POST['verificacion'])) {

$insertar_sql1 = "INSERT INTO exportacion (cod_productos, cod_factura, nombre_productos, cod_original, codificacion, cajas, unidades, 
unidades_total, unidades_vendidas, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, detalles, 
porcentaje_vendedor, descuento, dto1, dto2, iva, iva_v, ptj_ganancia, valor_iva, tope_min, precio_compra_con_descuento, vendedor, ip, fecha, 
fecha_mes, fecha_anyo, fecha_hora, fechas_vencimiento, fechas_vencimiento_seg) 
SELECT cod_productos, '$cod_factura', nombre_productos, cod_original, codificacion, cajas, unidades, unidades_total, unidades_vendidas, 
precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, detalles, porcentaje_vendedor, descuento, dto1, dto2, 
iva, iva_v, ptj_ganancia, valor_iva, tope_min, precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, fecha_hora, 
fechas_vencimiento, fechas_vencimiento_seg
FROM exportacion_temporal WHERE vendedor = '$cuenta_actual'";
$resultado_insertar1 = mysql_query($insertar_sql1, $conectar) or die(mysql_error());

$agre_cuentas_facturas = "INSERT INTO info_exportacion (cod_factura, vendedor, tipo_pago, cod_proveedores, valor_bruto, descuento, 
valor_neto, valor_iva, total, fecha, fecha_invert, hora)
VALUES ('$cod_factura', '$cuenta_actual', '$tipo_pago', '$cod_proveedores', '$valor_bruto', '$descuento', '$valor_neto', '$valor_iva', 
'$total', '$fecha_dmy', '$fecha_invert', '$fecha_hora')";
$resultado_cuentas_facturas = mysql_query($agre_cuentas_facturas, $conectar) or die(mysql_error());
//----------------------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------//
//----------------CICLO PARA RECORRER LOS DATOS EN TIEMPO REAL Y METERLOS EN LA TABLA DE EXPORTACION VENDEDOR PARA LA AUDITORIA DE LOS VENDEDORES-------------------//
for ($i=0; $i < $total_datos; $i++) {

$cod_exportacion_temporal = $_POST['cod_exportacion_temporal'][$i];

$sql_exportacion = "SELECT * FROM exportacion_temporal WHERE cod_exportacion_temporal = '$cod_exportacion_temporal'";
$consulta_exportacion = mysql_query($sql_exportacion, $conectar) or die(mysql_error());
$data_exportacion = mysql_fetch_assoc($consulta_exportacion);

$cod_productos = $data_exportacion['cod_productos']; 

$sql_productos = "SELECT unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta_productos = mysql_query($sql_productos, $conectar) or die(mysql_error());
$data_productos = mysql_fetch_assoc($consulta_productos);

$datos_venta = "SELECT SUM(unidades_vendidas) AS unidades_vendidas FROM ventas WHERE cod_productos = '$cod_productos' AND fecha = '$fecha'";
$consulta_venta = mysql_query($datos_venta, $conectar) or die(mysql_error());
$dato_venta = mysql_fetch_assoc($consulta_venta);


$cod_original = $data_exportacion['cod_original'];
$codificacion = $data_exportacion['codificacion'];
$nombre_productos = $data_exportacion['nombre_productos'];
$unidades = $data_exportacion['unidades'];
$cajas = $data_exportacion['cajas'];
$unidades_total = $data_exportacion['unidades_total'];
$unidades_faltantes = $data_productos['unidades_faltantes'];
$unidades_vendidas = $dato_venta['unidades_vendidas'];
$precio_compra = $data_exportacion['precio_compra'];
$precio_costo = $data_exportacion['precio_costo'];
$precio_venta = $data_exportacion['precio_venta'];
$vlr_total_venta = $data_exportacion['vlr_total_venta'];
$vlr_total_compra = $data_exportacion['vlr_total_compra'];
$detalles = $data_exportacion['detalles'];
$porcentaje_vendedor = $data_exportacion['porcentaje_vendedor'];
$descuento = $data_exportacion['descuento'];
$dto1 = $data_exportacion['dto1'];
$dto2 = $dto2['dto2'];
$iva = $data_exportacion['iva'];
$iva_v = $data_exportacion['iva_v'];
$ptj_ganancia = $data_exportacion['ptj_ganancia'];
$valor_iva = $data_exportacion['valor_iva'];
$tope_min = $data_exportacion['tope_min'];
$precio_compra_con_descuento = $data_exportacion['precio_compra_con_descuento'];
$vendedor = $data_exportacion['vendedor'];
$ip = $_SERVER['REMOTE_ADDR'];
$fecha_mes = $data_exportacion['fecha_mes'];
$fecha_anyo = $data_exportacion['fecha_anyo'];
$fecha_hora = $data_exportacion['fecha_hora'];
$fechas_vencimiento = $data_exportacion['fechas_vencimiento'];
$fechas_vencimiento_seg = $data_exportacion['fechas_vencimiento_seg'];
$cuenta = $cuenta_actual;
$fecha_time = time();

$actualizar_sql1 = "INSERT INTO camparacion_tablas (cod_productos, cod_factura, cod_original, codificacion, nombre_productos, unidades, cajas, 
unidades_total, unidades_faltantes, unidades_vendidas, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, detalles, 
porcentaje_vendedor, descuento, dto1, dto2, iva, iva_v, ptj_ganancia, valor_iva, tope_min, precio_compra_con_descuento, vendedor, ip, fecha, 
fecha_mes, fecha_anyo, fecha_hora, fechas_vencimiento, fechas_vencimiento_seg, fecha_time, cuenta)
VALUES ('$cod_productos', '$cod_factura', '$cod_original', '$codificacion', '$nombre_productos', '$unidades', '$cajas', 
'$unidades_total', '$unidades_faltantes', '$unidades_vendidas', '$precio_compra', '$precio_costo', '$precio_venta', '$vlr_total_venta', 
'$vlr_total_compra', '$detalles', '$porcentaje_vendedor', '$descuento', '$dto1', '$dto2', '$iva', '$iva_v', '$ptj_ganancia', '$valor_iva', 
'$tope_min', '$precio_compra_con_descuento', '$vendedor', '$ip', '$fecha', '$fecha_mes', '$fecha_anyo', '$fecha_hora', '$fechas_vencimiento', 
'$fechas_vencimiento_seg', '$fecha_time', '$cuenta')";
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());

/*
$insertar_sql2 = "INSERT INTO exportacion_vendedor (cod_productos, cod_factura, nombre_productos, cod_original, codificacion, cajas, 
unidades, unidades_total, unidades_vendidas, unidades_faltantes, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, detalles, 
porcentaje_vendedor, descuento, dto1, dto2, iva, iva_v, ptj_ganancia, valor_iva, tope_min, vendedor, 
ip, fecha, fecha_mes, fecha_anyo, fecha_hora, fechas_vencimiento, fechas_vencimiento_seg) 
VALUES ('$cod_productos', '$cod_factura', '$nombre_productos', '$cod_original', '$codificacion', '$cajas', '$unidades', '$unidades_total', 
'$unidades_vendidas', '$unidades_faltantes', '$precio_compra', '$precio_costo', '$precio_venta', '$vlr_total_venta', '$vlr_total_compra', '$detalles', 
'$porcentaje_vendedor', '$descuento', '$dto1', '$dto2', '$iva', '$iva_v', '$ptj_ganancia', '$valor_iva', '$tope_min', 
'$vendedor', '$ip', '$fecha', '$fecha_mes', '$fecha_anyo', '$fecha_hora', '$fechas_vencimiento', '$fechas_vencimiento_seg')";
$resultado_insertar2 = mysql_query($insertar_sql2, $conectar) or die(mysql_error());
*/
}
//----------------------------FIN DEL CICLO FOR------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------------------//
$borrar_sql = sprintf("DELETE FROM exportacion_temporal WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

echo "<center><font size='6' color='yellow'>LA FACTURA SE HA INTRODUCIDO EXITOSAMENTE</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; ../admin/exportacion_lista_subida.php">';
}
?>
</body>
</html>