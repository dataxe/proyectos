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
$cod_factura = $_GET['cod_factura'];
$pagina = $_GET['pagina'];

$datos_productos_fiados = "SELECT * FROM productos_fiados WHERE cod_productos_fiados = '$cod_productos_fiados'";
$consulta_toten = mysql_query($datos_productos_fiados, $conectar) or die(mysql_error());
$productos_fiados = mysql_fetch_assoc($consulta_toten);

$verific_cuentas_cobrar = "SELECT * FROM  cuentas_cobrar WHERE cod_factura = '$cod_factura'";
$consulta_cuentas_cobrar = mysql_query($verific_cuentas_cobrar, $conectar) or die(mysql_error());
$verific = mysql_fetch_assoc($consulta_cuentas_cobrar);

$sql_admin = "SELECT cod_base_caja, cuenta FROM administrador WHERE cuenta = '$cuenta_actual'";
$consulta_admin = mysql_query($sql_admin, $conectar) or die(mysql_error());
$matriz_admin = mysql_fetch_assoc($consulta_admin);

$deuda = $verific['subtotal'];
$cod_base_caja = $matriz_admin['cod_base_caja'];

$fecha_mes = date("m/Y");
$fecha_anyo = date("d/m/Y");
$fecha_hora = date("H:i:s");
$anyo = date("Y");
$fecha = strtotime(date("Y/m/d"));

if ((isset($cod_productos_fiados)) && ($deuda <= "0")) {
$insertar_a_ventas = sprintf("INSERT INTO ventas (cod_productos, cod_factura, cod_clientes, tipo_pago, cod_base_caja, nombre_productos, 
unidades_vendidas, precio_venta, vlr_total_venta, vendedor, cuenta, precio_compra, vlr_total_compra, iva, iva_v, descuento, 
precio_compra_con_descuento, und_vend_orig, devoluciones, ip, nombre_lineas, porcentaje_vendedor, descuento_ptj, nombre_ccosto, detalles, precio_costo, fecha_orig, fecha_mes, fecha_anyo, fecha_hora, anyo, fecha) 
VALUES  (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
envio_valores_tipo_sql($productos_fiados['cod_productos'], "text"),
envio_valores_tipo_sql($productos_fiados['cod_factura'], "text"),
envio_valores_tipo_sql($productos_fiados['cod_clientes'], "text"),
envio_valores_tipo_sql($productos_fiados['tipo_pago'], "text"),
envio_valores_tipo_sql($cod_base_caja, "text"),
envio_valores_tipo_sql($productos_fiados['nombre_productos'], "text"),
envio_valores_tipo_sql($productos_fiados['unidades_vendidas'], "text"),
envio_valores_tipo_sql($productos_fiados['precio_venta'], "text"),
envio_valores_tipo_sql($productos_fiados['vlr_total_venta'], "text"),	
envio_valores_tipo_sql($productos_fiados['vendedor'], "text"),
envio_valores_tipo_sql($cuenta_actual, "text"),
envio_valores_tipo_sql($productos_fiados['precio_compra'], "text"),
envio_valores_tipo_sql($productos_fiados['vlr_total_compra'], "text"),
envio_valores_tipo_sql($productos_fiados['iva'], "text"),
envio_valores_tipo_sql($productos_fiados['iva_v'], "text"),
envio_valores_tipo_sql($productos_fiados['descuento'], "text"),
envio_valores_tipo_sql($productos_fiados['precio_compra_con_descuento'], "text"),
envio_valores_tipo_sql($productos_fiados['und_vend_orig'], "text"),
envio_valores_tipo_sql($productos_fiados['devoluciones'], "text"),
envio_valores_tipo_sql($productos_fiados['ip'], "text"),
envio_valores_tipo_sql($productos_fiados['nombre_lineas'], "text"),
envio_valores_tipo_sql($productos_fiados['porcentaje_vendedor'], "text"),
envio_valores_tipo_sql($productos_fiados['descuento_ptj'], "text"),
envio_valores_tipo_sql($productos_fiados['nombre_ccosto'], "text"),
envio_valores_tipo_sql($productos_fiados['detalles'], "text"),
envio_valores_tipo_sql($productos_fiados['precio_costo'], "text"),
envio_valores_tipo_sql($productos_fiados['fecha_anyo'], "text"),
envio_valores_tipo_sql($fecha_mes, "text"),
envio_valores_tipo_sql($fecha_anyo, "text"),	
envio_valores_tipo_sql($fecha_hora, "text"),
envio_valores_tipo_sql($anyo, "text"),
envio_valores_tipo_sql($fecha, "text"));

$Resultado4 = mysql_query($insertar_a_ventas, $conectar) or die(mysql_error());

$borrar_de_productos_fiados  = sprintf("DELETE FROM productos_fiados WHERE cod_productos_fiados = '$cod_productos_fiados'", $cod_productos_fiados );
$Resultado2 = mysql_query($borrar_de_productos_fiados , $conectar) or die(mysql_error());

require_once("../admin/caja_actualizar_valor.php"); 
?>
<META HTTP-EQUIV='REFRESH' CONTENT="0.1; ../admin/<?php echo $pagina.'.php'?>?cod_factura=<?php echo $cod_factura?>">
?>
<?php
} else {?>
<br><br><br><center><td><strong><font color='yellow' size="4">EL PRODUCTO NO PUEDE SER ENVIADO A LAS VENTAS PORQUE EL CLIENTE NO HA PAGADO LA DEUDA </font></strong></td></center><br>

<META HTTP-EQUIV='REFRESH' CONTENT="5; ../admin/<?php echo $pagina.'.php'?>?cod_factura=<?php echo $cod_factura?>">
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
