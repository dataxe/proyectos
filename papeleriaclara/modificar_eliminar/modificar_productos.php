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
/*
$nombre_pagina_actual = 'MODIFICAR - PRODUCTOS';
$actualizar_nombre_pagina_actual = sprintf("UPDATE pagina_actual SET nombre_pagina_actual=%s WHERE cod_pagina_actual='1'",
envio_valores_tipo_sql($nombre_pagina_actual, "text"), envio_valores_tipo_sql($_POST['cod_pagina_actual'], "text"));
$resultado_actualizacion_pagina_actual = mysql_query($actualizar_nombre_pagina_actual, $conectar) or die(mysql_error());
*/
$cod_productos = $_GET['cod_productos'];

$sql_modificar_consulta = "SELECT * FROM productos where cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
$total_resultado = mysql_num_rows($modificar_consulta);

$unidades_vendidas_cambia = "0";
$unidades = $_POST["unidades"];
$precio_compra = $_POST['precio_compra'];
$precio_venta = $_POST['precio_venta'];
$utilidad = $precio_venta - $precio_compra;
$calculo_gasto = $unidades * $precio_compra;
$calculo_unidades_vendidas = $unidades - $datos['unidades_faltantes'];
$total_mercancia = $precio_compra * $datos['unidades_faltantes'];
$total_mercancia_cambia = $precio_compra * $unidades;
$total_venta_cambia = $precio_venta * $unidades_vendidas_cambia;
$total_venta = $precio_venta * $datos['unidades_vendidas'];
$total_utilidad_cambia = $utilidad * $unidades_vendidas_cambia;
$total_utilidad = $utilidad * $datos['unidades_vendidas'];
   
if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion") && ($unidades <> $datos['unidades_faltantes'])) {
$actualizar_sql1 = sprintf("UPDATE productos SET cod_productos_var=%s, nombre_productos=%s, cod_marcas=%s, cod_proveedores=%s, cod_nomenclatura=%s, 
   cod_tipo=%s, cod_original=%s, codificacion=%s, unidades=%s, unidades_faltantes=%s, unidades_vendidas=%s, precio_compra=%s, precio_venta=%s, 
   utilidad=%s, total_utilidad=%s, total_mercancia=%s, total_venta=%s, gasto=%s, detalles=%s, descripcion=%s, numero_factura=%s, cod_paises=%s 
   WHERE cod_productos_var=%s",
envio_valores_tipo_sql($_POST['cod_productos'], "text"),
envio_valores_tipo_sql($_POST['nombre_productos'], "text"),
envio_valores_tipo_sql($_POST['cod_marcas'], "text"),
envio_valores_tipo_sql($_POST['cod_proveedores'], "text"),
envio_valores_tipo_sql($_POST['cod_nomenclatura'], "text"),
envio_valores_tipo_sql($_POST['cod_tipo'], "text"),
envio_valores_tipo_sql($_POST['cod_original'], "text"),
envio_valores_tipo_sql($_POST['codificacion'], "text"),
envio_valores_tipo_sql($_POST['unidades'], "text"),
envio_valores_tipo_sql($_POST['unidades'], "text"),
envio_valores_tipo_sql($unidades_vendidas_cambia, "text"),
envio_valores_tipo_sql($_POST['precio_compra'], "text"),
envio_valores_tipo_sql($_POST['precio_venta'], "text"),
$utilidad,
$total_utilidad_cambia,
$total_mercancia_cambia,
$total_venta_cambia,
$calculo_gasto,
envio_valores_tipo_sql($_POST['detalles'], "text"),
envio_valores_tipo_sql($_POST['descripcion'], "text"),
envio_valores_tipo_sql($_POST['numero_factura'], "text"),
envio_valores_tipo_sql($_POST['cod_paises'], "text"),
envio_valores_tipo_sql($_POST['cod_productos'], "text"));

