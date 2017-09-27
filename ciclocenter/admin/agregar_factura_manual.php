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
date_default_timezone_set("America/Bogota");
include ("../formato_entrada_sql/funcion_env_val_sql.php");

$cod_productos = mysql_escape_string($_GET['cod_productos']);

$sql_modificar_consulta = "SELECT * FROM productos, proveedores WHERE productos.cod_proveedores = proveedores.cod_proveedores 
AND cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$unidades_venta = $_POST['unidades_venta'];
$calculo_unidades_faltantes = $datos['unidades_faltantes'] - $unidades_venta;
$calculo_total_mercancia = ($datos['precio_compra'] * $calculo_unidades_faltantes);
$calculo_total_venta = ($datos['precio_venta'] * ($datos['unidades_vendidas'] + $unidades_venta));
$calculo_total_utilidad = (($datos['precio_venta'] - $datos['precio_compra']) * ($datos['unidades_vendidas'] + $unidades_venta));
$calculo_unidades_vendidas = $unidades_venta + $datos['unidades_vendidas'];
$calculo_vlr_total = $datos['precio_venta'] * $unidades_venta;

$cod_facturacion = $_POST['cod_facturacion'];
$vendedor_nombre = $matriz_diseno['nombres']." ".$matriz_diseno['apellidos'];
$unidades_faltantes = $datos['unidades_faltantes'];
$vlr_total_venta = $datos['precio_venta'] * $unidades_venta; 
$vlr_total_compra = $datos['precio_compra'] * $unidades_venta;
$descuento_ = '0';
$vlr_total_venta_con_descuento = ($datos['precio_venta'] * $unidades_venta) - $descuento_;
$ip = $_SERVER['REMOTE_ADDR'];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
if($cod_facturacion == NULL || $unidades_venta == NULL) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='white'><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong> Ha dejado campos vacios. 
<img src=../imagenes/advertencia.gif alt='Advertencia'></strong></center></font>";
} elseif ($unidades_venta <= 0) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='white'><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong> Las unidades a vender no puede ser valores negativos o 
iguales a cero. <img src=../imagenes/advertencia.gif alt='Advertencia'></strong></center></font>";
} elseif ($unidades_venta > $unidades_faltantes) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='white'><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong> Las unidades a vender no pueden ser mayor a las unidades 
disponibles. <img src=../imagenes/advertencia.gif alt='Advertencia'></strong></center></font>";
} else {
$actualizar_sql1 = sprintf("UPDATE productos SET unidades_faltantes=%s, unidades_vendidas=%s, total_mercancia=%s, total_venta=%s, total_utilidad=%s 
  WHERE cod_productos_var=%s",
$calculo_unidades_faltantes,
$calculo_unidades_vendidas,
$calculo_total_mercancia,
$calculo_total_venta,
$calculo_total_utilidad,
envio_valores_tipo_sql($cod_productos, "text"));
					   
$actualizar_sql2 = sprintf("UPDATE factura_cod SET numero_factura=%s WHERE cod_factura = 1",
envio_valores_tipo_sql($cod_facturacion, "text"),
envio_valores_tipo_sql($_POST['cod_factura'], "text"));
					   
$agregar_a_factura = sprintf("INSERT INTO facturacion (cod_facturacion, cod_producto, nombre_productos, marca, vendedor, vendedor_nombre, 
precio_compra, vlr_unitario, vlr_total, cantidad, descripcion, fecha, fecha_mes, anyo, fecha_anyo) VALUES  (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
envio_valores_tipo_sql($cod_facturacion, "text"),
envio_valores_tipo_sql($cod_productos, "text"),
envio_valores_tipo_sql($datos['nombre_productos'], "text"),
envio_valores_tipo_sql($datos['nombre_proveedores'], "text"),
envio_valores_tipo_sql($cuenta_actual, "text"),
envio_valores_tipo_sql($vendedor_nombre, "text"),
envio_valores_tipo_sql($vlr_total_compra, "text"),
envio_valores_tipo_sql($datos['precio_venta'], "text"),
$calculo_vlr_total,
envio_valores_tipo_sql($unidades_venta, "text"),
envio_valores_tipo_sql($datos['descripcion'], "text"),
envio_valores_tipo_sql(date("Y/m/d"), "text"),
envio_valores_tipo_sql(date("H:i:s"), "text"),
envio_valores_tipo_sql(date("Y"), "text"),
envio_valores_tipo_sql(date("d/m/Y"), "text"));
			   
$agregar_a_factura_venta = sprintf("INSERT INTO ventas (cod_productos, cod_factura, nombre_productos, unidades_vendidas, precio_compra, 
  precio_venta, vlr_total_venta, vlr_total_compra, precio_compra_con_descuento, vendedor, ip, fecha_hora, fecha_mes, fecha_anyo, anyo, fecha) 
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
envio_valores_tipo_sql($cod_productos, "text"),
envio_valores_tipo_sql($cod_facturacion, "text"),
envio_valores_tipo_sql($datos['nombre_productos'], "text"),
envio_valores_tipo_sql($unidades_venta, "text"),
envio_valores_tipo_sql($datos['precio_compra'], "text"),
envio_valores_tipo_sql($datos['precio_venta'], "text"),
$vlr_total_venta,
$vlr_total_compra,
$vlr_total_venta_con_descuento,
envio_valores_tipo_sql($cuenta_actual, "text"),
envio_valores_tipo_sql($ip, "text"),
envio_valores_tipo_sql(date("H:i:s"), "text"),
envio_valores_tipo_sql(date("m/Y"), "text"),
envio_valores_tipo_sql(date("d/m/Y"), "text"),
envio_valores_tipo_sql(date("Y"), "text"),
envio_valores_tipo_sql(date("Y/m/d"), "text"));
					   
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
$resultado_actualizacion2 = mysql_query($actualizar_sql2, $conectar) or die(mysql_error());					   					   
$resultado_agregar = mysql_query($agregar_a_factura, $conectar) or die(mysql_error());
$resultado_agregar_venta = mysql_query($agregar_a_factura_venta, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/buscar_productos_facturacion_manual.php">';
}
}
$sql_obtener_cod = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta_cod = mysql_query($sql_obtener_cod, $conectar) or die(mysql_error());
$matriz_obtener_cod = mysql_fetch_assoc($modificar_consulta_cod);

