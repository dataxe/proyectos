<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");
$contrasena = $_POST['contrasena'];
 	
$obtener_informacion = "SELECT * FROM utililidad WHERE cod_utililidad = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$matriz_informacion = mysql_fetch_assoc($consultar_informacion);

$contra_db = $matriz_informacion['contrasena'];

if ($contra_db == $contrasena) {?>
<br><br><br><br>
<center>
<table width="80%" border='1'>
<tr>
<th align="center"><a href="../admin/convertir_de_csv_a_sql.php" target="_blank"><font color='yellow'>CONVERTIR DE CSV A SQL</font></a>&nbsp;&nbsp;&nbsp;&nbsp;</th>
<th align="center"><a href="../admin/ecryp.php" target="_blank"><font color='yellow'>ENCRYPTAR</a>&nbsp;&nbsp;&nbsp;&nbsp;</th>
<th align="center"><a href="../admin/mostrar_todo_egresos.php" target="_blank"><font color='yellow'>MOSTRAR TODOS LOS EGRESOS</font></a>&nbsp;&nbsp;&nbsp;&nbsp;</th>
<th align="center"><a href="../admin/disenos.php" target="_blank"><font color='yellow'>EDITAR DISENOS</font></a>&nbsp;&nbsp;&nbsp;&nbsp;</th>
<th align="center"><a href="../admin/conversion.php" target="_blank"><font color='yellow'>CONVERSION DE FECHAS</font></a>&nbsp;&nbsp;&nbsp;&nbsp;</th>
<!--<th align="center"><a href="../admin/menu_arreglo_de_fechas.php" target="_blank">MENU ARREGLO DE FECHAS</a></th>-->
<th align="center"><a href="../admin/editable_inventario_todo_utilidad.php" target="_blank"><font color='yellow'>INVENTARIO EDITABLE TODO</font></a>&nbsp;&nbsp;&nbsp;&nbsp;</th>
<th align="center"><a href="../admin/editable_ventas_todo_para_mantenimiento_utilidad.php" target="_blank"><font color='yellow'>VENTAS EDITABLE TODO</font></a>&nbsp;&nbsp;&nbsp;&nbsp;</th>
<th align="center"><a href="../admin/sql_insertar.php" target="_blank"><font color='yellow'>INSERTAR SQL</font></a>&nbsp;&nbsp;&nbsp;&nbsp;</th>
</tr>
</table>
<center>
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//Dth XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
</head>
<body>
</body>
</html>