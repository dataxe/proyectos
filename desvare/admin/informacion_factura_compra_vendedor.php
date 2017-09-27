<?php
$cuenta_actual = addslashes($_SESSION['usuario']);
?>
<center>
<table id="table" width="100%">
<form method="post" name="formulario" action="../admin/guardar_factura_productos_vendedor.php">
<td><strong>FECHA: </strong><input type="text" style="font-size:15px" name="fecha" value="<?php echo date("d/m/Y")?>" size="10" required autofocus>
<td><strong>FACTURA: </strong><input type="text" style="font-size:15px" name="numero_factura" value="" size="7" required autofocus>
<!--
<td><strong>PROVEEDOR: </strong><select name="cod_proveedores">
<?php $sql_consulta="SELECT cod_proveedores, nombre_proveedores FROM proveedores ORDER BY proveedores.cod_proveedores ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:13px" value="<?php echo $contenedor['cod_proveedores'] ?>"><?php echo $contenedor['nombre_proveedores'] ?></option>
<?php }?></select></td>
-->
<input type="hidden" style="font-size:15px" name="cod_proveedores" value="1" size="6">
<input type="hidden" style="font-size:15px" name="tipo_pago" value="contado" size="6">
<!--
<td><label><input type="radio" name="tipo_pago" value="contado" checked>
<strong>CONTADO</strong></label>
<br>
<label><input type="radio" name="tipo_pago" value="credito"> 
<strong>CREDITO</strong></label></td>
-->
<input type="hidden" style="font-size:15px" name="iva" value="0" size="1" required autofocus>

<input type="hidden" name="verificacion" value="verificacion" size="1">

<strong></strong><input type="hidden" style="font-size:15px" name="valor_bruto" value="0" size="6">
<strong></strong><input type="hidden" style="font-size:15px" name="descuento_factura" value="0" size="6">
<strong></strong><input type="hidden" style="font-size:15px" name="valor_neto" value="0" size="6">
<strong></strong><input type="hidden" style="font-size:15px" name="valor_iva" value="0" size="6">
<strong></strong><input type="hidden" style="font-size:15px" name="total" value="0" size="6">
<td><input type="image" src="../imagenes/guardar.png" name="vender" value="Guardar" /></td>
</table>
</form>