$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.2; ../admin/busqueda_productos.php">';

} elseif ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion") && ($unidades == $datos['unidades_faltantes'])) {
$actualizar_sql1 = sprintf("UPDATE productos SET cod_productos_var=%s, nombre_productos=%s, cod_marcas=%s, cod_proveedores=%s, cod_nomenclatura=%s, 
   cod_tipo=%s, cod_original=%s, codificacion=%s, precio_compra=%s, precio_venta=%s, utilidad=%s, total_utilidad=%s, total_mercancia=%s, total_venta=%s, 
   gasto=%s, detalles=%s, descripcion=%s, numero_factura=%s, cod_paises=%s WHERE cod_productos_var=%s",
envio_valores_tipo_sql($_POST['cod_productos'], "text"),
envio_valores_tipo_sql($_POST['nombre_productos'], "text"),
envio_valores_tipo_sql($_POST['cod_marcas'], "text"),
envio_valores_tipo_sql($_POST['cod_proveedores'], "text"),
envio_valores_tipo_sql($_POST['cod_nomenclatura'], "text"),
envio_valores_tipo_sql($_POST['cod_tipo'], "text"),
envio_valores_tipo_sql($_POST['cod_original'], "text"),
envio_valores_tipo_sql($_POST['codificacion'], "text"),
envio_valores_tipo_sql($_POST['precio_compra'], "text"),
envio_valores_tipo_sql($_POST['precio_venta'], "text"),
$utilidad,
$total_utilidad,
$total_mercancia,
$total_venta,
$calculo_gasto,
envio_valores_tipo_sql($_POST['detalles'], "text"),
envio_valores_tipo_sql($_POST['descripcion'], "text"),
envio_valores_tipo_sql($_POST['numero_factura'], "text"),
envio_valores_tipo_sql($_POST['cod_paises'], "text"),
envio_valores_tipo_sql($_POST['cod_productos'], "text"));

$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.2; ../admin/busqueda_productos.php">';
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
<td nowrap align="left">Codigo:</td>
<td><input type="text" name="cod_productos" value="<?php echo $datos['cod_productos_var']; ?>" size="70" required autofocus></td>
</tr>

<input type="hidden" name="cod_original" value="<?php echo $datos['cod_original']; ?>" size="70">
<tr valign="baseline">
<td nowrap align="left">No Factura:</td>
<td><input type="text" name="numero_factura" value="<?php echo $datos['numero_factura']; ?>" size="70"></td>
</tr>
<!--<tr valign="baseline">
<td nowrap align="right">Codif Precio:</td>-->
<input type="hidden" name="codificacion" value="<?php echo $datos['codificacion']; ?>" size="70">
<!--</tr>-->
<tr valign="baseline">
<td nowrap align="left">Nombre:</td>
<td><input type="text" name="nombre_productos" value="<?php echo $datos['nombre_productos']; ?>" size="70" required autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Marcas:</td>
<td>
<select name='cod_marcas'>
<?php $dato_guardado1 = $datos['cod_marcas'];

$sql_buscar_marcas = "SELECT * FROM marcas where cod_marcas = '$dato_guardado1'";
$dato_marca = mysql_query($sql_buscar_marcas, $conectar) or die(mysql_error());
$marca_encontrada = mysql_fetch_assoc($dato_marca);

$sql_consulta="SELECT * FROM marcas order by cod_marcas";
$resultado_marcas = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while (list($contenedor_marcas)=mysql_fetch_array($resultado_marcas)) {
if ($contenedor_marcas == $dato_guardado1) { ?>
<option selected value="<?php echo $marca_encontrada['cod_marcas'] ?>"><?php echo $marca_encontrada['nombre_marcas'] ?></option>
<?php } else { ?>
<option><?php echo $contenedor_marcas; ?></option>
<?php }} ?>
</select>
</td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Proveedores:</td>
<td>
<select name='cod_proveedores'>
<?php $dato_guardado2 = $datos['cod_proveedores'];

$sql_buscar_proveedores = "SELECT * FROM proveedores where cod_proveedores = '$dato_guardado2'";
$dato_proveedores = mysql_query($sql_buscar_proveedores, $conectar) or die(mysql_error());
$proveedores_encontrada = mysql_fetch_assoc($dato_proveedores);

$sql_consulta="SELECT * FROM proveedores order by cod_proveedores";
$resultado_proveedores = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while (list($contenedor_proveedores)=mysql_fetch_array($resultado_proveedores)) {
if ($contenedor_proveedores == $dato_guardado2) { ?>
<option selected value="<?php echo $proveedores_encontrada['cod_proveedores'] ?>"><?php echo $proveedores_encontrada['nombre_proveedores'] ?></option>
<?php } else { ?>
<option><?php echo $contenedor_proveedores; ?></option>
<?php }} ?>
</select>
</td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Paises:</td>
<td>
<select name='cod_paises'>
<?php $dato_guardado3 = $datos['cod_paises'];

