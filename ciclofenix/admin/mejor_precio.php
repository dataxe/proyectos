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
$buscar = $_POST['nom_productos'];
$mostrar_datos_sql = "SELECT * FROM productos, marcas, proveedores WHERE nombre_productos  like '$buscar%' AND 
((marcas.cod_marcas = productos.cod_marcas) AND (proveedores.cod_proveedores = proveedores.cod_proveedores) ORDER BY productos.precio_compra ASC";
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
<table id="table">
<tr>
<td><div align="center">Codigo</div></td>
<td><div align="center">Producto</div></td>
<td><div align="center">Marca</div></td>
<td><div align="center">Proveedor</div></td>
<td><div align="center">Precio Compra</div></td>
</tr>
<?php do { ?>
<tr>
<td><?php echo $matriz_consulta['cod_productos']; ?></td>
<td><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td><?php echo $matriz_consulta['nombre_marcas']; ?></td>
<td><?php echo $matriz_consulta['nombre_proveedores']; ?></td>
<td><?php echo $matriz_consulta['precio_compra']; ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<br>
<form method="post" name="formulario" action="mejor_precio.php">
<table align="center">
<td nowrap align="right">Producto:</td>
<td bordercolor="0">
<select name="nom_productos">
<?php $sql_consulta1="SELECT DISTINCT nombre_productos FROM productos WHERE nombre_productos <> '0' ORDER BY nombre_productos";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['nombre_productos'] ?>"><?php echo $contenedor['nombre_productos'] ?></option>
<?php }?>
</select></td></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Consultar Producto"></td>
</tr>
</table>
</form>
</body>
</html>
<?php mysql_free_result($consulta);?>
