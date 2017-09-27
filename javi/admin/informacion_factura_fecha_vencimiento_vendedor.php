<?php
$cuenta_actual = addslashes($_SESSION['usuario']);
?>
<center>
<table width="100%">
<form method="post" name="formulario" action="../admin/guardar_factura_fecha_vencimiento_vendedor.php">
<input type="hidden" name="verificacion" value="verificacion" size="1">
<td align="right"><input type="image" src="../imagenes/guardar.png" name="vender" value="Guardar" /></td>
</table>
</form>
<center>