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
$mostrar_datos_sql = "SELECT * FROM productos, marcas, nomenclatura WHERE (nombre_productos like '$buscar%' OR cod_productos_var = '$buscar') 
AND productos.cod_marcas = marcas.cod_marcas AND productos.cod_nomenclatura = nomenclatura.cod_nomenclatura order by nombre_productos ASC";
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
?>
<body>
<br>
<center>
<table width="90%">
<tr>
<td align="center"><strong>C&Oacute;DIGO</strong></td>
<td align="center"><strong>PRODUCTO</strong></td>
<td align="center"><strong>MARCA</strong></td>
<td align="center"><strong>UND</strong></td>
<td align="center"><strong>P.COMPRA</strong></td>
<td align="center"><strong>P.VENTA</strong></td>
</tr>
<?php do { 
$id = $datos['cod_productos_var'];
?>
<tr>
<td><?php echo $datos['cod_productos_var'];?></td>
<td><a href="../modificar_eliminar/productos_actualizar_inventario.php?cod_productos=<?php echo $datos['cod_productos_var']; ?>"><?php echo $datos['nombre_productos'];?></center></a></td>
<td><?php echo $datos['nombre_marcas'];?></td>
<td align="right"><?php echo $datos['unidades_faltantes'];?></td>
<td align="right"><?php echo number_format($datos['precio_compra'], 0, ",", ".");?></td>
<td align="right"><?php echo number_format($datos['precio_venta'], 0, ",", ".");?></td>
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<?php } else {
} ?>
</body>
</html>
<?php mysql_free_result($consulta);?>