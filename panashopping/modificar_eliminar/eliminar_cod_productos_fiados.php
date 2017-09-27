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

$cod_productos_fiados = $_GET['cod_productos_fiados'];
$sin_factura ='sin_factura';

$datos_cuentas_cobrar = "SELECT * FROM productos_fiados WHERE cod_productos_fiados like '$cod_productos_fiados'";
$consulta_toten = mysql_query($datos_cuentas_cobrar, $conectar) or die(mysql_error());
$producto_cuentas_cobrar = mysql_fetch_assoc($consulta_toten);

$fecha_mes = date("m/Y");
$fecha_anyo = date("d/m/Y");
$fecha_hora = date("H:i:s");
$anyo = date("Y");
$fecha = date("Y/m/d");

if ((isset($cod_cuenta_cobrar)) && ($cod_cuenta_cobrar != "")) {
$insertar_a_ventas = sprintf("INSERT INTO ventas (cod_productos, cod_factura, cod_clientes, nombre_productos, unidades_vendidas, precio_venta, 
	vlr_total_venta, vendedor, precio_compra, vlr_total_compra, descuento, precio_compra_con_descuento, ip, fecha_mes, fecha_anyo, fecha_hora, anyo, fecha) 
	VALUES  (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
envio_valores_tipo_sql($producto_cuentas_cobrar['cod_productos'], "text"),
envio_valores_tipo_sql($producto_cuentas_cobrar['cod_factura'], "text"),
envio_valores_tipo_sql($producto_cuentas_cobrar['cod_clientes'], "text"),
envio_valores_tipo_sql($producto_cuentas_cobrar['nombre_productos'], "text"),
envio_valores_tipo_sql($producto_cuentas_cobrar['unidades_vendidas'], "text"),
envio_valores_tipo_sql($producto_cuentas_cobrar['precio_venta'], "text"),
envio_valores_tipo_sql($producto_cuentas_cobrar['vlr_total_venta'], "text"),	
envio_valores_tipo_sql($producto_cuentas_cobrar['vendedor'], "text"),
envio_valores_tipo_sql($producto_cuentas_cobrar['precio_compra'], "text"),
envio_valores_tipo_sql($producto_cuentas_cobrar['vlr_total_compra'], "text"),
envio_valores_tipo_sql($producto_cuentas_cobrar['descuento'], "text"),	
envio_valores_tipo_sql($producto_cuentas_cobrar['precio_compra_con_descuento'], "text"),
envio_valores_tipo_sql($producto_cuentas_cobrar['ip'], "text"),
envio_valores_tipo_sql($fecha_mes, "text"),
envio_valores_tipo_sql($fecha_anyo, "text"),	
envio_valores_tipo_sql($fecha_hora, "text"),
envio_valores_tipo_sql($anyo, "text"),
envio_valores_tipo_sql($fecha, "text"));

$Resultado4 = mysql_query($insertar_a_ventas, $conectar) or die(mysql_error());

$borrar_de_cuentas_cobrar  = sprintf("DELETE FROM productos_fiados WHERE cod_cuenta_cobrar = '$cod_cuenta_cobrar'", $cod_cuenta_cobrar );
$Resultado2 = mysql_query($borrar_de_cuentas_cobrar , $conectar) or die(mysql_error());

echo "<META HTTP-EQUIV='REFRESH' CONTENT='0.1; ../admin/productos_fiados.php'>";
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
