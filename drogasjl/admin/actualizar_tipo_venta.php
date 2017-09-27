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

$cod_temporal = $_GET['cod_temporal'];
$cod_productos_var = $_GET['cod_productos'];
$tipo_venta = $_GET['tipo_venta'];
$pagina = $_GET['pagina'];

$sql_productos = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$consulta_productos = mysql_query($sql_productos, $conectar) or die(mysql_error());
$productos = mysql_fetch_assoc($consulta_productos);

$sql_temporal = "SELECT * FROM temporal WHERE cod_temporal = '$cod_temporal'";
$consulta_temporal = mysql_query($sql_temporal, $conectar) or die(mysql_error());
$temporal = mysql_fetch_assoc($consulta_productos);

$unidades = $productos['unidades'];
$precio_venta_caj = $productos['vlr_total_venta'];
$precio_venta_men = $productos['precio_venta'];
$unidades_men = '1';

$unidades_vendidas = $temporal['unidades_vendidas'];
$vlr_total_venta_caj = $unidades * $precio_venta_caj;
$vlr_total_venta_men = $unidades_men * $precio_venta_men;

if ($tipo_venta == '1') {
$actualizar_sql1 = sprintf("UPDATE temporal SET unidades_vendidas = '$unidades', precio_venta = '$precio_venta_caj', vlr_total_venta = '$vlr_total_venta_caj', 
tipo_venta = '$tipo_venta' WHERE cod_temporal = '$cod_temporal'");

$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php

} else {
$actualizar_sql1 = sprintf("UPDATE temporal SET unidades_vendidas = '$unidades_men', precio_venta = '$precio_venta_men', vlr_total_venta = '$vlr_total_venta_men', tipo_venta = '$tipo_venta'
WHERE cod_temporal = '$cod_temporal'");

$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; <?php echo $pagina?>">
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
</body>
</html>
