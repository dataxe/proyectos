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
include ("../formato_entrada_sql/funcion_env_val_sql.php");

$pagina = $_GET['pagina'];
$cod_temporal = $_GET['cod_temporal'];
$tipo_venta = $_GET['tipo_venta'];

$sql_temporal = "SELECT * FROM temporal WHERE cod_temporal = '$cod_temporal'";
$consulta_temporal = mysql_query($sql_temporal, $conectar) or die(mysql_error());
$temporal = mysql_fetch_assoc($consulta_temporal);

$cod_productos_var = $temporal['cod_productos'];
$unidades_vendidas = $temporal['unidades_vendidas'];

$sql_productos = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$consulta_productos = mysql_query($sql_productos, $conectar) or die(mysql_error());
$productos = mysql_fetch_assoc($consulta_productos);

$precio_costo_t0 = $productos['precio_costo'];
$precio_costo_t1 = $productos['precio_compra'];

$precio_venta_t0 = $productos['precio_venta'];
$precio_venta_t1 = $productos['vlr_total_venta'];

$unidades_t0 = $unidades_vendidas * $productos['unidades'];
$unidades_t1 = $unidades_vendidas / $productos['unidades'];

$vlr_total_venta_t0 = $precio_venta_t0 * $unidades_t0;
$vlr_total_venta_t1 = $precio_venta_t1 * $unidades_t1;

$detalles_0 = $productos['detalles'];
$detalles_temporal = $temporal['detalles'];
$detalles_1 = 'UND';

if ($tipo_venta == '1') {
$actualizar_sql = sprintf("UPDATE temporal SET tipo_venta=%s, detalles=%s, precio_costo=%s, precio_venta=%s, vlr_total_venta=%s, unidades_vendidas=%s WHERE cod_temporal=%s",
                       envio_valores_tipo_sql($tipo_venta, "text"),
                       envio_valores_tipo_sql($detalles_1, "text"),
                       envio_valores_tipo_sql($precio_costo_t1, "text"),
                       envio_valores_tipo_sql($precio_venta_t1, "text"),
                       envio_valores_tipo_sql($vlr_total_venta_t1, "text"),
                       envio_valores_tipo_sql($unidades_t1, "text"),
                       envio_valores_tipo_sql($cod_temporal, "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php
} else {
$actualizar_sql = sprintf("UPDATE temporal SET tipo_venta=%s, detalles=%s, precio_costo=%s, precio_venta=%s, vlr_total_venta=%s, unidades_vendidas=%s WHERE cod_temporal=%s",
                       envio_valores_tipo_sql($tipo_venta, "text"),
                       envio_valores_tipo_sql($detalles_0, "text"),
                       envio_valores_tipo_sql($precio_costo_t0, "text"),
                       envio_valores_tipo_sql($precio_venta_t0, "text"),
                       envio_valores_tipo_sql($vlr_total_venta_t0, "text"),
                       envio_valores_tipo_sql($unidades_t0, "text"),
                       envio_valores_tipo_sql($cod_temporal, "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title></title>
</head>
<body>
</body>
</html>
