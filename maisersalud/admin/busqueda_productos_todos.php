<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar);
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php
$datos_user = "SELECT cod_seguridad, cuenta FROM administrador WHERE cuenta = '$cuenta_actual'";
$consulta_user = mysql_query($datos_user, $conectar) or die(mysql_error());
$info_user = mysql_fetch_assoc($consulta_user);

$cod_seguridad = $info_user['cod_seguridad'];
//----------------------------------------------------------------------------------------------------------------//
//----------------------------------------------------------------------------------------------------------------//
$buscar = mysql_escape_string($_POST['buscar']);

if($buscar <> NULL) {
/*
$mostrar_datos_sql = "SELECT * FROM productos, marcas, nomenclatura WHERE (productos.cod_marcas = marcas.cod_marcas AND 
productos.cod_nomenclatura = nomenclatura.cod_nomenclatura) AND (Match(nombre_productos) AGAINST ('$buscar') OR (cod_productos_var LIKE '$buscar%'))";
*/
//$mostrar_datos_sql = "SELECT * FROM productos, marcas, nomenclatura WHERE (productos.cod_marcas = marcas.cod_marcas AND 
//productos.cod_nomenclatura = nomenclatura.cod_nomenclatura) AND (cod_productos_var = '$buscar' OR nombre_productos like '%$buscar%') order by cod_productos DESC";

$mostrar_datos_sql = "SELECT * FROM productos AS prod INNER JOIN marcas AS marc ON 
(prod.cod_marcas = marc.cod_marcas) AND (prod.cod_productos_var = '$buscar' OR prod.nombre_productos like '%$buscar%') order by prod.nombre_productos ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$total_resultados = mysql_num_rows($consulta);
$matriz_consulta = mysql_fetch_assoc($consulta);

 echo "<font color='yellow'><strong>".$total_resultados." Resultados para: ".$buscar."</strong></font><br>";
} else {
}
$datos_info = "SELECT * FROM info_impuesto_facturas WHERE estado = 'abierto' AND vendedor = '$cuenta_actual'";
$consulta_info = mysql_query($datos_info, $conectar) or die(mysql_error());
$info = mysql_fetch_assoc($consulta_info);
$cantidad_resultado = mysql_num_rows($consulta_info);

$maximo_valor = "SELECT Max(cod_factura) AS cod_factura FROM info_impuesto_facturas";
$consulta_maximo = mysql_query($maximo_valor, $conectar) or die(mysql_error());
$maximo = mysql_fetch_assoc($consulta_maximo);

if ($total_resultados <> 0) {
$pagina = 'temporal_todo.php';
?>
<br>
<center>
<table width="90%">
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>MARCA</strong></td>
<?php
//-------------------------------------------------------------------------------------------------------------//
if ($cod_seguridad <> 4) { ?>
<td align="center"><strong>UND</strong></td>
<?php
} else {
}
//-------------------------------------------------------------------------------------------------------------//
?>
<td align="center"><strong>P.VENTA</strong></td>
</tr>
<?php do { 
$cod_productos_var = $matriz_consulta['cod_productos_var'];
$nombre_productos = $matriz_consulta['nombre_productos'];
$nombre_marcas = $matriz_consulta['nombre_marcas'];
$nombre_nomenclatura = $matriz_consulta['nombre_nomenclatura'];
$unidades_faltantes = $matriz_consulta['unidades_faltantes'];
$precio_venta = $matriz_consulta['precio_venta'];
$descripcion = $matriz_consulta['descripcion'];
?>
<td><?php echo $cod_productos_var; ?></td>
<td ><a href="../modificar_eliminar/agregar_lista_temporal.php?cod_productos=<?php echo $cod_productos_var?>&pagina=<?php echo $pagina?>&cod_factura=<?php if ($cantidad_resultado > '0') {
echo $info['cod_factura']; } else { echo $maximo['cod_factura']+1;}?>"><?php echo utf8_encode($nombre_productos)?></a></td>
<td><?php echo $nombre_marcas; ?></td>

<?php
//-------------------------------------------------------------------------------------------------------------//
if ($cod_seguridad <> 4) { ?>
<td align="center"><font size= "+2"><?php echo $unidades_faltantes; ?></font></td>
<?php
} else {
}
//-------------------------------------------------------------------------------------------------------------//
?>

<td align="right"><font size= "+2"><?php echo number_format($precio_venta, 0, ",", "."); ?></font></td>
</tr>
<?php 
}
while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<?php
} else {
}
?>