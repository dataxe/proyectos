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

$cod_productos_var = addslashes($_GET['cod_productos']);

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
<a href="../admin/cargar_inventario_copia_vendedores.php"><center><FONT color='yellow'>REGRESAR</FONT></center></a>
<br>
<body>
<center>
<?php
if (isset($_GET['cod_productos'])) {

$sql_verificar_existencia = "SELECT cod_productos_var, nombre_productos, numero_factura, fechas_anyo, cuenta
FROM productos_copia_seguridad WHERE cod_productos_var = '$cod_productos_var'";
$consulta_verificar_existencia = mysql_query($sql_verificar_existencia, $conectar) or die(mysql_error());
$datos_verificar_existencia = mysql_fetch_assoc($consulta_verificar_existencia);
$resultado = mysql_num_rows($consulta_verificar_existencia);

if ($resultado <> 0) {
echo '<br><font color="yellow" size="+3"><img src=../imagenes/advertencia.gif alt="advertencia"> ESTE PRODUCTO YA FUE CARGADO <img src=../imagenes/advertencia.gif alt="advertencia"></font>';
} else {
}
}
?>
<form method="post" name="formulario_de_actualizacion" action="editar_productos_copia_vendedores.php">
<fieldset><legend><font color='yellow'><strong>ACTUALIZAR REGISTRO</strong></font></legend>
<br>
<table align="center" width='100%'>
<span id="envio_mensaje"></span> <br><br> 
<tr>
<td align='center' title="Codigo del prodctos."><font size='2px'><strong>CODIGO</strong></font></td>
<td align='center' title="Nombre del producto."><font size='2px'><strong>PRODUCTO</strong></font></td>
<td align='center' title="Unidades en inventario."><font size='2px'><strong>UND</strong></font></td>
<!-- INICIO PARA OCULTAR O MOSTRAR LA CASILLA DE UNIDADES NUEVAS -->
<?php
if ($resultado <> 0) { } else { ?>
<td align='center'><font size='2px'><strong>UND.N</strong></font></td>
<?php } ?>
<!-- FIN PARA OCULTAR O MOSTRAR LA CASILLA DE UNIDADES NUEVAS -->
<td align='center'><font size='2px'><strong>U.PQ</strong></font></td>
<td align='center'><font size='2px'><strong>P.VENT</strong></font></td>
<td align='center'><font size='2px'><strong>P.VENT2</strong></font></td>
<!-- INICIO PARA OCULTAR O MOSTRAR LA CASILLA DE FECHA DE VENCIMIENTO -->
<?php
if ($resultado <> 0) { } else { ?>
<td align='center'><font size='2px'><strong>F.VENC</strong></font></td>
<?php } ?>
<!-- FIN PARA OCULTAR O MOSTRAR LA CASILLA DE FECHA DE VENCIMIENTO -->
</tr>
<td align='center'><?php echo $cod_productos_var; ?></td>
<td align='center'><?php echo $datos['nombre_productos']; ?></td>
<td align='center'><?php echo $datos['unidades_faltantes']; ?></td>
<!-- INICIO PARA OCULTAR O MOSTRAR LA CASILLA DE UNIDADES NUEVAS -->
<?php
if ($resultado <> 0) { } else { ?>
<td align="center"><input onblur="validar_codigo(this);" type="text" name="unidades_nuevas" value="" id="unidades_nuevas" size="2" required autofocus/></td>
<?php } ?>
<!-- FIN PARA OCULTAR O MOSTRAR LA CASILLA DE UNIDADES NUEVAS -->
<td align='center'><?php echo $datos['unidades']; ?></td>
<td align='center'><?php echo number_format($datos['precio_venta'], 0, ",", "."); ?></td>
<td align='center'><?php echo number_format($datos['vlr_total_venta'], 0, ",", "."); ?></td>
<!-- INICIO PARA OCULTAR O MOSTRAR LA CASILLA DE FECHA DE VENCIMIENTO -->
<?php
if ($resultado <> 0) { } else { ?>
<td align='center'><input type="text" name="fechas_vencimiento" value="<?php echo $datos['fechas_vencimiento']; ?>" size="10"></td>
<?php } ?>
<!-- FIN PARA OCULTAR O MOSTRAR LA CASILLA DE FECHA DE VENCIMIENTO -->

<input type="hidden" name="cod_productos_var" value="<?php echo $cod_productos_var; ?>" size="70">
<input type="hidden" name="pagina" value="cargar_inventario_copia_vendedores.php" size="70">
</tr>
</table>
</fieldset>
<br>
<input type="hidden" name="MM_update" value="formulario_de_actualizacion">
<!-- INICIO PARA OCULTAR O MOSTRAR EL BOTON SUBMIT -->
<?php
if ($resultado <> 0) { } else { ?>
<center><td><input type="submit" id="boton1" value="Actualizar registro"></td></center>
<?php } ?>
<!-- FIN PARA OCULTAR O MOSTRAR EL BOTON SUBMIT -->

</form>
<center>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
<script src="prototype.js" type="text/javascript"></script>
<script type="text/javascript">
function validar_codigo(usuario)        {
var url = 'validar_signo_positivo.php';
var parametros='unidades_nuevas='+unidades_nuevas.value;
var ajax = new Ajax.Updater('envio_mensaje',url,{method: 'get', parameters: parametros});
}
</script>