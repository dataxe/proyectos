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

if (isset($_POST["nombre_productos"])) {
$cod_productos_var = $_POST["cod_productos_var"];
$cod_marcas = $_POST["cod_marcas"];
$cod_nomenclatura = $_POST["cod_nomenclatura"];
$nombre_factura = $_POST["nombre_factura"];
$unidades = $_POST["unidades"];
$cajas = $_POST["cajas"];
$detalles = $_POST["nombre_metrica"];
if ($detalles == 'UND') {
$nombre_productos0 = $_POST["nombre_productos"];
$nombre_productos1 = preg_replace("/,/", '.', $nombre_productos0);
$nombre_productos2 = preg_replace("/'/", ' PULG', $nombre_productos1);
$nombre_productos3 = preg_replace("/;/", ' :', $nombre_productos2);
$nombre_productos4 = preg_replace("/#/", 'NO', $nombre_productos3);
$nombre_productos = strtoupper(preg_replace('/"/', ' PULG', $nombre_productos4));
} else {
$nombre_productos0 = $_POST["nombre_productos"].' *'.$unidades.''.$detalles;
$nombre_productos1 = preg_replace("/,/", '.', $nombre_productos0);
$nombre_productos2 = preg_replace("/'/", ' PULG', $nombre_productos1);
$nombre_productos3 = preg_replace("/;/", ' :', $nombre_productos2);
$nombre_productos4 = preg_replace("/#/", 'NO', $nombre_productos3);
$nombre_productos = strtoupper(preg_replace('/"/', ' PULG', $nombre_productos4));
}
$tope_minimo = '1';
//$cod_tipo = $_POST["cod_tipo"];
$cod_paises = '1';
$cod_proveedores = '1';
$precio_compra = $_POST["precio_compra"];
$precio_vent = $_POST["precio_venta"];
$precio_venta = $precio_vent / $unidades;
$precio_costo = $precio_compra / $unidades;
$vlr_total_compra = $precio_compra;
$vlr_total_venta = $precio_vent;
$porcentaje_cuenta_actual = 'NO';
$cod_lineas = $_POST["cod_lineas"];
$cod_ccosto = $_POST["cod_ccosto"];
$porcentaje_vendedor = $_POST["porcentaje_vendedor"];
$fechas_agotado = $_POST["fechas_agotado"];
$fechas_agotado_seg = $_POST["fechas_agotado_seg"];
$codificacion = $_POST["codificacion"];
$url = $_POST["url"];
$numero_factura = '0';
$unidades_vendidas = '0';

$cod_original = $precio_vent;
$unidades_total = $unidades * $cajas;
$unidades_faltantes = $unidades * $cajas;
$und_orig = $unidades_total;
$descripcion = $precio_compra.' - '.$precio_vent;

$dto1 = '0';
$dto2 = '0';
$iva = $_POST["iva"];
$iva_v = '0';
$tipo_pago = '0';
$ip = $_SERVER['REMOTE_ADDR'];

$fechas_agotado = '0';
$fechas_agotado_seg = '0';
$cuenta = $cuenta_actual;
$fechas_dia_invert = date("Y/m/d");
$fechas_dia = strtotime($fechas_dia_invert);
$fechas_mes = date("m/Y");
$fechas_anyo = date("d/m/Y");
$fechas_hora = date("H:i:s");

$fechas_vencimiento = $_POST["fechas_vencimiento"];
$dato_fecha = explode('/', $fechas_vencimiento);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];
$fechas_vencimiento_Y_m_d = $anyo.'/'.$mes.'/'.$dia;
$fechas_vencimiento_seg = strtotime($fechas_vencimiento_Y_m_d);

$utilidad = $precio_venta - $precio_costo;
$total_utilidad = $precio_vent - $precio_compra;	

$datos_filtro = "SELECT * FROM productos WHERE nombre_productos = '$nombre_productos' AND cod_marcas = '$cod_marcas'";
$consulta_filtro = mysql_query($datos_filtro, $conectar) or die(mysql_error());
$matriz_filtro = mysql_fetch_assoc($consulta_filtro);
$numero_de_productos = mysql_num_rows($consulta_filtro);

