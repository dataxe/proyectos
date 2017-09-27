<?php
require_once('mpdf/mpdf.php');
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");
//-------------------------------------------------------------------------------------------------------------------//
//-------------------------------------------------------------------------------------------------------------------//
$cuenta_actual = addslashes($_SESSION['usuario']);
$cod_facturas_cargadas_stiker = intval($_GET['cod_facturas_cargadas_stiker']);
$cod_factura = intval($_GET['cod_factura']);
$proveedor = $_GET['proveedor'];

$mostrar_datos_sql = "SELECT unidades_total, nombre_productos, cod_productos, fecha_anyo, fecha, fecha_mes, cod_interno 
FROM facturas_cargadas_stiker WHERE cod_facturas_cargadas_stiker = '$cod_facturas_cargadas_stiker' ORDER BY cod_facturas_cargadas_stiker";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);

$codigoHTML='
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>'."Stiker_Factura_No_".$cod_factura.'_Proveedor_'.$proveedor.'</title>
</head>
<body>
<table width="95%">
';
while ($datos = mysql_fetch_assoc($consulta)) {

$unidades_total = $datos['unidades_total'];
$nombre_productos = $datos['nombre_productos'];
$cod_productos = $datos['cod_productos'];
$fecha_anyo = ($datos['fecha_anyo']);
$data_fecha = explode('/', $fecha_anyo);
$dia = $data_fecha[0];
$mes = $data_fecha[1];
$anyo = $data_fecha[2];
$fecha_anyo_ym = $mes.$anyo;
$fecha_anyo_seg = $data_fecha[0];
$fecha_ymd = strtotime($datos['fecha']);
$fecha = date("mY", $fecha_ymd);
$fecha_mes = $datos['fecha_mes'];
$cod_interno = $datos['cod_interno'];

for ($i=0; $i < $unidades_total; $i++) {
$total_resultados--;

if ($total_resultados%4 == 0) {
$codigoHTML.='
<tr>
<div></div>
</tr>
';
}
$codigoHTML.='
<td>
||||||||||||||||||||||||||||||||||||||||||||||||||<br>
<div align="left">'.$nombre_productos.'&nbsp;<br>'.$cod_productos.'-'.$fecha_anyo_ym.'-'.$cod_interno.'</div>
</td>
';
}
}
$codigoHTML.='
</table>
</body>
</html>';
//$mpdf = new mPDF('c', 'A4');
$mpdf = new mPDF('c', 'A4');
//$mpdf = new mPDF('en-GB-x','A4','','',5,5,5,5,0,0);
//$mpdf = new mPDF('','', 0, '', 15, 15, 16, 16, 9, 9, 'L');
//$mpdf = new mPDF('utf-8', 'A4');
//$mpdf=new mPDF('UTF-8-s','');
$mpdf->mirrorMargins = 1;
$mpdf->SetDisplayMode('fullpage');
$mpdf->writeHTML($codigoHTML);
$nombre_archivo = 'Stiker_Sin_Barras_Factura_No_'.$cod_factura.'_Proveedor_'.$proveedor;
$mpdf->output($nombre_archivo, 'I');
?>