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

$obtener_productos = "SELECT * FROM productos";
$resultado_productos = mysql_query($obtener_productos, $conectar) or die(mysql_error());
$matriz_productos = mysql_fetch_assoc($resultado_productos);
$total = mysql_num_rows($resultado_productos);
?>
<center>
<a href="../admin/importacion_csv_vendedor.php"><font size="6px" color="white">REGRESAR</font></a>
<br><br>
<?php 
if ($total == 0) {
?>
<td><font size="6px" color="yellow">SUBIR PRODUCTOS</font></td>
<br><br>
<table>
<form action="importar_productos.php" method="POST" enctype="multipart/form-data" name="form1" id="form1">
<tr></tr>
<td><font  size="5px">Selecionar archivo: <br /> <input name="csv" type="file" required autofocus/></font></td>
<tr></tr>
<td><br><input type="submit" name="Submit" value="AGREGAR" /></td>
</form>
</table>
<?php
} else {
?>
<td><font size="5px" color="yellow">NECESITA VACIAR LA TABLA PRODUCTOS PARA PODER SUBIR EL ARCHIVO.</font></td>
<td ><a href="../admin/copia_seguridad_base_importacion.php?verificacion=1"><center><img src=../imagenes/vaciar.png alt='vaciar' title='Vaciar tabla'"></center></a></td>
<?php
}
?>
</center>