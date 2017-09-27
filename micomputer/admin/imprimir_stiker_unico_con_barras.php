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
$cod_productos_var = addslashes($_GET['cod_productos_var']);

$mostrar_datos_sql = "SELECT cod_productos_var, nombre_productos, unidades_faltantes FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);

$codigoHTML='
<html>
<head>

<style>
body {
font-family: sans-serif;
font-size: 9pt;
}
.barcode {
padding: 1.5mm;
margin: 0;
vertical-align: top;
color: #000000;
}
</style>

</head>
<body>
<div style="text-align: center;">
<table>
';
while ($datos = mysql_fetch_assoc($consulta)) {

$cod_productos_var = $datos['cod_productos_var'];
$nombre_productos = $datos['nombre_productos'];
$unidades_faltantes = $datos['unidades_faltantes'];
/*
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
*/
for ($i=0; $i < $unidades_faltantes; $i++) {

$total_resultados--;

if ($total_resultados%4 == 0) {
$codigoHTML.='
<tr>
';
}
$codigoHTML.='
<td align="center">
'.$nombre_productos.'&nbsp;&nbsp;
<br>
<barcode code="'.$cod_productos_var.'" type="EAN128A"/>
<br>
'.$cod_productos_var.'
</td>
<br>
<br>
';
}
}
$codigoHTML.='
</tr>
</table>
</div>
</body>
</html>
';
/*
A4-L = horizontal
A4 = carta
A5 = extralarga
A5-L = horizontal
Letter = oficio
*/
$mpdf=new mPDF('c','A4','','',5,5,5,5,0,0);
$mpdf->mirrorMargins = 1;
$mpdf->SetDisplayMode('fullpage');
$mpdf->WriteHTML($codigoHTML);
$nombre_archivo = 'Stiker_Con_Barras_Producto_'.$cod_productos_var.'.pdf';
$mpdf->output($nombre_archivo, 'I');
exit;
?>