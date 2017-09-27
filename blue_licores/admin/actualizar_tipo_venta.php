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
$tipo_precio = $_GET['tipo_precio'];

$sql_temporal = "SELECT * FROM temporal WHERE cod_temporal = '$cod_temporal'";
$consulta_temporal = mysql_query($sql_temporal, $conectar) or die(mysql_error());
$temporal = mysql_fetch_assoc($consulta_temporal);

$cod_productos_var = $temporal['cod_productos'];
$unidades_vendidas = $temporal['unidades_vendidas'];

$sql_productos = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$consulta_productos = mysql_query($sql_productos, $conectar) or die(mysql_error());
$productos = mysql_fetch_assoc($consulta_productos);

$detalles = $tipo_precio;

if ($tipo_precio == 'P.V') {
$precio_venta =  $productos['precio_venta'];
$vlr_total_venta = $unidades_vendidas * $precio_venta;

$actualizar_sql = sprintf("UPDATE temporal SET tipo_venta=%s, detalles=%s, precio_venta=%s, vlr_total_venta=%s WHERE cod_temporal=%s",
                       envio_valores_tipo_sql($tipo_precio, "text"),
                       envio_valores_tipo_sql($detalles, "text"),
                       envio_valores_tipo_sql($precio_venta, "text"),
                       envio_valores_tipo_sql($vlr_total_venta, "text"),
                       envio_valores_tipo_sql($cod_temporal, "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php
} elseif ($tipo_precio == 'P.D') {
$precio_venta =  $productos['vlr_total_venta'];
$vlr_total_venta = $unidades_vendidas * $precio_venta;

$actualizar_sql = sprintf("UPDATE temporal SET tipo_venta=%s, detalles=%s, precio_venta=%s, vlr_total_venta=%s WHERE cod_temporal=%s",
                       envio_valores_tipo_sql($tipo_precio, "text"),
                       envio_valores_tipo_sql($detalles, "text"),
                       envio_valores_tipo_sql($precio_venta, "text"),
                       envio_valores_tipo_sql($vlr_total_venta, "text"),
                       envio_valores_tipo_sql($cod_temporal, "text"));

$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php
} elseif ($tipo_precio == 'P.M') {
$precio_venta =  $productos['precio_compra'];
$vlr_total_venta = $unidades_vendidas * $precio_venta;

$actualizar_sql = sprintf("UPDATE temporal SET tipo_venta=%s, detalles=%s, precio_venta=%s, vlr_total_venta=%s WHERE cod_temporal=%s",
                       envio_valores_tipo_sql($tipo_precio, "text"),
                       envio_valores_tipo_sql($detalles, "text"),
                       envio_valores_tipo_sql($precio_venta, "text"),
                       envio_valores_tipo_sql($vlr_total_venta, "text"),
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
