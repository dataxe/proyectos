<?php
$datos_factura = "SELECT cod_temporal FROM temporal WHERE vendedor = '$cuenta_actual'";
$consulta = mysql_query($datos_factura, $conectar) or die(mysql_error());
$total_datos = mysql_num_rows($consulta);

$datos_info_factura = "SELECT * FROM info_impuesto_facturas WHERE vendedor = '$cuenta_actual' AND estado = '$valor_factura'";
$consulta_info_factura = mysql_query($datos_info_factura, $conectar) or die(mysql_error());
$info_factura = mysql_fetch_assoc($consulta_info_factura);

$suma_temporal = "SELECT  Sum(vlr_total_venta) As total_venta, Sum(vlr_total_compra) As total_compra FROM temporal WHERE vendedor = '$cuenta_actual'";
$consulta_temporal = mysql_query($suma_temporal, $conectar) or die(mysql_error());
$matriz_temporal = mysql_fetch_assoc($consulta_temporal);

$datos_info = "SELECT * FROM info_impuesto_facturas WHERE (estado = 'abierto') AND (vendedor = '$cuenta_actual')";
$consulta_info = mysql_query($datos_info, $conectar) or die(mysql_error());
$info = mysql_fetch_assoc($consulta_info);
$cantidad_resultado = mysql_num_rows($consulta_info);

$maxima_factura = "SELECT Max(cod_factura) AS cod_factura FROM info_impuesto_facturas";
$consulta_maxima = mysql_query($maxima_factura, $conectar) or die(mysql_error());
$maxima = mysql_fetch_assoc($consulta_maxima);


require("funcion_verificar.php");
$requerir_funcion = new bloquear_multiple_intento;
?>
<center>
<form method="post" name="formulario" action="../admin/venta_productos.php">
<table id="table" width="100%">
<td><a href="../admin/busq_facturas_fecha.php" tabindex=3><img src=../imagenes/deboluciones.png alt="Deboluciones"></a></td>

<td><strong>FACTURA: </strong><input type="text" style="font-size:20px" tabindex=3 name="numero_factura" value="<?php if ($cantidad_resultado == '1') {
echo $info['cod_factura']; } else { echo $maxima['cod_factura']+1;}?>" size="3" required autofocus></td>
<!--<td><strong>Factura No</strong></td>
<td><div class="text" id="cod_factura-<?php //echo $cuenta_actual?>-<?php //echo $cuenta_actual?>"><?php //if ($cantidad_resultado <> '0') {
//echo $info['cod_factura']; } else { echo $maxima['cod_factura']+1;}?>
</div></td>-->
<td><strong>FECHA: </strong><input type="text" style="font-size:20px" tabindex=3 name="fecha_anyo" value="<?php echo  date("d/m/Y")?>" size="8" required autofocus></td>

<td><strong>FECHA PAGO: </strong><input type="text" style="font-size:20px" tabindex=3 name="fecha_pago" value="<?php echo  date("d/m/Y")?>" size="8" required autofocus></td>

<input type="hidden" name="monto_deuda" value="<?php echo $matriz_temporal['total_venta']; ?>" size="4">

<td><strong>DESCUENTO: </strong><select name="descuento_factura" tabindex=3>
<?php $sql_consulta="SELECT * FROM descuento_ptj ORDER BY nombre_descuento_ptj ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:20px" value="<?php echo $contenedor['nombre_descuento_ptj'] ?>"><?php echo $contenedor['nombre_descuento_ptj'].$contenedor['abr_ptj'] ?></option>
<?php }?>
</select></td>

<!--
<td><strong>$DESCUENTO </strong><input type="text" style="font-size:20px" name="descuento_factura" value="0" size="4" required autofocus></td>
<td><strong>$Descuento</strong></td>
<td><div class="text" id="descuento-<?php// echo $cuenta_actual?>-<?php// echo $cuenta_actual?>"><?php //echo $info_factura['descuento'];?></div></td>
<td><strong>Subtotal </strong></td>
<td><font color='yellow' size= "+1"><?php //echo number_format($matriz_temporal['total_venta'] - $info_factura['descuento']); ?></font></td>
<<td>Iva % <input type="text" style="height:26" name="iva" value="0" size="3" required autofocus></td>-->
<input type="hidden" style="font-size:20px" name="iva" value="0" size="4" required autofocus>
<!--
<td><strong>%IVA: </strong><input type="text" style="font-size:20px" name="iva" value="0" size="4" required autofocus></td>
<td><strong>%Iva</strong></strong></td>
<td><div class="text" id="iva-<?php //echo $cuenta_actual?>-<?php //echo $cuenta_actual?>"><?php//echo $info_factura['iva'];?></div></td>
<td>Cancelado <input type="text" style="height:26" name="vlr_cancelado" value="" size="4" required autofocus></td>-->
<td><label><input type="radio" name="tipo_pago" tabindex=3 value="contado" checked>
<strong>CONTADO</strong></label>
<br>
<label><input type="radio" name="tipo_pago" value="credito" tabindex=3> 
<strong>CREDITO</strong></label></td>

<td><strong>CLIENTE: </strong><select name="cod_clientes" tabindex=3>
<?php $sql_consulta="SELECT * FROM clientes ORDER BY nombres ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:17px" value="<?php echo $contenedor['cod_clientes'] ?>"><?php echo $contenedor['nombres'].' '.$contenedor['apellidos'] ?></option>
<?php }?>
</select></td>

<td><strong>T.COSTO: </strong><font size= "+3"><?php echo number_format($matriz_temporal['total_compra'], 0, ",", "."); ?></font></td>
<td><strong>T.VENTA: </strong><font size= "+3"><?php echo number_format($matriz_temporal['total_venta'], 0, ",", "."); ?></font></td>

<td><strong>RECIBIDO: </strong><input type="text" style="font-size:28px" name="vlr_cancelado" value="" size="6" required autofocus></td>
<!--<td><strong>Recibido</strong></td>
<td><div class="text" id="vlr_cancelado-<?php //echo $cuenta_actual?>-<?php //echo $cuenta_actual?>"><?php //echo $info_factura['vlr_cancelado'];?></div></td>
<td><strong>Cambio </strong></td>
<td><font color='yellow' size= "+2"><?php //echo number_format($info_factura['vlr_cancelado'] - ($matriz_temporal['total_venta'] - $info_factura['descuento'])); ?></font></td>-->
<input type="hidden" name="total_datos" value="<?php echo $total_datos; ?>" size="4">
<?php while ($datos = mysql_fetch_assoc($consulta)) {?>
<input type="hidden" name="cod_temporal[]" value="<?php echo $datos['cod_temporal']; ?>" size="4">
<?php } ?>
<?php
$pagina ='temporal.php'
?>
<input type="hidden" name="pagina" value="<?php echo $pagina?>" size="15">
<input type="hidden" name="flete" value="0" size="15">
<input type="hidden" name="verificacion_envio" value="1" size="15">
<td><a><input type="image" src="../imagenes/ok.png" tabindex=3 name="vender" value="Guardar" /></a></td>
</table>
<?php $requerir_funcion->iniciar(); ?>