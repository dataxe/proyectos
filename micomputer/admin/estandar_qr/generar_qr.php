<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../../conexiones/conexione.php');
require_once('../../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
    } else { header("Location:../../index.php");
}
//$cuenta_actual = addslashes($_SESSION['usuario']);
//include ("../admin/seguridad_diseno_plantillas.php");
$PNG_TEMP_DIR = dirname(__FILE__).DIRECTORY_SEPARATOR.'temp'.DIRECTORY_SEPARATOR;
//html PNG location prefix
$PNG_WEB_DIR = 'temp/';
include "qrlib.php";    
//ofcourse we need rights to create temp dir
if (!file_exists($PNG_TEMP_DIR))
mkdir($PNG_TEMP_DIR);
    
$nombre_archivo = $PNG_TEMP_DIR.'cod-.png';
/*L permite restaurar el 7% de un código QR -M permite restaurar el 15% de un código QR -Q permite restaurar el 25% de un código QR
-H permite restaurar el 30% de un código QR (o sus equivalentes en minúsculas l, m, q o h).
Cuanto mayor dea la posiblidad de corrección de errores, mayor es el código QR. Recomiendo L.*/
$nivel_coreccion_error = 'L';
$tamano_imagen = 4;
if (isset($_REQUEST['size']))
$tamano_imagen = min(max((int)$_REQUEST['size'], 1), 10);

//if (isset($_GET['cod_productos'])) { 
//permite guardar la imagen generada en la carpeta temp
//$nombre_archivo = $PNG_TEMP_DIR.'cod_'.$_GET['cod_productos'].'.png';
//$nombre_archivo = $PNG_TEMP_DIR.'cod_'.md5($_GET['cod_productos'].'|'.$nivel_coreccion_error.'|'.$tamano_imagen).'.png';
QRcode::png($_GET['cod_productos'], $nombre_archivo, $nivel_coreccion_error, $tamano_imagen, 2);        
//}
//muestra la imagen generada QR
for ($i=0; $i < $_GET['unidades']; $i++) { 
echo '<img src="'.$PNG_WEB_DIR.basename($nombre_archivo).'" />';   
}      
// benchmark
//QRtools::timeBenchmark();    
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<title><?php echo $_GET['cod_productos'];?></title>
<style type="text/css">
body { 
background-color: #333;
}
</style>



    