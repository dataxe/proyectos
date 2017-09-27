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
$buscar = $_POST['fecha'];
$frag = explode('-', $buscar);

$fecha_mes = $frag[0];
$nombre_ccosto = $frag[1];

$mostrar_datos_sum = "SELECT Sum(vlr_total_venta) As vlr_total_venta_sum, Sum(vlr_total_compra) As vlr_total_compra_sum, Sum(((precio_venta/1.16)*(iva/100))*unidades_vendidas) As sum_iva 
FROM ventas WHERE fecha_mes = '$fecha_mes' AND nombre_ccosto = '$nombre_ccosto' ORDER BY fecha_hora DESC";
$consulta_sum = mysql_query($mostrar_datos_sum, $conectar) or die(mysql_error());
$matriz_sum = mysql_fetch_assoc($consulta_sum);

$base = $datos['precio_venta']/1.16;
$iva_ptj = $datos['iva']/100;
$unidades_vendidas = $datos['unidades_vendidas'];
$iva_valor = ($base * $iva_ptj) * $unidades_vendidas;

$vlr_total_compra_sum = $matriz_sum['vlr_total_compra_sum'];
$vlr_total_venta_sum = $matriz_sum['vlr_total_venta_sum'];
$sum_iva = $matriz_sum['sum_iva'];
$ganancia = $vlr_total_venta_sum - $vlr_total_compra_sum;
$ptj_ganancia = ($ganancia / $vlr_total_compra_sum) * 100;

$mostrar_datos_sql = "SELECT * FROM ventas WHERE fecha_mes = '$fecha_mes' AND nombre_ccosto = '$nombre_ccosto' ORDER BY fecha_hora DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

require_once("menu_centro_costo.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<form method="post" name="formulario" action="">
<table align="center">
<td nowrap align="right">MES - CENTRO COSTO:</td>
<td bordercolor="0">
<select name="fecha">
<?php $sql_consulta1="SELECT DISTINCT fecha_mes, nombre_ccosto FROM ventas ORDER BY fecha DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['fecha_mes'].'-'.$contenedor['nombre_ccosto'] ?>"><?php echo $contenedor['fecha_mes'].' - '.$contenedor['nombre_ccosto'] ?></option>
<?php }?>
</select></td></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" value="Consultar Ventas"></td>
</tr>
</table>
</form>
</center>
<center>
<?php echo "<font color='yellow' size='+3'>CENTRO COSTO: ".$buscar."</font>";?>
<table width="100%">
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>MET</strong></td>
<td align="center"><strong>P.COSTO</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
<td align="center"><strong>TOTAL</strong></td>
<td align="center"><strong>%IVA</strong></td>
<td align="center"><strong>$IVA</strong></td>
<td align="center"><strong>%DESC</strong></td>
<td align="center"><strong>CENT.COSTO</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>FECHA VENTA</strong></td>
<td align="center"><strong>VENDEDOR</strong></td>
<td align="center"><strong>FECHA PAGO</strong></td>
<td align="center"><strong>PAGO A</strong></td>
<td align="center"><strong>HORA</strong></td>
</tr>
<?php do { 
$base = $datos['precio_venta']/1.16;
$iva_ptj = $datos['iva']/100;
$unidades_vendidas = $datos['unidades_vendidas'];
$iva_valor = ($base * $iva_ptj) * $unidades_vendidas;
$precio_venta = $datos['precio_venta'];
?>
<tr>
<td ><?php echo $datos['cod_productos']; ?></td>
<td ><?php echo $datos['nombre_productos']; ?></td>
<td align="right"><?php echo $datos['unidades_vendidas']; ?></td>
<td align="center"><?php echo $datos['detalles']; ?></td>
<td align="right"><?php echo $datos['precio_costo']; ?></td>
<td align="right"><?php echo $datos['precio_venta']; ?></td>
<td align="right"><?php echo $datos['vlr_total_venta']; ?></td>
<td align="center"><?php echo $datos['iva']; ?></td>
<td align="right"><?php echo intval($iva_valor); ?></td>
<td align="center"><?php echo $datos['descuento_ptj']; ?></td>
<td align="center"><?php echo $datos['nombre_ccosto']; ?></td>
<td align="center"><?php echo $datos['cod_factura']; ?></td>
<td align="right"><?php echo $datos['fecha_orig']; ?></td>
<td align="center"><?php echo $datos['vendedor']; ?></td>
<td align="right"><?php echo $datos['fecha_anyo']; ?></td>
<td align="center"><?php echo $datos['cuenta']; ?></td>
<td align="right"><?php echo $datos['fecha_hora']; ?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>

<table width='60%'>
<br>
<tr>
<td align="center"><strong>TOTAL COSTO</td>
<td align="center"><strong>TOTAL VENTA</td>
<td align="center"><strong>TOTAL IVA</td>
<td align="center"><strong>TOTAL GANANCIA</td>
<td align="center"><strong>% GANANCIA</td>
</tr>
<tr>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($vlr_total_compra_sum, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($vlr_total_venta_sum, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($sum_iva, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($ganancia, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+1"><strong><?php echo number_format($ptj_ganancia, 0, ",", ".").'%'; ?></font></td>
</tr>
</table>