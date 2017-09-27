<?php
$datos_factura = "SELECT * FROM temporal_cotizar WHERE vendedor = '$cuenta_actual'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);

$suma_temporal = "SELECT  Sum(vlr_total_venta) As total_venta, Sum(vlr_total_compra) As total_compra FROM temporal_cotizar WHERE vendedor = '$cuenta_actual'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$matriz_temporal = mysql_fetch_assoc($consulta_temporal);

$datos_info = "SELECT * FROM info_impuesto_facturas_cotizar WHERE (estado = 'abierto') AND (vendedor = '$cuenta_actual')";
$consulta_info = mysql_query($datos_info, $conectar) or die(mysql_error());
$info = mysql_fetch_assoc($consulta_info);
$cantidad_resultado = mysql_num_rows($consulta_info);

$maxima_factura = "SELECT Max(cod_factura) AS cod_factura FROM temporal_cotizar";
$consulta_maxima = mysql_query($maxima_factura, $conectar) or die(mysql_error());
$maxima = mysql_fetch_assoc($consulta_maxima);

$maxima_factura = "SELECT AVG(descuento_ptj) AS descuento_ptj FROM temporal_cotizar WHERE vendedor = '$cuenta_actual'";
$consulta_maxima = mysql_query($maxima_factura, $conectar) or die(mysql_error());
$descuento_promedio = mysql_fetch_assoc($consulta_maxima);

$descuento_ptj = $descuento_promedio['descuento_ptj'];

require("funcion_verificar.php");
$requerir_funcion = new bloquear_multiple_intento;
?>
<center>
<form method="post" name="formulario" action="../admin/venta_cotizar.php">
<table id="table" width="100%">
<td><a href="../admin/busq_facturas_fecha.php"><img src=../imagenes/deboluciones.png alt="Deboluciones"></a></td>

<td><input type="hidden" style="font-size:20px" name="numero_factura" value="<?php if ($cantidad_resultado == '1') {
echo $info['cod_factura']; } else { echo $maxima['cod_factura']+1;}?>" size="3" required autofocus></td>
<!--<td><strong>Factura No</strong></td>
<td><div class="text" id="cod_factura-<?php //echo $cuenta_actual?>-<?php //echo $cuenta_actual?>"><?php //if ($cantidad_resultado <> '0') {
//echo $info['cod_factura']; } else { echo $maxima['cod_factura']+1;}?>
</div></td>-->
<td><strong>FECHA: </strong><font size= "+1"><?php echo  date("d/m/Y")?></font></td>
<input type="hidden" style="font-size:20px" name="fecha_anyo" value="<?php echo  date("d/m/Y")?>" size="8" required autofocus>

<td><strong>FECHA PAGO: </strong><input type="text" style="font-size:20px" name="fecha_pago" value="<?php echo  date("d/m/Y")?>" size="8" required autofocus></td>

<input type="hidden" name="monto_deuda" value="<?php echo $matriz_temporal['total_venta']; ?>" size="4">

<td align="center"><strong>%DESCUENTO: </strong><font size= "+2"><?php echo  $descuento_ptj?>%</font></td>
<input type="hidden" style="font-size:20px" name="descuento_factura" value="<?php echo  number_format($descuento_ptj, 0, ",", ".")?>" size="8" required autofocus>
<!--
<td><strong>$DESCUENTO </strong><input type="text" style="font-size:20px" name="descuento_factura" value="0" size="4" required autofocus></td>
<td><strong>$Descuento</strong></td>
<td><div class="text" id="descuento-<?php// echo $cuenta_actual?>-<?php// echo $cuenta_actual?>"><?php //echo $info_factura['descuento'];?></div></td>
<td><strong>Subtotal </strong></td>
<td><font color='yellow' size= "+1"><?php //echo number_format($matriz_temporal['total_venta'] - $info_factura['descuento']); ?></font></td>
<<td>Iva % <input type="text" style="height:26" name="iva" value="0" size="3" required autofocus></td>-->
<input type="hidden" style="font-size:20px" name="iva" value="0" size="4" required autofocus>
<!--
<td><strong>%IVA: </strong><input type="text" style="font-size:20px" name="iva" value="0" size="4" required autofocus></td>-
<td><strong>%Iva</strong></strong></td>
<td><div class="text" id="iva-<?php //echo $cuenta_actual?>-<?php //echo $cuenta_actual?>"><?php//echo $info_factura['iva'];?></div></td>
<td>Cancelado <input type="text" style="height:26" name="vlr_cancelado" value="" size="4" required autofocus></td>
<td><label><input type="radio" name="tipo_pago" value="contado" checked>
<strong>CONTADO</strong></label>
<br>
<label><input type="radio" name="tipo_pago" value="credito"> 
<strong>CREDITO</strong></label></td>
-->
<td><strong>CLIENTE: </strong><select name="cod_clientes">
<?php $sql_consulta="SELECT * FROM clientes ORDER BY nombres ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:17px" value="<?php echo $contenedor['cod_clientes'] ?>"><?php echo $contenedor['nombres'].' '.$contenedor['apellidos'] ?></option>
<?php }?>
</select></td>

<td align="center"><strong>T.COSTO: </strong><font size= "+3"><?php echo $matriz_temporal['total_compra']; ?></font></td>
<td align="center"><strong>T.VENTA: </strong><font size= "+3"><?php echo number_format($matriz_temporal['total_venta'], 0, ",", "."); ?></font></td>
<!--
<td><strong>RECIBIDO: </strong><input type="text" style="font-size:28px" name="vlr_cancelado" value="" size="6" required autofocus></td>
<td><strong>Recibido</strong></td>
<td><div class="text" id="vlr_cancelado-<?php //echo $cuenta_actual?>-<?php //echo $cuenta_actual?>"><?php //echo $info_factura['vlr_cancelado'];?></div></td>
<td><strong>Cambio </strong></td>
<td><font color='yellow' size= "+2"><?php //echo number_format($info_factura['vlr_cancelado'] - ($matriz_temporal['total_venta'] - $info_factura['descuento'])); ?></font></td>-->
<input type="hidden" name="total_datos" value="<?php echo $total_datos; ?>" size="4">
<?php while ($datos = mysql_fetch_assoc($consulta)) {?>
<input type="hidden" name="cod_productos[]" value="<?php echo $datos['cod_productos']; ?>" size="4">
<?php } ?>
<?php
$pagina ='cotizar_factura.php'
?>
<input type="hidden" name="pagina" value="<?php echo $pagina?>" size="15">
<input type="hidden" name="flete" value="0" size="15">
<input type="hidden" name="verificacion_envio" value="1" size="15">
<td><a><input type="image" src="../imagenes/ok.png" name="vender" value="Guardar" /></a></td>
</table>
<?php $requerir_funcion->iniciar(); ?>