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
?>
<center>
<form method="post" name="formulario" action="" accept-charset="UTF-8">
<table align="center" id="table">
<td nowrap align="right">Cod Factura:</td>
<td bordercolor="0">
<select name="cod_facturacion">
<?php $sql_consulta1="SELECT DISTINCT cod_facturacion FROM facturacion ORDER BY cod_facturacion DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {
?>
<option value="<?php echo $contenedor['cod_facturacion'] ?>"><?php echo $contenedor['cod_facturacion'] ?></option>
<?php }?>
</select></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" value="Consultar Factura"></td>
</tr>
</table>
</form>
</center>
<?php
$numer_factura = $_POST['cod_facturacion'];

$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$matriz_informacion = mysql_fetch_assoc($consultar_informacion);
$num_informacion = mysql_num_rows($consultar_informacion); 

$datos_factura = "SELECT * FROM facturacion WHERE cod_facturacion like '$numer_factura'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$datos_total_factura = "SELECT  Sum(vlr_total) as vlr_totl FROM facturacion WHERE cod_facturacion like '$numer_factura'";
$consulta_total = mysql_query($datos_total_factura, $conectar) or die(mysql_error());
$matriz_total_consulta = mysql_fetch_assoc($consulta_total);

$datos_vendedor = "SELECT DISTINCT * FROM facturacion WHERE cod_facturacion like '$numer_factura'";
$total_vendedor = mysql_query($datos_vendedor, $conectar) or die(mysql_error());
$matriz_vendedor = mysql_fetch_assoc($total_vendedor);

$datos_impuesto = "SELECT * FROM info_impuesto_facturas WHERE cod_factura like '$numer_factura'";
$total_impuesto = mysql_query($datos_impuesto, $conectar) or die(mysql_error());
$matriz_impuesto = mysql_fetch_assoc($total_impuesto);
$pagina = "busq_facturas_cod_factura";
?>
<center>
<table id="table" width="800">
<td><div align="center"><strong><p style="font-size:14px"><?php echo $matriz_informacion['nombre'];?><br><?php echo $matriz_informacion['localidad'];?>
<br><br><?php echo $matriz_informacion['nit'];?></strong></div></td>
<table id="numero_factura" width="800">
 <td nowrap align="right"><strong>FACTURA N&ordm;:</td>
<td><input type="text" name="numero_factura" value="<?php echo $_POST['cod_facturacion']; ?>" size="8"></td>

<td nowrap align="right"><strong>FECHA:</td>
<?php $obtener_fecha = "SELECT * FROM facturacion WHERE cod_facturacion = '$numer_factura'";
$consultar_fecha = mysql_query($obtener_fecha, $conectar) or die(mysql_error());
$matriz_fecha = mysql_fetch_assoc($consultar_fecha);
?>
<td><input type="text" name="fech" value="<?php echo $matriz_fecha['fecha_anyo']; ?>" size="9"></td>

