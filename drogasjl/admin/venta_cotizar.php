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
date_default_timezone_set("America/Bogota");
require("funcion_verificar.php");
$requerir_funcion = new bloquear_multiple_intento;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<body>
<?php
$cod_factura = $_POST['numero_factura'];
$descuento_factura = $_POST['descuento_factura'];
$vlr_cancelado = $_POST['vlr_cancelado'];
$cod_clientes = $_POST['cod_clientes'];
$tipo_pago = $_POST['tipo_pago'];
$fecha_pago = $_POST['fecha_pago'];

$suma_temporal = "SELECT Sum(vlr_total_venta -(vlr_total_venta*($descuento_factura/100))) As total_venta, 
Sum((vlr_total_venta - (($descuento_factura/100)*vlr_total_venta))/((iva/100)+(100/100))) As subtotal_base, 
Sum(((vlr_total_venta - (($descuento_factura/100)*vlr_total_venta))/((iva/100)+(100/100)))*(iva/100)) As total_iva, 
Sum(vlr_total_venta*($descuento_factura/100)) AS total_desc, Sum(vlr_total_compra) AS vlr_total_compra FROM temporal_cotizar WHERE vendedor = '$cuenta_actual'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$suma_datos = mysql_fetch_assoc($consulta_temporal);

$subtotal_base = number_format($suma_datos['subtotal_base']);
$total_desc = number_format($suma_datos['total_desc']);
$total_iva = number_format($suma_datos['total_iva']);
$total_venta_temp = ($suma_datos['total_venta']);
$vlr_total_compra = ($suma_datos['vlr_total_compra']);

$flete = '0';
$vlr_vuelto = $vlr_cancelado - $total_venta_temp;
$estado = 'cerrado';

$venta_total = $total_venta_temp;
$subtotal = '0';
$vendedor = $cuenta_actual;
$ip = $_SERVER['REMOTE_ADDR'];
$total_datos = intval($_POST['total_datos']);
$pagina = $_POST['pagina'];
$devoluciones = 0;

$fecha_anyo = $_POST['fecha_anyo'];
$dato_fecha = explode('/', $fecha_anyo);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];
$fecha_dia = $anyo.'/'.$mes.'/'.$dia;
$fecha = strtotime($fecha_dia);
$fecha_mes = $mes.'/'.$anyo;
$fecha_hora = date("H:i:s");

$fecha_actual_hoy = date("Y/m/d");
$fechas_agotado = date("d/m/Y");
$fechas_agotado_seg = strtotime($fecha_actual_hoy);

$obtener_cliente = "SELECT * FROM clientes WHERE cod_clientes = '$cod_clientes'";
$resultado_cliente = mysql_query($obtener_cliente, $conectar) or die(mysql_error());
$matriz_cliente = mysql_fetch_assoc($resultado_cliente);

$nombre_cliente = $matriz_cliente['nombres'].' '.$matriz_cliente['apellidos'];

$sql_cuentas_cobrar = "SELECT * FROM cuentas_cobrar WHERE cod_clientes = '$cod_clientes'";
$modificar_cuentas_cobrar = mysql_query($sql_cuentas_cobrar, $conectar) or die(mysql_error());
$total_encontrado_cobrar = mysql_num_rows($modificar_cuentas_cobrar);

$sql_info_imp_factura = "SELECT * FROM info_impuesto_facturas_cotizar WHERE cod_factura = '$cod_factura'";
$modificar_info_imp_factura = mysql_query($sql_info_imp_factura, $conectar) or die(mysql_error());
$total_encontrado_info_imp_factura = mysql_num_rows($modificar_info_imp_factura);

$sql_admin = "SELECT cod_base_caja, cuenta FROM administrador WHERE cuenta = '$cuenta_actual'";
$consulta_admin = mysql_query($sql_admin, $conectar) or die(mysql_error());
$matriz_admin = mysql_fetch_assoc($consulta_admin);

