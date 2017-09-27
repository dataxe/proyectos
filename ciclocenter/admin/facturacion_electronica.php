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

$pagina = $_SERVER['PHP_SELF'];
$ip = $_SERVER['REMOTE_ADDR'];
$fecha = date("d/m/Y");
$fecha_invert = date("Y/m/d");
$hora = date("H:i:s");

$agregar_reg = "INSERT INTO registro_movimientos (cuenta, pagina, ip, fecha, fecha_invert, hora)
VALUES ('$cuenta_actual','$pagina', '$ip', '$fecha','$fecha_invert','$hora')";
$resultado_reg = mysql_query($agregar_reg, $conectar) or die(mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php
$datos_info = "SELECT * FROM info_impuesto_facturas WHERE vendedor = '$cuenta_actual' AND estado = 'abierto'";
$consulta_info = mysql_query($datos_info, $conectar) or die(mysql_error());
$info = mysql_fetch_assoc($consulta_info);
$cantidad_resultado = mysql_num_rows($consulta_info);

$maximo_valor = "SELECT Max(cod_factura) AS cod_factura FROM info_impuesto_facturas";
$consulta_maximo = mysql_query($maximo_valor, $conectar) or die(mysql_error());
$maximo = mysql_fetch_assoc($consulta_maximo);
?>
<center>
<form action="../admin/agregar_factura_electronica.php" method="get">
<input name="cod_productos" id="foco" style="height:26" placeholder="C&oacute;digo del producto"/>
<input type="hidden" name="cod_factura" value="<?php if ($cantidad_resultado == '1') { echo $info['cod_factura']; } else { echo $maximo['cod_factura']+1;}?>">
<input type="submit" style="font-size:15px" name="buscador" value="Vender Producto" />
</form>
<?php require_once('factura_eletronica.php');?>
</center>
<body>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>