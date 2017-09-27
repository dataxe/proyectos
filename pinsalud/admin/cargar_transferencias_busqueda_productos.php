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
$mostrar_datos_sql = "SELECT * FROM productos, marcas, nomenclatura WHERE (productos.cod_marcas = marcas.cod_marcas AND 
productos.cod_nomenclatura = nomenclatura.cod_nomenclatura) AND (cod_productos_var = '$buscar' OR nombre_productos like '$buscar%') order by nombre_productos DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$tota_reg = mysql_num_rows($consulta);

 echo "<font color='yellow'><strong>".$tota_reg." Resultados para: ".$buscar."</strong></font><br>";
} else {
}
?>
<br>
<center>
<table width="90%">
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>MARCA</strong></td>
<td align="center"><strong>ESTANTE</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>P.COMPRA</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
</tr>
<?php do { ?>
<td><?php echo $datos['cod_productos_var']; ?></td>
<td><a href="../modificar_eliminar/agregar_cargar_transferencias_temporal.php?cod_productos=<?php echo $datos['cod_productos_var']; ?>"><?php echo $datos['nombre_productos']; ?></a></td>
<td><?php echo $datos['nombre_marcas']; ?></td>
<td><?php echo $datos['nombre_nomenclatura']; ?></td>
<td align="right"><font color='white' size= "+1"><?php echo $datos['unidades_faltantes']; ?></font></td>
<td align="right"><font color='white' size= "+1"><?php echo number_format($datos['precio_costo']); ?></font></td>
<td align="right"><font color='white' size= "+1"><?php echo number_format($datos['precio_venta']); ?></font></td>
</tr>
<?php 
} 
while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>