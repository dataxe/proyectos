<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../evitar_mensaje_error/error.php'); 
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>ALMACEN</title>
</head>
<body>
<?php
if(isset($_GET["cod_productos"]) && is_numeric($_GET["cod_productos"])) {
$unidades = $_GET['unidades'];
$columnas = 8;
$calc_filas = $unidades / $columnas;
$filas = intval($calc_filas)+1;

print ("<center><table border=0 width=600 align=center>");
while ($filas>0):
echo "<tr>";
$filas--;
while ($columnas>0):
echo "<td>";
print "<img src='codigoBarras_img.php?cod_productos=".$_GET["cod_productos"].' '."'>";
print ("</td>");
$columnas--;
endwhile;
$columnas = 8;
echo "</TR>";
endwhile;
print "</table></center>";
}
?>
</body>
</html>