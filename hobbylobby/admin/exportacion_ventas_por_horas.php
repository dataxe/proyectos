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
<td><font size="6px" color="yellow">EXPORTAR POR HORA DE VENTA</font></td>
<br><br>
<form action="ver_reporte_multiple_archivo_horas_csv.php" method="GET" enctype="multipart/form-data" name="form1">
<table>
<td><strong>FECHA: </strong><select name="fecha_anyo">
<?php $sql_consulta="SELECT distinct fecha_anyo FROM ventas ORDER BY fecha DESC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:13px" value="<?php echo $contenedor['fecha_anyo'] ?>"><?php echo $contenedor['fecha_anyo'] ?></option>
<?php }?></select></td>
</table>
<table>
<input type="hidden" name="tabla" value="ventas" size="60">
<input type="hidden" name="campo" value="fecha_hora" size="60">
<input type="hidden" name="tipo" value="hora" size="60">

<td><br><input type="submit" name="Submit" value="VER" /></td>
</form>
</table>
</center>