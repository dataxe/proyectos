<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
include ("../formato_entrada_sql/funcion_env_val_sql.php");

if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);

$obtener_diseno = "SELECT * FROM disenos WHERE nombre_disenos LIKE 'por_defecto.css'";
$resultado_diseno = mysql_query($obtener_diseno, $conectar) or die(mysql_error());
$matriz_diseno = mysql_fetch_assoc($resultado_diseno); ?>
<link rel="stylesheet" type="text/css" href="../estilo_css/por_defecto.css">

<style type="text/css"> <!--
body { background-color: #000000;}
--></style>
<center>
<?php
$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$matriz_informacion = mysql_fetch_assoc($consultar_informacion);
?>
<table id="table" width="800">
<td><div align="center"><strong><p style="font-size:14px"><?php echo $matriz_informacion['nombre'];?><br><?php echo $matriz_informacion['localidad'];?>
<br><br><?php echo $matriz_informacion['nit'];?></strong></div></td>
	
<table id="table" width="800">
<form method="post" id="table" name="formulario" action="../admin/facturacion.php">
<td nowrap align="right"><strong>FACTURA N&ordm;:</td>
<?php $obtener_facturacion = "SELECT * FROM factura_cod WHERE cod_factura = 1";
$modificar_facturacion = mysql_query($obtener_facturacion, $conectar) or die(mysql_error());
$matriz_factura = mysql_fetch_assoc($modificar_facturacion);?>
<td><input type="text" name="numero_factura" value="<?php echo $matriz_factura['numero_factura']; ?>" size="8"></td>
<?php $factura_act = $matriz_factura['numero_factura'];?>

<td nowrap align="right"><strong>FECHA:</td>
<?php $obtener_fecha = "SELECT * FROM facturacion WHERE cod_facturacion = '$factura_act'";
$consultar_fecha = mysql_query($obtener_fecha, $conectar) or die(mysql_error());
$matriz_fecha = mysql_fetch_assoc($consultar_fecha);?>
<td><input type="text" name="fech" value="<?php echo $matriz_fecha['fecha']; ?>" size="10"></td>

<td nowrap align="left"><strong>VENDEDOR:</td>
<td><?php echo $cuenta_actual; ?></td>
</form>
</table>
</center>
<?php
$buscar = $matriz_factura['numero_factura'];
$muestra_faltante = $numero_de_pagina * $numero_maximo_de_muestra;
$datos_factura = "SELECT * FROM facturacion WHERE cod_facturacion like '$buscar'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

$datos_total_factura = "SELECT  Sum(vlr_total) as vlr_totl FROM facturacion WHERE cod_facturacion like '$buscar'";
$consulta_total = mysql_query($datos_total_factura, $conectar) or die(mysql_error());
$matriz_total_consulta = mysql_fetch_assoc($consulta_total);

$datos_facturacion = "SELECT * FROM facturacion WHERE cod_facturacion like '$buscar'";
$consulta_total = mysql_query($datos_facturacion, $conectar) or die(mysql_error());
$matriz_factura = mysql_fetch_assoc($consulta_total);

$datos_facturacion_mano_obra = "SELECT * FROM facturacion WHERE cod_facturacion like '$buscar' 
AND nombre_productos = 'MANO DE OBRA'";
$consulta_totall = mysql_query($datos_facturacion_mano_obra, $conectar) or die(mysql_error());
$matriz_mano_obra = mysql_fetch_assoc($consulta_totall);

$iva_valor = $_POST['iva'];
$flete_valor = $_POST['flete'];
$descuento_valor = $_POST['descuento_factura'];

$calculo_subtotal = $matriz_total_consulta['vlr_totl'] - $descuento_valor; 
$calculo_total = $calculo_subtotal;
$calculo_iva_sin_man_obr = $calculo_subtotal * ($iva_valor / 100);
$calculo_flete = $calculo_subtotal * ($flete_valor / 100);
$calculo_mano_obra =  $matriz_mano_obra['vlr_total'] * ($iva_valor / 100);
$calculo_iva = $calculo_iva_sin_man_obr - $calculo_mano_obra;

$vlr_cancelado = $_POST['vlr_cancelado'];
$vlr_vuelto = $vlr_cancelado - $calculo_total;

if ($descuento_valor == NULL) {
echo "<font color='yellow'><center><br><strong>Ha dejado el campo Descuento vacio.</strong></center></font>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; facturacion.php">';
}
if ($flete_valor == NULL) {
echo "<font color='yellow'><center><br><strong>Ha dejado el campo Flete vacio.</strong></center></font>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; facturacion.php">';
}
if ($iva_valor == NULL) {
echo "<font color='yellow'><center><br><strong>Ha dejado el campo Iva vacio.</strong></center></font>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; facturacion.php">';
}
if ($vlr_cancelado == NULL) {
echo "<font color='yellow'><center><br><strong>Ha dejado el campo VLR CANCELADO vacio.</strong></center></font>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; facturacion.php">';
}
elseif ($iva_valor == NULL && $flete_valor == NULL) {
echo "<font color='yellow'><center><br><strong>Ha dejado los campos Flete e Iva vacios.</strong></center></font>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; facturacion.php">';
}
elseif ($iva_valor == NULL && $descuento_valor == NULL) {
echo "<font color='yellow'><center><br><strong>Ha dejado los campos Descuento e Iva vacios.</strong></center></font>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; facturacion.php">';
}
elseif ($flete_valor == NULL && $descuento_valor == NULL) {
echo "<font color='yellow'><center><br><strong>Ha dejado los campos Descuento y Flete vacios.</strong></center></font>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; facturacion.php">';
}
elseif ($flete_valor == NULL && $descuento_valor == NULL || $iva_valor == NULL) {
echo "<font color='yellow'><center><br><strong>Ha dejado los campos Descuento, Flete e Iva vacios.</strong></center></font>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; facturacion.php">';
}
$agregar_regis = sprintf("INSERT INTO info_impuesto_facturas (iva, flete, descuento, fecha_dia, fecha_mes, fecha_anyo, vlr_cancelado, vlr_vuelto, cod_factura) 
VALUES 	(%s, %s, %s, %s, %s, %s, %s, %s, %s)",
$iva_valor, 
$flete_valor, 
$descuento_valor,
envio_valores_tipo_sql(date("Y/m/d"), "text"),
envio_valores_tipo_sql(date("Y/m"), "text"),
envio_valores_tipo_sql(date("Y"), "text"),
envio_valores_tipo_sql($vlr_cancelado, "text"),
envio_valores_tipo_sql($vlr_vuelto, "text"),
envio_valores_tipo_sql($buscar, "text"));

$resultado_sq = mysql_query($agregar_regis, $conectar) or die(mysql_error());
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<link href="../imagenes/<?php echo $matriz_informacion['icono'];?>" type="image/x-icon" rel="shortcut icon" />
<title><?php echo "Factura No ".$buscar;?></title>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title><?php echo $matriz_factura['numero_factura']?></title>
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
<td><?php echo $matriz_consulta['cantidad']; ?></td>
<td><?php echo $matriz_consulta['cod_producto']; ?></td>
<td><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td align="right"><?php echo number_format($matriz_consulta['vlr_unitario']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['vlr_total']); ?></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta));

