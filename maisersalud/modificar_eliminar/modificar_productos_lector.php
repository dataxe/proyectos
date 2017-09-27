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

$cod_productos_var = $_GET['cod_productos'];

$sql_modificar_consulta = "SELECT * FROM productos where cod_productos_var = '$cod_productos_var'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
$total_resultado = mysql_num_rows($modificar_consulta);

$sql_productos = "SELECT * FROM productos where cod_productos_var = '$cod_productos_var'";
$modificar_productos = mysql_query($sql_productos, $conectar) or die(mysql_error());
$total_resultado = mysql_num_rows($modificar_productos);

$cod_productos = $datos['cod_productos'];

$cod_productos_var = $_POST['cod_productos_var'];
$nombre_productos0 = $_POST["nombre_productos"];
$nombre_productos1 = preg_replace('/,/', '.', $nombre_productos0);
$nombre_productos2 = preg_replace("/'/", ' -', $nombre_productos1);
$nombre_productos3 = preg_replace("/;/", ' :', $nombre_productos2);
$nombre_productos = strtoupper(preg_replace('/"/', ' -', $nombre_productos3));
$cod_marcas = $_POST['cod_marcas'];
$cod_proveedores = $_POST['cod_proveedores'];
$cod_nomenclatura = $_POST['cod_nomenclatura'];
$cod_tipo = $_POST['cod_tipo'];
$cod_original = $_POST['cod_original'];
$codificacion = $_POST['codificacion'];
$und_orig = $_POST['unidades'];
$unidades_nuevas = $_POST['unidades_nuevas'];
$unidades = $und_orig + $unidades_nuevas;
$precio_compra = $_POST['precio_compra'];
$precio_costo = $_POST['precio_compra'];
$precio_venta = $_POST['precio_venta'];
$detalles = $_POST['detalles'];
$descripcion0 = $_POST["descripcion"];
$descripcion1 = preg_replace('/,/', '.', $descripcion0);
$descripcion2 = preg_replace("/'/", ' -', $descripcion1);
$descripcion3 = preg_replace("/;/", ' :', $nombre_productos2);
$descripcion = strtoupper(preg_replace('/"/', ' -', $descripcion3));
$numero_factura = $_POST['numero_factura'];
$cod_paises = $_POST['cod_paises'];
$porcentaje_vendedor = $_POST['porcentaje_vendedor'];
$fechas_vencimiento = $_POST['fechas_vencimiento'];

$unidades_vendidas_cambia = "0";
$utilidad = $precio_venta - $precio_compra;
$gasto = $unidades * $precio_compra;
$calculo_unidades_vendidas = $unidades - $datos['unidades_faltantes'];
$total_mercancia = $precio_compra * $datos['unidades_faltantes'];
$total_mercancia_cambia = $precio_compra * $unidades;
$total_venta = $precio_venta * $datos['unidades_vendidas'];
$total_venta_cambia = $precio_venta * $datos['unidades_vendidas'];
$total_utilidad_cambia = $utilidad * $unidades_vendidas_cambia;
$total_utilidad = $utilidad * $datos['unidades_vendidas'];

$fechas_dia = date("Y/m/d");
$fechas_mes = date("m/Y");
$fechas_anyo = date("d/m/Y");
$fechas_hora = date("H:i:s");

$dato_fecha = explode('/', $fechas_vencimiento);
$dia = $dato_fecha[0];
$mes = $dato_fecha[1];
$anyo = $dato_fecha[2];
$fechas_vencimiento_Y_m_d = $anyo.'/'.$mes.'/'.$dia;
$fechas_vencimiento_seg = strtotime($fechas_vencimiento_Y_m_d);

