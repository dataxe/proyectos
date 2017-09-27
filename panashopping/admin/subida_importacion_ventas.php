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
<a href="../admin/importacion_csv.php"><font size="6px" color="white">REGRESAR</font></a>
<br><br>
<td><font size="6px" color="yellow">SUBIR VENTAS</font></td>
<br><br>
<table>
<form action="importar_ventas.php" method="POST" enctype="multipart/form-data" name="form1" id="form1">
<tr></tr>
<td><font  size="5px">Selecionar archivo: <br /> <input name="csv" type="file" required autofocus/></font></td>
<tr></tr>
<td><br><input type="submit" name="Submit" value="AGREGAR" /></td>
</form>
</table>
</center>