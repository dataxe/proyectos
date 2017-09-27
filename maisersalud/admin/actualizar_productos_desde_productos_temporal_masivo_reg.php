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

$mostrar_datos_productos = "SELECT cod_productos_var, unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$consulta_productos = mysql_query($mostrar_datos_productos, $conectar) or die(mysql_error());
$datos_productos = mysql_fetch_assoc($consulta_productos);

$cod_marcas = $datos['cod_marcas']; 
$cod_proveedores = $datos['cod_proveedores']; 
$numero_factura = '0'; 
$tope_minimo = $datos['tope_minimo'];

$unidades_faltantes_temp = $datos['unidades_faltantes'];
$unidades_faltantes_prod = $datos_productos['unidades_faltantes'];
$unidades_faltantes =  $unidades_faltantes_prod + $unidades_faltantes_temp;

$detalles = $datos['detalles']; 
$precio_costo = $datos['precio_costo']; 
$precio_compra = $datos['precio_compra']; 
$precio_venta = $datos['precio_venta']; 
$vlr_total_compra = $datos['vlr_total_compra']; 
$vlr_total_venta = $datos['vlr_total_venta'];
$cod_interno = $datos['cod_interno'];
$cuenta_actual = $datos['cuenta_actual']; 
$cuenta = $datos['cuenta']; 
$porcentaje_vendedor = $datos['porcentaje_vendedor']; 
$iva = $datos['iva']; 
$fechas_anyo = strtotime(date("Y/m/d"));
$fechas_dia = date("d/m/Y");
$fechas_mes = date("m/Y");
$fechas_hora = date("H:i:s");

$actualizar_sql1 = sprintf("UPDATE productos SET cod_marcas = '$cod_marcas', cod_proveedores = '$cod_proveedores', 
unidades_faltantes = '$unidades_faltantes', detalles = '$detalles', precio_costo = '$precio_costo', precio_compra = '$precio_compra',
precio_venta = '$precio_venta', vlr_total_compra = '$vlr_total_compra', vlr_total_venta = '$vlr_total_venta', cod_interno = '$cod_interno', 
iva = '$iva', fechas_anyo = '$fechas_anyo', fechas_dia = '$fechas_dia', fechas_mes = '$fechas_mes', fechas_hora = '$fechas_hora'
WHERE cod_productos_var = '$cod_productos_var'");
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());

$borrar_sql = sprintf("DELETE FROM productos_temporal WHERE cod_productos = '$cod_productos'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
}
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; productos_temporal_para_revision_menu.php">';
echo "<br><center><font color='yellow' size='15px'> Se han actualizado correctamente los productos</font><center>";
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