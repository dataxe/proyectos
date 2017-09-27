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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$deboluciones_inventario = "SELECT * FROM operacion WHERE origen_operacion = 'productos'";
$consulta = mysql_query($deboluciones_inventario, $conectar) or die(mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<br><br>
<center>
<td align="center"><font color="yellow" size="+2"><strong>DEVOLUCIONES INVENTARIO</font></td>
<br>
<?php require_once("menu_devolucion_inventario.php");?>
<br>
<table width='90%'>
<tr>
<th align="center">CODIGO</th>
<th align="center">PRODUCTO</th>
<th align="center">UND ANTES DE MODIFICAR</th>
<th align="center">UND NUEVAS</th>
<th align="center">UND DESPUES DE MODIFICAR</th>
<th align="center">COMENTARIO</th>
<th align="center">PRECIO COSTO</th>
<th align="center">PRECIO VENTA</th>
<th align="center">FECHA</th>
<th align="center">HORA</th>
</tr>
<?php 
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos  = $datos['cod_productos'];
$nombre_productos = $datos['nombre_productos'];
$und_inventario = $datos['und_inventario'];
$und_nuevas  = $datos['und_nuevas'];
$unidades_faltantes  = $datos['unidades_faltantes'];
$fecha_orig = $datos['fecha_orig'];
$comentario = $datos['comentario'];
$precio_costo = $datos['precio_costo'];
$precio_venta = $datos['precio_venta'];
$fecha_hora = $datos['fecha_hora'];
?>
<tr>
<td align='left'><?php echo $cod_productos ?></td>
<td align='left'><?php echo $nombre_productos ?></td>
<td align='center'><?php echo number_format($und_inventario, 0, ",", ".") ?></td>
<td align='center'><?php echo number_format($und_nuevas, 0, ",", ".") ?></td>
<td align='center'><?php echo number_format($unidades_faltantes, 0, ",", ".") ?></td>
<td align='left'><?php echo $comentario ?></td>
<td align='right'><?php echo number_format($precio_costo, 0, ",", ".") ?></td>
<td align='right'><?php echo number_format($precio_venta, 0, ",", ".") ?></td>
<td align='center'><?php echo $fecha_orig ?></td>
<td align='center'><?php echo $fecha_hora ?></td>
</tr>
<?php } ?>
</table>
</center>
</body>
</html>
<?php mysql_free_result($consulta);?>