<?php 
require_once("dompdf/dompdf_config.inc.php");
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");
/*
$cuenta_actual = addslashes($_SESSION['usuario']);
$cod_factura = intval($_GET['numero_factura']);
$descuento = addslashes($_GET['descuento']);
$tipo_pago = intval($_GET['tipo_pago']);
$cod_clientes = intval($_GET['cod_clientes']);
*/
$cuenta_actual = 'administrador';
$cod_factura = 707;
$descuento = 0;
$tipo_pago = 1;
$cod_clientes = 1;

$obtener_diseno = "SELECT * FROM disenos WHERE nombre_disenos LIKE 'por_defecto.css'";
$resultado_diseno = mysql_query($obtener_diseno, $conectar) or die(mysql_error());
$matriz_diseno = mysql_fetch_assoc($resultado_diseno); 

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
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Lista</title>
</head>

<body>
<!--<link rel="stylesheet" type="text/css" href="../estilo_css/por_defecto.css">-->

<div align="center">
<table width="95%">
<tr>
<td align="center"><p style="font-size:20px">'.$dat['cabecera'].' - '.$dat['localidad'].'
<p style="font-size:20px">Res: '.$dat['res'].', Del '.$dat['res1'].' Al '.$dat['res2'].' 
<br>
Direccion: '.$dat['direccion'].' - Tel: '.$dat['telefono'].'
<br>'.$dat['nit'].'</td>
</tr>
</table>

<table width="95%">
<tr>
<td align="left"><p style="font-size:20px">Fact N&ordm;: '.$cod_factura.' | 
Fecha: '.$info_fact['fecha_anyo'].' | Hora: '.date("H:i").'
<br>
Cliente: '.$nombre_cliente.' | Nit: '.$cedula.' | Direccion: '.$direccion.' | 
Vendedor: '.$info_fact['vendedor'].' </p></td>
</tr>
</table>



<br>
    <table width="95%">
      <tr>
        <td align="center"><strong>C&oacute;digo</strong></td>
        <td align="center"><strong>Descripci&oacute;n</strong></td>
        <td align="center"><strong>Und</strong></td>
        <td align="center"><strong>.</strong></td>
        <td align="center"><strong>V.unit</strong></td>
        <td align="center"><strong>V.total</strong></td>
      </tr>';

        $consulta=mysql_query("SELECT * FROM ventas where cod_factura = '$cod_factura'");
        while($dato=mysql_fetch_array($consulta)){
$codigoHTML.='
      <tr>
        <td align="left">'.$dato['cod_productos'].'</td>
        <td align="left">'.$dato['nombre_productos'].'</td>
        <td align="center">'.$dato['unidades_vendidas'].'</td>
        <td align="center">'.$dato['detalles'].'</td>
        <td align="right">'.$dato['precio_venta'].'</td>
        <td align="right">'.$dato['vlr_total_venta'].'</td>
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

</div>
</body>
</html>';

$codigoHTML=utf8_decode($codigoHTML);
$dompdf=new DOMPDF();
$dompdf->load_html($codigoHTML);
ini_set("memory_limit","128M");
$dompdf->render();
$nombre_archivo = "Factura de Venta ".$cod_factura.".pdf";
$dompdf->stream($nombre_archivo);
?>