$sql_buscar_paises = "SELECT * FROM paises where cod_paises = '$dato_guardado3'";
$dato_paises = mysql_query($sql_buscar_paises, $conectar) or die(mysql_error());
$paises_encontrada = mysql_fetch_assoc($dato_paises);

$sql_consulta="SELECT * FROM paises order by cod_paises";
$resultado_paises = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while (list($contenedor_paises)=mysql_fetch_array($resultado_paises)) {
if ($contenedor_paises == $dato_guardado3) { ?>
<option selected value="<?php echo $paises_encontrada['cod_paises'] ?>"><?php echo $paises_encontrada['nombre_paises'] ?></option>
<?php } else { ?>
<option><?php echo $contenedor_paises; ?></option>
<?php }} ?>
</select>
</td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Casilla:</td>
<td>
<select name='cod_nomenclatura'>
<?php $dato_guardado4 = $datos['cod_nomenclatura'];

$sql_buscar_nomenclatura = "SELECT * FROM nomenclatura where cod_nomenclatura = '$dato_guardado4'";
$dato_nomenclatura = mysql_query($sql_buscar_nomenclatura, $conectar) or die(mysql_error());
$nomenclatura_encontrada = mysql_fetch_assoc($dato_nomenclatura);

$sql_consulta="SELECT * FROM nomenclatura order by cod_nomenclatura";
$resultado_nomenclatura = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while (list($contenedor_nomenclatura)=mysql_fetch_array($resultado_nomenclatura)) {
if ($contenedor_nomenclatura == $dato_guardado4) { ?>
<option selected value="<?php echo $nomenclatura_encontrada['cod_nomenclatura'] ?>"><?php echo $nomenclatura_encontrada['nombre_nomenclatura'] ?></option>
<?php } else {?>
<option><?php echo $contenedor_nomenclatura; ?></option>
<?php }} ?>
</select>
</td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Tipos:</td>
<td>
<select name='cod_tipo'>
<?php $dato_guardado5 = $datos['cod_tipo'];

$sql_buscar_tipo = "SELECT * FROM tipo where cod_tipo = '$dato_guardado5'";
$dato_tipo = mysql_query($sql_buscar_tipo, $conectar) or die(mysql_error());
$tipo_encontrada = mysql_fetch_assoc($dato_tipo);

$sql_consulta="SELECT * FROM tipo order by cod_tipo";
$resultado_tipo = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while (list($contenedor_tipo)=mysql_fetch_array($resultado_tipo)) {
if ($contenedor_tipo == $dato_guardado5) { ?>
<option selected value="<?php echo $tipo_encontrada['cod_tipo'] ?>"><?php echo $tipo_encontrada['nombre_tipo'] ?></option>
<?php } else { ?>
<option><?php echo $contenedor_tipo; ?></option>
<?php }} ?>
</select>
</td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Unidades:</td>
<td><input type="text" name="unidades" value="<?php echo $datos['unidades_faltantes']; ?>" size="70" required autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Pre 2:</td>
<td><input type="text" name="precio_compra" value="<?php echo $datos['precio_compra']; ?>" size="70" required autofocus></td>
</tr>
<tr valign="baseline">
<td nowrap align="left">Valor:</td>
<td><input type="text" name="precio_venta" value="<?php echo $datos['precio_venta']; ?>" size="70" required autofocus></td>
</tr>
<!--<tr valign="baseline">
<td nowrap align="right">Detalles:</td>-->
<input type="hidden" name="detalles" value="<?php echo $datos['detalles']; ?>" size="70">
<tr valign="baseline">
<td nowrap align="left">Descripcion:</td>
<td><input type="text" name="descripcion" value="<?php echo $datos['descripcion']; ?>" size="70"></td>
</tr>
<td><input type="hidden" name="utilidad" value="<?php echo $datos['utilidad']; ?>" size="70"></td>
<td><input type="hidden" name="unidades_vendidas" value="<?php echo $datos['unidades_vendidas']; ?>" size="70"></td>
<td><input type="hidden" name="total_mercancia" value="<?php echo $datos['total_mercancia']; ?>" size="70"></td>
<tr valign="baseline">
<td nowrap align="left">&nbsp;</td>
<td><input type="submit" id="boton1" value="Actualizar registro"></td>
</tr>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<input type="hidden" name="cod_productos" value="<?php echo $datos['cod_productos_var']; ?>">
</form>
<center>
<p>&nbsp;</p>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
