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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");

$numer_factura = $_POST['cod_factura'];

$datos_factura = "SELECT * FROM productos2 WHERE cod_factura = '$numer_factura'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$cod_proveedores = $matriz_consulta['cod_proveedores'];

$datos_proveedor = "SELECT * FROM proveedores WHERE cod_proveedores like '$cod_proveedores'";
$consulta_proveedor = mysql_query($datos_proveedor, $conectar) or die(mysql_error());
$proveedor = mysql_fetch_assoc($consulta_proveedor);

$nombre_proveedores = $proveedor['nombre_proveedores'];

$pagina ="buscar_facturas_compra";
?>
<center>
<form method="post" name="formulario" action="buscar_facturas_compra.php" accept-charset="UTF-8">
<table align="center">
<td nowrap align="right">Fecha Factura - Cod Factura:</td>
<td bordercolor="0">
<select name="cod_factura" id="foco">
<?php $sql_consulta1="SELECT DISTINCT cod_factura, fecha_pago FROM cuentas_facturas2 WHERE cod_factura <> '0' ORDER BY cod_factura ASC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {
$numero_registros = mysql_num_rows($resultado);
?>
<option value="<?php echo $contenedor['cod_factura'] ?>"><?php echo $contenedor['fecha_pago'].' - '.$contenedor['cod_factura'] ?></option>
<?php }?>
</select></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" value="Consultar Factura"></td>
</tr>
</table>
</form>
</center>
<?php $obtener_fecha = "SELECT * FROM cuentas_facturas2 WHERE cod_factura = '$numer_factura'";
$consultar_fecha = mysql_query($obtener_fecha, $conectar) or die(mysql_error());
$matriz_fecha = mysql_fetch_assoc($consultar_fecha);
$num_fecha = mysql_num_rows($consultar_fecha); ?>
<center>
<table width="100%">
<td><strong>FACTURA N&ordm;: <font size= "+0"><?php echo $_POST['cod_factura']; ?></font></td>
<td><strong>PROVEEDOR: <font size= "+0"><?php echo $nombre_proveedores; ?></font></td>
<td><strong>FECHA: <font size= "+0"><?php echo $matriz_fecha['fecha_pago']; ?></font></td>
<td><strong>TIPO PAGO: <font size= "+0"><?php echo $matriz_consulta['tipo_pago']; ?></font></td>
<td><strong>V.BRUTO: <font size= "+0"><?php echo number_format($matriz_fecha['valor_bruto']); ?></font></td>
<td><strong>DESCUENTO: <font size= "+0"><?php echo number_format($matriz_fecha['descuento']); ?></font></td>
<td><strong>V.NETO: <font size= "+0"><?php echo number_format($matriz_fecha['valor_neto']); ?></font></td>
<td><strong>V.IVA: <font size= "+0"><?php echo number_format($matriz_fecha['valor_iva']); ?></font></td>
<td><strong>TOTAL: <font size= "+0"><?php echo number_format($matriz_fecha['total']); ?></font></td>
<!--<td><strong>VENDEDOR:</td>
<td><?php //echo $matriz_vendedor['vendedor']; ?></td>-->
 </tr>
</table>
</center>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<form method="post" name="formulario" action="../admin/buscar_imprimir_factura.php" accept-charset="UTF-8" target="_blank">
<center>
<table width="100%">
<tr>
<td align="center"><strong>CODIGO</strong></td>
<td align="center"><strong>UNDS</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<!--<td><strong>MARCA</strong></td>-->
<td align="center"><strong>P.COMPRA</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
</tr>
<?php do { ?>
<tr>
<td><font><?php echo $matriz_consulta['cod_productos']; ?></td></font>
<td><font><?php echo $matriz_consulta['unidades']; ?></td></font>
<td><font><?php echo $matriz_consulta['nombre_productos']; ?></td></font>
<!--<td><font color='yellow'><?php //echo $matriz_consulta['marca']; ?></td></font>-->
<td align="right"><font><?php echo number_format($matriz_consulta['precio_compra'], 0, ",", "."); ?></td></font>
<td align="right"><font><?php echo number_format($matriz_consulta['precio_venta'], 0, ",", "."); ?></td></font>
</tr>	 
<?php 
} 
while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>