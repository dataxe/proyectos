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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");
/*
$verificar_pag = "SELECT * FROM pagina_actual WHERE cod_pagina_actual='1'";
$resultado_verificar_pag = mysql_query($verificar_pag, $conectar) or die(mysql_error());

$nombre_pagina_actual = 'ELIMINAR';
if ($resultado_verificar_pag['nombre_pagina_actual'] <> $nombre_pagina_actual) {
$actualizar_nombre_pagina_actual = sprintf("UPDATE pagina_actual SET nombre_pagina_actual=%s WHERE cod_pagina_actual='1'",
envio_valores_tipo_sql($nombre_pagina_actual, "text"), envio_valores_tipo_sql($_POST['cod_pagina_actual'], "text"));
$resultado_actualizacion_pagina_actual = mysql_query($actualizar_nombre_pagina_actual, $conectar) or die(mysql_error());
}
*/
?>
<?php $pagina_actual = $_SERVER["PHP_SELF"];
$numero_maximo_de_muestra = 300;
$numero_de_pagina = 0;
if (isset($_GET['numero_de_pagina'])) {
  $numero_de_pagina = $_GET['numero_de_pagina'];
}
$muestra_faltante = $numero_de_pagina * $numero_maximo_de_muestra;
$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM productos_eliminados WHERE cod_productos like '$buscar%' OR nombre_productos like '%$buscar%' order by nombre_productos";
$limite_consulta_sql = sprintf("%s LIMIT %d, %d", $mostrar_datos_sql, $muestra_faltante, $numero_maximo_de_muestra);
$consulta = mysql_query($limite_consulta_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

if (isset($_GET['numero_total_de_registros'])) {
$numero_total_de_registros = $_GET['numero_total_de_registros'];
} else {
$todo_consulta = mysql_query($mostrar_datos_sql);
$numero_total_de_registros = mysql_num_rows($todo_consulta);
}
$total_pagina_consulta = ceil($numero_total_de_registros/$numero_maximo_de_muestra)-1;

$consulta_caracter_vacio = "";
if (!empty($_SERVER['QUERY_STRING'])) {
$parametros = explode("&", $_SERVER['QUERY_STRING']);
$nuevos_parametros = array();
foreach ($parametros as $parametro) {
if (stristr($parametro, "numero_de_pagina") == false && 
stristr($parametro, "numero_total_de_registros") == false) {
array_push($nuevos_parametros, $parametro);
}
}
if (count($nuevos_parametros) != 0) {
$consulta_caracter_vacio = "&" . htmlentities(implode("&", $nuevos_parametros));
}
}
$consulta_caracter_vacio = sprintf("&numero_total_de_registros=%d%s", $numero_total_de_registros, $consulta_caracter_vacio);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<?php require_once("../busquedas/busqueda_productos_eliminados.php");?>
<body>
<p>
<center>
<table width="50%" align="center" >
<tr>
<td width="23%" align="center"><?php if ($numero_de_pagina > 0) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, 0, $consulta_caracter_vacio); ?>" >Primero</a><?php }?></td>
<td width="31%" align="center"><?php if ($numero_de_pagina > 0) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, max(0, $numero_de_pagina - 1), $consulta_caracter_vacio); ?>" >Anterior</a><?php }?></td>
<td width="23%" align="center"><?php if ($numero_de_pagina < $total_pagina_consulta) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, min($total_pagina_consulta, $numero_de_pagina + 1), $consulta_caracter_vacio); ?>" >Siguiente</a><?php }?></td>
<td width="23%" align="center"><?php if ($numero_de_pagina < $total_pagina_consulta) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, $total_pagina_consulta, $consulta_caracter_vacio); ?>" >&Uacute;ltimo</a><?php }?></td>
</tr>
</table>
</center>
<br>
<center>
<table >
<tr>
<td><div align="center"><strong>CODIGO</strong></div></td>
<td><div align="center"><strong>PRODUCTO</strong></div></td>
<!--<td><div align="center"><strong>Marca</strong></div></td>
<td><div align="center"><strong>Proveedor</strong></div></td>-->
<td><div align="center"><strong>UND</strong></div></td>
<td><div align="center"><strong>PRECIO COMPRA</strong></div></td>
<td><div align="center"><strong>PRECIO VENTA</strong></div></td>
<!--<td><div align="center"><strong>Margen Utilidad</strong></div></td>-->
<td><div align="center"><strong>DESCRIPCION</strong></div></td>
<td><div align="center"><strong>FECHA ELIMINACION</strong></div></td>
<td colspan="1"><div align="center" ><strong>ELIM</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td><?php echo $matriz_consulta['cod_productos']; ?></td>
<td><?php echo $matriz_consulta['nombre_productos']; ?></td>
<!--<td><?php //echo $matriz_consulta['nombre_marcas']; ?></td>
<td><?php //echo $matriz_consulta['nombre_proveedores']; ?></td>-->
<td><div align="right"><?php echo $matriz_consulta['unidades_faltantes']; ?></div></td>
<td><div align="right"><?php echo number_format($matriz_consulta['precio_costo']); ?></div></td>
<td><div align="right"><?php echo number_format($matriz_consulta['precio_venta']); ?></div></td>
<!--<td><?php //echo number_format($matriz_consulta['utilidad']); ?></td>-->
<td><?php echo $matriz_consulta['descripcion']; ?></td>
<td><?php echo $matriz_consulta['fechas_anyo'].' - '.$matriz_consulta['fechas_hora']; ?></td>
<td><a href="../modificar_eliminar/eliminar_productos_eliminados.php?cod_productos=<?php echo $matriz_consulta['cod_productos']; ?>"><center><img src=../imagenes/eliminar.png></center></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
</body>
</html>
<?php mysql_free_result($consulta);?>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>