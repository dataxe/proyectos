<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");


if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
$cod_factura = $_GET['cod_factura'];
$proveedor = $_GET['proveedor'];

$obtener_diseno = "SELECT * FROM disenos WHERE nombre_disenos LIKE 'por_defecto.css'";
$resultado_diseno = mysql_query($obtener_diseno, $conectar) or die(mysql_error());
$matriz_diseno = mysql_fetch_assoc($resultado_diseno); 

$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$dat = mysql_fetch_assoc($consultar_informacion);
?>
<link rel="stylesheet" type="text/css" href="../estilo_css/por_defecto.css">
<style type="text/css"> <!--body { background-color: #333333;}--></style>

<?php
$mostrar_datos_sql = "SELECT * FROM facturas_cargadas_stiker WHERE cod_factura = '$cod_factura' ORDER BY cod_facturas_cargadas_stiker";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<link href="../imagenes/<?php echo $dat['icono'];?>" type="image/x-icon" rel="shortcut icon" />
<title><?php echo "Factura No ".$cod_factura." - Proveedor ".$proveedor ?></title>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>
<body>
<br><br>
<table width="100%">
<?php 
while ($datos = mysql_fetch_assoc($consulta)) {
$unidades_total = $datos['unidades_total'];
$nombre_productos = $datos['nombre_productos'];
$cod_productos = $datos['cod_productos'];
$fecha_ymd = strtotime($datos['fecha']);
$fecha = date("mY", $fecha_ymd);
$fecha_mes = $datos['fecha_mes'];
$cod_interno = $datos['cod_interno'];

for ($i=0; $i < $unidades_total; $i++) { 
if ($i%4 == 0) {
?>
<tr>
<td align="center">||||||||||||||||||||||||||||||||||||||||||||||||||&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td align="center">||||||||||||||||||||||||||||||||||||||||||||||||||&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td align="center">||||||||||||||||||||||||||||||||||||||||||||||||||&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td align="center">||||||||||||||||||||||||||||||||||||||||||||||||||&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
</tr>
<?php
//---------------------------------------- CIERRE CONDICIONA IF QUE GENERA LAS 4 COLUMNAS -------------------------------------//
}
?>
<td align="center"><font size='3'><?php echo $nombre_productos ?><br><?php echo $cod_productos.'-'.$fecha.'-'.$cod_interno ?></font></td>
<?php
//---------------------------------------- CIERRE CICLO FOR QUE DICE CUANTOS STIKER VAN A IMPRIMIRSE-------------------------------------//
}
?>
<td><br></td>
<?php
//---------------------------------------- CIERRE CICLO WHILE -------------------------------------//
}
?>
<td><input align="left" type="image" id ="foco" src="../imagenes/imprimir.png" name="imprimir" onClick="window.print();"/></td>
</table>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>