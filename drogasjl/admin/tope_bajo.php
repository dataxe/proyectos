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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM productos, marcas WHERE productos.cod_marcas = marcas.cod_marcas AND 
unidades_faltantes <= tope_minimo order by unidades_faltantes ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<td><strong><font color='white'>PRODUCTOS EN TOPE MINIMO: </font></strong></td><br><br>
<table id="table" id="table">
<tr>
<td><div align="center"><strong>C&Oacute;DIGO</strong></div></td>
<td><div align="center"><strong>PRODUCTO</strong></div></td>
<td><div align="center"><strong>MARCA</strong></div></td>
<!--<td><div align="center"><strong>Proveedor</strong></div></td>
<td><div align="center"><strong>Unds</strong></div></td>-->
<td><div align="center"><strong>UND</strong></div></td>
<td><div align="center"><strong>TOPE</strong></div></td>
<!--<td><div align="center"><strong>Compra</strong></div></td>-->
<td><div align="center"><strong>P.VENTA</strong></div></td>
<td><div align="center"><strong>DESCRIPCI&Oacute;N</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td ><?php echo $matriz_consulta['cod_productos_var']; ?></td>
<td ><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td ><?php echo $matriz_consulta['nombre_marcas']; ?></td>
<!--<td ><?php //echo $matriz_consulta['nombre_proveedores']; ?></td>
<td align="right"><?php //echo $matriz_consulta['unidades']; ?></td>-->
<td align="right"><?php echo $matriz_consulta['unidades_faltantes']; ?></td>
<td align="right"><?php echo $matriz_consulta['tope_minimo']; ?></td>
<!--<td align="right"><?php //echo number_format($matriz_consulta['precio_compra']); ?></td>-->
<td align="right"><?php echo number_format($matriz_consulta['precio_venta']); ?></td>
<td align="right"><?php echo $matriz_consulta['descripcion']; ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>