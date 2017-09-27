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

$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM productos WHERE unidades_faltantes = '0' order by nombre_productos";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<?php //require_once("../busquedas/busqueda_productos_agotados.php");?>
<body>
<br>
<center>
<table >
<tr>
<td><div align="center"><strong>C&oacute;digo</strong></div></td>
<td><div align="center"><strong>Nombre</strong></div></td>
<!--<td><div align="center"><strong>Marca</strong></div></td>
<td><div align="center"><strong>Proveedor</strong></div></td>-->
<!--<td><div align="center"><strong>Unidades</strong></div></td>-->
<td><div align="center"><strong>Disponibles</strong></div></td>
<td><div align="center"><strong>Precio Compra</strong></div></td>
<td><div align="center"><strong>Precio Venta</strong></div></td>
<td><div align="center"><strong>Margen Utilidad</strong></div></td>
<td><div align="center"><strong>Descripcion</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td><?php echo $matriz_consulta['cod_productos_var']; ?></td>
<td><?php echo $matriz_consulta['nombre_productos']; ?></td>
<!--<td><?php //echo $matriz_consulta['nombre_marcas']; ?></td>
<td><?php //echo $matriz_consulta['cod_proveedores']; ?></td>-->
<!--<td><?php //echo $matriz_consulta['unidades']; ?></td>-->
<td align="right"><?php echo $matriz_consulta['unidades_faltantes']; ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_compra']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_venta']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['utilidad']); ?></td>
<td align="right"><?php echo $matriz_consulta['descripcion']; ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
</body>
</html>
<?php mysql_free_result($consulta);?>