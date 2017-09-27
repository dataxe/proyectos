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
<br>
<td><font size="6px" color="yellow">CARGAR FACTURA ARCHIVO PLANO</font></td>
<br><br>
<td><a href="../admin/factura_por_archivo_plano_verificacion1.php"><font size="3px" color="yellow">VERIFICAR FACTURAS ANTERIORES 1</font></a></td>
<br><br>
<table>
<form action="importacion_cargar_factura_archivo_plano.php" method="POST" enctype="multipart/form-data" name="form1" id="form1">
<input type="hidden" name="cod_factura" value="<?php echo $cod_factura ?>"/>
<tr></tr>
<td><font  size="5px">Selecionar archivo: <br /> <input name="csv" type="file" required autofocus/></font></td>
<tr></tr>
<td><br><input type="submit" name="Submit" value="AGREGAR" /></td>
</form>
</table>
</center>