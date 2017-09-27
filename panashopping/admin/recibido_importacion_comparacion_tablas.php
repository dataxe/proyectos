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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

if (isset($_POST['si'])) {

$total_datos = intval($_POST['total_datos']);

/* -------------------------INICIO CALCULO DE FACTURA UNICA----------------------- */
$sql_importacion_temporal = "SELECT MAX(cod_factura) AS cod_factura FROM camparacion_tablas";
$consulta_importacion_temporal = mysql_query($sql_importacion_temporal, $conectar) or die(mysql_error());
$max_cod_factura = mysql_fetch_assoc($consulta_importacion_temporal);

$cod_factura = $max_cod_factura['cod_factura'] + 1;
/* -------------------------FIN CALCULO DE FACTURA UNICA----------------------- */

/* -------------------------INICIO CICLO FOR PARA INSERTAR LOS DATOS QUE VIENEN DEL VECTOR ----------------------- */
for ($i=0; $i < $total_datos; $i++) {

$cod_exportacion = $_POST['cod_exportacion'][$i];

$sql_exportacion = "SELECT * FROM exportacion WHERE cod_exportacion = '$cod_exportacion'";
$consulta_exportacion = mysql_query($sql_exportacion, $conectar) or die(mysql_error());
$data_exportacion = mysql_fetch_assoc($consulta_exportacion);

$cod_productos = $data_exportacion['cod_productos'];

$sql_productos = "SELECT unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta_productos = mysql_query($sql_productos, $conectar) or die(mysql_error());
$data_productos = mysql_fetch_assoc($consulta_productos);


$cod_original = $data_exportacion['cod_original'];
$codificacion = $data_exportacion['codificacion'];
$nombre_productos = $data_exportacion['nombre_productos'];
$unidades = $data_exportacion['unidades'];
$cajas = $data_exportacion['cajas'];
$unidades_total = $data_exportacion['unidades_total'];
$unidades_faltantes = $data_productos['unidades_faltantes'];
$unidades_vendidas = $data_exportacion['unidades_vendidas'];
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
}
/* -------------------------FIN CICLO FOR PARA INSERTAR LOS DATOS QUE VIENEN DEL VECTOR ----------------------- */
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/resultado_comparacion_tablas.php?cod_factura=<?php echo $cod_factura ?>">
<?php
}
if (isset($_POST['no'])) {
echo "<META HTTP-EQUIV='REFRESH' CONTENT='0.1; exportacion_lista_vendedor.php'>";
}
?>

