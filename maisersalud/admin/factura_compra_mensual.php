<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
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
//include ("../registro_movimientos/registro_cierre_caja.php");

require_once("menu_facturas_compra.php");
$fecha = addslashes($_POST['fecha']);
$hora = date("H:i:s");
$campo = 'fecha_mes';

//-------------------------------------------- CALCULO PARA EL TOTAL DE COMPRA --------------------------------------------//
$mostrar_datos_totales = "SELECT sum(precio_compra_con_descuento) AS tot_fact, sum(valor_iva) AS valor_iva, 
sum(vlr_total_venta * cajas) AS vlr_total_ventas FROM facturas_cargadas_inv WHERE fecha_mes = '$fecha'";
$consulta_totales = mysql_query($mostrar_datos_totales, $conectar) or die(mysql_error());
$totales = mysql_fetch_assoc($consulta_totales);

$tot_fact = $totales['tot_fact'];
$valor_iva = $totales['valor_iva'];
$vlr_total_ventas = $totales['vlr_total_ventas'];

$mostrar_datos_sql = "SELECT facturas_cargadas_inv.cod_factura, proveedores.nombre_proveedores, 
Sum(facturas_cargadas_inv.precio_compra_con_descuento) AS precio_compra_con_descuento, facturas_cargadas_inv.vendedor, 
facturas_cargadas_inv.fecha_anyo, facturas_cargadas_inv.fecha_hora, facturas_cargadas_inv.fecha_mes, facturas_cargadas_inv.tipo_pago
FROM proveedores RIGHT JOIN facturas_cargadas_inv ON proveedores.cod_proveedores = facturas_cargadas_inv.cod_proveedores
GROUP BY facturas_cargadas_inv.cod_factura HAVING (((facturas_cargadas_inv.fecha_mes)='$fecha')) ORDER BY facturas_cargadas_inv.fecha DESC";
//$mostrar_datos_sql = "SELECT * FROM facturas_cargadas_inv WHERE fecha_mes = '$fecha'";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);
$datos = mysql_fetch_assoc($consulta);
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
<td nowrap align="right">FACTURA COMPRA MENSUAL:</td>
<td bordercolor="0">
<select name="fecha" id="foco">
<?php $sql_consulta1="SELECT DISTINCT fecha_mes FROM facturas_cargadas_inv ORDER BY fecha DESC";
$resultado = mysql_query($sql_consulta1, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option value="<?php echo $contenedor['fecha_mes'] ?>"><?php echo $contenedor['fecha_mes'] ?></option>
<?php }?>
</select></td></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Consultar Compras"></td>
</tr>
</table>
</form>
</center>
<?php
if ($fecha <> NULL) {?>
<center>
<fieldset><legend><font color='yellow' size='+3'>COMPRAS TOTALES MES: <?php echo $fecha.' - '.$hora;?></font></legend>
<table width='40%'>
<br>
<tr>
<td align="center"><strong>TOTAL FACTURA COMPRA</td>
<td align="center"><strong>TOTAL VENT PUBLIC (PROYEC)</td>
</tr>
<tr>
<td align="right"><font color="yellow" size="+2"><strong><?php echo number_format($tot_fact, 0, ",", "."); ?></font></td>
<td align="right"><font color="yellow" size="+2"><strong><?php echo number_format($vlr_total_ventas, 0, ",", "."); ?></font></td>
</tr>
</table>
</center>
</fieldset>
<!--
<center>
<table>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_datos_ventas_pequena.php?fecha=<?php echo $fecha?>&campo=<?php echo $campo?>" target="_blank"><img src=../imagenes/imprimir_1.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<a href="../admin/imprimir_datos_ventas_grande.php?fecha=<?php echo $fecha?>&campo=<?php echo $campo?>" target="_blank"><img src=../imagenes/imprimir_.png alt="imprimir"></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</td>
</table>
</center>
-->
<br>
<fieldset><legend><font color='yellow' size='+3'>COMPRAS MES: <?php echo $fecha.' - '.$hora;?></font></legend>
<table width='70%'>
<tr>
<td align="center"><strong>VER</strong></td>
<td align="center"><strong>FACTURA</strong></td>
<td align="center"><strong>PROVEEDOR</strong></td>
<td align="center"><strong>TIPO PAGO</strong></td>
<td align="center"><strong>TOTAL COMPRA</strong></td>
<td align="center"><strong>FECHA</strong></td>
<td align="center"><strong>CUENTA</strong></td>
</tr>
<?php 
do { 
$base = $datos['precio_venta']/1.16;
$iva_ptj = $datos['iva']/100;
$unidades_total = $datos['unidades_total'];
$iva_valor = ($base * $iva_ptj) * $unidades_total;
$precio_venta = $datos['precio_venta'];

$cod_factura = $datos['cod_factura'];
$nombre_proveedores = $datos['nombre_proveedores'];
$precio_compra_con_descuento = $datos['precio_compra_con_descuento'];
$fecha_anyo = $datos['fecha_anyo'];
$vendedor = strtoupper($datos['vendedor']);
$tipo_pago = strtoupper($datos['tipo_pago']);
$unidades = $datos['unidades'];
$cajas = $datos['cajas'];
$unidades_total = $datos['unidades_total'];
$unidades_faltantes = $datos['unidades_faltantes'];
?>
<tr>
<td align="center"><a href="../admin/ver_factura_compra.php?cod_factura=<?php echo $cod_factura?>&proveedor=<?php echo $nombre_proveedores?>"><img src=../imagenes/ver.png alt="ver"></a></td>
<td ><?php echo $cod_factura; ?></td>
<td ><?php echo $nombre_proveedores; ?></td>
<td ><?php echo $tipo_pago; ?></td>
<td align="right"><?php echo number_format($precio_compra_con_descuento, 0, ",", "."); ?></td>
<td align="center"><?php echo $fecha_anyo; ?></td>
<td align="center"><?php echo $vendedor; ?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta));?>
</table>
</fieldset>
</body>
</html>
<center>
<?php
} else {
}
?>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>