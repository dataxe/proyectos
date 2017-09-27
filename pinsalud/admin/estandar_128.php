<?php 
error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar); 
require_once('../evitar_mensaje_error/error.php');
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
//include ("../seguridad/seguridad_diseno_plantillas.php");
$obtener_informacion = "SELECT * FROM informacion_almacen WHERE cod_informacion_almacen = '1'";
$consultar_informacion = mysql_query($obtener_informacion, $conectar) or die(mysql_error());
$matriz_informacion = mysql_fetch_assoc($consultar_informacion);
$num_informacion = mysql_num_rows($consultar_informacion); 

$unidades = $_GET['unidades'];
require_once('estandar128/Barcode.php');
Image_Barcode::draw($_GET['cod_productos'], $_GET['nombre_productos'],  $_GET['descripcion'], 'code128', 'png');

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<link href="../imagenes/<?php echo $matriz_informacion['icono'];?>" type="image/x-icon" rel="shortcut icon" />
<?php //header($_GET['cod_productos']); ?>
<head>
<title><?php echo $_GET['cod_productos'];?></title>
<body>
</body>
</head>
</html>