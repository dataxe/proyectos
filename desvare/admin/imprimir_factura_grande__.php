<?php 
//ob_start();
require_once('dompdf/dompdf_config.inc.php');
error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");

if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
$cod_factura = intval($_GET['numero_factura']);
$descuento = addslashes($_GET['descuento']);
$tipo_pago = intval($_GET['tipo_pago']);
$cod_clientes = intval($_GET['cod_clientes']);

$obtener_diseno = "SELECT * FROM disenos WHERE nombre_disenos LIKE 'por_defecto.css'";
$resultado_diseno = mysql_query($obtener_diseno, $conectar) or die(mysql_error());
$matriz_diseno = mysql_fetch_assoc($resultado_diseno); 

$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$dat = mysql_fetch_assoc($consultar_informacion);
?>

<?php
$obtener_info_fact = "SELECT * FROM info_impuesto_facturas WHERE cod_factura = '$cod_factura'";
$resultado_info_fact = mysql_query($obtener_info_fact, $conectar) or die(mysql_error());
$info_fact = mysql_fetch_assoc($resultado_info_fact);

$obtener_cliente = "SELECT * FROM clientes WHERE cod_clientes = '$cod_clientes'";
$resultado_cliente = mysql_query($obtener_cliente, $conectar) or die(mysql_error());
$matriz_cliente = mysql_fetch_assoc($resultado_cliente);

$nombre_cliente = $matriz_cliente['nombres'].' '.$matriz_cliente['apellidos'];
$cedula = $matriz_cliente['cedula'];
$direccion = $matriz_cliente['direccion'];

//----------------- CALCULOS PARA TIPOS DE PAGOS -----------------//
//----------------- PAGO POR CONTADO -----------------//
if ($tipo_pago == '1') {
$obtener_info_venta = "SELECT * FROM ventas WHERE cod_factura = '$cod_factura'";
$resultado_info_venta = mysql_query($obtener_info_venta, $conectar) or die(mysql_error());
$info_venta = mysql_fetch_assoc($resultado_info_venta);

$obtener_calculo_fact = "SELECT sum(vlr_total_venta) as vlr_total_venta FROM ventas WHERE cod_factura = '$cod_factura'";
$resultado_calculo_fact = mysql_query($obtener_calculo_fact, $conectar) or die(mysql_error());
$calculo_fact = mysql_fetch_assoc($resultado_calculo_fact);

$calculo_subtotal = $calculo_fact['vlr_total_venta'] - ($calculo_fact['vlr_total_venta'] * ($descuento/100));

$suma_temporal = "SELECT Sum(vlr_total_venta -(vlr_total_venta*($descuento/100))) As total_venta, 
Sum((vlr_total_venta - (($descuento/100)*vlr_total_venta))/((iva/100)+(100/100))) As subtotal_base, 
Sum(((vlr_total_venta - (($descuento/100)*vlr_total_venta))/((iva/100)+(100/100)))*(iva/100)) As total_iva, 
Sum(vlr_total_venta*($descuento/100)) AS total_desc, Sum(vlr_total_venta) AS total_venta_neta FROM ventas WHERE cod_factura = '$cod_factura'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_temporal);

$total_venta_neta = $suma['total_venta_neta'];
$subtotal_base = $suma['subtotal_base'];
$total_desc = $suma['total_desc'];
$total_iva = $suma['total_iva'];
$total_venta_temp = $suma['total_venta'];

//----------------- PAGO POR CREDITO -----------------//
} else {
$obtener_info_venta = "SELECT * FROM productos_fiados WHERE cod_factura = '$cod_factura'";
$resultado_info_venta = mysql_query($obtener_info_venta, $conectar) or die(mysql_error());
$info_venta = mysql_fetch_assoc($resultado_info_venta);

$obtener_calculo_fact = "SELECT sum(vlr_total_venta) as vlr_total_venta FROM productos_fiados WHERE cod_factura = '$cod_factura'";
$resultado_calculo_fact = mysql_query($obtener_calculo_fact, $conectar) or die(mysql_error());
$calculo_fact = mysql_fetch_assoc($resultado_calculo_fact);

$calculo_subtotal = $calculo_fact['vlr_total_venta'] - ($calculo_fact['vlr_total_venta'] * ($descuento/100));

$suma_temporal = "SELECT Sum(vlr_total_venta -(vlr_total_venta*($descuento/100))) As total_venta, 
Sum((vlr_total_venta - (($descuento/100)*vlr_total_venta))/((iva/100)+(100/100))) As subtotal_base, 
Sum(((vlr_total_venta - (($descuento/100)*vlr_total_venta))/((iva/100)+(100/100)))*(iva/100)) As total_iva, 
Sum(vlr_total_venta*($descuento/100)) AS total_desc, Sum(vlr_total_venta) AS total_venta_neta FROM productos_fiados WHERE cod_factura = '$cod_factura'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_temporal);

