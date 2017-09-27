<?php
/*
error_reporting(E_ALL ^ E_NOTICE);
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
include ("../formato_entrada_sql/funcion_env_val_sql.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../../registro_movimientos/registro_cierre_caja.php");
*/
?>
<html>
<head>
<title>.::EDITAXE - REPARAR BASE</title>
<link href="../imagenes/icono.ico" type="image/x-icon" rel="shortcut icon" />
</head>

<CENTER>
<BR>
<?php
/*
$result_handle = mysql_list_dbs () or die ("mysql_list_dbs() failed with this error message: '" . mysql_error () . "'");
$number_rows = mysql_num_rows ($result_handle);
*/
?>
<form name="frepopt" action="reparar_db.php" method="POST">
<input type="hidden" name="repopt" value="1">
<select name="nombre_db">
<option value="pinsalud">PINSALUD</option>
</select>
<input type="submit" name="submit" value="Reparar / Optimizar Tablas" style="width: 200px;">
</form>
</CENTER>
</body>
</html>
