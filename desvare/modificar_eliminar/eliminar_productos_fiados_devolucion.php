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
$cod_clientes = $_GET['cod_clientes'];
$cliente = $_GET['cliente'];
$cod_productos_fiados = $_GET['cod_productos_fiados'];
$pagina = $_GET['pagina'];

$datos_producto = "SELECT * FROM productos_fiados WHERE cod_productos_fiados = '$cod_productos_fiados'";
$consulta_toten = mysql_query($datos_producto, $conectar) or die(mysql_error());
$producto_factura = mysql_fetch_assoc($consulta_toten);

$cantidad = $producto_factura['unidades_vendidas'];

$fecha = date("d/m/Y - H:i:s");

$buscar_producto_actual = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$consulta_total = mysql_query($buscar_producto_actual, $conectar) or die(mysql_error());
$producto_actual = mysql_fetch_assoc($consulta_total);

$unidades_faltantes = $producto_actual['unidades_faltantes'] + $cantidad;
$unidades_vendidas = $producto_actual['unidades_vendidas'] - $cantidad;
$total_mercancia = $unidades_faltantes * $producto_actual['precio_compra'];
$total_venta = ($producto_actual['total_venta']) - ($producto_actual['precio_compra'] * $cantidad);
$total_utilidad = $producto_actual['total_utilidad'] - (($producto_actual['precio_venta'] - $producto_actual['precio_compra']) * $cantidad);

if ((isset($cod_productos)) && ($cod_productos != "")) {

$borrar_de_productos_fiados = sprintf("DELETE FROM productos_fiados WHERE cod_productos_fiados = '$cod_productos_fiados'");
$Resultado2 = mysql_query($borrar_de_productos_fiados, $conectar) or die(mysql_error());

$productos_regresados = sprintf("UPDATE productos SET unidades_faltantes = '$unidades_faltantes', unidades_vendidas = '$unidades_vendidas', 
total_mercancia = '$total_mercancia', total_venta = '$total_venta', total_utilidad = '$total_utilidad' WHERE cod_productos_var = '$cod_productos'");
$Resultado3 = mysql_query($productos_regresados, $conectar) or die(mysql_error());

$sql_prod_fiados = "SELECT Sum(vlr_total_venta) As vlr_total_venta FROM productos_fiados WHERE cod_clientes = '$cod_clientes'";
$modificar_prod_fiados = mysql_query($sql_prod_fiados, $conectar) or die(mysql_error());
$datos_fiad = mysql_fetch_assoc($modificar_prod_fiados);

$sum_abonos_valor = "SELECT Sum(abonado) AS abonado FROM cuentas_cobrar_abonos WHERE cod_clientes = '$cod_clientes'";
$consulta_sum_abonos = mysql_query($sum_abonos_valor, $conectar) or die(mysql_error());
$sum_abonos = mysql_fetch_assoc($consulta_sum_abonos);

$monto_deuda = $datos_fiad['vlr_total_venta'];
$abonado = $sum_abonos['abonado'];
$subtotal = $monto_deuda - $abonado;

$actualizar_sql = sprintf("UPDATE cuentas_cobrar SET monto_deuda = '$monto_deuda', abonado = '$abonado', subtotal = '$subtotal' 
WHERE cod_clientes = '$cod_clientes'");
$resultado_actualizacion = mysql_query($actualizar_sql, $conectar) or die(mysql_error());

$factura_producto_cancelado = sprintf("INSERT INTO factura_producto_cancelado (vendedor, cliente, cod_productos, cod_factura, 
nombre_productos, unidades_vendidas, vlr_unitario, vlr_total, fecha) VALUES  (%s, %s, %s, %s, %s, %s, %s, %s, %s)",
envio_valores_tipo_sql($cuenta_actual, "text"),
envio_valores_tipo_sql($producto_factura['cod_clientes'], "text"),
envio_valores_tipo_sql($producto_factura['cod_productos'], "text"),
envio_valores_tipo_sql($producto_factura['cod_factura'], "text"),
envio_valores_tipo_sql($producto_factura['nombre_productos'], "text"),
envio_valores_tipo_sql($cantidad, "text"),
envio_valores_tipo_sql($producto_factura['precio_venta'], "text"),
envio_valores_tipo_sql($producto_factura['vlr_total_venta'], "text"),		
envio_valores_tipo_sql($fecha, "text"));
$Resultado4 = mysql_query($factura_producto_cancelado, $conectar) or die(mysql_error());

echo "<META HTTP-EQUIV='REFRESH' CONTENT='0.1; ../admin/productos_fiados.php?cod_clientes=$cod_clientes&cliente=$cliente'>";
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
