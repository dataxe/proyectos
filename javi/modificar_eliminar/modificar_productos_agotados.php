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

$edicion_de_formulario = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
$edicion_de_formulario .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
$cod_productos = $_GET['cod_productos'];

$sql_modificar_consulta = "SELECT * FROM productos where cod_productos_var like '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
$total_resultado = mysql_num_rows($modificar_consulta);

$unidades_vendidas_cambia = "0";
$unidades = $_POST["unidades"];
$precio_compra = $datos['precio_compra'];
$precio_venta = $datos['precio_venta'];
$utilidad = $precio_venta - $precio_compra;
$calculo_gasto = $unidades * $precio_compra;
$calculo_unidades_vendidas = $unidades - $datos['unidades_faltantes'];
$total_mercancia_cambia = $precio_compra * $unidades;
$total_venta_cambia = $precio_venta * $unidades_vendidas_cambia;
$total_utilidad_cambia = $utilidad * $unidades_vendidas_cambia;
   
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
$actualizar_sql1 = sprintf("UPDATE productos SET nombre_productos=%s, unidades=%s, unidades_faltantes=%s, unidades_vendidas=%s, 
    utilidad=%s, total_utilidad=%s, total_mercancia=%s, total_venta=%s, gasto=%s WHERE cod_productos_var=%s",
envio_valores_tipo_sql($_POST['nombre_productos'], "text"),
envio_valores_tipo_sql($_POST['unidades'], "text"),
envio_valores_tipo_sql($_POST['unidades'], "text"),
$unidades_vendidas_cambia,
$utilidad,
$total_utilidad_cambia,
$total_mercancia_cambia,
$total_venta_cambia,
$calculo_gasto,
envio_valores_tipo_sql($cod_productos, "text"));	   
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());

echo "<META HTTP-EQUIV='REFRESH' CONTENT='0.2; ../modificar_eliminar/productos_agotados.php'>";
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<body>
<center>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center">
<tr valign="baseline">
<td nowrap align="right">Codigo:</td>
<td><?php echo $datos['cod_productos_var']; ?></td>
</tr>
<!--<tr valign="baseline">
<td nowrap align="right">Id Factura:</td>
<td><input type="text" name="cod_original" value="<?php //echo $datos['cod_original']; ?>" size="50"></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">Codif Precio:</td>
<td><input type="text" name="codificacion" value="<?php //echo $datos['codificacion']; ?>" size="50"></td>
</tr>-->
<tr valign="baseline">
<td nowrap align="right">Nombre:</td>
<td><input type="text" name="nombre_productos" value="<?php echo $datos['nombre_productos']; ?>" size="50" required autofocus></td>
</tr>
<!--<tr valign="baseline">
<td nowrap align="right">Marca:</td>
<td><input type="text" name="nombre_marcas" value="<?php //echo $datos['nombre_marcas']; ?>" size="50"></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">Proveedor:</td>
<td><input type="text" name="cod_proveedores" value="<?php //echo $datos['cod_proveedores']; ?>" size="50"></td>
</tr>-->
<tr valign="baseline">
<td nowrap align="right">Unidades:</td>
<td><input type="text" name="unidades" id="foco" value="<?php echo $datos['unidades_faltantes']; ?>" size="50" required autofocus></td>
</tr>
<!--<tr valign="baseline">
<td nowrap align="right">Precio Compra:</td>
<td><input type="text" name="precio_compra" value="<?php //echo $datos['precio_compra']; ?>" size="50" required autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">Precio Venta:</td>
<td><input type="text" name="precio_venta" value="<?php //echo $datos['precio_venta']; ?>" size="50" required autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="right">Descripcion:</td>
<td><input type="text" name="descripcion" value="<?php //echo $datos['descripcion']; ?>" size="50"></td>
</tr>-->
<td><input type="hidden" name="utilidad" value="<?php echo $datos['utilidad']; ?>" size="30"></td>
<td><input type="hidden" name="unidades_vendidas" value="<?php echo $datos['unidades_vendidas']; ?>" size="30"></td>
<td><input type="hidden" name="total_mercancia" value="<?php echo $datos['total_mercancia']; ?>" size="30"></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td><input type="submit" id="boton1" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
</form>
<center>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
<script>
window.onload = function() {
  document.getElementById("foco").focus();
}
</script>