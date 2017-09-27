<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
//$cuenta_actual = addslashes($_SESSION['usuario']);
$obtener_diseno = "SELECT * FROM disenos WHERE nombre_disenos LIKE 'por_defecto.css'";
$resultado_diseno = mysql_query($obtener_diseno, $conectar) or die(mysql_error());
$matriz_diseno = mysql_fetch_assoc($resultado_diseno); ?>
<link rel="stylesheet" type="text/css" href="../estilo_css/por_defecto.css">

<style type="text/css"><!--
body { background-color: #000000;}
--></style>
<?php
$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$matriz_informacion = mysql_fetch_assoc($consultar_informacion);
$num_informacion = mysql_num_rows($consultar_informacion); 

$buscar = $_POST['envio_cod_facturacion'];

$datos_factura = "SELECT * FROM facturacion WHERE cod_facturacion like '$buscar'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$datos_total_factura = "SELECT  Sum(vlr_total) as vlr_totl FROM facturacion WHERE cod_facturacion like '$buscar'";
$consulta_total = mysql_query($datos_total_factura, $conectar) or die(mysql_error());
$matriz_total_consulta = mysql_fetch_assoc($consulta_total);

$datos_facturacion = "SELECT * FROM facturacion WHERE cod_facturacion like '$buscar'";
$consulta_total = mysql_query($datos_facturacion, $conectar) or die(mysql_error());
$matriz_factura = mysql_fetch_assoc($consulta_total);

$datos_facturacion_mano_obra = "SELECT * FROM facturacion WHERE cod_facturacion like '$buscar' AND nombre_productos = 'MANO DE OBRA'";
$consulta_totall = mysql_query($datos_facturacion_mano_obra, $conectar) or die(mysql_error());
$matriz_mano_obra = mysql_fetch_assoc($consulta_totall);
?>
<center>
<table id="table" width="800">
<td><div align="center"><strong><p style="font-size:14px"><?php echo $matriz_informacion['nombre'];?><br><?php echo $matriz_informacion['localidad'];?><br><br><?php echo $matriz_informacion['nit'];?></strong></div></td>
	
<table id="table" width="800">
<form method="post" name="formulario" action="../admin/facturacion.php">
<td nowrap align="right"><strong>FACTURA N&ordm;:</td>
<td align="right"><?php echo $_POST['envio_cod_facturacion'];?></td>

<td nowrap align="right"><strong>FECHA:</td>
<td align="right"><?php echo $_POST['envio_fecha']; ?></td>

<td nowrap align="right"><strong>VENDEDOR:</td>
<td align="right"><?php echo $matriz_factura['vendedor']; ?></td>
</form>
</table>
</center>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<link href="../imagenes/<?php echo $matriz_informacion['icono'];?>" type="image/x-icon" rel="shortcut icon" />
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title><?php echo "Factura No ".$buscar;?></title>
</head>
<body>
<center>
<table id="table" width="800">
<tr>
<td><div align="center"><strong>CAN</strong></div></td>
<td><div align="center"><strong>REFERENCIA</strong></div></td>
<td><div align="center"><strong>PRODUCTO</strong></div></td>
<td><div align="center"><strong>V. UNITARIO</strong></div></td>
<td><div align="center"><strong>V. TOTAL</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td ><?php echo $matriz_consulta['cantidad']; ?></td>
<td ><?php echo $matriz_consulta['cod_producto']; ?></td>
<td ><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td align="right"><?php echo number_format($matriz_consulta['vlr_unitario']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['vlr_total']); ?></td>
</tr>	 <?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>

<?php 
$calculo_subtotal = $matriz_total_consulta['vlr_totl'] - $_POST['descuento_factura']; 
$calculo_total = $calculo_subtotal;
$calculo_iva_sin_man_obr = $calculo_subtotal * 
$calculo_flete = $calculo_subtotal * ($_POST['flete'] / 100);
$calculo_mano_obra =  $matriz_mano_obra['vlr_total'] * ($_POST['iva'] / 100);
$calculo_iva = $calculo_iva_sin_man_obr - $calculo_mano_obra;
$vlr_cancelado = $_POST['vlr_cancelado'];
$vlr_vuelto = $vlr_cancelado - $calculo_total;
?>
<form action="" method="get">
<table id="table" width="800">
<tr>
<td><div align="center"><strong>SUBTOTAL</strong></div></td>
<td><div align="center"><strong>DESCUENTO</strong></div></td>
<td><div align="center"><strong>SUBTOTAL</strong></div></td>
<td><div align="center"><strong>IVA</strong></div></td>
<td><div align="center"><strong>FLETE</strong></div></td>
<td><div align="center"><strong>CANCELADO</strong></div></td>
<td><div align="center"><strong>CAMBIO</strong></div></td>
<td><div align="center"><strong>TOTAL</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td align="right"><?php echo number_format($matriz_total_consulta['vlr_totl']); ?></td>
<td align="right"><?php echo number_format($_POST['descuento_factura']); ?></td>
<td align="right"><?php echo number_format($calculo_subtotal); ?></td>
<td align="right"><?php echo number_format($calculo_iva); ?></td>
<td align="right"><?php echo number_format($calculo_flete); ?></td>
<td align="right"><?php echo number_format($vlr_cancelado); ?></td>
<td align="right"><?php echo number_format($vlr_vuelto); ?></td>
<td align="right"><font size="+1"><strong><?php echo number_format($calculo_total); ?></strong></font></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
<table id="enunciado" width="800">
<tr>
<td><div align="justify"><strong><?php echo $matriz_informacion['info_legal'];?></strong></div></td>
</table>
<input type="button" name="imprimir" value="Imprimir"  onClick="window.print();"/>
</form>