<?php
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");


if (isset($_GET["cod_productos"])) {

$cod_productos = intval($_GET["cod_productos"]);
$pagina = $_GET["pagina"];

$agregar_registros_sql1 = "INSERT INTO productos (cod_productos_var, nombre_productos, cod_marcas, cod_proveedores, 
cod_nomenclatura, cod_tipo, cod_lineas, cod_ccosto, cod_paises, numero_factura, 
unidades, cajas, unidades_total, unidades_faltantes, unidades_vendidas, und_orig, 
precio_compra, precio_costo, precio_venta, vlr_total_compra, vlr_total_venta, 
cod_interno, tope_minimo, utilidad, total_utilidad, total_mercancia, 
total_venta, gasto, descuento, tipo_pago, ip, codificacion, url, 
cod_original, detalles, descripcion, dto1, dto2, iva, iva_v, 
fechas_dia, fechas_mes, fechas_anyo, fechas_hora, fechas_vencimiento, 
porcentaje_vendedor, fechas_vencimiento_seg, fechas_agotado, fechas_agotado_seg, vendedor, cuenta) SELECT cod_productos_var, nombre_productos, cod_marcas, cod_proveedores, 
cod_nomenclatura, cod_tipo, cod_lineas, cod_ccosto, cod_paises, numero_factura, 
unidades, cajas, unidades_total, unidades_faltantes, unidades_vendidas, und_orig, 
precio_compra, precio_costo, precio_venta, vlr_total_compra, vlr_total_venta, 
cod_interno, tope_minimo, utilidad, total_utilidad, total_mercancia, 
total_venta, gasto, descuento, tipo_pago, ip, codificacion, url, 
cod_original, detalles, descripcion, dto1, dto2, iva, iva_v, 
fechas_dia, fechas_mes, fechas_anyo, fechas_hora, fechas_vencimiento, 
porcentaje_vendedor, fechas_vencimiento_seg, fechas_agotado, fechas_agotado_seg, vendedor, cuenta 
FROM productos_temporal WHERE productos_temporal.cod_productos = '$cod_productos'";
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());

$borrar_sql = sprintf("DELETE FROM productos_temporal WHERE cod_productos = '$cod_productos'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="3; <?php echo $pagina ?>">';
<?php
echo "<br><center><font color='yellow' size='15px'> Se ha ingresado correctamente el producto <strong>".$nombre_productos.".</strong></font><center>";
}
?>