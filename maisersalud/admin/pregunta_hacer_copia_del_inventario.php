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
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<br><br>
<center>
<a href="../admin/inventario_productos_copia_estado_viejo.php?"><center><font color='yellow'>VER COPIA DE INVENTAARIO DISPONIBLE</font></center></a>
<br>
<table>
<center><font color='yellow' size= '+2'>&iquest; DESEA HACER UNA COPIA DEL INVENTARIO ACTUAL ?</font></center><br>
<form method="post" name="formulario" action="copia_inventario_actual.php">
<input type="submit" name="si" value="SI" />&nbsp; &nbsp;<input type="submit" name="no" value="NO" />
</form>
</table>
</center>
</body>
</html>