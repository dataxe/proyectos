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
<td><font size="5px" color="yellow">CARGAR TRANSFERENCIA ARCHIVO PLANO - </font><strong><a href="transferencia_lista_archivo_plano.php"><font color='yellow' size="5px">VER LISTA</font></a></strong></td>

<br><br>
<table>
<form action="importacion_cargar_transferencia_archivo_plano.php" method="POST" enctype="multipart/form-data" name="form1" id="form1">
<input type="hidden" name="cod_factura" value="<?php echo $cod_factura ?>"/>
<tr></tr>
<td><font  size="5px">Selecionar archivo: <br /> <input name="csv" type="file" required autofocus/></font></td>
<tr></tr>
<td><br><input type="submit" name="Submit" value="AGREGAR" /></td>
</form>
</table>
</center>