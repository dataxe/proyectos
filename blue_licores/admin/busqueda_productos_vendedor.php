<?php error_reporting(E_ALL ^ E_NOTICE);?>
<?php require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include("../session/funciones_admin.php");
//include("../notificacion_alerta/mostrar_noficacion_alerta.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
  } else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<center>
<?php require_once("temporal.php");?>
<br><br>
<form action="" method="post">
<input name="palabra" id="foco" style="height:26"/>
<input type="submit" name="buscador" value="Buscar productos" />
</form>
</center>
<body>
<?php
$buscar = mysql_escape_string($_POST['palabra']);

if($buscar <> NULL) {
$mostrar_datos_sql = "SELECT * FROM productos AS prod INNER JOIN marcas AS marc ON (prod.cod_marcas = marc.cod_marcas) 
AND (prod.cod_productos_var like '$buscar' OR prod.nombre_productos) order by prod.nombre_productos ASC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$matriz_consulta = mysql_fetch_assoc($consulta);

 echo "<font color='yellow'><strong>Resultados para: ".$buscar."</strong></font><br>";
} else {
}
$unidades_temporal = '20';
?>
<center>
<table>
<tr>
<td align="center"><strong>C&oacute;digo</strong></td>
<td align="center"><strong>Nombre</strong></td>
<td align="center"><strong>Inv</strong></td>
<td align="center"><strong>P.Comp</strong></td>
<td align="center"><strong>P.Vent</strong></td>
<td align="center"><strong>Utilidad</strong></td>
<td align="center"><strong>Descripcion</strong></td>
</tr>
<?php do { ?>
<td><?php echo $matriz_consulta['cod_productos_var']; ?></td>
<td nowrap><a href="../modificar_eliminar/agregar_lista_temporal.php?cod_productos=<?php echo $matriz_consulta['cod_productos_var']?>"><?php echo $matriz_consulta['nombre_productos']; ?></a></td>

<td><?php echo $matriz_consulta['nombre_productos']; ?></td>
<td align="right"><?php echo $matriz_consulta['unidades_faltantes']; ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_compra']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['precio_venta']); ?></td>
<td align="right"><?php echo number_format($matriz_consulta['utilidad']); ?></td>
<td><?php echo $matriz_consulta['descripcion']; ?></td>
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