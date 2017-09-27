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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

//ver los que faltan por aactualizar
$mostrar_datos_sql = "SELECT cod_productos_var, nombre_productos, unidades_faltantes, precio_compra, precio_costo, precio_venta, 
vlr_total_compra, vlr_total_venta, detalles, fechas_anyo, cuenta from productos_copia_inventario WHERE not exists 
(SELECT 1 FROM productos_copia_seguridad WHERE productos_copia_seguridad.cod_productos_var = productos_copia_inventario.cod_productos_var 
ORDER BY productos_copia_seguridad.nombre_productos ASC)";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total = mysql_num_rows($consulta);

//--------------------------------------------------------------------------------------------------------//
$datos_user = "SELECT cod_seguridad, cuenta FROM administrador WHERE cuenta = '$cuenta_actual'";
$consulta_user = mysql_query($datos_user, $conectar) or die(mysql_error());
$info_user = mysql_fetch_assoc($consulta_user);

$cod_seguridad = $info_user['cod_seguridad'];
//--------------------------------------------------------------------------------------------------------//
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
<table border='1'>
<td><strong><a href="cargar_inventario_copia_vendedores.php"><font color='white'>CARGAR INVENTARIO MANUAL VENDEDOR</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
<td><strong><a href="productos_cargados_inventario_vendedores_comparacion_unidades.php"><font color='white'>PRODUCTOS CARGADOS</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
<td><strong><a href="productos_sin_cargar_inventario_vendedores.php"><font color='yellow'>FALTAN POR CARGAR</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
</table>
<br>
<td align="center"><font color="yellow" size="+2"><strong>INVENTARIO DE PRODUCTOS SIN CARGAR [<?php echo number_format($total, 0, ",", ".") ?>]</font></td>
<br>
<table width='80%' border='1'>
<tr>
<td align="center">C&Oacute;DIGO</td>
<td align="center">PRODUCTO</td>
<!-- //////////////////////////////////////////////////////////////////////////////////////// -->
<?php if ($cod_seguridad == 3 || $cod_seguridad == 2) { ?>
<td align="center">UND.INV</td>
<?php } else { } ?>
<!-- //////////////////////////////////////////////////////////////////////////////////////// -->
<td align="center">P.VENTA</td>
<td align="center">P.VENTA2</td>
</tr>
<?php 
while ($datos = mysql_fetch_assoc($consulta)) {
$cod_productos_var = $datos['cod_productos_var'];
$nombre_productos = $datos['nombre_productos'];
$unidades_faltantes = $datos['unidades_faltantes'];
$detalles = $datos['detalles'];
$precio_costo = $datos['precio_costo'];
$precio_venta = $datos['precio_venta'];
$precio_compra = $datos['precio_compra'];
$vlr_total_compra = $datos['vlr_total_compra'];
$vlr_total_venta = $datos['vlr_total_venta'];
$resta = $unidades_faltantes_nuevo - $unidades_faltantes_viejo;
?>
<tr>
<td align='left'><?php echo $cod_productos_var ?></td>
<td><a href="../modificar_eliminar/productos_actualizar_inventario_copia_vendedores.php?cod_productos=<?php echo $cod_productos_var; ?>"><?php echo $nombre_productos;?></a></td>
<!-- //////////////////////////////////////////////////////////////////////////////////////// -->
<?php if ($cod_seguridad == 3 || $cod_seguridad == 2) { ?>
<td align='center'><?php echo $unidades_faltantes ?></td>
<?php } else { } ?>
<!-- //////////////////////////////////////////////////////////////////////////////////////// -->
<td align='right'><?php echo number_format($precio_venta, 0, ",", ".") ?></td>
<td align='right'><?php echo number_format($vlr_total_venta, 0, ",", ".") ?></td>
</tr>
<?php } ?>
</table>
</center>
</body>
</html>