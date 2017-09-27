<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
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
?>
<!DOCTYPE html>
<html>
<head>
<title></title>
<script type="text/javascript" src="ajax_busqueda_inmediata.js"></script>
</head>
<?php require_once("factura.php");?>
<body>
<center>
<br>
<input type="text" id="busqueda" name="busqueda" onkeyup="hacer_busqueda()" style="height:26" required />
<div id="logo_cargador"></div>
</center>
</body>
</html>
<script>
window.onload = function() {
document.getElementById("busqueda").focus();
}
</script>