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

if (isset($_GET['cod_productos'])) {

$cod_productos = intval($_GET['cod_productos']);
$pagina = $_GET['pagina'];

$sql_productos_temporal = "SELECT * FROM productos_temporal WHERE cod_productos = '$cod_productos'";
$consulta_productos_temporal = mysql_query($sql_productos_temporal, $conectar) or die(mysql_error());
$datos_productos_temporal = mysql_fetch_assoc($consulta_productos_temporal);

$sql_productos = "SELECT * FROM productos WHERE cod_productos = '$cod_productos'";
$consulta_productos = mysql_query($sql_productos, $conectar) or die(mysql_error());
$datos_productos = mysql_fetch_assoc($consulta_productos);
//--------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------//
$cod_productos_var = $datos_productos_temporal['cod_productos_var'];
$cod_marcas = $datos_productos_temporal['cod_marcas'];
$cod_proveedores = $datos_productos_temporal['cod_proveedores'];
$cajas = $datos_productos_temporal['cajas'];
$detalles = $datos_productos_temporal['detalles'];
$iva = $datos_productos_temporal['iva'];
$unidades = $datos_productos_temporal['unidades'];
$precio_costo = $datos_productos_temporal['precio_costo'];
$precio_venta = $datos_productos_temporal['precio_venta'];
$precio_compra = $datos_productos_temporal['precio_compra'];
$vlr_total_venta = $datos_productos_temporal['vlr_total_venta'];
$cod_interno = $datos_productos_temporal['cod_interno'];

$unidades_faltantes_productos = $datos_productos['unidades_faltantes'];
$unidades_faltantes_productos_temporal = $datos_productos_temporal['unidades_faltantes'];

$unidades_faltantes = $unidades_faltantes_productos + $unidades_faltantes_productos_temporal;

//--------------------------------------------------------------------------------------------------------------------------//
//--------------------------------------------------------------------------------------------------------------------------//
$actualizar_kardex = sprintf("UPDATE productos SET cod_marcas = '$cod_marcas', cod_proveedores = '$cod_proveedores', cajas = '$cajas', 
detalles = '$detalles', iva = '$iva', unidades = '$unidades', precio_costo = '$precio_costo', precio_venta = '$precio_venta', 
precio_compra = '$precio_compra', vlr_total_venta = '$vlr_total_venta', cod_interno = '$cod_interno', unidades_faltantes = '$unidades_faltantes' 
WHERE cod_productos_var = '$cod_productos_var'");
$resultado_actualiza_kardex = mysql_query($actualizar_kardex, $conectar) or die(mysql_error());

$borrar_sql = sprintf("DELETE FROM productos_temporal WHERE cod_productos = '$cod_productos'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0; <?php echo $pagina ?>">
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>
<br>
<body>
</body>
</html>
