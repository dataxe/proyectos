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
$fecha_anyo_concat = $_GET['fecha_anyo'];
$tabla = $_GET['tabla'];

$frag_fecha = explode('-', $fecha_anyo_concat);
$fecha_anyo = $frag_fecha[0];
$fecha_anyo_dmy = $frag_fecha[1];
?>
<center>
<a href="../admin/exportacion_csv_vendedor.php"><font size="6px" color="white">REGRESAR</font></a>
<br><br>
<td><font size="6px" color="yellow">EXPORTAR POR HORA DE <?php echo strtoupper($tabla);?> FECHA: <?php echo $fecha_anyo_dmy;?></font></td>
<br><br>
<form action="descargar_reporte_multiple_archivo_horas_csv.php" method="GET" enctype="multipart/form-data" name="form1">
<table>
<td><strong>HORA INICIAL: </strong><select name="hora_ini">
<?php $sql_consulta="SELECT DISTINCT fecha_hora, fecha FROM $tabla WHERE fecha = '$fecha_anyo' ORDER BY fecha_hora ASC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:13px" value="<?php echo $contenedor['fecha_hora'] ?>"><?php echo $contenedor['fecha_hora'] ?></option>
<?php }?></select></td>

<td><strong>HORA FINAL: </strong><select name="hora_fin">
<?php $sql_consulta="SELECT DISTINCT fecha_hora, fecha FROM $tabla WHERE fecha = '$fecha_anyo' ORDER BY fecha_hora DESC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:13px" value="<?php echo $contenedor['fecha_hora'] ?>"><?php echo $contenedor['fecha_hora'] ?></option>
<?php }?></select></td>

<input type="hidden" name="fecha_anyo" value="<?php echo $fecha_anyo;?>" size="60">
<input type="hidden" name="tabla" value="<?php echo $tabla;?>" size="60">
<input type="hidden" name="campo" value="fecha_hora" size="60">
<input type="hidden" name="tipo" value="hora" size="60">

<tr></tr>
<td><br><input type="submit" name="Submit" value="DESCARGAR" /></td>
</form>
</table>
</center>