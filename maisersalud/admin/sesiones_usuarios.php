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

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$numero_maximo_de_muestra = 200;
$numero_de_pagina = 0;
if (isset($_GET['numero_de_pagina'])) {
$numero_de_pagina = $_GET['numero_de_pagina'];
}
$muestra_faltante = $numero_de_pagina * $numero_maximo_de_muestra;
$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM sesiones WHERE (usuario like '%$buscar%' OR ip like '%$buscar%' OR navegador like '%$buscar%' 
OR version like '%$buscar%') ORDER BY fecha_ini_time DESC";
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
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title>ALMACEN</title>
</head>
<body>
<center>
<a href="../admin/pregunta_borrar_sesiones.php?"><center><font color='yellow' size= '+2'>BORRAR TODAS LAS SESIONES</font></center></a>
<br>
<?php require_once("../busquedas/busqueda_sesiones.php");?>
<table width="50%" align="center">
<tr>
<td width="23%" align="center"><?php if ($numero_de_pagina > 0) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, 0, $consulta_caracter_vacio); ?>" >Primero</a><?php }?></td>
<td width="31%" align="center"><?php if ($numero_de_pagina > 0) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, max(0, $numero_de_pagina - 1), $consulta_caracter_vacio); ?>" >Anterior</a><?php }?></td>
<td width="23%" align="center"><?php if ($numero_de_pagina < $total_pagina_consulta) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, min($total_pagina_consulta, $numero_de_pagina + 1), $consulta_caracter_vacio); ?>" >Siguiente</a><?php }?></td>
<td width="23%" align="center"><?php if ($numero_de_pagina < $total_pagina_consulta) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, $total_pagina_consulta, $consulta_caracter_vacio); ?>" >&Uacute;ltimo</a><?php }?></td>
</tr>
</table>
<br>
</center>
<center>
<table width='100%'>
<tr>
<!--<td><strong>Cod</strong></td>-->
<td align="center"><strong>USUARIO</strong></td>
<td align="center"><strong>IP</strong></td>
<td align="center"><strong>NAVEGADOR</strong></td>
<!--<td><strong>PLATAFORMA</strong></td>-->
<td align="center"><strong>FECHA ENTRADA</strong></td>
<td align="center"><strong>FECHA SALIDA</strong></td>
<td align="center"><strong>ELM</strong></td>
</tr>
<?php do { ?>
<tr>
<!--<td ><?php //echo $matriz_consulta['cod_sesiones']; ?></td>-->
<td ><?php echo $matriz_consulta['usuario']; ?></td>
<td ><?php echo $matriz_consulta['ip']; ?></td>
<td ><?php echo $matriz_consulta['navegador']; ?></td>
<!--<td ><?php echo $matriz_consulta['plataforma']; ?></td>-->
<td align="center"><?php echo $matriz_consulta['fecha_entrada']; ?></td>
<td align="center"><?php echo $matriz_consulta['fecha_salida']; ?></td>
<td><a href="../modificar_eliminar/eliminar_sesiones.php?cod_sesiones=<?php echo $matriz_consulta['cod_sesiones']; ?>"><center><img src=../imagenes/eliminar.png></a></td>
</tr>
<?php }
while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>