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

$obtener_diseno = "SELECT estilo_css, cod_seguridad FROM administrador WHERE cuenta LIKE '$cuenta_actual'";
$resultado_diseno = mysql_query($obtener_diseno, $conectar) or die(mysql_error());
$seguridad_acceso = mysql_fetch_assoc($resultado_diseno);

$nivel_acceso = '3';
if ($seguridad_acceso['cod_seguridad'] <> $nivel_acceso) {
header("Location:../admin/acceso_denegado.php");
}
?>
<link rel="stylesheet" type="text/css" href="../estilo_css/<?php echo $seguridad_acceso['estilo_css'];?>">
<center>
<form action="" method="post">
<input name="palabra" id="foco" style="height:26"/>
<input type="submit" name="buscador" value="Buscar productos" />
</form>
</center>
<br>
<?php
$buscar = $_POST['palabra'];
$mostrar_datos_sql = "SELECT * FROM ventas WHERE cod_factura like 'sin_factura' and (cod_productos like '$buscar%' OR nombre_productos like '%$buscar%')
 ORDER BY fecha_hora DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
</center>
<br>
<center>
<table id="table" id="table">
<tr>
<td><div align="center"><strong>Cod</strong></div></td>
<td><div align="center"><strong>Nombre</strong></div></td>
<td><div align="center"><strong>Cant</strong></div></td>
<td><div align="center"><strong>P.Compra</strong></div></td>
<td><div align="center"><strong>P.Venta</strong></div></td>
<td><div align="center"><strong>Desc</strong></div></td>
<td><div align="center"><strong>P.Desc</strong></div></td>
<td><div align="center"><strong>Vendedor</strong></div></td>
<td><div align="center"><strong>Ip</strong></div></td>
<td><div align="center"><strong>Fecha</strong></div></td>
<td><div align="center"><strong>Eliminar</strong></div></td>
  </tr>
<?php do { ?>
<tr>
<td ><?php echo $matriz_consulta['cod_productos']; ?></td>
<td ><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td align="right"><?php echo $matriz_consulta['unidades_vendidas']; ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_compra']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_venta']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['descuento']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_compra_con_descuento']); ?></td>
<td align="right"><?php echo $matriz_consulta['vendedor']; ?></td>
<td align="right"><?php echo $matriz_consulta['ip']; ?></td>
<td align="right"><?php echo $matriz_consulta['fecha_hora']; ?></td>
<td  nowrap><a href="../modificar_eliminar/eliminar_ventas_sin_factura.php?cod_productos=<?php echo $matriz_consulta['cod_productos']?>&cod_ventas=<?php echo $matriz_consulta['cod_ventas'];?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></a></td>
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