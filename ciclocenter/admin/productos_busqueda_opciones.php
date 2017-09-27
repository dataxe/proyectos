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

$numero_maximo_de_muestra = 9999;
$numero_de_pagina = 0;
if (isset($_GET['numero_de_pagina'])) {
  $numero_de_pagina = $_GET['numero_de_pagina'];
}
$muestra_faltante = $numero_de_pagina * $numero_maximo_de_muestra;
$buscar = $_POST['palabra'];
$opcion_busqueda = $_POST['opcion_busqueda'];

$mostrar_datos_sql = "SELECT * FROM productos, marcas, proveedores WHERE $opcion_busqueda like '%$buscar%' AND unidades_faltantes <> '0' AND 
((marcas.cod_marcas = rel_produ_marcas.cod_rel_marcas) AND (productos.cod_productos = rel_produ_marcas.cod_rel_productos) AND 
  (proveedores.cod_proveedores = rel_produ_provee.cod_rel_proveedores) AND(productos.cod_productos = rel_produ_provee.cod_rel_productos))order by productos.nombre_productos";
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
<body>
<p>
<script>
window.onload = function() {
  document.getElementById("foco").focus();
}
</script>
<center>
 <form action="../admin/productos_busqueda_opciones.php" id="table" method="post">
<select name="opcion_busqueda">
<option value="nombre_productos">Nombre</option>
<option value="cod_productos_var" selected>Codigo</option>
<option value="unidades_faltantes">Unidades faltantes</option>
<option value="fechas">Fecha Insc</option>
<option value="fechas_inventario">Fecha invent</option>
</select> 
<input id="foco" name="palabra"/>
<input type="submit" name="buscador" value="Buscar"/>
</form>
</center><br>
<center>
<table id="table">
<tr>
<td><div align="center" ><strong>C&oacute;digo</strong></div></td>
<td><div align="center" ><strong>Nombre</strong></div></td>
<td><div align="center" ><strong>Marca</strong></div></td>
<td><div align="center" ><strong>Proveedor</strong></div></td>
<td><div align="center" ><strong>Unidades</strong></div></td>
<td><div align="center" ><strong>Disponibles</strong></div></td>
<td><div align="center" ><strong>Precio Compra</strong></div></td>
<td><div align="center" ><strong>Precio Venta</strong></div></td>
<td><div align="center" ><strong>Margen Utilidad</strong></div></td>
<td><div align="center" ><strong>Descripcion</strong></div></td>
<td colspan="2"><div align="center" ><strong>Operaciones</strong></div></td>
</tr>
<?php do { ?>
<tr>
<td><?php echo $matriz_consulta['cod_productos_var']; ?></td>
<td><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td><?php echo $matriz_consulta['nombre_marcas']; ?></td>
<td><?php echo $matriz_consulta['nombre_proveedores']; ?></td>
<td><?php echo $matriz_consulta['unidades']; ?></td>
<td><?php echo $matriz_consulta['unidades_faltantes']; ?></td>
<td><?php echo $matriz_consulta['precio_compra']; ?></td>
<td><?php echo $matriz_consulta['precio_venta']; ?></td>
<td><?php echo $matriz_consulta['utilidad']; ?></td>
<td><?php echo $matriz_consulta['descripcion']; ?></td>
<td><a href="../modificar_eliminar/modificar_productos.php?cod_productos=<?php echo $matriz_consulta['cod_productos']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></a></td>
<td  nowrap><a href="../modificar_eliminar/venta_productos.php?cod_productos=<?php echo $matriz_consulta['cod_productos']; ?>"><center><img src=../imagenes/vender.png alt="Vender"></a></td>
</tr>
<?php } while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
</body>
</html>
<?php mysql_free_result($consulta);?>