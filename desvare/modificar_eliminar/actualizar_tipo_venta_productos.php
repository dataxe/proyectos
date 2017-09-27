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

$cod_temporal = $_GET['cod_temporal'];
$cod_productos = $_GET['cod_productos'];
$pagina = $_GET['pagina'];

$sql_modificar_consulta = "SELECT * FROM temporal WHERE cod_temporal = '$cod_temporal' AND cod_productos = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$cajas_v = '1';
$cajas = $datos['cajas'] + $cajas_v;
$unidades_cajas = $datos['unidades_cajas'] * $cajas;
$precio_venta = $datos['precio_venta'];
$vlr_total_venta = $datos['precio_venta'] * ($datos['unidades_cajas'] * $cajas);


if (isset($_GET['tipo'])) {
$actualizar_sql1 = sprintf("UPDATE temporal SET unidades_vendidas = '$unidades_cajas', cajas = '$cajas', precio_venta = '$precio_venta', vlr_total_venta = '$vlr_total_venta' 
WHERE cod_temporal = '$cod_temporal' AND cod_productos = '$cod_productos'");

$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/<?php echo $pagina?>">
<?php
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
