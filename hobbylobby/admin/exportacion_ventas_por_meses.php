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
?>
<center>
<a href="../admin/exportacion_csv.php"><font size="6px" color="white">REGRESAR</font></a>
<br><br>
<td><font size="6px" color="yellow">EXPORTAR POR MES DE VENTA</font></td>
<br><br>
<table>
<form action="descargar_reporte_multiple_archivo_csv.php" method="GET" enctype="multipart/form-data" name="form1" id="form1">

<td><strong>MES INICIAL: </strong><select name="fecha_ini">
<?php $sql_consulta="SELECT distinct fecha_mes FROM ventas ORDER BY fecha ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:13px" value="<?php echo $contenedor['fecha_mes'] ?>"><?php echo $contenedor['fecha_mes'] ?></option>
<?php }?></select></td>

<td><strong>MES FINAL: </strong><select name="fecha_fin">
<?php $sql_consulta="SELECT distinct fecha_mes FROM ventas ORDER BY fecha DESC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:13px" value="<?php echo $contenedor['fecha_mes'] ?>"><?php echo $contenedor['fecha_mes'] ?></option>
<?php }?></select></td>

<input type="hidden" name="tabla" value="ventas" size="60">
<input type="hidden" name="campo" value="fecha_mes" size="60">
<input type="hidden" name="tipo" value="mes" size="60">

<tr></tr>
<td><br><input type="submit" name="Submit" value="DESCARGAR" /></td>
</form>
</table>
</center>