$cod_base_caja = $matriz_admin['cod_base_caja'];

//-------------------------------------- LLAVE DE ENTRADA DEL CONDICIONAL VENDER POR CONTADO ------------------------------------------//
if (($venta_total >= $vlr_total_compra) && $requerir_funcion->bloquear($_POST['verificador'])) {

for ($i=0; $i < $total_datos; $i++) {
$cod_productos = $_POST['cod_productos'][$i];

$sql_mconsulta = "SELECT * FROM temporal_cotizar WHERE cod_productos = '$cod_productos' AND vendedor = '$cuenta_actual'";
$mconsulta = mysql_query($sql_mconsulta, $conectar) or die(mysql_error());
$datos_temp = mysql_fetch_assoc($mconsulta);

$tipo = $datos_temp['detalles'];


$und_vend_orig = $datos_temp['unidades_vendidas'];
$precio_compra = $datos_temp['precio_compra'];
$precio_costo = $datos_temp['precio_costo'];

$vlr_total_venta = $datos_temp['vlr_total_venta'];
$vlr_total_compra_temp = $datos_temp['vlr_total_compra'];
$iva = $datos_temp['iva'];
$descuento_ptj = $descuento_factura;
$descuento = $datos_temp['descuento'];
$precio_compra_con_descuento = $datos_temp['precio_compra_con_descuento'];
$vendedor = $datos_temp['vendedor'];
$porcentaje_vendedor = $datos_temp['porcentaje_vendedor'];
$detalles = $datos_prod['detalles'];
$nombre_lineas = $datos_temp['nombre_lineas'];
$nombre_ccosto = $datos_temp['nombre_ccosto'];
$tipo_pago = '1';
//----------------------------- INSERTAR PRODUCTOS A LAS ventas_cotizar -----------------------------//
$agregar_reg_ventas = "INSERT INTO ventas_cotizar (cod_productos, cod_factura, cod_clientes, cod_base_caja, nombre_productos, unidades_vendidas, 
und_vend_orig, devoluciones, precio_compra, precio_costo, precio_venta, vlr_total_venta, vlr_total_compra, iva, descuento_ptj, descuento, 
precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, anyo, fecha_hora, porcentaje_vendedor, detalles, tipo_pago, fecha_orig, 
nombre_lineas, nombre_ccosto)
VALUES ('$cod_productos', '$cod_factura', '$cod_clientes', '$cod_base_caja', '$nombre_productos', '$unidades_vendidas', '$und_vend_orig', 
'$devoluciones', '$precio_compra', '$precio_costo', '$precio_venta', '$vlr_total_venta', '$vlr_total_compra_temp', '$iva', '$descuento_ptj', '$descuento', 
'$precio_compra_con_descuento', '$vendedor', '$ip', '$fecha', '$fecha_mes', '$fecha_anyo', '$anyo', '$fecha_hora', '$porcentaje_vendedor', 
'$detalles', '$tipo_pago', '$fecha_anyo', '$nombre_lineas', '$nombre_ccosto')";
$resultado_ventas = mysql_query($agregar_reg_ventas, $conectar) or die(mysql_error());
}
//----------------------------- INFO INPUESTO FACTURAS OPERACIONES -----------------------------//
if ($total_encontrado_info_imp_factura == 0) {
$agregar_regis = sprintf("UPDATE info_impuesto_facturas_cotizar SET cod_clientes = '$cod_clientes', cod_factura = '$cod_factura', 
descuento = '$descuento_factura', estado = '$estado', vendedor = '$cuenta_actual', fecha_dia = '$fecha', fecha_anyo = '$fecha_anyo', 
fecha_mes = '$fecha_mes', fecha_hora = '$fecha_hora' WHERE vendedor = '$cuenta_actual' AND estado = 'abierto'");
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());
} else {
$agregar_regis = sprintf("UPDATE info_impuesto_facturas_cotizar SET cod_clientes = '$cod_clientes', vendedor = '$cuenta_actual', fecha_dia = '$fecha', 
fecha_anyo = '$fecha_anyo', fecha_mes = '$fecha_mes', fecha_hora = '$fecha_hora', estado = '$estado', descuento = '$descuento_factura' 
WHERE vendedor = '$cuenta_actual' AND estado = 'abierto'");
$resultado_regis = mysql_query($agregar_regis, $conectar) or die(mysql_error());
}
//----------------------------- CALCULO TOTAL VENTA -----------------------------//
$suma_temporal = "SELECT Sum(vlr_total_venta -(vlr_total_venta*($descuento_factura/100))) As total_venta, 
Sum((vlr_total_venta - (($descuento_factura/100)*vlr_total_venta))/((iva/100)+(100/100))) As subtotal_base, 
Sum(((vlr_total_venta - (($descuento_factura/100)*vlr_total_venta))/((iva/100)+(100/100)))*(iva/100)) As total_iva, 
Sum(vlr_total_venta*($descuento_factura/100)) AS total_desc FROM temporal_cotizar WHERE vendedor = '$cuenta_actual'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_temporal);

