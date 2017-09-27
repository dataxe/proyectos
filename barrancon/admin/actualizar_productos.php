<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../registro_movimientos/registro_movimientos.php");
?>
<script>
window.onload = function() {
document.getElementById("foco").focus();
}
</script>
<?php
$buscar = $_POST['buscar'];
$mostrar_datos_sql = "SELECT * FROM productos AS prod INNER JOIN marcas AS marc ON 
(prod.cod_marcas = marc.cod_marcas AND (prod.cod_productos_var = '$buscar' OR prod.nombre_productos like '$buscar%')) INNER JOIN nomenclatura AS 
nomen ON (prod.cod_nomenclatura = nomen.cod_nomenclatura) INNER JOIN lineas AS lineas ON (prod.cod_lineas = lineas.cod_lineas) order by prod.nombre_productos DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);
$total_datos = mysql_num_rows($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>

</head>
<?php  
if ($buscar <> NULL && $total_datos <>'0') {
$pagina = 'cargar_inventario.php';
?>
<body>
<br>
<center>
<table width="95%">
<tr>
<td align="center"><strong>EDIT</strong></td>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<!--<td align="center"><strong>Marca</strong></td>-->
<td align="center"><strong>MARCA</strong></td>
<!--<td align="center"><strong>Casilla</strong></td>
<td align="center"><strong>Unidades</strong></td>-->
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>P.COMPRA</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
<!--<td><strong>Margen Utilidad</strong></td>
<td><strong>Detalles</strong></td>-->
<td><strong>DESCRIPCI&Oacute;N</strong></td>
</tr>
<?php do { 
$id = $datos['cod_productos_var'];
?>
<tr>
<td><a href="../modificar_eliminar/productos_actualizar_inventario.php?cod_productos=<?php echo $datos['cod_productos_var']; ?>&pagina=<?php echo $pagina; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>
<td><div class="text" id="cod_productos_var-<?php echo $id?>"><?php echo $datos['cod_productos_var'];?></div></td>
<td><div class="textarea" id="nombre_productos-<?php echo $id?>"><?php echo utf8_encode($datos['nombre_productos']);?></div></td>
<!--<td><?php //echo $datos['nombre_marcas']; ?></td>-->
<td><div class="select" id="cod_proveedores-<?php echo $id?>"><?php echo $datos['nombre_marcas'];?></td>
<!--<td><?php //echo $datos['nombre_nomenclatura']; ?></td>
<td align="right"><?php //echo $datos['unidades']; ?></td>-->
<td align="right"><div class="text" id="unidades_faltantes-<?php echo $id?>"><?php echo $datos['unidades_faltantes'];?></div></td>
<td align="right"><div class="text" id="precio_compra-<?php echo $id?>"><?php echo $datos['precio_compra'];?></div></td>
<td align="right"><div class="text" id="precio_venta-<?php echo $id?>"><?php echo $datos['precio_venta'];?></div></td>
<!--<td align="right"><?php //echo number_format($datos['utilidad']); ?></td>-->
<!--<td><?php //echo $datos['detalles']; ?></td>-->
<td align="right"><div class="textarea" id="descripcion-<?php echo $id?>"><?php echo $datos['descripcion'];?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<?php } else {
} ?>
</body>
</html>
<?php mysql_free_result($consulta);?>