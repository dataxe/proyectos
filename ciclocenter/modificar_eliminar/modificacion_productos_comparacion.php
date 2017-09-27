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

$cod_productos = $_GET['cod_productos'];
$cod_camparacion_tablas = $_GET['cod_camparacion_tablas'];
$cod_factura = $_GET['cod_factura'];
$unidades_faltantes = $_GET['unidades_faltantes'];

$sql_modificar_consulta = "SELECT * FROM productos where cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);
$total_resultado = mysql_num_rows($modificar_consulta);

$precio_venta = $datos['precio_venta'];
$precio_costo = $datos['precio_costo'];

$utilidad = $precio_venta - $precio_costo;
$total_utilidad = $utilidad * $unidades_faltantes;
$total_mercancia = $precio_costo * $unidades_faltantes;
   
if (isset($_GET['actualizar'])=='actualizar') {

$actualiza_productos = sprintf("UPDATE productos SET unidades_faltantes = '$unidades_faltantes', utilidad = '$utilidad', total_utilidad = '$total_utilidad', 
total_mercancia = '$total_mercancia' WHERE cod_productos_var = '$cod_productos'");
$resultado_actualiza_productos = mysql_query($actualiza_productos, $conectar) or die(mysql_error());

$borrar_sql = sprintf("DELETE FROM camparacion_tablas WHERE cod_camparacion_tablas = '$cod_camparacion_tablas'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/resultado_comparacion_tablas.php?cod_factura=<?php echo $cod_factura;?>">
<?php
}
if (isset($_GET['eliminar'])=='eliminar') {
$borrar_sql = sprintf("DELETE FROM camparacion_tablas WHERE cod_camparacion_tablas = '$cod_camparacion_tablas'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());
?>
<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/resultado_comparacion_tablas.php?cod_factura=<?php echo $cod_factura;?>">
<?php
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<body>
</body>
</html>
<?php
mysql_free_result($modificar_consulta);
?>
