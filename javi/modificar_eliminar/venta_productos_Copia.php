,,/<?php error_reporting(E_ALL ^ E_NOTICE);?>
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

$cod_productos = $_GET['cod_productos'];
$sql_modificar_consulta = "SELECT * FROM productos where cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<body>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>
<center>
<form method="post" name="formulario_de_actualizacion" action="<?php echo $edicion_de_formulario; ?>">
<table align="center" >
<tr valign="baseline">
<td nowrap align="left">Codigo:</td>
<td><?php echo $datos['cod_productos_var']; ?></td>
</tr>
<input type="hidden" name="cod_producto" value="<?php echo $datos['cod_productos_var']; ?>" size="7">
<tr valign="baseline">
<td nowrap align="left">Nombre Producto:</td>
<td><?php echo $datos['nombre_productos']; ?></td>
</tr>
<input type="hidden" name="cod_producton" value="<?php echo $datos['cod_productos']; ?>" size="7">
<input type="hidden" name="nombre_productos" value="<?php echo $datos['nombre_productos']; ?>" size="7">
<tr valign="baseline">
<td nowrap align="left">Und Disponibles:</td>
<td><?php echo $datos['unidades_faltantes']; ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Precio Compra:</td>
<td><?php echo number_format($datos['precio_compra']); ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Precio Venta:</td>
<td><?php echo number_format($datos['precio_venta']); ?></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Clientes:</td>
<td><select name="cod_clientes">
<?php $sql_consulta="SELECT * FROM clientes ORDER BY nombres";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['cod_clientes'] ?>"><?php echo $contenedor['cedula']." - ".$contenedor['nombres']." ".$contenedor['apellidos'] ?></option>
<?php }?>
</select></td>

<tr valign="baseline">
<td nowrap align="left">Und a Vender:</td>
<td><input type="text" id="foco" name="unidades_venta" value="" size="50" required autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">$ Descuento:</td>
<td><input type="text" name="descuento" value="0" size="50" required autofocus></td>
</tr>
<?php 
$tipo_venta = "sin_factura";
?>
<input type="hidden" name="unidades_faltantes_oculto" value="<?php echo $datos['unidades_faltantes']; ?>" size="7">
<tr>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td><input type="submit" value="Vender"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_productos" value="<?php echo $datos['cod_productos']; ?>">
</form>
<?php 
$unidades_venta = $_POST['unidades_venta'];
$unidades_faltantes_oculto = $_POST['unidades_faltantes_oculto'];
$calculo_unidades_faltantes = $datos['unidades_faltantes'] - $_POST['unidades_venta'];
$calculo_suma_descuento = $_POST['descuento'] + $datos['descuento'];
$calculo_total_mercancia = $datos['precio_compra'] * $calculo_unidades_faltantes;
$calculo_total_venta = ($datos['precio_venta'] * ($datos['unidades_vendidas'] + $_POST['unidades_venta']));
$calculo_total_utilidad = (($datos['precio_venta'] - $datos['precio_compra']) * ($datos['unidades_vendidas'] + $_POST['unidades_venta']));
$calculo_unidades_vendidas = $_POST['unidades_venta'] + $datos['unidades_vendidas'];
$precio_compra_con_descuento = ($datos['precio_venta'] * $_POST['unidades_venta']) - $_POST['descuento'];
$ip = $_SERVER['REMOTE_ADDR'];
$vlr_total_venta = $datos['precio_venta'] * $_POST['unidades_venta']; 
$vlr_total_compra = $datos['precio_compra'] * $_POST['unidades_venta'];
$cod_clientes = $_POST['cod_clientes'];

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion")) {
if($unidades_venta == NULL) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='white'><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong> No ha ingresado las unidades a vender. 
<img src=../imagenes/advertencia.gif alt='Advertencia'></strong></center></font>";
} elseif ($unidades_venta > $unidades_faltantes_oculto) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='white'><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong> Las unidades a vender no pueden ser mayor a las 
unidades disponibles. <img src=../imagenes/advertencia.gif alt='Advertencia'></strong></center></font>";
} elseif ($unidades_venta <= 0) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='white'><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong> Las unidades a vender no pueden ser valores negativos o 
iguales a cero. <img src=../imagenes/advertencia.gif alt='Advertencia'></strong></center></font>";
} elseif ($cod_clientes <> 1) {
$actualizar_sql1 = sprintf("UPDATE productos SET unidades_faltantes=%s, unidades_vendidas=%s, total_mercancia=%s, total_venta=%s, total_utilidad=%s, 
    descuento=%s WHERE cod_productos=%s",
             $calculo_unidades_faltantes,
             $calculo_unidades_vendidas,
             $calculo_total_mercancia,
             $calculo_total_venta,
             $calculo_total_utilidad,
             $calculo_suma_descuento,
             envio_valores_tipo_sql($_POST['cod_productos'], "text"));

$agregar_registros_sql2 = sprintf("INSERT INTO cuentas_cobrar (cod_clientes, cod_productos, nombre_productos, unidades_vendidas, precio_venta, 
vlr_total_venta, vendedor, precio_compra, vlr_total_compra, descuento, precio_compra_con_descuento, ip, fecha_mes, fecha_anyo, fecha_hora, fecha) 
VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
             envio_valores_tipo_sql($_POST['cod_clientes'], "text"),
             envio_valores_tipo_sql($_POST['cod_producto'], "text"),
             envio_valores_tipo_sql($_POST['nombre_productos'], "text"),
             envio_valores_tipo_sql($_POST['unidades_venta'], "text"),
             envio_valores_tipo_sql($datos['precio_venta'], "text"),
             $vlr_total_venta,
             envio_valores_tipo_sql($cuenta_actual, "text"),
             envio_valores_tipo_sql($datos['precio_compra'], "text"),
             $vlr_total_compra,
             envio_valores_tipo_sql($_POST['descuento'], "text"),
             envio_valores_tipo_sql($precio_compra_con_descuento, "text"),
             envio_valores_tipo_sql($ip, "text"),
             envio_valores_tipo_sql(date("Y/m"), "text"),
             envio_valores_tipo_sql(date("Y"), "text"),
             envio_valores_tipo_sql(date("Y/m/d - H:i"), "text"),
             envio_valores_tipo_sql(date("Y/m/d"), "text"));

