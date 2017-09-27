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

include ("../registro_movimientos/registro_movimientos.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php 
if (isset($_POST['si'])) {
$actualizar_sql1 = sprintf("TRUNCATE TABLE productos_copia_inventario");
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());

echo "<br><br><center><font color='yellow' size= '+2'>LA COPIA DEL INVENTARIO FUE BORRADO</font></center>";
echo "<META HTTP-EQUIV='REFRESH' CONTENT='2; inventario_productos_copia_estado_viejo.php'>";

} if (isset($_POST['no'])) {
echo "<META HTTP-EQUIV='REFRESH' CONTENT='0.1; inventario_productos_copia_estado_viejo.php'>";
}
?>
</body>
</html>