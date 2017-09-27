<?php
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php');
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../registro_movimientos/registro_movimientos.php");

if (isset($_GET["nombre_disenos"])) {

$nombre_disenos = addslashes($_GET["nombre_disenos"]);
$pagina = $_GET["pagina"];

$actualizar_regis = sprintf("UPDATE administrador SET estilo_css = '$nombre_disenos' WHERE cuenta = '$cuenta_actual'");
$resultado_regis = mysql_query($actualizar_regis, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0; <?php echo $pagina ?>">
<?php
}
?>