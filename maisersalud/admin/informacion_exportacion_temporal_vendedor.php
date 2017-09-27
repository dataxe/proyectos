<?php
$cuenta_actual = addslashes($_SESSION['usuario']);

$sql_cod_factura_exportacion = "SELECT MAX(cod_factura) AS cod_factura FROM exportacion";
$consulta_cod_factura = mysql_query($sql_cod_factura_exportacion, $conectar) or die(mysql_error());
$cod_factura_exportacion = mysql_fetch_assoc($consulta_cod_factura);

$cod_factura = $cod_factura_exportacion['cod_factura'] + 1;

$sql_exportacion_temporal = "SELECT cod_exportacion_temporal FROM exportacion_temporal WHERE vendedor = '$cuenta_actual' ORDER BY cod_exportacion_temporal DESC";
$consulta_exportacion_temporal = mysql_query($sql_exportacion_temporal, $conectar) or die(mysql_error());
$total_exportacion_temporal = mysql_num_rows($consulta_exportacion_temporal);
?>
<center>
<table width="100%">
<form method="post" name="formulario" action="../admin/guardar_exportacion_temporal_exportacion_vendedor.php">
<td><strong>FECHA: </strong><input type="text" style="font-size:15px" name="fecha" value="<?php echo date("d/m/Y")?>" size="10" required autofocus>
<td><strong>FACTURA: <?php echo $cod_factura ?></strong></td>
<?php
//-----------------------------------------------------------------------------------------------------------------------------------------//
//---PARA MANDAR DATOS DE LA LLAVE DE LA TABLA Y CON UN CICLO PROCESARLOS CUANDO LLEGUEN, PARA METERLOS EN LA TABLA EXPORTACION VENDEDOR. 
//---ESTO SE HACE PARA Q QUEDE EL REGISTRO ORIGINAL DE LA AUDITORIA DEL VENDEDOR Y EL PUEDA VERLO------------------------//
while ($datos_exportacion_temporal = mysql_fetch_assoc($consulta_exportacion_temporal)) {
$cod_exportacion_temporal = $datos_exportacion_temporal['cod_exportacion_temporal'];
?>
<input type="hidden" name="cod_exportacion_temporal[]" value="<?php echo $cod_exportacion_temporal ?>" size="20">
<?php
}
?>
<input type="hidden" style="font-size:15px" name="cod_factura" value="<?php echo $cod_factura ?>" size="7" required autofocus>
<input type="hidden" name="total_datos" value="<?php echo $total_exportacion_temporal ?>" size="20">
<input type="hidden" name="verificacion" value="verificacion" size="1">

<td><div align="right"><input type="image" src="../imagenes/guardar.png" name="vender" value="Guardar" /></td>
</table>
</form>