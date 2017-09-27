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
<form method="post" name="formulario" action="../admin/venta_productos_agregar_venta_vendedor.php">
<table width="100%">

<td align='center'><strong>FACTURA: </strong><input type="text" style="font-size:20px" tabindex=3 name="numero_factura" value="<?php if ($cantidad_resultado == '1') {
echo $info['cod_factura']; } else { echo $maxima['cod_factura']+1;}?>" size="6" required autofocus></td>

<td align='left'><strong>FECHA: </strong><input type="text" style="font-size:20px" tabindex=3 name="fecha_anyo" value="<?php echo  date("d/m/Y")?>" size="9" required autofocus></td>

<td><strong>FECHA PAGO: </strong><input type="text" style="font-size:20px" tabindex=3 name="fecha_pago" value="<?php echo  date("d/m/Y")?>" size="9" required autofocus></td>

<input type="hidden" name="monto_deuda" value="<?php echo $matriz_temporal['total_venta']; ?>" size="4">

<td align='center'><strong>DESCUENTO: </strong><select name="descuento_factura" tabindex=3>
<?php $sql_consulta="SELECT * FROM descuento_ptj ORDER BY nombre_descuento_ptj ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:20px" value="<?php echo $contenedor['nombre_descuento_ptj'] ?>"><?php echo $contenedor['nombre_descuento_ptj'].$contenedor['abr_ptj'] ?></option>
<?php }?>
</select></td>

<input type="hidden" style="font-size:20px" tabindex=3 name="iva" value="0" size="4" required autofocus>

<td><label><input type="radio" name="tipo_pago" tabindex=3 value="contado" checked>
<strong>CONTADO</strong></label>
<br>
<label><input type="radio" name="tipo_pago" tabindex=3 value="credito"> 
<strong>CREDITO</strong></label></td>

<td align='left'><strong>CLIENTE: </strong><select name="cod_clientes" tabindex=3>
<?php $sql_consulta="SELECT * FROM clientes ORDER BY nombres ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:17px" value="<?php echo $contenedor['cod_clientes'] ?>"><?php echo $contenedor['nombres'].' '.$contenedor['apellidos'] ?></option>
<?php }?>
</select></td>

<td align='left'><strong>VENDEDOR: </strong><select name="cuenta" tabindex=3>
<?php $sql_consulta="SELECT cuenta FROM administrador ORDER BY cuenta ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:17px" value="<?php echo $contenedor['cuenta'] ?>"><?php echo $contenedor['cuenta'] ?></option>
<?php }?>
</select></td>

<td><strong>SUBTOTAL: </strong><font size= "+3"><?php echo number_format($matriz_temporal['total_venta'], 0, ",", "."); ?></font></td>

<td><strong>RECIBIDO: </strong><input type="text" style="font-size:28px" name="vlr_cancelado" value="" size="6" required autofocus></td>

<input type="hidden" name="total_datos" value="<?php echo $total_datos; ?>" size="4">
<?php while ($datos = mysql_fetch_assoc($consulta)) {?>
<input type="hidden" name="cod_temporal[]" value="<?php echo $datos['cod_temporal']; ?>" size="4">
<?php } ?>
<?php
$pagina ='agregar_venta_vendedor.php'
?>
<input type="hidden" name="pagina" value="<?php echo $pagina?>" size="15">
<input type="hidden" name="flete" value="0" size="15">
<input type="hidden" name="verificacion_envio" value="1" size="15">
<td><a><input type="image" src="../imagenes/ok.png" tabindex=3 name="vender" value="Guardar" /></a></td>
</table>
<?php $requerir_funcion->iniciar(); ?>