$subtotal_base = number_format($suma['subtotal_base']);
$total_desc = number_format($suma['total_desc']);
$total_iva = number_format($suma['total_iva']);
$total_venta_temp = $suma['total_venta'];
?>
<center>
<table>
<td><font color='yellow' size= "+3">FACTURA NO:</font></td><td align='right'><font color='yellow' size= "+3"><?php echo $cod_factura; ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">CLIENTE:</font></td><td><font color='yellow' size= "+3"><?php echo $nombre_cliente; ?></td>
<tr></tr>
<td><font color='yellow' size= "+2">SUBTOTAL:</font></td><td align='right'><font color='yellow' size= "+3"><?php echo $subtotal_base; ?></td>
<tr></tr>
<td><font color='green' size= "+2">%DESCUENTO:</font></td><td align='right'><font color='green' size= "+2"><?php echo $descuento_factura.'%'; ?></td>
<tr></tr>
<td><font color='green' size= "+2">$DESCUENTO:</font></td><td align='right'><font color='green' size= "+2"><?php echo $total_desc; ?></td>
<tr></tr>
<td><font color='green' size= "+2">IVA:</font></td><td align='right'><font color='green' size= "+2"><?php echo $total_iva; ?></td>
<tr></tr>
<td><font color='yellow' size= "+3">TOTAL VENTA:</font></td><td align='right'><font color='yellow' size= "+3"><?php echo number_format($total_venta_temp); ?></td>
</table>
<br>
<table>
<form method="post" action="../admin/<?php echo $pagina;?>">
<td>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="image" id ="foco" src="../imagenes/listo.png" name="listo" value="listo" />
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_factura.php?numero_factura=<?php echo $cod_factura?>&descuento=<?php echo $descuento_factura?>&tipo_pago=<?php echo $tipo_pago?>&cod_clientes=<?php echo $cod_clientes?>" target="_blank"><img src=../imagenes/imprimir_1.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_factura_grande.php?numero_factura=<?php echo $cod_factura?>&descuento=<?php echo $descuento_factura?>&tipo_pago=<?php echo $tipo_pago?>&cod_clientes=<?php echo $cod_clientes?>" target="_blank"><img src=../imagenes/imprimir_.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</form>
</table>
</center>
<?php
//----------------------------- BORRAR DE TEMPORAL PRODUCTOS VENDIDOS -----------------------------//
$borrar_sql = sprintf("DELETE FROM temporal_cotizar WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

//require_once("../admin/caja_actualizar_valor.php"); 
} //-------------------------------------- LLAVE DE CIERRE DEL CONDICIONAL VENDER POR CONTADO ------------------------------------------//
?>
</body>
</html>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>