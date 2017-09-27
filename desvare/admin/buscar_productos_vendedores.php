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

$numero_maximo_de_muestra = 200;
$numero_de_pagina = 0;
if (isset($_GET['numero_de_pagina'])) {
$numero_de_pagina = $_GET['numero_de_pagina'];
}
$muestra_faltante = $numero_de_pagina * $numero_maximo_de_muestra;
$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM productos WHERE (nombre_productos like '%$buscar%' OR cod_productos_var like '%$buscar%' OR cod_proveedores like '%$buscar%' OR cod_tipo like '%$buscar%' 
 order by nombre_productos DESC";
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
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>
<?php require_once("../busquedas/buscar_productos_vendedores.php");?>
<body>
<p>
<center>
<table id="table" width="50%"  id="table">
<tr>
<td width="23%" align="center"><?php if ($numero_de_pagina > 0) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, 0, $consulta_caracter_vacio); ?>" >Primero</a><?php }?></td>
<td width="31%" align="center"><?php if ($numero_de_pagina > 0) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, max(0, $numero_de_pagina - 1), $consulta_caracter_vacio); ?>" >Anterior</a><?php }?></td>
<td width="23%" align="center"><?php if ($numero_de_pagina < $total_pagina_consulta) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, min($total_pagina_consulta, $numero_de_pagina + 1), $consulta_caracter_vacio); ?>">Siguiente</a><?php }?></td>
<td width="23%" align="center"><?php if ($numero_de_pagina < $total_pagina_consulta) {?><a href="<?php printf("%s?numero_de_pagina=%d%s", $pagina_actual, $total_pagina_consulta, $consulta_caracter_vacio); ?>" >&Uacute;ltimo</a><?php }?></td>
</tr>
</table>
</center>
<br>
<br>
<center>
<table id="table">
<tr>
<td><div align="center" ><strong>Id Factura</strong></div></td>
<td><div align="center" ><strong>Cod</strong></div></td>
<td><div align="center" ><strong>Tipo</strong></div></td>
<td><div align="center" ><strong>Nombre</strong></div></td>
<td><div align="center" ><strong>Marca</strong></div></td>
<td><div align="center" ><strong>Proveedor</strong></div></td>
<td><div align="center" ><strong>Procedencia</strong></div></td>
<td><div align="center" ><strong>Casilla</strong></div></td>
<td><div align="center" ><strong>Und</strong></div></td>
<td><div align="center" ><strong>Inv</strong></div></td>
<td><div align="center" ><strong>P.Comp</strong></div></td>
<td><div align="center" ><strong>P.Vent</strong></div></td>
<td><div align="center" ><strong>P.letra</strong></div></td>
<td><div align="center" ><strong>Utilidad</strong></div></td>
<td><div align="center" ><strong>Descripcion</strong></div></td>
<td><div align="center" ><strong>Vender</strong></div></td>
</tr>
<?php do { ?>
<td><?php echo $matriz_consulta['cod_original']; ?></td>
<td><?php echo $matriz_consulta['cod_productos_var']; ?></td>
<td><?php echo $matriz_consulta['cod_tipo']; ?></td>
<td><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td><?php echo $matriz_consulta['nombre_marcas']; ?></td>
<td><?php echo $matriz_consulta['cod_proveedores']; ?></td>
<td><?php echo $matriz_consulta['productos_procedencia']; ?></td>
<td><?php echo $matriz_consulta['nombre_nomenclatura']; ?></td>
<td><?php echo $matriz_consulta['unidades']; ?></td>
<td><?php echo $matriz_consulta['unidades_faltantes']; ?></td>
<td><?php echo $matriz_consulta['precio_compra']; ?></td>
<td><?php echo $matriz_consulta['precio_venta']; ?></td>
<td><?php echo $matriz_consulta['codificacion']; ?></td>
<td><?php echo $matriz_consulta['utilidad']; ?></td>
<td><?php echo $matriz_consulta['descripcion']; ?></td>
<td  nowrap><a href="../modificar_eliminar/venta_productos.php?cod_productos=<?php echo $matriz_consulta['cod_productos_var']; ?>"><center><img src=../imagenes/vender.png alt="Vender"></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>

