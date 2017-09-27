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

$mostrar_datos_productos_temporal = "SELECT MAX(numero_factura) AS numero_factura FROM productos_temporal";
$consulta_productos_temporal = mysql_query($mostrar_datos_productos_temporal, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta_productos_temporal);

$numero_factura = $datos['numero_factura'] + 1;
?>
<center>
<a href="../admin/productos_temporal_para_revision_menu.php"><font color='white'><strong>REGRESAR</font></strong></font></a></td>
<br><br>
<td><font size="5px" color="yellow">CARGAR ARCHIVO PLANO DE PRODUCTOS PARA VERIFICACION (CSV DELIMITADOS POR COMAS)</font></td>
<br><br>
<table>
<form action="importacion_productos_temporal_archivo_plano.php" method="POST" enctype="multipart/form-data" name="form1" id="form1">
<input type="hidden" name="numero_factura" value="<?php echo $numero_factura ?>"/>
<tr></tr>
<td><font  size="5px">Selecionar archivo: <br /> <input name="csv" type="file" required autofocus/></font></td>
<tr></tr>
<td><br><input type="submit" name="Submit" value="AGREGAR" /></td>
</form>
</table>
</center>