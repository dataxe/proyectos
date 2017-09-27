<?php
$cuenta_actual = addslashes($_SESSION['usuario']);

$sql_data_usuario = "SELECT cod_seguridad FROM administrador WHERE cuenta = '$cuenta_actual'";
$consulta_usuario = mysql_query($sql_data_usuario, $conectar) or die(mysql_error());
$matriz_usuario = mysql_fetch_assoc($consulta_usuario);

$cod_seguridad_user = $matriz_usuario['cod_seguridad'];
?>
<script type="text/javascript" src="inmediata_busqueda_productos.js"></script>
<center>
<td><strong><a href="cuentas_cobrar.php"><font color='white'>CUENTAS COBRAR</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
<td><strong><a href="factura_eletronica.php"><font color='white'>VENTA ELECTRONICA</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
<?php
if ($cod_seguridad_user == 5) { 
?>
<td><strong><font color='yellow'>VENTA MANUAL: </strong></td>
<?php
} else {
?>
<td><strong><font color='yellow'>VENTA MANUAL: <a href="temporal_todo.php"><font color='white'>%</font></a></strong></td> <input type="text" id="busqueda" name="busqueda" onkeyup="hacer_busqueda()" style="height:26" required placeholder="Buscar" required autofocus/>
<div id="logo_cargador"></div>
<?php
}
?>
<br><br>