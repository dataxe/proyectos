<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('conexiones/conexione.php');
mysql_select_db($base_datos, $conectar);
include ("session/funciones_admin.php");
$sql = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consulta = mysql_query($sql, $conectar) or die(mysql_error());
$dato = mysql_fetch_assoc($consulta);
?>
<META HTTP-EQUIV="REFRESH" CONTENT="4; admin/factura_eletronica.php">
<title><?php echo $dato['titulo'];?></title>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">

<link rel="stylesheet" type="text/css" href="estilo_css/estilo_acceso2.css">

<style type="text/css">
<!--
body {
font-family: 'ethnocen', Fallback, sans-serif;
}
-->
</style>
<link href="imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
<br>
<center><img src=imagenes/cargador_inicio.gif alt="cargador_inicio">
<br><br>
<font color='white' size='4'><strong><?php echo $dato['cabecera'];?></strong></font>
<br>
<img src=imagenes/porcentaje.gif alt="porcentaje"></center>
</body>