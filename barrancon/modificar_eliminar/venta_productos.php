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
date_default_timezone_set("America/Bogota");
include ("../formato_entrada_sql/funcion_env_val_sql.php");
/*
$cod_productos = $_POST['cod_productos'];
$cod_temporal = $_POST['cod_temporal'];
//$cod_temporal = '2';

$sql_modificar_consulta = "SELECT * FROM temporal WHERE cod_productos = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$sql_productos = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_productos = mysql_query($sql_productos, $conectar) or die(mysql_error());
$productos_datos = mysql_fetch_assoc($modificar_productos);*/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<br>
<body>
<?php 
/*$unidades_venta = $datos['unidades_vendidas'];
$unidades_disponibles = $productos_datos['unidades_faltantes'];
$calculo_unidades_faltantes = $productos_datos['unidades_faltantes'] - $unidades_venta;
$calculo_suma_descuento = $datos['descuento'];
$calculo_total_mercancia = $datos['precio_compra'] * $calculo_unidades_faltantes;
$calculo_total_venta = ($datos['precio_venta'] * ($datos['unidades_vendidas'] + $unidades_venta));
$calculo_total_utilidad = (($datos['precio_venta'] - $datos['precio_compra']) * ($datos['unidades_vendidas'] + $unidades_venta));
$calculo_unidades_vendidas = $unidades_venta + $datos['unidades_vendidas'];
$precio_compra_con_descuento = ($datos['precio_venta'] * $unidades_venta);
$ip = $_SERVER['REMOTE_ADDR'];
$vlr_total_venta = $datos['precio_venta'] * $unidades_venta; 
$vlr_total_compra = $datos['precio_compra'] * $unidades_venta;
$cod_clientes = $_POST['cod_clientes'];
$tipo_venta ='sin_factura';
$descuento = '0';

if (isset($_POST['cod_productos'])) {
if($unidades_venta == NULL || $unidades_venta == '0') {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='white'><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong> No ha ingresado las unidades a vender. 
<img src=../imagenes/advertencia.gif alt='Advertencia'></strong></center></font>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="1; ../admin/busqueda_productos_vendedor.php">';
} 
elseif ($unidades_venta > $unidades_disponibles) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='white'><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong> Las unidades a vender no pueden ser mayor a las 
unidades disponibles. <img src=../imagenes/advertencia.gif alt='Advertencia'></strong></center></font>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="1; ../admin/busqueda_productos_vendedor.php">';
} 
*/
if (isset($_POST['actualizar'])) {
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/venta_directa.php">';
}
if (isset($_POST['vender'])) {
for($i=0; $i < $_POST['total_datos']; $i++) {
$cod_productos = $_POST['cod_productos'][$i];

$sql_modificar_consulta = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$tipo_venta = 'sin_factura';
$nombre_productos = $_POST['nombre_productos'][$i];
$unidades_vendidas = $_POST['unidades_vendidas'][$i];
$precio_compra = $_POST['precio_compra'][$i];
$precio_venta = $_POST['precio_venta'][$i];
$vlr_total_venta = $_POST['vlr_total_venta'][$i];
$vlr_total_compra = $_POST['vlr_total_compra'][$i];
$descuento = $_POST['descuento'][$i];
$precio_compra_con_descuento = $_POST['precio_compra_con_descuento'][$i];
$vendedor = $cuenta_actual;
$ip = $_SERVER['REMOTE_ADDR'];
$fecha = date("Y/m/d");
$fecha_mes = date("m/Y");
$fecha_anyo = date("d/m/Y");
$anyo = date("Y");
$fecha_hora = date("H:i:s");

$unidades_faltantes = $datos['unidades_faltantes'] - $unidades_vendidas;
$unidades_vendidax = $datos['unidades_vendidas'] + $unidades_vendidas;
$total_mercancia = $datos['precio_compra'] * $unidades_faltantes;
$total_venta = (($datos['unidades_vendidas'] + $unidades_vendidas) * $datos['precio_venta']);
$total_utilidad = ($datos['utilidad'] * ($datos['unidades_vendidas'] + $unidades_vendidas));
$descuento = $datos['descuento'];


$agregar_registros_sql2 = "INSERT INTO ventas (cod_productos, cod_factura, nombre_productos, unidades_vendidas, precio_compra, 
precio_venta, vlr_total_venta, vlr_total_compra, descuento, precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, anyo, 
fecha_hora)
VALUES ('$cod_productos','$tipo_venta','$nombre_productos','$unidades_vendidas','$precio_compra','$precio_venta','$vlr_total_venta','$vlr_total_compra',
'$descuento','$precio_compra_con_descuento','$vendedor','$ip','$fecha','$fecha_mes','$fecha_anyo','$anyo','$fecha_hora')";

$actualizar_sql1 = sprintf("UPDATE productos SET unidades_faltantes = '$unidades_faltantes', unidades_vendidas = '$unidades_vendidax', 
total_mercancia = '$total_mercancia', total_venta = '$total_venta', descuento = '$descuento' WHERE cod_productos_var = '$cod_productos'");

$resultado_sql2 = mysql_query($agregar_registros_sql2, $conectar) or die(mysql_error());
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
}
$borrar_sql = sprintf("TRUNCATE TABLE temporal");
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error());

echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/venta_directa.php">';
}
?>
</body>
</html>