$obtener_ultimo_num_factura = "SELECT numero_factura FROM factura_cod WHERE cod_factura = 1";
$cargar_num_factura = mysql_query($obtener_ultimo_num_factura, $conectar) or die(mysql_error());
$matriz_num_factura = mysql_fetch_assoc($cargar_num_factura);

$aumentar_mas_1_factura = sprintf("UPDATE factura_cod SET numero_factura=%s WHERE cod_factura = 1",
envio_valores_tipo_sql($matriz_num_factura['numero_factura']+1, "text"),
envio_valores_tipo_sql($_POST['cod_factura'], "text"));
             
$resultado_aumentar_mas_1_factura = mysql_query($aumentar_mas_1_factura, $conectar) or die(mysql_error());
?>
<form action="" method="get">
<table  id="table" width="800">
<tr>
<td><div align="center"><strong>SUBTOTAL</strong></div></td>
<td><div align="center"><strong>DESCUENTO</strong></div></td>
<td><div align="center"><strong>SUBTOTAL</strong></div></td>
<td><div align="center"><strong>IVA INC</strong></div></td>
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
<td align="right"><font size="+1"><?php echo number_format($vlr_vuelto); ?></td>
<td align="right"><font size="+1"><strong><?php echo number_format($calculo_total); ?></strong></font></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
<table id="enunciado" width="800">
<tr>
<td><div align="justify"><strong><?php echo $matriz_informacion['info_legal'];?></strong></div></td>
</table>
<input type="button" value="Ir atr&aacute;s" onclick="history.back()"/>
<input type="button" name="imprimir" value="Imprimir"  onClick="window.print();"/>
</form>