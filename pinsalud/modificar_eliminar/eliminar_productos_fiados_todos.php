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

$cod_factura = $_GET['cod_factura'];
$pagina = $_GET['pagina'];

$datos_productos_fiados = "SELECT * FROM productos_fiados WHERE cod_factura = '$cod_factura'";
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
$devoluciones = '0';
$ip = $_SERVER['REMOTE_ADDR'];


if ((isset($cod_factura)) && ($deuda <= "0")) {

$insertar_a_ventas = "INSERT INTO ventas (cod_productos, cod_factura, cod_clientes, tipo_pago, cod_base_caja, nombre_productos, unidades_vendidas, 
precio_venta, precio_compra, precio_costo, vlr_total_venta, vlr_total_compra, descuento_ptj, nombre_ccosto, iva, iva_v, vendedor, cuenta, descuento, precio_compra_con_descuento, 
ip, nombre_lineas, fecha_orig, fecha_mes, fecha_anyo, fecha, anyo, fecha_hora, porcentaje_vendedor, detalles, und_vend_orig, devoluciones) 
SELECT cod_productos, '$cod_factura', cod_clientes, tipo_pago, '$cod_base_caja', nombre_productos, unidades_vendidas, precio_venta, precio_compra, 
precio_costo, vlr_total_venta, vlr_total_compra, descuento_ptj, nombre_ccosto, iva, iva_v, vendedor, '$cuenta_actual', descuento, precio_compra_con_descuento, '$ip', 
nombre_lineas, fecha_anyo, '$fecha_mes', '$fecha_anyo', '$fecha', '$anyo', '$fecha_hora', porcentaje_vendedor, detalles, und_vend_orig, 
devoluciones FROM productos_fiados WHERE cod_factura = '$cod_factura'";
$resultado_ventas = mysql_query($insertar_a_ventas, $conectar) or die(mysql_error());

$borrar_de_productos_fiados  = sprintf("DELETE FROM productos_fiados WHERE cod_factura = '$cod_factura'", $cod_factura );
$Resultado2 = mysql_query($borrar_de_productos_fiados , $conectar) or die(mysql_error());

require_once("../admin/caja_actualizar_valor.php");
?>
<META HTTP-EQUIV='REFRESH' CONTENT="0.1; ../admin/<?php echo $pagina?>">
<?php
} else {?>
<br><br><br><center><td><strong><font color='yellow' size="4">LOS PRODUCTO NO PUEDE SER ENVIADO A LAS VENTAS PORQUE EL CLIENTE NO HA PAGADO LA DEUDA </font></strong></td></center><br>

<META HTTP-EQUIV='REFRESH' CONTENT="5; ../admin/<?php echo $pagina?>">
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
