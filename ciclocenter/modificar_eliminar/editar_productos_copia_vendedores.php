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

$cod_productos_var = $_POST['cod_productos_var'];

$sql_modificar_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
$total_resultado = mysql_num_rows($modificar_consulta);

$nombre_productos0 = $datos["nombre_productos"];
$nombre_productos1 = preg_replace('/,/', '.', $nombre_productos0);
$nombre_productos2 = preg_replace("/'/", ' -', $nombre_productos1);
$nombre_productos3 = preg_replace("/;/", ' :', $nombre_productos2);
$nombre_productos = strtoupper(preg_replace('/"/', ' -', $nombre_productos3));
$cod_marcas = $datos['cod_marcas'];
$cod_proveedores = $datos['cod_proveedores'];
$cod_lineas = $datos['cod_lineas'];
$cod_ccosto = $datos['cod_ccosto'];
$cod_nomenclatura = $datos['cod_nomenclatura'];
$cod_tipo = $datos['cod_tipo'];
$cod_original = $datos['cod_original'];
$codificacion = $datos['codificacion'];
$und_orig = $datos['unidades_faltantes'];
$unidades = $datos['unidades'];
$cajas = $datos['cajas'];
$dto1 = $datos['dto1'];
$dto2 = $datos['dto2'];
$iva = $datos['iva'];
$iva_v = $datos['iva_v'];
$fechas_agotado = $datos['fechas_agotado'];
$fechas_agotado_seg = $datos['fechas_agotado_seg'];
$cod_interno = $datos['cod_interno'];
$tope_minimo = $datos['tope_minimo'];
$descuento = $datos['descuento'];
$unidades_total = $datos['unidades_total'];
$unidades_nuevas = abs($_POST['unidades_nuevas']);
$unidades_faltantes = $und_orig + $unidades_nuevas;
$precio_compra = $datos['precio_costo'];
$precio_costo = $datos['precio_costo'];
$precio_venta = $datos['precio_venta'];
$vlr_total_venta = $datos['vlr_total_venta'];
$vlr_total_compra = $precio_compra;
$detalles = $datos['detalles'];
$descripcion0 = $datos["descripcion"];
$descripcion1 = preg_replace('/,/', '.', $descripcion0);
$descripcion2 = preg_replace("/'/", ' -', $descripcion1);
$descripcion3 = preg_replace("/;/", ' :', $nombre_productos2);
$descripcion = strtoupper(preg_replace('/"/', ' -', $descripcion3));
$numero_factura = $datos['numero_factura'];
$cod_paises = $datos['cod_paises'];
$porcentaje_vendedor = strtoupper($datos['porcentaje_vendedor']);
$fechas_vencimiento = $_POST['fechas_vencimiento'];
$pagina = $_POST['pagina'];

$fecha_orig = date("d/m/Y");
$fecha = strtotime(date("Y/m/d"));
$fecha_mes = date("m/Y");
$fechas_dia = strtotime(date("Y/m/d"));
$fechas_mes = date("m/Y");
$fecha_anyo = time();
$anyo_ = date("Y");
$fecha_hora = date("H:i:s");
$ip = $_SERVER['REMOTE_ADDR'];
$cuenta = $cuenta_actual;
$fecha_time = time();

$dato_fecha = explode('/', $fechas_vencimiento);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];
$fechas_vencimiento_Y_m_d = $anyo.'/'.$mes.'/'.$dia;
$fechas_vencimiento_seg = strtotime($fechas_vencimiento_Y_m_d);

$actualizar_sql1 = sprintf("UPDATE productos SET unidades_faltantes = '$unidades_faltantes', fechas_dia = '$fechas_dia', fechas_mes = '$fechas_mes', 
fechas_anyo = '$fecha_anyo', fechas_hora = '$fecha_hora', fechas_vencimiento = '$fechas_vencimiento',fechas_vencimiento_seg = '$fechas_vencimiento_seg', 
vendedor = '$cuenta_actual' WHERE cod_productos_var = '$cod_productos_var'");
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());

$agregar_operacion = "INSERT INTO productos_copia_seguridad (cod_productos_var, nombre_productos, cod_marcas, 
cod_proveedores, cod_nomenclatura, cod_tipo, cod_lineas, cod_ccosto, cod_paises, numero_factura, unidades, cajas, unidades_total, 
unidades_faltantes, und_orig, precio_compra, precio_costo, precio_venta, vlr_total_compra, vlr_total_venta, cod_interno, 
tope_minimo, descuento, ip, codificacion, url, cod_original, detalles, 
descripcion, dto1, dto2, iva, iva_v, fechas_dia, fechas_mes, fechas_anyo, fechas_hora, fechas_vencimiento, porcentaje_vendedor, 
fechas_vencimiento_seg, fechas_agotado, fechas_agotado_seg, vendedor, cuenta)
VALUES ('$cod_productos_var', '$nombre_productos', '$cod_marcas', '$cod_proveedores', '$cod_nomenclatura', '$cod_tipo', '$cod_lineas', 
'$cod_ccosto', '$cod_paises', '$numero_factura', '$unidades', '$cajas', '$unidades_total', '$unidades_faltantes', '$und_orig', '$precio_compra', 
'$precio_costo', '$precio_venta', '$vlr_total_compra', '$vlr_total_venta', '$cod_interno', '$tope_minimo', 
'$descuento', '$ip', '$codificacion', '$url', 
'$cod_original', '$detalles', '$descripcion', '$dto1', '$dto2', '$iva', '$iva_v', '$fechas_dia', '$fechas_mes', '$fecha_anyo', '$fecha_hora', 
'$fechas_vencimiento', '$porcentaje_vendedor', '$fechas_vencimiento_seg', '$fechas_agotado', '$fechas_agotado_seg', '$cuenta', '$cuenta')";
$resultado_operacion = mysql_query($agregar_operacion, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/<?php echo $pagina ?>">
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 