if ($total_resultado == NULL) {
echo "<head>";
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='true'></embed>";
echo "<font color='yellow'><center><img src=../imagenes/advertencia.gif alt='Advertencia'>El producto <strong>$cod_productos</strong> no existe dentro del inventario del sistema. <img src=../imagenes/advertencia.gif alt='Advertencia'></center></font>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; ../admin/buscar_productos_editar.php">';
echo "</head>";
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion") && ($unidades <> $datos['unidades_faltantes'])) {

$actualizar_sql1 = sprintf("UPDATE productos SET cod_productos_var = '$cod_productos_var', nombre_productos = UPPER('$nombre_productos'), cod_marcas = '$cod_marcas',
cod_proveedores = '$cod_proveedores', cod_nomenclatura = '$cod_nomenclatura', cod_tipo = '$cod_tipo', cod_original = '$cod_original', codificacion = '$codificacion', 
unidades_faltantes = '$unidades', unidades_vendidas = '$unidades_vendidas_cambia', und_orig = '$und_orig', precio_compra = '$precio_compra', precio_costo = '$precio_costo', 
precio_venta = '$precio_venta', utilidad = '$utilidad', total_utilidad = '$total_utilidad_cambia', total_mercancia = '$total_mercancia_cambia', 
total_venta = '$total_venta_cambia', gasto = '$gasto', detalles = '$detalles', descripcion = UPPER('$descripcion'), numero_factura = '$numero_factura',
cod_paises = '$cod_paises', fechas_dia = '$fechas_dia', fechas_mes = '$fechas_mes', fechas_anyo = '$fechas_anyo', fechas_hora = '$fechas_hora', 
fechas_vencimiento = '$fechas_vencimiento',fechas_vencimiento_seg = '$fechas_vencimiento_seg',vendedor = '$cuenta_actual', porcentaje_vendedor = UPPER('$porcentaje_vendedor')
WHERE cod_productos = '$cod_productos'");

$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.2; ../admin/buscar_productos_editar.php">';

} elseif ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "formulario_de_actualizacion") && ($unidades == $datos['unidades_faltantes'])) {

$actualizar_sql1 = sprintf("UPDATE productos SET cod_productos_var = '$cod_productos_var', nombre_productos = UPPER('$nombre_productos'), cod_marcas = '$cod_marcas',
cod_proveedores = '$cod_proveedores', cod_nomenclatura = '$cod_nomenclatura', cod_tipo = '$cod_tipo', cod_original = '$cod_original', codificacion = '$codificacion', 
unidades_faltantes = '$unidades', unidades_vendidas = '$unidades_vendidas_cambia', und_orig = '$und_orig', precio_compra = '$precio_compra', precio_costo = '$precio_costo', 
precio_venta = '$precio_venta', utilidad = '$utilidad', total_utilidad = '$total_utilidad', total_mercancia = '$total_mercancia', 
total_venta = '$total_venta', gasto = '$gasto', detalles = '$detalles', descripcion = UPPER('$descripcion'), numero_factura = '$numero_factura',
cod_paises = '$cod_paises', fechas_dia = '$fechas_dia', fechas_mes = '$fechas_mes', fechas_anyo = '$fechas_anyo', fechas_hora = '$fechas_hora', 
fechas_vencimiento = '$fechas_vencimiento', fechas_vencimiento_seg = '$fechas_vencimiento_seg', vendedor = '$cuenta_actual', porcentaje_vendedor = UPPER('$porcentaje_vendedor') 
WHERE cod_productos = '$cod_productos'");

$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.2; ../admin/buscar_productos_editar.php">';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<a href="../admin/buscar_productos_editar.php"><center><FONT color='white'>REGRESAR</FONT></center></a>
<br>
<body>
<center>
<form method="post" name="formulario_de_actualizacion" action="">
<table width="100%">
<tr>
<td align='center'><font size='2px'><strong>CODIGO</strong></font></td>
<td align='center'><font size='2px'><strong>FACT</strong></font></td>
<td align='center'><font size='2px'><strong>PRODUCTO</strong></font></td>
<td align='center'><font size='2px'><strong>MARCA</strong></font></td>
<td align='center'><font size='2px'><strong>PROVEEDOR</strong></font></td>
<td align='center'><font size='2px'><strong>PAIS</strong></font></td>
<td align='center'><font size='2px'><strong>EST</strong></font></td>
<td align='center'><font size='2px'><strong>TIPO</strong></font></td>
<td align='center'><font size='2px'><strong>UND</strong></font></td>
<td align='center'><font size='2px'><strong>UND.N</strong></font></td>
<td align='center'><font size='2px'><strong>P.COMP</strong></font></td>
<td align='center'><font size='2px'><strong>P.VENT</strong></font></td>
<td align='center'><font size='2px'><strong>F.VENC</strong></font></td>
<td align='center'><font size='2px'><strong>RAYA</strong></font></td>
<td align='center'><font size='2px'><strong>DESCRIP</strong></font></td>
</tr>
<?php
while ($productos = mysql_fetch_assoc($modificar_productos)) {

?>
<td align='center'><input type="text" name="cod_productos_var" value="<?php echo $productos['cod_productos_var']; ?>" size="18" required autofocus></td>
<input type="hidden" name="cod_original" value="<?php echo $productos['cod_original']; ?>" size="7">
<td align='center'><input type="text" name="numero_factura" value="<?php echo $productos['numero_factura']; ?>" size="3"></td>
<input type="hidden" name="codificacion" value="<?php echo $productos['codificacion']; ?>" size="7"></td>
<td align='center'><input type="text" name="nombre_productos" value="<?php echo $productos['nombre_productos']; ?>" size="30" required autofocus></td>
<td>
<select name='cod_marcas'>
<?php $dato_guardado1 = $productos['cod_marcas'];

$sql_buscar_marcas = "SELECT * FROM marcas where cod_marcas = $dato_guardado1";
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

<td>
<select name='cod_proveedores'>
<?php $dato_guardado2 = $productos['cod_proveedores'];

$sql_buscar_proveedores = "SELECT * FROM proveedores where cod_proveedores = $dato_guardado2";
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
<td>
<select name='cod_paises'>
<?php $dato_guardado3 = $productos['cod_paises'];

$sql_buscar_paises = "SELECT * FROM paises where cod_paises = $dato_guardado3";
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
<td>
<select name='cod_nomenclatura'>
<?php $dato_guardado4 = $productos['cod_nomenclatura'];

$sql_buscar_nomenclatura = "SELECT * FROM nomenclatura where cod_nomenclatura = $dato_guardado4";
$dato_nomenclatura = mysql_query($sql_buscar_nomenclatura, $conectar) or die(mysql_error());
$nomenclatura_encontrada = mysql_fetch_assoc($dato_nomenclatura);

$sql_consulta="SELECT * FROM nomenclatura order by cod_nomenclatura";
$resultado_nomenclatura = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while (list($contenedor_nomenclatura)=mysql_fetch_array($resultado_nomenclatura)) {
if ($contenedor_nomenclatura == $dato_guardado4) { ?>
<option selected value="<?php echo $nomenclatura_encontrada['cod_nomenclatura'] ?>"><?php echo $nomenclatura_encontrada['nombre_nomenclatura'] ?></option>
<?php } else { ?>
<option><?php echo $contenedor_nomenclatura; ?></option>
<?php }} ?>
</select>
</td>

<td>
<select name='cod_tipo'>
<?php $dato_guardado5 = $productos['cod_tipo'];

$sql_buscar_tipo = "SELECT * FROM tipo where cod_tipo = $dato_guardado5";
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
<td align='center'><?php echo $productos['unidades_faltantes']; ?></td>
<input type="hidden" name="unidades" value="<?php echo $productos['unidades_faltantes']; ?>" size="2" required autofocus>
<td align='center'><input type="text" name="unidades_nuevas" id="foco" value="" size="2" required autofocus></td>
<td align='center'><input type="text" name="precio_compra" value="<?php echo $productos['precio_costo']; ?>" size="6" required autofocus></td>
<td align='center'><input type="text" name="precio_venta" value="<?php echo $productos['precio_venta']; ?>" size="6" required autofocus></td>
<input type="hidden" name="detalles" value="<?php echo $productos['detalles']; ?>" size="10">
<td align='center'><input type="text" name="fechas_vencimiento" value="<?php echo $productos['fechas_vencimiento']; ?>" size="8"></td>
<td align='center'><input type="text" name="porcentaje_vendedor" value="<?php echo $productos['porcentaje_vendedor']; ?>" size="1"></td>
<td align='center'><input type="text" name="descripcion" value="<?php echo $productos['descripcion']; ?>" size="10"></td>
<input type="hidden" name="utilidad" value="<?php echo $productos['utilidad']; ?>" size="70">
<input type="hidden" name="unidades_vendidas" value="<?php echo $productos['unidades_vendidas']; ?>" size="70">
<input type="hidden" name="total_mercancia" value="<?php echo $productos['total_mercancia']; ?>" size="70">
</tr>
<?php } ?>
</table>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td><input type="submit" id="boton1" value="Actualizar registro"></td>
</tr>
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