$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
$resultado_sql2 = mysql_query($agregar_registros_sql2, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/buscar_productos.php">';
}
else {
  $actualizar_sql1 = sprintf("UPDATE productos SET unidades_faltantes=%s, unidades_vendidas=%s, total_mercancia=%s, total_venta=%s, total_utilidad=%s, 
    descuento=%s WHERE cod_productos=%s",
             $calculo_unidades_faltantes,
             $calculo_unidades_vendidas,
             $calculo_total_mercancia,
             $calculo_total_venta,
             $calculo_total_utilidad,
             $calculo_suma_descuento,
             envio_valores_tipo_sql($_POST['cod_productos'], "text"));

$agregar_registros_sql2 = sprintf("INSERT INTO ventas (cod_ventas, cod_productos, cod_factura, nombre_productos, unidades_vendidas, precio_compra, 
precio_venta, vlr_total_venta, vlr_total_compra, descuento, precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, 
fecha_hora) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
             envio_valores_tipo_sql($_POST['cod_ventas'], "text"),
             envio_valores_tipo_sql($_POST['cod_producto'], "text"),
             envio_valores_tipo_sql($tipo_venta, "text"),
             envio_valores_tipo_sql($_POST['nombre_productos'], "text"),
             envio_valores_tipo_sql($_POST['unidades_venta'], "text"), 
             envio_valores_tipo_sql($datos['precio_compra'], "text"),
             envio_valores_tipo_sql($datos['precio_venta'], "text"),
             $vlr_total_venta,
             $vlr_total_compra,
             envio_valores_tipo_sql($_POST['descuento'], "text"),
             envio_valores_tipo_sql($precio_compra_con_descuento, "text"),
             envio_valores_tipo_sql($cuenta_actual, "text"),
             envio_valores_tipo_sql($ip, "text"),
             envio_valores_tipo_sql(date("Y/m/d"), "text"),
             envio_valores_tipo_sql(date("Y/m"), "text"),
             envio_valores_tipo_sql(date("Y"), "text"),
             envio_valores_tipo_sql(date("Y/m/d - H:i"), "text"));   
     
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
$resultado_sql2 = mysql_query($agregar_registros_sql2, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/buscar_productos.php">';
}
}
?>
<p>&nbsp;</p>
</body>
</html>
<?php mysql_free_result($modificar_consulta);?>