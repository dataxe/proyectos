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

$cod_productos = $_GET['cod_productos'];
$cod_temporal = $_GET['cod_temporal'];
//$cod_temporal = '2';

$sql_modificar_consulta = "SELECT * FROM temporal WHERE cod_productos = '$cod_productos'";
$modificar_consulta = mysql_query($sql_modificar_consulta, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($modificar_consulta);

$sql_productos = "SELECT * FROM productos WHERE cod_productos_var = '$cod_productos'";
$modificar_productos = mysql_query($sql_productos, $conectar) or die(mysql_error());
$productos_datos = mysql_fetch_assoc($modificar_productos);
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
$unidades_venta = $datos['unidades_vendidas'];
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

if (isset($_GET['cod_productos'])) {
if($unidades_venta == NULL) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='white'><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong> No ha ingresado las unidades a vender. 
<img src=../imagenes/advertencia.gif alt='Advertencia'></strong></center></font>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="1; ../admin/busqueda_productos_vendedor.php">';
} elseif ($unidades_venta > $unidades_disponibles) {
echo "<embed SRC='../sonidos/alarma.mp3' hidden='true' autostart='true' loop='3'></embed>";
echo "<font color='white'><center><img src=../imagenes/advertencia.gif alt='Advertencia'><strong> Las unidades a vender no pueden ser mayor a las 
unidades disponibles. <img src=../imagenes/advertencia.gif alt='Advertencia'></strong></center></font>";
echo '<META HTTP-EQUIV="REFRESH" CONTENT="1; ../admin/busqueda_productos_vendedor.php">';
} else {
  $actualizar_sql1 = sprintf("UPDATE productos SET unidades_faltantes=%s, unidades_vendidas=%s, total_mercancia=%s, total_venta=%s, total_utilidad=%s, 
    descuento=%s WHERE cod_productos_var=%s",
             $calculo_unidades_faltantes,
             $calculo_unidades_vendidas,
             $calculo_total_mercancia,
             $calculo_total_venta,
             $calculo_total_utilidad,
             $calculo_suma_descuento,
             envio_valores_tipo_sql($cod_productos, "text"));

$agregar_registros_sql2 = sprintf("INSERT INTO ventas (cod_productos, cod_factura, nombre_productos, unidades_vendidas, precio_compra, 
precio_venta, vlr_total_venta, vlr_total_compra, descuento, precio_compra_con_descuento, vendedor, ip, fecha, fecha_mes, fecha_anyo, anyo, 
fecha_hora) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
             envio_valores_tipo_sql($datos['cod_productos'], "text"),
             envio_valores_tipo_sql($tipo_venta, "text"),
             envio_valores_tipo_sql($datos['nombre_productos'], "text"),
             envio_valores_tipo_sql($unidades_venta, "text"), 
             envio_valores_tipo_sql($datos['precio_compra'], "text"),
             envio_valores_tipo_sql($datos['precio_venta'], "text"),
             $vlr_total_venta,
             $vlr_total_compra,
             envio_valores_tipo_sql($descuento, "text"),
             envio_valores_tipo_sql($precio_compra_con_descuento, "text"),
             envio_valores_tipo_sql($cuenta_actual, "text"),
             envio_valores_tipo_sql($ip, "text"),
             envio_valores_tipo_sql(date("Y/m/d"), "text"),
             envio_valores_tipo_sql(date("m/Y"), "text"),
             envio_valores_tipo_sql(date("d/m/Y"), "text"),
             envio_valores_tipo_sql(date("Y"), "text"),
             envio_valores_tipo_sql(date("H:i:s"), "text")); 

$borrar_sql = sprintf("DELETE FROM temporal WHERE cod_temporal = $cod_temporal AND cod_productos = $cod_productos");

     
$resultado_actualizacion1 = mysql_query($actualizar_sql1, $conectar) or die(mysql_error());
$resultado_sql2 = mysql_query($agregar_registros_sql2, $conectar) or die(mysql_error());
$Result1 = mysql_query($borrar_sql, $conectar) or die(mysql_error()); 
echo '<META HTTP-EQUIV="REFRESH" CONTENT="0.1; ../admin/busqueda_productos_vendedor.php">';
}
}
?>
<p>&nbsp;</p>
</body>
</html>
<?php mysql_free_result($modificar_consulta);?>