<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
      } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
date_default_timezone_set("America/Bogota");
include ("../formato_entrada_sql/funcion_env_val_sql.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php 
$tipo_pago = $_POST['tipo_pago'];
$numero_factura = $_POST['numero_factura'];
$cod_proveedores = $_POST['cod_proveedores'];
$valor_bruto = $_POST['valor_bruto'];
$descuento = $_POST['descuento_factura'];
$valor_neto = $_POST['valor_neto'];
$valor_iva = addslashes($_POST['valor_iva']);
$total = addslashes($_POST['total']);
$fecha = $_POST['fecha'];
$vendedor = $cuenta_actual;
$ip = $_SERVER['REMOTE_ADDR'];
$fechas_dia = date("Y/m/d");
$fechas_mes = date("m/Y");
$fechas_anyo = date("d/m/Y");
$fechas_hora = date("H:i:s");
$unidades_vendidas = "0";
$abonado = "0";

// ------------------- FACTURAS INTRODUCIDAS POR CONTADO --------------------------------
if (isset($_POST['verificacion']) && ($_POST['tipo_pago'])) {

$actualizar_sql1 = "INSERT INTO productos2 (cod_productos, cod_factura, nombre_productos, cod_original, codificacion, cajas, unidades, unidades_total, unidades_vendidas, 
precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, detalles, porcentaje_vendedor, descuento, dto1, dto2, iva, iva_v, ptj_ganancia, valor_iva, 
tope_min, precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, fecha_hora, fechas_vencimiento, fechas_vencimiento_seg) 
SELECT cod_productos, '$numero_factura', nombre_productos, cod_original, codificacion, cajas, unidades, unidades_total, unidades_vendidas, precio_compra, precio_costo, 
precio_venta, vlr_total_venta, vlr_total_compra, detalles, porcentaje_vendedor, descuento, dto1, dto2, iva, iva_v, ptj_ganancia, valor_iva, tope_min, 
precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, fecha_hora, fechas_vencimiento, fechas_vencimiento_seg
FROM cargar_factura_temporal2 WHERE vendedor = '$cuenta_actual'";

$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());

$agre_cuentas_facturas = "INSERT INTO cuentas_facturas2 (cod_factura, tipo_pago, cod_proveedores, valor_bruto, descuento, valor_neto, valor_iva, 
total, fecha_pago)
VALUES ('$numero_factura', '$tipo_pago', '$cod_proveedores', '$valor_bruto', '$descuento', '$valor_neto', '$valor_iva', '$total', '$fecha')";

$resultado_cuentas_facturas = mysql_query($agre_cuentas_facturas, $conectar) or die(mysql_error());

$borrar_sql = sprintf("DELETE FROM cargar_factura_temporal2 WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

echo "<center><font size='6' color='yellow'>LA FACTURA SE HA INTRODUCIDO EXITOSAMENTE</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; ../admin/cargar_factura_temporal_vendedor.php">';
}
?>
</body>
</html>