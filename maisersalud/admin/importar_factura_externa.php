<?php
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar);
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");

$sql_importacion_temporal = "SELECT MAX(cod_factura) AS cod_factura FROM importacion";
$consulta_importacion_temporal = mysql_query($sql_importacion_temporal, $conectar) or die(mysql_error());
$max_cod_factura = mysql_fetch_assoc($consulta_importacion_temporal);

$cod_factura = $max_cod_factura['cod_factura'] + 1;
?>
<center>
<a href="../admin/cargar_exportacion_temporal.php"><font size="6px" color="yellow">REGRESAR</font></a>
<br><br>
<td><font size="6px" color="yellow">CARGAR FACTURA EXTERNA</font></td>
<br><br>
<table>
<form action="importacion_factura_externa_temporal.php" method="POST" enctype="multipart/form-data" name="form1" id="form1">
<td><font size="5px">Factura: <?php echo $cod_factura ?></font></td>
<input type="hidden" name="cod_factura" value="<?php echo $cod_factura ?>"/>
<tr></tr>
<td><font  size="5px">Selecionar archivo: <br /> <input name="csv" type="file" required autofocus/></font></td>
<tr></tr>
<td><br><input type="submit" name="Submit" value="AGREGAR" /></td>
</form>
</table>
</center>