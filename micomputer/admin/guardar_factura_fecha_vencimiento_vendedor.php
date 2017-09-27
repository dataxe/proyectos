<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
      } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
date_default_timezone_set("America/Bogota");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php 
if (isset($_POST['verificacion'])) {

$actualiza_fechas_vencimiento = sprintf("UPDATE productos, cargar_factura_temporal2 SET 
productos.fechas_vencimiento = cargar_factura_temporal2.fechas_vencimiento,
productos.fechas_vencimiento_seg = cargar_factura_temporal2.fechas_vencimiento_seg 
WHERE productos.cod_productos_var = cargar_factura_temporal2.cod_productos AND cargar_factura_temporal2.vendedor = '$cuenta_actual'");
$resultado_actualiza_fechas_vencimiento = mysql_query($actualiza_fechas_vencimiento, $conectar) or die(mysql_error());

$borrar_sql = sprintf("DELETE FROM cargar_factura_temporal2 WHERE vendedor = '$cuenta_actual'");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

echo "<center><font size='6' color='yellow'>SE HA ACTUALIZADO EXITOSAMENTE LA FECHA DE VENCIMIENTO</font><center>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="3; ../admin/cargar_factura_temporal_fecha_vencimiento_vendedor.php">';
}
?>
</body>
</html>