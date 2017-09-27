<?php error_reporting(E_ALL ^ E_NOTICE);
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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$pagina = $_SERVER['PHP_SELF'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<td>
<strong>
<font color='white'>
<a href="#">.</a>
SELECIONAR DISE&Ntilde;OS:
<a href="../admin/utilidades_entrada.php" target="_blank">.</a>
</font>
</strong>
</td>
<br><br>

</center>
<table align="center">
<?php $obtener_css = "SELECT nombre_disenos, url_img FROM disenos";
$resultado = mysql_query($obtener_css, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {
$nombre_disenos = $contenedor['nombre_disenos'];
$url_img = $contenedor['url_img'];
?>
<td></td>
<td><a href="../admin/asignar_diseno_usuario.php?nombre_disenos=<?php echo $nombre_disenos ?>&pagina=<?php echo $pagina ?>"><img src=<?php echo $url_img ?> width="115" height="42"></a></td>
<td></td>
<?php 
}
?>
</table>
</body>
</html>
