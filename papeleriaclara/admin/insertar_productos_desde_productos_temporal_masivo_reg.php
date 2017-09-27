<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
$cuenta_actual = addslashes($_SESSION['usuario']);
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

if (isset($_POST['cod_productos'])) {
$total_datos = intval($_POST['total_datos']);

for ($i=0; $i < $total_datos; $i++) { 

$cod_productos = $_POST['cod_productos'][$i];

$mostrar_datos_productos_temporal = "SELECT * FROM productos_temporal WHERE cod_productos = '$cod_productos'";
$consulta_productos_temporal = mysql_query($mostrar_datos_productos_temporal, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta_productos_temporal);

$cod_productos_var = $datos['cod_productos_var'];

//$mostrar_datos_productos = "SELECT cod_productos_var, unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos_var'";
//$consulta_productos = mysql_query($mostrar_datos_productos, $conectar) or die(mysql_error());
//$datos_productos = mysql_fetch_assoc($consulta_productos);
$cod_marcas = $datos['cod_marcas']; 
$cod_proveedores = $datos['cod_proveedores']; 
$cod_nomenclatura = $datos['cod_nomenclatura']; 
$cod_original = $datos['cod_original']; 
$numero_factura = '0'; 
$nombre_productos = $datos['nombre_productos']; 
$unidades = $datos['unidades']; 
$cajas = $datos['cajas']; 
$tope_minimo = $datos['tope_minimo'];
//$unidades_faltantes_temp = $datos['unidades_faltantes'];
//$unidades_faltantes_prod = $datos_productos['unidades_faltantes'];
$unidades_faltantes =  $datos['unidades_faltantes'];

$unidades_vendidas = $datos['unidades_vendidas']; 
$unidades_total = $datos['unidades_total']; 
$und_orig = $datos['und_orig']; 
$detalles = $datos['detalles']; 
$precio_costo = $datos['precio_costo']; 
$precio_compra = $datos['precio_compra']; 
$precio_venta = $datos['precio_venta']; 
$vlr_total_compra = $datos['vlr_total_compra']; 
$vlr_total_venta = $datos['vlr_total_venta'];
$cod_interno = $datos['cod_interno'];
$codificacion = $datos['codificacion']; 
$utilidad = $datos['utilidad']; 
$total_utilidad = $datos['total_utilidad']; 
$url = $datos['url']; 
$descripcion = $datos['descripcion']; 
$cod_lineas = $datos['cod_lineas']; 
$cod_ccosto = $datos['cod_ccosto']; 
$cod_paises = $datos['cod_paises']; 
$cuenta_actual = $datos['cuenta_actual']; 
$cuenta = $datos['cuenta']; 
$porcentaje_vendedor = $datos['porcentaje_vendedor']; 
$dto1 = $datos['dto1']; 
$dto2 = $datos['dto2']; 
$iva = $datos['iva']; 
$iva_v = $datos['iva_v']; 
$tipo_pago = $datos['tipo_pago']; 
$ip = $datos['ip'];
$fechas_vencimiento = $datos['fechas_vencimiento']; 
$fechas_vencimiento_seg = $datos['fechas_vencimiento_seg']; 
$fechas_agotado = $datos['fechas_agotado']; 
$fechas_agotado_seg = $datos['fechas_agotado_seg']; 
$fechas_anyo = strtotime(date("Y/m/d"));
$fechas_dia = date("d/m/Y");
$fechas_mes = date("m/Y");
$fechas_hora = date("H:i:s");

$agregar_registros_sql1 = "INSERT INTO productos (cod_productos_var, cod_marcas, cod_proveedores, cod_nomenclatura, cod_original, numero_factura, 
nombre_productos, unidades, cajas, tope_minimo, unidades_faltantes, unidades_vendidas, unidades_total, und_orig, 
detalles, precio_costo, precio_compra, precio_venta, vlr_total_compra, vlr_total_venta, cod_interno, codificacion, utilidad, total_utilidad, url, descripcion, cod_lineas, cod_ccosto, cod_paises, vendedor, cuenta, 
porcentaje_vendedor, dto1, dto2, iva, iva_v, tipo_pago, ip, fechas_vencimiento, fechas_vencimiento_seg, fechas_agotado, fechas_agotado_seg, fechas_anyo, fechas_dia, fechas_mes, 
fechas_hora) 
VALUES ('$cod_productos_var', '$cod_marcas', '$cod_proveedores', '$cod_nomenclatura', '$cod_original', '$numero_factura', 
'$nombre_productos', '$unidades', '$cajas', '$tope_minimo', '$unidades_faltantes', '$unidades_vendidas', '$unidades_total', '$und_orig', 
'$detalles', '$precio_costo', '$precio_compra', '$precio_venta', '$vlr_total_compra', '$vlr_total_venta', '$cod_interno', '$codificacion', '$utilidad', '$total_utilidad', '$url', '$descripcion', '$cod_lineas', 
'$cod_ccosto', '$cod_paises', '$cuenta_actual', '$cuenta', '$porcentaje_vendedor', '$dto1', '$dto2', '$iva', '$iva_v', '$tipo_pago', '$ip','$fechas_vencimiento', 
'$fechas_vencimiento_seg', '$fechas_agotado', '$fechas_agotado_seg', '$fechas_anyo', '$fechas_dia', '$fechas_mes', '$fechas_hora')";
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());

$borrar_sql = sprintf("DELETE FROM productos_temporal WHERE cod_productos = '$cod_productos'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
}
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; productos_temporal_para_revision_menu.php">';
echo "<br><center><font color='yellow' size='15px'> Se han ingresado correctamente los productos</font><center>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
</head>
<body>
</body>
</html>