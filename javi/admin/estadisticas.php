<?php
include("../pchar_class/pDraw.class.php");
include("../pchar_class/pImage.class.php");
include("../pchar_class/pPie.class.php");
include("../pchar_class/pData.class.php");

error_reporting(E_ALL ^ E_NOTICE);
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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");


$fecha = '28/10/2014';
$consulta = "SELECT sum(vlr_total_venta), vendedor FROM ventas WHERE fecha_anyo = '$fecha' GROUP BY vendedor";
$r = mysql_query($consulta);

$registro = mysql_fetch_row($r);
$dato1 = $registro[0];
$vendedor1 = $registro[1];

$registro = mysql_fetch_row($r);
$dato2 = $registro[0];
$vendedor2 = $registro[1];

$registro = mysql_fetch_row($r);
$dato3 = $registro[0];
$vendedor3 = $registro[1];

echo("<center>");
echo("<table>");
echo("<tr>");
echo("<td>VENDEDORES</td>");
echo("<td>VENTAS</td>");
echo("</tr>");
echo("<tr>");
echo("<td>$vendedor1</td>");
echo("<td>$dato1</td>");
echo("</tr>");
echo("<tr>");
echo("<td>$vendedor2</td>");
echo("<td>$dato2</td>");
echo("</tr>");
/*
echo("<tr>");
echo("<td>$vendedor3</td>");
echo("<td>$dato3</td>");
echo("</tr>");
*/
echo("</table>");

$tabla=new pData();

$tabla->addPoints(array($dato1,$dato2),"serie");
$tabla->setSerieDescription("serie","Sexo");

$tabla->addPoints(array("$vendedor1 - $dato1","$vendedor2 - $dato2"),"etiquetas");
$tabla->setAbscissa("etiquetas");

$imagen=new pImage(600,400,$tabla, TRUE);

$pastel=new pPie($imagen,$tabla);

$pastel->draw3DPie(250,140,array("Radius"=>100,"DrawLabels"=>TRUE,"LabelStacked"=>TRUE,"Border"=>TRUE));

$imagen->Render("graficapastel.png");
echo ("<img src=\"graficapastel.png\">");
echo("<center>");
mysql_close();
?>


