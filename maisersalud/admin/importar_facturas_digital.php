<?php
require_once('../conexiones/conexione.php'); 
mysql_select_db($base_datos, $conectar);
include ("../session/funciones_admin.php");
date_default_timezone_set("America/Bogota");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");

$nombre_archivo = time().'-'.$_FILES['nombre_archivo']['name'];
$fecha_cargue = date("Y/m/d - H:i:s");
$cod_facturas = $_POST['cod_facturas'];
$cod_proveedor = $_POST['cod_proveedor'];
$fecha_llegada = $_POST['fecha_llegada'];
$url_archivo = "../facturas_cargadas/".$nombre_archivo;
/*
$verificar_cod_facturas = mysql_query("SELECT cod_facturas FROM facturas_cargadas WHERE cod_facturas LIKE '$cod_facturas'");
$existencia_cod_facturas = mysql_num_rows($verificar_cod_facturas);
$verificar_nombre_archivo = mysql_query("SELECT nombre_archivo FROM facturas_cargadas WHERE nombre_archivo LIKE '$nombre_archivo'");
$existencia_nombre_archivo = mysql_num_rows($verificar_nombre_archivo);
*/
if (isset($_POST['cod_facturas'])) {

$agregar_registros_sql1 = ("INSERT INTO facturas_cargadas (cod_facturas, cod_proveedor, fecha_llegada, nombre_archivo, url_archivo, 
fecha_cargue) VALUES ('$cod_facturas', '$cod_proveedor', '$fecha_llegada', '$nombre_archivo', '$url_archivo', '$fecha_cargue')");
$resultado_sql1 = mysql_query($agregar_registros_sql1, $conectar) or die(mysql_error());

copy($_FILES['nombre_archivo']['tmp_name'], $url_archivo);
//redirect---------------------------------------------------------------------------------------------------
echo "<br><br><center><font color='white' align ='justify' size='+3'>El archivo: ".$_FILES['nombre_archivo']['name']." se subio con exito</font><center> ";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; agregar_facturas_al_sistema.php">';
} else {
echo "<br><br><center><font color='white' alling ='justify' size='+3'>Error</font><center> ";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="5; agregar_facturas_al_sistema.php">';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title></title>
</head>
<body>
</body>
</html> 