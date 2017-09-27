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
include ("../formato_entrada_sql/funcion_env_val_sql.php");

$cod_facturas_cargadas = $_GET['cod_facturas_cargadas'];

$mostrar_datos_sql = "SELECT * FROM facturas_cargadas WHERE cod_facturas_cargadas = '$cod_facturas_cargadas'";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$url_archivo = $datos['url_archivo'];

if ((isset($_GET['cod_facturas_cargadas'])) && ($_GET['cod_facturas_cargadas'] != "")) {
$borrar_sql = sprintf("DELETE FROM facturas_cargadas WHERE cod_facturas_cargadas=%s",
envio_valores_tipo_sql($cod_facturas_cargadas, "text"));
unlink($datos['url_archivo']);

$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/descargar_archivos_subidos.php">';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
</body>
</html>