$filtro_nombre = $matriz_filtro['nombre_productos'];

$verificar_cod_productos = mysql_query("SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'");
$existencia_cod_productos = mysql_num_rows($verificar_cod_productos);

if ($existencia_cod_productos > 0) {
echo "<center><font color='yellow' size='+6'><br><img src=../imagenes/advertencia.gif alt='Advertencia'> El codigo: <strong> ".$cod_productos_var. " </strong>ya existe. 
<img src=../imagenes/advertencia.gif alt='Advertencia'></font><center>";
echo"<br><td><a href='../admin/productos.php'><center><font color='yellow' size='+3'>REGRESAR</font></center></a></td>";
echo "<center><embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed></center>";
}
if($numero_de_productos > 0) {
$datos_marcas = "SELECT * FROM marcas WHERE cod_marcas = '$cod_marcas'";
$consulta_marcas = mysql_query($datos_marcas, $conectar) or die(mysql_error());
$matriz_marcas = mysql_fetch_assoc($consulta_marcas);

echo "<font color='yellow' size='+3'><br><img src=../imagenes/advertencia.gif alt='Advertencia'> El producto ".$filtro_nombre." MARCA ".$matriz_marcas['nombre_marcas']. " existe dentro del inventario del sistema. 
<img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
echo"<br><td><a href='../admin/productos.php'><center><font color='yellow' size='+3'>REGRESAR</font></center></a></td>";
echo "<center><embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='2'></embed></center>";
//echo "<embed src='../sonidos/alarma.mp3' hidden='true autostart='true' loop='3'>";
}
// Hay campos en blanco
elseif($cod_productos_var==NULL || $nombre_productos==NULL || $unidades==NULL) {
echo "<font color='yellow'><br><img src=../imagenes/advertencia.gif alt='Advertencia'> Ha dejado campos vacios. 
<img src=../imagenes/advertencia.gif alt='Advertencia'></font>";
echo "<center><embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed></center>";
} else {
if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {


$agregar_registros_sql1 = "INSERT INTO productos (cod_productos_var, cod_marcas, cod_proveedores, cod_nomenclatura, cod_original, numero_factura, 
nombre_productos, unidades, cajas, tope_minimo, unidades_faltantes, unidades_vendidas, unidades_total, und_orig, detalles, precio_costo, 
precio_compra, precio_venta, vlr_total_compra, vlr_total_venta, codificacion, utilidad, total_utilidad, url, descripcion, cod_lineas, cod_ccosto, cod_paises, vendedor, cuenta, 
porcentaje_vendedor, dto1, dto2, iva, iva_v, tipo_pago, ip, fechas_vencimiento, fechas_vencimiento_seg, fechas_agotado, fechas_agotado_seg, fechas_anyo, fechas_dia, fechas_mes, 
fechas_hora) 
VALUES ('$cod_productos_var', '$cod_marcas', '$cod_proveedores', '$cod_nomenclatura', '$cod_original', '$numero_factura', UPPER('$nombre_productos'), 
'$unidades', '$cajas', '$tope_minimo', '$unidades_faltantes', '$unidades_vendidas', '$unidades_total', '$und_orig', '$detalles', '$precio_costo', 
'$precio_compra', '$precio_venta', '$vlr_total_compra', '$vlr_total_venta', '$codificacion', '$utilidad', '$total_utilidad', '$url', '$descripcion', '$cod_lineas', 
'$cod_ccosto', '$cod_paises', '$cuenta_actual', '$cuenta', '$porcentaje_vendedor', '$dto1', '$dto2', '$iva', '$iva_v', '$tipo_pago', '$ip','$fechas_vencimiento', 
'$fechas_vencimiento_seg', '$fechas_agotado', '$fechas_agotado_seg', '$fechas_anyo', '$fechas_dia', '$fechas_mes', '$fechas_hora')";
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; productos.php">';
echo "<br><center><font color='yellow' size='15px'> Se ha ingresado correctamente el producto <strong>".$nombre_productos.".</strong></font><center>";
}
//mysql_query($agregar_registros_sql1) or die(mysql_error());
      }
   }
?>