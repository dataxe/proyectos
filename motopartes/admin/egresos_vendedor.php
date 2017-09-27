<?php error_reporting(E_ALL ^ E_NOTICE);
require_once('../conexiones/conexione.php'); 
require_once('../evitar_mensaje_error/error.php'); 
mysql_select_db($base_datos, $conectar); 
include ("../session/funciones_admin.php");
if (verificar_usuario()){
//print "Bienvenido (a), <strong>".$_SESSION['usuario'].", </strong>al sistema.";
	} else { header("Location:../index.php");
}
$cuenta_actual = addslashes($_SESSION['usuario']);
include ("../seguridad/seguridad_diseno_plantillas.php");
include ("../registro_movimientos/registro_movimientos.php");
//include ("../registro_movimientos/registro_cierre_caja.php");

$fecha_mes = mysql_escape_string($_POST['fecha_mes']);
$mostrar_datos_sql = "SELECT * FROM egresos order by fecha_invert DESC";
$consulta = mysql_query($mostrar_datos_sql, $conectar) or die(mysql_error());
$datos = mysql_fetch_assoc($consulta);

$datos_sql = "SELECT SUM(costo) AS costo FROM egresos WHERE fecha_mes = '$fecha_mes' order by fecha_invert DESC";
$consulta_dat = mysql_query($datos_sql, $conectar) or die(mysql_error());
$total_egreso = mysql_fetch_assoc($consulta_dat);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8"> 
<title>ALMACEN</title>
</head>
<body>
<center>
<td><strong><font color='yellow' size="6px">EGRESOS: </font></strong></td><br><br>
<td>
<a href="../admin/agregar_egresos_vendedor.php"><FONT color='yellow' size="5px">AGREGAR NUEVO EGRESO</FONT></a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<!--<a href="../admin/agregar_concepto_egresos.php"><FONT color='white'>AGREGAR NUEVO CONCEPTO DE EGRESO</FONT></a>-->
</td>
</center>
<br>
<center>
<form method="post" id="table" name="formulario" action="">
<table id="table">
<tr>
<td><div align="center" ><strong>MES</strong></div></td>
<td><select name="fecha_mes" require autofocus>
<?php $sql_consulta="SELECT DISTINCT fecha_mes FROM egresos ORDER BY fecha_mes DESC";
$resultado = mysql_query($sql_consulta, $conectar) or die(mysql_error());
while ($contenedor=mysql_fetch_array($resultado)) {?>
<option style="font-size:20px" value="<?php echo $contenedor['fecha_mes'] ?>"><?php echo $contenedor['fecha_mes']?></option>
<?php }?></select></td>
<tr valign="baseline">
<td nowrap align="right">&nbsp;</td>
<td bordercolor="1"><input type="submit" id="boton1" value="Ver"></td>
</tr>
</table>
</form>
<?php
if (isset($_POST['fecha_mes'])) {
echo "<font color ='yellow' size='+3'>RESULTADOS PARA: ".$fecha_mes."</font>";
?>
<center>
<table width="90%" id="table">
<tr>
<!--<td><div align="center"><strong>ELM</strong></div></td>-->
<td><div align="center"><strong>CONCEPTO</strong></div></td>
<td><div align="center"><strong>COMENTARIO</strong></div></td>
<td><div align="center"><strong>COSTO</strong></div></td>
<td><div align="center"><strong>FECHA</strong></div></td>
</tr>
<?php do { ?>
<tr>
<!--<td ><a href="../modificar_eliminar/eliminar_egresos.php?cod_egresos=<?php echo $datos['cod_egresos']; ?>"><center><img src=../imagenes/eliminar.png alt="eliminar"></center></a></td>-->
<td ><?php echo $datos['conceptos']; ?></td>
<td align="justify"><?php echo $datos['comentarios']; ?></td>
<td align="right"><?php echo number_format($datos['costo']); ?></td>
<td align="right"><?php echo $datos['fecha']; ?></td>
<!--<td ><a href="../modificar_eliminar/modificar_egresos.php?cod_egresos=<?php echo $datos['cod_egresos']; ?>"><center><img src=../imagenes/actualizar.png alt="Actualizar"></center></a></td>-->
</tr>
<?php } while ($datos = mysql_fetch_assoc($consulta)); ?>
</table>
<table id="table">
<td><font color='white' size='+3'>TOTAL: </font></td>
<td><font color='white' size='+3'><?php echo number_format($total_egreso['costo'])?></font></td>
</table>
</center>
<?php
} else {
}
?>
</body>
</html>
<?php mysql_free_result($consulta);