$total_venta_neta = $suma['total_venta_neta'];
$subtotal_base = $suma['subtotal_base'];
$total_desc = $suma['total_desc'];
$total_iva = $suma['total_iva'];
$total_venta_temp = $suma['total_venta'];
}
$codigoHTML='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<link href="../imagenes/'.$dat['icono'].'" type="image/x-icon" rel="shortcut icon" />

<title>'."Factura de Venta: ".$cod_factura.'</title>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>
<body>
<link rel="stylesheet" type="text/css" href="../estilo_css/por_defecto.css">
<style type="text/css"> <!--body { background-color: #333333;}--></style>

<table width="800" align="center">
<td align="center"><p style="font-size:20px">'.$dat['cabecera'].' - '.$dat['localidad'].'
<p style="font-size:20px">Res: '.$dat['res'].', Del '.$dat['res1'].' Al '.$dat['res2'].' 
- Direccion: '.$dat['direccion'].' - Tel: '.$dat['telefono'].'
<br>'.$dat['nit'].'</td>
</table>
<br>
<table width="800" align="center">
<td align="center"><p style="font-size:17px"><strong>Fact N&ordm;:</strong> '.$cod_factura.' | 
<strong>Fecha:</strong> '.$info_fact['fecha_anyo'].' | <strong>Hora:</strong> '.date("H:i").' | <strong>Cliente:</strong> '.$nombre_cliente.' | <strong>Nit:</strong> '.$cedula.' | <strong>Direccion:</strong> '.$direccion.' | 
<strong>Vendedor:</strong> '.$info_fact['vendedor'].' </p></td>
</table>

<br>
<table width="800" align="center">
<tr>
<td align="center"><strong><p style="font-size:17px">C&oacute;digo</strong></td>
<td></td>
<td align="center"><strong><p style="font-size:17px">Descripci&oacute;n</strong></td>
<td></td>
<td align="center"><strong><p style="font-size:17px">Und</strong></td>
<td align="center"><strong><p style="font-size:17px">.</strong></td>
<td align="right"><strong><p style="font-size:17px">V.unit</strong></td>
<td align="right"><strong><p style="font-size:17px">V.total</strong></td>
</tr>';
while ($info_venta = mysql_fetch_assoc($resultado_info_venta)) { 
$codigoHTML.='
<tr>
<td align="center"><p style="font-size:17px">'.$info_venta['cod_productos'].'</td>
<td></td>
<td align="center"><p style="font-size:17px">'.utf8_encode($info_venta['nombre_productos']).'</td>
<td></td>
<td align="center"><p style="font-size:17px">'.$info_venta['unidades_vendidas'].'</td>
<td align="center"><p style="font-size:17px">'.$info_venta['detalles'].'</td>
<td align="right"><p style="font-size:17px">'.number_format($info_venta['precio_venta'], 0, ",", ".").'</td>
<td align="right"><p style="font-size:17px">'.number_format($info_venta['vlr_total_venta'], 0, ",", ".").'</td>
</tr>';
}
$codigoHTML.='
<tr>
<tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr><tr></tr>
<td align="center"><p style="font-size:17px"><strong>Subtot </strong></td>
<td align="center"><p style="font-size:17px"><strong>%Desc </strong></td>
<td></td>
<td align="center"><p style="font-size:17px"><strong>$Desc </strong></td>
<td></td>
<td align="center"><p style="font-size:17px"><strong>Iva </strong></td>
<td></td>
<td align="right"><p style="font-size:17px"><strong>Total </strong></td>
</tr>
<tr>
<td align="center"><p style="font-size:17px">'.number_format($subtotal_base, 0, ",", ".").'</td>
<td align="center"><p style="font-size:17px">'.$descuento.'%'.'</td>
<td></td>
<td align="center"><p style="font-size:17px">'.number_format($total_desc, 0, ",", ".").'</td>
<td></td>
<td align="center"><p style="font-size:17px">'.number_format($total_iva, 0, ",", ".").'</td>
<td></td>
<td align="right"><p style="font-size:17px">'.number_format($total_venta_temp, 0, ",", ".").'</td>
</tr>
<td><input align="center" type="image" id ="foco" src="../imagenes/imprimir.png" name="imprimir" onClick="window.print();"/></td>
</table>
<body>
</html>';

echo $codigoHTML;
?>

<?php
/*
$codigoHTML=utf8_decode($codigoHTML);
$dompdf = new DOMPDF();
$dompdf->load_html($codigoHTML);
ini_set("memory_limit","500M");
$dompdf->render();
//$pdf = $dompdf->output();
$filename = 'nombre.pdf';
$dompdf->stream($filename);
*/
?>