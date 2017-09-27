<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<?php
$buscar = mysql_escape_string($_POST['buscar']);

if($buscar <> NULL) {
$mostrar_datos_sql = "SELECT * FROM productos, marcas WHERE (productos.cod_marcas = marcas.cod_marcas) AND (cod_productos_var = '$buscar' OR nombre_productos like '$buscar%' 
OR cod_original = '$buscar' OR detalles = '$buscar') order by nombre_productos DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

 echo "<font color='yellow'><strong>Resultados para: ".$buscar."</strong></font><br>";
} else {
}
?>
<br>
<center>
<table width="90%">
<tr>
<td><div align="center" ><strong>AGREG</strong></div></td>
<!--<td><div align="center" ><strong>Editar</strong></div></td>-->
<td><div align="center" ><strong>C&Oacute;DIGO</strong></div></td>
<td><div align="center" ><strong>COD FACT</strong></div></td>
<td><div align="center" ><strong>BARRAS</strong></div></td>
<td><div align="center" ><strong>PRODUCTO</strong></div></td>
<!--<td><div align="center" ><strong>Marca</strong></div></td>-->
<td><div align="center" ><strong>MARCA</strong></div></td>
<!--<td><div align="center" ><strong>Tipo</strong></div></td>
<td><div align="center" ><strong>Pais</strong></div></td>

<td><div align="center" ><strong>Und</strong></div></td>-->
<td><div align="center" ><strong>UND</strong></div></td>
<td><div align="center" ><strong>P.COMPRA</strong></div></td>
<td><div align="center" ><strong>P.VENTA</strong></div></td>
<!--<td><div align="center" ><strong>Utilidad</strong></div></td>
<td><div align="center" ><strong>Detalles</strong></div></td>-->
</tr>
<?php do { ?>
<td ><a href="../modificar_eliminar/agregar_cargar_pedido_temporal.php?cod_productos=<?php echo $matriz_consulta['cod_productos_var']; ?>"><img src=../imagenes/agregar.png alt="Agregar"></a></td>
<!--<tr><td><a href="../modificar_eliminar/modificar_productos.php?cod_productos=<?php //echo $matriz_consulta['cod_productos_var']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></a></td>-->
<td><?php echo $matriz_consulta['cod_productos_var']; ?></td>
<td><?php echo $matriz_consulta['cod_original']; ?></td>
<td><?php echo $matriz_consulta['detalles']; ?></td>
<td><?php echo $matriz_consulta['nombre_productos']; ?></td>
<!--<td><?php //echo $matriz_consulta['nombre_marcas']; ?></td>-->
<td><?php echo $matriz_consulta['nombre_marcas']; ?></td>
<!--<td><?php //echo $matriz_consulta['nombre_tipo']; ?></td>
<td><?php //echo $matriz_consulta['nombre_paises']; ?></td>
<td><?php //echo $matriz_consulta['unidades']; ?></td>-->
<td align="right"><font color='white' size= "+1"><?php echo $matriz_consulta['unidades_faltantes']; ?></font></td>
<td align="right"><font color='white' size= "+1"><?php echo number_format($matriz_consulta['precio_costo']); ?></font></td>
<td align="right"><font color='white' size= "+1"><?php echo number_format($matriz_consulta['precio_venta']); ?></font></td>
<!--<td align="right"><?php echo number_format($matriz_consulta['utilidad']); ?></td>
<td><?php //echo $matriz_consulta['detalles']; ?></td>-->
</tr>
<?php 
} 
while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>