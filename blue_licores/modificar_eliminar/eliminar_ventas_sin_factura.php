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

$cod_productos = $_GET['cod_productos'];
$cod_ventas = $_GET['cod_ventas'];

if ((isset($cod_productos)) && ($cod_productos != "")) {
$datos_producto_venta = "SELECT * FROM ventas WHERE cod_productos LIKE '$cod_productos' AND cod_ventas LIKE '$cod_ventas'";
$consulta_toten = mysql_query($datos_producto_venta, $conectar) or die(mysql_error());
$producto_venta = mysql_fetch_assoc($consulta_toten);

$cantidad = $producto_venta['unidades_vendidas'];
	 
$datos_producto_actual = "SELECT * FROM productos WHERE cod_productos_var = $cod_productos";
$consulta_total = mysql_query($datos_producto_actual, $conectar) or die(mysql_error());
$producto_actual = mysql_fetch_assoc($consulta_total);

$unidades_faltantes = $producto_actual['unidades_faltantes'] + $cantidad;
$unidades_vendidas = $producto_actual['unidades_vendidas'] - $cantidad;
$total_mercancia = $producto_actual['total_mercancia'] + ($producto_actual['precio_compra'] * $cantidad);
$total_venta = ($producto_actual['total_venta']) - ($producto_actual['precio_compra'] * $cantidad);
$total_utilidad = $producto_actual['total_utilidad'] - (($producto_actual['precio_venta'] - $producto_actual['precio_compra']) * $cantidad);
			
$productos_regresados = sprintf("UPDATE productos SET unidades_faltantes=%s, unidades_vendidas=%s, total_mercancia=%s, total_venta=%s, total_utilidad=%s 
  WHERE cod_productos_var=%s",
$unidades_faltantes,
$unidades_vendidas,
$total_mercancia,
$total_venta,
$total_utilidad,
$cod_productos);

$sin_factura = "sin_factura";
$factura_producto_cancelado = sprintf("INSERT INTO factura_producto_cancelado (cod_factura_producto_cancelado, vendedor, cod_productos, cod_factura, nombre_productos, unidades_vendidas, vlr_unitario, fecha) VALUES  (%s, %s, %s, %s, %s, %s, %s, %s)",
envio_valores_tipo_sql($_POST['cod_factura_producto_cancelado'], "text"),
envio_valores_tipo_sql($producto_venta['vendedor'], "text"),
envio_valores_tipo_sql($producto_venta['cod_productos'], "text"),
envio_valores_tipo_sql($sin_factura, "text"),
envio_valores_tipo_sql($producto_venta['nombre_productos'], "text"),
envio_valores_tipo_sql($producto_venta['unidades_vendidas'], "text"),
envio_valores_tipo_sql($producto_venta['precio_venta'], "text"),
envio_valores_tipo_sql($producto_venta['fecha'], "text"));

$borrar_de_ventas = sprintf("DELETE FROM ventas WHERE cod_productos = $cod_productos AND cod_ventas LIKE '$cod_ventas'",
$cod_productos);
				   			   
$Resultado2 = mysql_query($borrar_de_ventas, $conectar) or die(mysql_error());
$Resultado3 = mysql_query($productos_regresados, $conectar) or die(mysql_error());
$Resultado4 = mysql_query($factura_producto_cancelado, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.2; ../admin/eliminar_ventas_sin_factura.php">';
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
