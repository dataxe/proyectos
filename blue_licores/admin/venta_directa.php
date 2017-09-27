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
?>
<!DOCTYPE html>
<html>
<head>
<title></title>
<script type="text/javascript" src="inmediata_busqueda_productos.js"></script>
</head>
<body>
<center>
<input type="text" id="busqueda" name="busqueda" onkeyup="hacer_busqueda()" style="height:26" required placeholder="Buscar"/>
<div id="logo_cargador"></div>
<br>
<?php require_once("temporal.php");?>
</center>
</body>
</html>
<script>
window.onload = function() {
document.getElementById("busqueda").focus();
}
</script>