$sql_obtener_cod_facturacion = "SELECT DISTINCT cod_facturacion FROM facturacion ORDER BY cod_facturacion DESC";
$modificar_facturacion = mysql_query($sql_obtener_cod_facturacion, $conectar) or die(mysql_error());
$matriz_modificar_facturacion = mysql_fetch_assoc($modificar_facturacion);

$factura_actual = $matriz_modificar_facturacion['cod_facturacion'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<body>
<?php 
$obtener_facturacion = "SELECT cod_factura, numero_factura FROM factura_cod WHERE cod_factura = '1'";
$modificar_facturacion = mysql_query($obtener_facturacion, $conectar) or die(mysql_error());
$contenedor_factura = mysql_fetch_assoc($modificar_facturacion);

$obtener_vendedor = "SELECT nombres, apellidos FROM administrador WHERE cuenta LIKE '$cuenta_actual'";
$modificar_vendedor = mysql_query($obtener_vendedor, $conectar) or die(mysql_error());
$contenedor_vendedor = mysql_fetch_assoc($modificar_vendedor);

$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$matriz_informacion = mysql_fetch_assoc($consultar_informacion);
?>
<script>
window.onload = function() {
  document.getElementById("unidades_venta").focus();
}
</script>
<center>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
  <table align="center">
<tr valign="baseline">
<td nowrap align="left">Codigo:</td>
<td><?php echo $datos['cod_productos_var']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Nombre Producto:</td>
<td><?php echo $datos['nombre_productos']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Marca:</td>
<td><?php echo $datos['nombre_proveedores']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Und Disponibles:</td>
<td><?php echo $datos['unidades_faltantes']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Precio:</td>
<td><?php echo number_format($datos['precio_compra']); ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Venta:</td>
<td><?php echo number_format($datos['precio_venta']); ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Vendedor:</td>
<td><?php echo $contenedor_vendedor['nombres']." ".$contenedor_vendedor['apellidos']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Cod Factura:</td>
<td><input type="text" name="cod_facturacion" value="<?php echo $contenedor_factura['numero_factura']; ?>" size="50" required autofocus></td>
<!--<td><input type="text" name="cod_facturacion" value="<?php //echo $contenedor_factura['numero_factura']+1; ?>" size="50"></td>-->
</tr>
<tr valign="baseline">
<td nowrap align="left">Und a Vender:</td>
<td><input id="unidades_venta" type="text" name="unidades_venta" value="" size="50" required autofocus></td>
</tr>
<tr>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td><input type="submit" id="boton1" value="Vender"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_productos" value="<?php echo $datos['cod_productos_var']; ?>">
</form>
<br>
<?php mysql_free_result($modificar_consulta);?>