<?php
$cuenta_actual = addslashes($_SESSION['usuario']);
/*
$suma_factura = "SELECT sum(vlr_total_venta) as vlr_total_venta, sum(vlr_total_compra) as vlr_total_compra, sum(descuento) as descuento,
sum(precio_compra_con_descuento) as precio_compra_con_descuento, sum(valor_iva) as valor_iva FROM exportacion_temporal WHERE vendedor = '$cuenta_actual'";
$consulta_suma = mysql_query($suma_factura, $conectar) or die(mysql_error());
$suma = mysql_fetch_assoc($consulta_suma);
*/
$sql_cod_factura_exportacion = "SELECT MAX(cod_factura) AS cod_factura FROM exportacion";
$consulta_cod_factura = mysql_query($sql_cod_factura_exportacion, $conectar) or die(mysql_error());
$cod_factura_exportacion = mysql_fetch_assoc($consulta_cod_factura);

$cod_factura = $cod_factura_exportacion['cod_factura'] + 1;
?>
<center>
<table id="table" width="100%">
<form method="post" name="formulario" action="../admin/guardar_exportacion_temporal_exportacion.php">
<td><strong>FECHA: </strong><input type="text" style="font-size:15px" name="fecha" value="<?php echo date("d/m/Y")?>" size="10" required autofocus>
<td><strong>FACTURA: <?php echo $cod_factura ?></strong></td>

<input type="hidden" style="font-size:15px" name="cod_factura" value="<?php echo $cod_factura ?>" size="7" required autofocus>
	
<input type="hidden" style="font-size:15px" name="iva" value="0" size="1" required autofocus>
<!--</td>-->
<input type="hidden" name="verificacion" value="verificacion" size="1">
<!--
<td><label><input type="radio" name="tipo_pago" value="contado" checked>
<strong>CONTADO</strong></label>
<br>
<label><input type="radio" name="tipo_pago" value="credito"> 
<strong>CREDITO</strong></label></td>

<td><div align="right"><strong>V.BRUTO(SN.IVA):</strong><input type="text" style="font-size:15px" name="valor_bruto" value="<?php echo $suma['vlr_total_compra'];?>" size="6" required autofocus></div></td>
<td><div align="right"><strong>DESCUENTO:</strong><input type="text" style="font-size:15px" name="descuento_factura" value="<?php echo $suma['descuento'];?>" size="6" required autofocus></div></td>
<td><div align="right"><strong>V.NETO:</strong><input type="text" style="font-size:15px" name="valor_neto" value="<?php echo $suma['vlr_total_compra'];?>" size="6" required autofocus></div></td>
<td><div align="right"><strong>V.IVA:</strong><input type="text" style="font-size:15px" name="valor_iva" value="<?php echo $suma['valor_iva'];?>" size="6" required autofocus></div></td>
<td><div align="right"><strong>TOTAL:</strong><input type="text" style="font-size:15px" name="total" value="<?php echo $suma['precio_compra_con_descuento'];?>" size="6" required autofocus></div></td>
-->
<td><div align="right"><input type="image" src="../imagenes/guardar.png" name="vender" value="Guardar" /></td>
</table>
</form>