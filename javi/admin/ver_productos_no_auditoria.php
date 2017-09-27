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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$cod_factura = intval($_GET['cod_factura']);
$pagina = 'exportacion_lista_subida.php';
$pagina_ = 'ver_productos_no_auditoria.php';

$mostrar_datos_sql = "SELECT * FROM camparacion_tablas WHERE cod_factura = '$cod_factura' ORDER BY cod_camparacion_tablas DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$total_incluidos = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<br>
<a href="<?php echo $pagina?>"><font color='yellow'>REGRESAR</font></a><br><br>
<td><strong><font color='yellow' size='+2'>VER LISTA DE PRODUCTOS NO INCLUIDOS EN LA AUDIRORIA: <?php echo $cod_factura?></font></strong></td><br><br>

<form method="post" name="formulario" action="ver_productos_no_incluidos_auditoria.php">
<table width="80%">
<?php do { 
$cod_productos = $datos['cod_productos'];
?>
<input type="hidden" name="cod_productos[]" value="<?php echo $cod_productos; ?>">
<input type="hidden" name="total_incluidos" value="<?php echo $total_incluidos; ?>">
<input type="hidden" name="cod_factura" value="<?php echo $cod_factura; ?>">
<input type="hidden" name="pagina" value="<?php echo $pagina_; ?>">
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<br>
<td bordercolor="1"><input type="submit" id="boton1" value="VER NO INCLUIDOS"></td>
<input type="hidden" name="insertar_datos" value="formulario">
</form>