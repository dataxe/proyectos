<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
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

if ((isset($_POST["insertar_datos"])) && ($_POST["insertar_datos"] == "formulario")) {

$consulta_sql = $_POST['consulta_sql']; 

$insertar_reg = "$consulta_sql";
$resultado_reg = mysql_query($insertar_reg, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; sql_insertar.php">';
echo "<br><br><center><font color='yellow' size='10px'> LA CONSULTA SE HA HECHO CORRECTAMENTE</font><center>";
}
?>