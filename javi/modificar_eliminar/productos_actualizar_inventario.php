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

$cod_productos_var = $_GET['cod_productos'];

$sql_modificar_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos_var'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
$total_resultado = mysql_num_rows($modificar_consulta);

$cod_productos = $datos['cod_productos'];
$cod_marcas = $datos['cod_marcas'];
$cod_proveedores = $datos['cod_proveedores'];
$cod_lineas = $datos['cod_lineas'];
$cod_ccosto = $datos['cod_ccosto'];
$cod_nomenclatura = $datos['cod_nomenclatura'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<a href="../admin/cargar_inventario.php"><center><FONT color='yellow'>REGRESAR</FONT></center></a>
<br>
<body>
<center>
<form method="post" name="formulario_de_actualizacion" action="editar_productos.php">
<fieldset><legend><font color='yellow'><strong>ACTUALIZAR REGISTRO</strong></font></legend>
<table align="center" width='100%'>
<tr>
<td align="center"><strong>MARCA</strong></td>
<td align="center"><strong>PROVEEDOR</strong></td>
<td align="center"><strong>LINEA</strong></td>
<td align="center"><strong>CENTRO COSTO</strong></td>
<td align="center"><strong>ESTANTE</strong></td>
</tr>
<tr>
<td align="center">
<select name="cod_marcas">
<?php
if (isset($cod_marcas)) {
echo "<option style='font-size:20px' value='-1' >...</option>";
} else {
echo  "<option style='font-size:20px' value='-1' selected >...</option>";
}
$consulta2 = mysql_query("SELECT cod_marcas, nombre_marcas FROM marcas ORDER BY nombre_marcas ASC");
while ($datos2 = mysql_fetch_array($consulta2)) {
if(isset($cod_marcas) and $cod_marcas == $datos2['cod_marcas']) {
$seleccionado = "selected";
} else {
$seleccionado = "";
}
$codigo = $datos2['cod_marcas'];
$nombre = $datos2['nombre_marcas'];
echo "<option style='font-size:20px' value='".$codigo."' $seleccionado >".$nombre."</option>";
}
?>
</td>

<td align="center">
<select name="cod_proveedores">
<?php
if (isset($cod_proveedores)) {
echo "<option style='font-size:20px' value='-1' >...</option>";
} else {
echo  "<option style='font-size:20px' value='-1' selected >...</option>";
}
$consulta2 = mysql_query("SELECT cod_proveedores, nombre_proveedores FROM proveedores ORDER BY nombre_proveedores ASC");
while ($datos2 = mysql_fetch_array($consulta2)) {
if(isset($cod_proveedores) and $cod_proveedores == $datos2['cod_proveedores']) {
$seleccionado = "selected";
} else {
$seleccionado = "";
}
$codigo = $datos2['cod_proveedores'];
$nombre = $datos2['nombre_proveedores'];
echo "<option style='font-size:20px' value='".$codigo."' $seleccionado >".$nombre."</option>";
}
?>
</td>

<td align="center">
<select name="cod_lineas">
<?php
if (isset($cod_lineas)) {
echo "<option style='font-size:20px' value='-1' >...</option>";
} else {
echo  "<option style='font-size:20px' value='-1' selected >...</option>";
}
$consulta2 = mysql_query("SELECT cod_lineas, nombre_lineas FROM lineas ORDER BY nombre_lineas ASC");
while ($datos2 = mysql_fetch_array($consulta2)) {
if(isset($cod_lineas) and $cod_lineas == $datos2['cod_lineas']) {
$seleccionado = "selected";
} else {
$seleccionado = "";
}
$codigo = $datos2['cod_lineas'];
$nombre = $datos2['nombre_lineas'];
echo "<option style='font-size:20px' value='".$codigo."' $seleccionado >".$nombre."</option>";
}
?>
</td>

<td align="center">
<select name="cod_ccosto">
<?php
if (isset($cod_ccosto)) {
echo "<option style='font-size:20px' value='-1' >...</option>";
} else {
echo  "<option style='font-size:20px' value='-1' selected >...</option>";
}
$consulta2 = mysql_query("SELECT cod_ccosto, nombre_ccosto FROM centro_costo ORDER BY nombre_ccosto ASC");
while ($datos2 = mysql_fetch_array($consulta2)) {
if(isset($cod_ccosto) and $cod_ccosto == $datos2['cod_ccosto']) {
$seleccionado = "selected";
} else {
$seleccionado = "";
}
$codigo = $datos2['cod_ccosto'];
$nombre = $datos2['nombre_ccosto'];
echo "<option style='font-size:20px' value='".$codigo."' $seleccionado >".$nombre."</option>";
}
?>
</td>


<td align="center">
<select name="cod_nomenclatura">
<?php
if (isset($cod_nomenclatura)) {
echo "<option style='font-size:20px' value='-1' >...</option>";
} else {
echo  "<option style='font-size:20px' value='-1' selected >...</option>";
}
$consulta2 = mysql_query("SELECT cod_nomenclatura, nombre_nomenclatura FROM nomenclatura ORDER BY nombre_nomenclatura ASC");
while ($datos2 = mysql_fetch_array($consulta2)) {
if(isset($cod_nomenclatura) and $cod_nomenclatura == $datos2['cod_nomenclatura']) {
$seleccionado = "selected";
} else {
$seleccionado = "";
}
$codigo = $datos2['cod_nomenclatura'];
$nombre = $datos2['nombre_nomenclatura'];
echo "<option style='font-size:20px' value='".$codigo."' $seleccionado >".$nombre."</option>";
}
?>
</td>
</tr>
</table>
<br>
<table width="100%">
<tr>
<td align='center' title="Codigo del prodctos."><font size='2px'><strong>CODIGO</strong></font></td>
<td align='center' title="Nombre del producto."><font size='2px'><strong>PRODUCTO</strong></font></td>
<td align='center' title="Unidades en inventario."><font size='2px'><strong>UND</strong></font></td>
<td align='center' title="Unidades nuevas a ingresar, En metrica del producto. Ingresar las unidades ya convertidas si son en (MT, CM, HJ, LT, KG)"><font size='2px'><strong>UND.N</strong></font></td>
<td align='center'><font size='2px'><strong>COMENTARIO</strong></font></td>
<td align='center' title="Metrica del producto. Forma de ingresar el producto. (UND, MT, CM, HJ, LT, KG)"><font size='2px'><strong>MET</strong></font></td>
<td align='center' title="Si el producto se vende menudiado coloque aqui las unidades que contine la bolsa o el paquete donde viene, si se vende por unidades completas coloque el valor 1."><font size='2px'><strong>MED</strong></font></td>
<td align='center' title="Stock. tope minimo"><font size='2px'><strong>STK</strong></font></td>
<td align='center' title="Precio de costo de cada unidad que tiene la bolsa o el paquete donde viene. precio costo menudiado. (P.COST.UND / MED)."><font size='2px'><strong>P.COST.MET</strong></font></td>
<td align='center' title="Precio de venta de cada unidad que tiene la bolsa o el paquete donde viene. precio venta menudiado. (P.VENT.UND / MED)."><font size='2px'><strong>P.VENT.MET</strong></font></td>
<td align='center' title="Precio de costo de la unidad, la bolsa o el paquete completo."><font size='2px'><strong>P.COST.UND</strong></font></td>
<td align='center' title="Precio de venta de la unidad, la bolsa o el paquete completo."><font size='2px'><strong>P.VENT.UND</strong></font></td>
</tr>
<td align='center' title="Codigo del prodctos."><input type="text" name="cod_productos_var" value="<?php echo $datos['cod_productos_var']; ?>" size="20" required autofocus></td>
<input type="hidden" name="numero_factura" value="<?php echo $datos['numero_factura']; ?>" size="5">
<input type="hidden" name="codificacion" value="<?php echo $datos['codificacion']; ?>" size="7"></td>
<td align='center' title="Nombre del producto."><input type="text" name="nombre_productos" value="<?php echo $datos['nombre_productos']; ?>" size="50" required autofocus></td>
<td align='center' title="Unidades en inventario."><?php echo $datos['unidades_faltantes']; ?></td>
<input type="hidden" name="unidades_f" value="<?php echo $datos['unidades_faltantes']; ?>" size="2" required autofocus>
<td align='center' title="Unidades nuevas a ingresar. En metrica del producto"><input type="text" name="unidades_nuevas" id="foco" value="" size="2" required autofocus></td>
<td align='center'><input type="text" name="comentario" value="" size="30"></td>
<td align='center' title="Metrica del producto. Forma de ingresar el producto."><input type="text" name="detalles" value="<?php echo $datos['detalles']; ?>" size="2"></td>
<td align='center' title="Unidades metricas del producto."><input type="text" name="unidades" value="<?php echo $datos['unidades']; ?>" size="3"></td>
<td align='center' title="Stock. tope minimo."><input type="text" name="tope_minimo" value="<?php echo $datos['tope_minimo']; ?>" size="1"></td>
<td align='center' title="Precio de costo en metrica."><input type="text" name="precio_costo" value="<?php echo $datos['precio_costo']; ?>" size="6" required autofocus></td>
<td align='center' title="Precio de venta en metrica."><input type="text" name="precio_venta" value="<?php echo $datos['precio_venta']; ?>" size="6" required autofocus></td>
<td align='center' title="Precio de costo en metrica."><input type="text" name="precio_compra" value="<?php echo $datos['precio_compra']; ?>" size="6" required autofocus></td>
<td align='center' title="Precio de venta en metrica."><input type="text" name="vlr_total_venta" value="<?php echo $datos['vlr_total_venta']; ?>" size="6" required autofocus></td>
<input type="hidden" name="cod_paises" value="1" size="70">
<input type="hidden" name="cod_tipo" value="1" size="8">
<input type="hidden" name="fechas_vencimiento" value="<?php echo $datos['fechas_vencimiento']; ?>" size="8">
<input type="hidden" name="porcentaje_vendedor" value="<?php echo $datos['porcentaje_vendedor']; ?>" size="1">
<input type="hidden" name="descripcion" value="<?php echo $datos['descripcion']; ?>" size="10">
<input type="hidden" name="utilidad" value="<?php echo $datos['utilidad']; ?>" size="70">
<input type="hidden" name="unidades_vendidas" value="<?php echo $datos['unidades_vendidas']; ?>" size="70">
<input type="hidden" name="cod_productos" value="<?php echo $cod_productos; ?>" size="70">
<input type="hidden" name="pagina" value="cargar_inventario.php" size="70">
</tr>
</table>
</fieldset>
<br>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<center><td><input type="submit" id="boton1" value="Actualizar registro"></td></center>
</form>
<center>
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
