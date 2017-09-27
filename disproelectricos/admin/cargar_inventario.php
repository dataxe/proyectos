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
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<!DOCTYPE html>
<html>
<head>
<title></title>
<script type="text/javascript" src="inmediata_busqueda_cargar_inventario.js"></script>
</head>
<body>
<center>
<br>
<td><strong><a href="buscar_productos_editar.php"><font color='white'>CARGAR POR CODIGO</font></a></strong></td>&nbsp;&nbsp;&nbsp;&nbsp;
<td><strong><font color='yellow'>CARGAR MANUAL: </font></strong></td> <input type="text" id="busqueda" name="busqueda" onkeyup="hacer_busqueda()" style="height:26" required placeholder="Buscar"/>
<div id="logo_cargador"></div>
</center>
</body>
</html>
<script>
window.onload = function() {
document.getElementById("busqueda").focus();
}
</script>