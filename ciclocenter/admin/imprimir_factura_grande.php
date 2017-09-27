<?php 
require_once('mpdf/mpdf.php');
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");

$cuenta_actual = addslashes($_SESSION['usuario']);
$cod_factura = intval($_GET['numero_factura']);
$descuento = addslashes($_GET['descuento']);
$tipo_pago = intval($_GET['tipo_pago']);
$cod_clientes = intval($_GET['cod_clientes']);
/*
$obtener_diseno = "SELECT * FROM disenos WHERE nombre_disenos LIKE 'por_defecto.css'";
$resultado_diseno = mysql_query($obtener_diseno, $conectar) or die(mysql_error());
$matriz_diseno = mysql_fetch_assoc($resultado_diseno); 
*/
$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$dat = mysql_fetch_assoc($consultar_informacion);

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

$fecha_pago = $info_venta['fecha_pago'];

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

$fecha_pago = $info_venta['fecha_pago'];

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
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'."Factura de Venta: ".$cod_factura.'</title>
</head>
<body>
<!--<link rel="stylesheet" type="text/css" href="../estilo_css/por_defecto.css">-->
<link href="../imagenes/'.$dat['icono'].'" type="image/x-icon" rel="shortcut icon" />

<div align="center">
<table width="95%">
<tr>
<td align="center"><p style="font-size:20px">'.$dat['cabecera'].'<br>'.$dat['nit'].'
<p style="font-size:20px">Res: '.$dat['res'].', Del '.$dat['res1'].' Al '.$dat['res2'].' 
<br>
Direccion: '.$dat['direccion'].' - Tel: '.$dat['telefono'].'
<br>'.$dat['localidad'].'</td>
</tr>
</table>

<table width="95%">
<tr>
<td align="left"><p style="font-size:20px">Factura N&ordm;: '.$cod_factura.'
<br>
Fecha: '.$info_fact['fecha_anyo'].' | Hora: '.date("H:i").' | Fecha Pago: '.$fecha_pago.'
<br>
Cliente: '. ucwords(strtolower(utf8_decode($nombre_cliente))).' | Nit: '.$cedula.' | Direccion: '.ucwords(strtolower($direccion)).' | 
Vendedor: '.ucwords(strtolower($info_fact['vendedor'])).' </p></td>
</tr>
</table>
<br>
<table width="95%">
<tr>
<td align="left"><strong>C&oacute;digo</strong></td>
<td align="center"><strong>Descripci&oacute;n</strong></td>
<td align="center"><strong>Und</strong></td>
<td align="center"><strong>.</strong></td>
<td align="center"><strong>V.unit</strong></td>
<td align="center"><strong>V.total</strong></td>
</tr>';
$resultado_info_venta = mysql_query("SELECT * FROM ventas WHERE cod_factura = '$cod_factura'");
while ($info_venta = mysql_fetch_assoc($resultado_info_venta)) { 
$codigoHTML.='
<tr>
<td align="left">'.$info_venta['cod_productos'].'</td>
<td align="left">'.ucfirst(strtolower($info_venta['nombre_productos'])).'</td>
<td align="center">'.$info_venta['unidades_vendidas'].'</td>
<td align="center">'.$info_venta['detalles'].'</td>
<td align="right">'.number_format($info_venta['precio_venta'], 0, ",", ".").'</td>
<td align="right">'.number_format($info_venta['vlr_total_venta'], 0, ",", ".").'</td>
</tr>';
} 
$codigoHTML.='
</table>
<br>
<br>
<table width="95%">
<tr>
<td align="center"><strong>Subtot</strong></td>
<td align="center"><strong>%Desc</strong></td>
<td align="center"><strong>$Desc</strong></td>
<td align="center"><strong>Iva</strong></td>
<td align="center"><strong>Total</strong></td>
</tr>
<tr>
<td align="center"><strong>'.number_format($subtotal_base, 0, ",", ".").'</strong></td>
<td align="center"><strong>'.$descuento.'%'.'</strong></td>
<td align="center"><strong>'.number_format($total_desc, 0, ",", ".").'</strong></td>
<td align="center"><strong>'.number_format($total_iva, 0, ",", ".").'</strong></td>
<td align="center"><strong>'.number_format($total_venta_temp, 0, ",", ".").'</strong></td>
</tr>
</table>
<br><br>
<br><br>
<td align="center">____________________________________</td>
<br>
<td align="center"><strong>Firma y CC del Cliente</strong></td>
<br><br><br>
<td align="center"><strong>'.$dat['info_legal'].'</strong></td>
</div>
</body>
</html>';

//$mpdf = new mPDF('c', 'A4');
//$mpdf = new mPDF('en-GB-x','A4','','',5,5,5,5,0,0);
//$mpdf = new mPDF('','', 0, '', 15, 15, 16, 16, 9, 9, 'L');
//$mpdf = new mPDF('utf-8', 'A4');
//$mpdf=new mPDF('UTF-8-s','');
$mpdf = new mPDF('utf-8','A4','','',5,5,5,5,0,0);
$mpdf->mirrorMargins = 1;
$mpdf->SetDisplayMode('fullpage');
$mpdf->writeHTML(utf8_encode($codigoHTML));
$nombre_archivo = 'Factura_de_Venta_'.$cod_factura;
$mpdf->output($nombre_archivo, 'I');
?>