<td nowrap align="right"><strong>VENDEDOR:</td>
<td><?php echo $matriz_vendedor['vendedor']; ?></td>
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
<?php 
$calculo_subtotal = $matriz_total_consulta['vlr_totl'] - $_POST['descuento_factura']; 
$calculo_total = $calculo_subtotal;
?>
<form method="post" name="formulario" action="../admin/buscar_imprimir_factura.php" accept-charset="UTF-8" target="_blank">
<center>
<table id="table" width="800">
<tr>
<td><div align="center"><strong>CAN</strong></div></td>
<td><div align="center"><strong>REF</strong></div></td>
<td><div align="center"><strong>PRODUCTO</strong></div></td>
<!--<td><div align="center"><strong>MARCA</strong></div></td>-->
<td><div align="center"><strong>DESCRIPCION</strong></div></td>
<td><div align="center"><strong>V. UNITARIO</strong></div></td>
<td><div align="center"><strong>V. TOTAL</strong></div></td>
<td><div align="center" ><strong>ELIM</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td><font color='yellow'><?php echo $matriz_consulta['cantidad']; ?></td></font>
<td><font color='yellow'><?php echo $matriz_consulta['cod_producto']; ?></td></font>
<td><font color='yellow'><?php echo $matriz_consulta['nombre_productos']; ?></td></font>
<!--<td><font color='yellow'><?php //echo $matriz_consulta['marca']; ?></td></font>-->
<td><font color='yellow'><?php echo $matriz_consulta['descripcion']; ?></td></font>
<td align="right"><font color='yellow'><?php echo number_format($matriz_consulta['vlr_unitario']); ?></td></font>
<td align="right"><font color='yellow'><?php echo number_format($matriz_consulta['vlr_total']); ?></td></font>
<td  nowrap><a href="../modificar_eliminar/eliminar_productos_factura.php?cod_producto=<?php echo $matriz_consulta['cod_producto']?>&cod_referencia=<?php echo $matriz_consulta['cod_factura']?>&pagina=<?php echo $pagina?>&cod_facturacion=<?php echo $_POST['cod_facturacion'];?>"><center><img src=../imagenes/eliminar.png alt="Eliminar"></a></td>
</tr>
<?php 
}
while ($matriz_consulta = mysql_fetch_assoc($consulta)); 
?>	
<table id="table" width="800">
<tr>
<td><div align="center"><strong>SUBTOTAL</strong></div></td>
<td><div align="center"><strong>DESCUENTO</strong></div></td>
<td><div align="center"><strong>SUBTOTAL</strong></div></td>
<td><div align="center"><strong>IVA %</strong></div></td>
<!--<td><div align="center"><strong>FLETE</strong></div></td>-->
<td><div align="center"><strong>CANCELADO</strong></div></td>
<td><div align="center"><strong>CAMBIO</strong></div></td>
<td><div align="center"><strong>TOTAL</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td align="right"><?php echo number_format($matriz_total_consulta['vlr_totl']); ?></td>
<td width="10"><input type="text" name="descuento_factura" value="<?php echo $matriz_impuesto['descuento']; ?>" size="15"></td>
<td align="right"><?php echo number_format($calculo_subtotal); ?></td>
<td width="10"><input type="text" name="iva" value="<?php echo $matriz_impuesto['iva']; ?>" size="15"></td>
<input type="hidden" name="flete" value="<?php echo $matriz_impuesto['flete']; ?>" size="15">
<td width="10"><input type="text" name="vlr_cancelado" value="<?php echo $matriz_impuesto['vlr_cancelado']; ?>" size="15"></td>
<td width="10"><input type="text" name="vlr_vuelto" value="<?php echo $matriz_impuesto['vlr_vuelto']; ?>" size="15"></td>
<td ><?php echo '.'; ?></td>
<input type="hidden" name="envio_cod_facturacion" value="<?php echo $_POST['cod_facturacion']; ?>" size="15">
<input type="hidden" name="envio_fecha" value="<?php echo $matriz_fecha['fecha_anyo'];?>" size="15">
<input type="hidden" name="envio_moto" value="<?php echo $matriz_vendedor['moto']; ?>" size="15">
<input type="hidden" name="envio_nombre" value="<?php echo $matriz_vendedor['nombre']; ?>" size="15">
<input type="hidden" name="envio_direccion" value="<?php echo $matriz_vendedor['direccion']; ?>" size="15">
<input type="hidden" name="envio_ciudad" value="<?php echo $matriz_vendedor['ciudad']; ?>" size="15">
<input type="hidden" name="envio_vendedor" value="<?php echo $matriz_vendedor['vendedor']; ?>" size="15">
</tr>
<?php 
} 
while ($matriz_consulta = mysql_fetch_assoc($consulta)); 
?>
<table id="enunciado" width="800">
<tr>
<td><div align="justify"><strong><?php echo $matriz_informacion['info_legal'];?></strong></div></td>
</table>
<td align="center"><input type="submit" value="Ver Factura" /></td>
</form>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
</head>