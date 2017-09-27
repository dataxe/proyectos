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
include ("../formato_entrada_sql/funcion_env_val_sql.php");

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM productos_eliminados WHERE cod_productos like '$buscar%' OR nombre_productos like '%$buscar%' order by nombre_productos";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<?php require_once("../busquedas/busqueda_productos_eliminados.php");?>
<body>
<br>
<center>
<table>
<tr>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<!--<td><strong>Marca</strong></td>
<td><strong>Proveedor</strong></td>-->
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>PRECIO COMPRA</strong></td>
<td align="center"><strong>PRECIO VENTA</strong></td>
<!--<td><strong>Margen Utilidad</strong></td>-->
<td align="center"><strong>DESCRIPCION</strong></td>
<td align="center"><strong>FECHA ELIMINACION</strong></td>
<td align="center"><strong>ELIM</strong></td>
</tr>
<?php do { ?>
<tr>
<td><?php echo $matriz_consulta['cod_productos']; ?></td>
<td><?php echo $matriz_consulta['nombre_productos']; ?></td>
<!--<td><?php //echo $matriz_consulta['nombre_marcas']; ?></td>
<td><?php //echo $matriz_consulta['nombre_proveedores']; ?></td>-->
<td align="right"><?php echo $matriz_consulta['unidades_faltantes']; ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_costo']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_venta']); ?></td>
<!--<td><?php //echo number_format($matriz_consulta['utilidad']); ?></td>-->
<td><?php echo $matriz_consulta['descripcion']; ?></td>
<td><?php echo $matriz_consulta['fechas_anyo'].' - '.$matriz_consulta['fechas_hora']; ?></td>
<td><a href="../modificar_eliminar/eliminar_productos_eliminados.php?cod_productos=<?php echo $matriz_consulta['cod_productos']; ?>"><center><img src=../imagenes/eliminar.png></center></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
</body>
</html>
<?php mysql_free_result($consulta);?>