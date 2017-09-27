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
$buscar = $_POST['buscar'];

if($buscar <> NULL) {
$mostrar_datos_sql = "SELECT * FROM productos, marcas, proveedores, nomenclatura, paises, tipo WHERE (productos.cod_marcas = marcas.cod_marcas AND 
productos.cod_proveedores = proveedores.cod_proveedores AND productos.cod_nomenclatura = nomenclatura.cod_nomenclatura AND 
productos.cod_paises = paises.cod_paises AND productos.cod_tipo = tipo.cod_tipo) AND (cod_productos_var like '$buscar%' OR nombre_productos like '$buscar%' 
OR nombre_proveedores like '$buscar%') 
order by cod_productos DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

 echo "<font color='yellow'><strong>Resultados para: ".$buscar."</strong></font><br>";
} else {
}
?>
<br>
<center>
<table id="table">
<tr>
<td><div align="center" ><strong>Cod</strong></div></td>
<td><div align="center" ><strong>Nombre</strong></div></td>
<!--<td><div align="center" ><strong>Marca</strong></div></td>-->
<td><div align="center" ><strong>Prov</strong></div></td>
<td><div align="center" ><strong>Tipo</strong></div></td>
<!--<td><div align="center" ><strong>Pais</strong></div></td>
<td><div align="center" ><strong>Casilla</strong></div></td>
<td><div align="center" ><strong>Und</strong></div></td>-->
<td><div align="center" ><strong>Inv</strong></div></td>
<td><div align="center" ><strong>P.Comp</strong></div></td>
<td><div align="center" ><strong>P.Vent</strong></div></td>
<td><div align="center" ><strong>Utilidad</strong></div></td>
<!--<td><div align="center" ><strong>Detalles</strong></div></td>-->
<td><div align="center" ><strong>Descripcion</strong></div></td>
<td><div align="center" ><strong>Vender</strong></div></td>
</tr>
<?php do { ?>
<td><?php echo $matriz_consulta['cod_productos_var']; ?></td>
<td><?php echo $matriz_consulta['nombre_productos']; ?></td>
<!--<td><?php //echo $matriz_consulta['nombre_marcas']; ?></td>-->
<td><?php echo $matriz_consulta['nombre_proveedores']; ?></td>
<td><?php echo $matriz_consulta['nombre_tipo']; ?></td>
<!--<td><?php //echo $matriz_consulta['nombre_paises']; ?></td>
<td><?php //echo $matriz_consulta['nombre_nomenclatura']; ?></td>
<td><?php //echo $matriz_consulta['unidades']; ?></td>-->
<td align="right"><?php echo $matriz_consulta['unidades_faltantes']; ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_compra']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_venta']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['utilidad']); ?></td>
<!--<td><?php //echo $matriz_consulta['detalles']; ?></td>-->
<td><?php echo $matriz_consulta['descripcion']; ?></td>
<td  nowrap><a href="../admin/agregar_factura_manual.php?cod_productos=<?php echo $matriz_consulta['cod_productos_var']; ?>"><center><img src=../imagenes/vender.png alt="Vender"></a></td>
</tr>
<?php 
} 
while ($matriz_consulta = mysql_fetch_assoc($consulta)); ?>
</table>